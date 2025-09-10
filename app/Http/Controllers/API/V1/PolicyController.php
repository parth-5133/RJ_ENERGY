<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Policy;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorePolicyRequest;
use App\Enums\DocumentType;
use App\Models\AppDocument;

class PolicyController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;
        $isEmployee = in_array($role_code, [$this->employeeRoleCode]);
        $isClient = in_array($role_code, [$this->clientRoleCode]);

        $policies = Policy::where('policies.company_id', $CompanyId)
            ->when($isEmployee, function ($query) {
                return $query->where('policies.is_active', '=', 1)
                    ->where('policies.display_to_employee', '=', 1);
            })
            ->when($isClient, function ($query) {
                return $query->where('policies.is_active', '=', 1)
                    ->where('policies.display_to_client', '=', 1);
            })
            ->leftJoin('users', 'policies.updated_by', '=', 'users.id')
            ->leftJoin('users as created_by_user', 'policies.created_by', '=', 'created_by_user.id')
            ->select(
                'policies.id',
                'policies.policy_name',
                'policies.policy_description',
                'policies.effective_date',
                'policies.expiration_date',
                'policies.is_active',
                DB::raw("CONCAT(created_by_user.first_name, ' ', created_by_user.last_name) as issued_by"),
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(policies.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->orderBy('policies.created_at', 'asc')
            ->get();

        // Attach document information to each policy
        foreach ($policies as $policy) {
            $document = AppDocument::where('ref_primaryid', $policy->id)
                ->where('document_type', DocumentType::policy_document)
                ->first();

            if ($document) {
                $policy->document_path = $document->relative_path;
                $policy->document_name = $document->file_display_name;
            }
        }

        return ApiResponse::success($policies, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StorePolicyRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $data = $request->validated();
        $data['created_by'] = $currentUser->id;
        $data['created_at'] = now();
        $data['updated_at'] = null;
        $data['company_id'] = $CompanyId;

        $policy = Policy::create($data);

        if (!empty($request->policy_document)) {
            $file = $request->policy_document;
            $fileId = uniqid();

            $basePath = 'policy_documents/' . $policy->policy_name;

            if (!Storage::disk('public')->exists($basePath)) {
                Storage::disk('public')->makeDirectory($basePath);
            }

            $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');
            $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $documentTypeId = DocumentType::policy_document;

            AppDocument::create([
                'ref_primaryid' => $policy->id,
                'document_type' => $documentTypeId,
                'relative_path' => $filePath,
                'file_id' => $fileId,
                'extension' => $file->getClientOriginalExtension(),
                'file_display_name' => $fileDisplayName,
                'is_active' => true,
                'created_by' => $currentUser->id,
                'created_at' => now(),
                'updated_at' => null,
            ]);
        }

        return ApiResponse::success($policy, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $policyId = $request->policyId;

        $policy = Policy::find($policyId);

        $policy->document = AppDocument::where('ref_primaryid', $policy->id)
            ->where('document_type', DocumentType::policy_document)
            ->first();

        if ($policy) {
            return ApiResponse::success($policy, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($policy, ResMessages::NOT_FOUND);
        }
    }
    public function update(StorePolicyRequest $request)
    {
        $policyId = $request->policyId;

        $policy = Policy::find($policyId);

        if (!$policy) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $policy->fill($request->validated());
        $policy->updated_by = $currentUser->id;
        $policy->updated_at = now();

        if (!empty($request->policy_document)) {
            $file = $request->policy_document;
            $fileId = uniqid();

            $basePath = 'policy_documents/' . $policy->policy_name;

            if (!Storage::disk('public')->exists($basePath)) {
                Storage::disk('public')->makeDirectory($basePath);
            }

            $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');
            $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $documentTypeId = DocumentType::policy_document;

            $existingDocument = AppDocument::where('ref_primaryid', $policy->id)
                ->where('document_type', $documentTypeId)
                ->first();

            if ($existingDocument) {
                $existingDocument->update([
                    'relative_path' => $filePath,
                    'file_id' => $fileId,
                    'extension' => $file->getClientOriginalExtension(),
                    'file_display_name' => $fileDisplayName,
                    'updated_at' => now(),
                    'updated_by' => $currentUser->id,
                ]);
                return ApiResponse::success($existingDocument, ResMessages::UPDATED_SUCCESS);
            }

            AppDocument::create([
                'ref_primaryid' => $policy->id,
                'document_type' => $documentTypeId,
                'relative_path' => $filePath,
                'file_id' => $fileId,
                'extension' => $file->getClientOriginalExtension(),
                'file_display_name' => $fileDisplayName,
                'is_active' => true,
                'created_by' => $currentUser->id,
                'created_at' => now(),
                'updated_at' => null,
            ]);
        }

        $policy->save();

        return ApiResponse::success($policy,  ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $policy = Policy::find($id);

        if ($policy) {
            $policy->delete();
            return ApiResponse::success($policy, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($policy, ResMessages::NOT_FOUND);
        }
    }
}
