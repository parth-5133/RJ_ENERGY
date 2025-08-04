<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\SolarProposal;
use App\Models\SolarLoan;
use App\Models\SolarDocument;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateProposalRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use App\Models\Sequence;
use Illuminate\Http\Request;


class ConsumerApplicationController extends Controller
{

    public function index()
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $applications = SolarProposal::where('user_id', $currentUser->id)->get();

        return ApiResponse::success($applications, ResMessages::RETRIEVED_SUCCESS);
    }
    public function getDocumentsList()
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $applications = SolarProposal::where('user_id', $currentUser->id)->first();

        $documents = SolarDocument::where('proposal_id', $applications->id)->get();

        return ApiResponse::success($documents, ResMessages::RETRIEVED_SUCCESS);
    }
    public function create(StoreUpdateProposalRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $sequence = Sequence::where('type', 'applicationId')->first();
        $newSequenceNo = $sequence->sequenceNo + 1;
        $applicationId = $sequence->prefix . '-' . str_pad($newSequenceNo, 4, '0', STR_PAD_LEFT);

        $proposal = SolarProposal::create([
            'user_id'          => $currentUser->id,
            'application_id'   => $applicationId,
            'solar_capacity'   => $request->solar_capacity,
            'roof_type'        => $request->roof_type,
            'roof_area'        => $request->roof_area,
            'usage_pattern'    => $request->usage_pattern,
            'net_metering'     => $request->net_metering,
            'subsidy_claimed'  => $request->subsidy_claimed,
            'purchase_mode'    => $request->purchase_mode,
            'loan_required'    => $request->loan_required,
        ]);

        Sequence::where('type', 'applicationId')->update(['sequenceNo' => $newSequenceNo]);


        if ($request->loan_required === 'Yes') {
            SolarLoan::create([
                'proposal_id'    => $proposal->id,
                'bank_name'      => $request->bank_name,
                'bank_branch'    => $request->bank_branch,
                'account_number' => $request->account_number,
                'ifsc_code'      => $request->ifsc_code,
                'loan_mode'      => $request->loan_mode,
            ]);
        }

        $documentFields = [
            'aadhaar_card',
            'pan_card',
            'electricity_bill',
            'bank_proof',
            'passport_photo',
            'ownership_proof',
            'site_photo',
            'self_declaration',
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store("solar_documents/{$proposal->id}", 'public');
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileId = uniqid();

                SolarDocument::create([
                    'proposal_id'       => $proposal->id,
                    'relative_path'     => $path,
                    'file_id'           => $fileId,
                    'extension'         => $extension,
                    'file_display_name' => ucfirst(str_replace('_', ' ', $field)),
                ]);
            }
        }

        return ApiResponse::success(null, ResMessages::CREATED_SUCCESS);
    }
    public function delete($id)
    {
        $proposal = SolarProposal::find($id);
        $loan = SolarLoan::where('proposal_id', $id)->first();
        $document = SolarDocument::where('proposal_id', $id)->first();

        if ($proposal) {
            $proposal->delete();
            $loan->delete();
            $document->delete();
            return ApiResponse::success($proposal, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($proposal, ResMessages::NOT_FOUND);
        }
    }
    public function downloadDocument(Request $request)
    {
        $id = $request->id;
        $document = SolarDocument::find($id);

        if ($document) {
            $fileUrl = asset("storage/{$document->relative_path}");
            return ApiResponse::success($fileUrl, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($document, ResMessages::NOT_FOUND);
        }
    }
    public function gettApplictaionId()
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        return SolarProposal::where('user_id', $currentUser->id)
            ->value('application_id');
    }
}
