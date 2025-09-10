<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeResignation;
use App\Helpers\ApiResponse;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use App\Constants\ResMessages;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreEmployeeResignationRequest;
use App\Models\AppDocument;
use App\Enums\DocumentType;
use Illuminate\Support\Facades\Storage;

class EmployeeResignationController extends Controller
{
    public function index()
    {
        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $employeeResignationQuery = DB::table('employee_resignations')
            ->where('employee_resignations.company_id', $CompanyId)
            ->leftJoin('users as usersName', 'employee_resignations.employee_id', '=', 'usersName.id')
            ->leftJoin('users as users', 'employee_resignations.updated_by', '=', 'users.id')
            ->leftJoin('app_documents', 'employee_resignations.id', '=', 'app_documents.ref_primaryid')
            ->where('app_documents.document_type', DocumentType::resignation_letter)
            ->select(
                'employee_resignations.id',
                DB::raw("DATE_FORMAT(employee_resignations.resignation_date, '%d/%m/%Y') as resignation_date"),
                'employee_resignations.status',
                DB::raw("DATE_FORMAT(employee_resignations.last_working_date, '%d/%m/%Y') as last_working_date"),
                'employee_resignations.updated_by',
                DB::raw("CONCAT(usersName.first_name, ' ', usersName.last_name) as employee_name"),
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                'app_documents.relative_path as document',
                'app_documents.file_display_name as document_name',
                DB::raw("DATE_FORMAT(employee_resignations.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                'usersName.employee_id'
            )->whereNull('employee_resignations.deleted_at')
            ->orderBy('employee_resignations.id', 'desc');

        if ($role_code === $this->employeeRoleCode) {
            $employeeResignationQuery->where('employee_resignations.employee_id', JWTUtils::getCurrentUserByUuid()->id);
        }

        $employeeResignation = $employeeResignationQuery->get();

        return ApiResponse::success($employeeResignation, ResMessages::RETRIEVED_SUCCESS);
    }

    public function store(StoreEmployeeResignationRequest $request)
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
        if ($request->employee_id == null || $request->employee_id == 0) {
            $data['employee_id'] = $currentUser->id;
        }

        $employeeResignation = EmployeeResignation::create($data);

        if (!empty($request->document)) {
            $file = $request->document;

            $fileId = uniqid();

            $basePath = 'employee_resignation/' . $employeeResignation->id;

            if (!Storage::disk('public')->exists($basePath)) {
                Storage::disk('public')->makeDirectory($basePath);
            }

            $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');


            $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $documentTypeId = DocumentType::resignation_letter;

            $EmployeeDocument = AppDocument::create([
                'ref_primaryid' => $employeeResignation->id,
                'document_type' => $documentTypeId,
                'relative_path' => $filePath,
                'file_id' => $fileId,
                'extension' => $file->getClientOriginalExtension(),
                'file_display_name' => $fileDisplayName,
                'is_active' => true,
                'created_by' => $employeeResignation->employee_id,
                'created_at' => now(),
            ]);
        }

        return ApiResponse::success($employeeResignation, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $Id = $request->resignationId;

        $data = EmployeeResignation::where('employee_resignations.id', $Id)
            ->leftJoin('users', 'employee_resignations.employee_id', '=', 'users.id')
            ->select(
                'employee_resignations.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name"),
                'employee_resignations.resignation_date',
                'employee_resignations.last_working_date',
                'employee_resignations.reason',
                'employee_resignations.status',
            )
            ->first();

        if (!$data) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $data->document = AppDocument::where('ref_primaryid', $data->id)
            ->where('document_type', DocumentType::resignation_letter)
            ->first();

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function update(StoreEmployeeResignationRequest $request)
    {
        $Id = $request->resignationId;

        $data = EmployeeResignation::find($Id);

        if (!$data) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $data->fill($request->validated());
        $data->updated_by = $currentUser->id;
        $data->updated_at = now();
        $data->save();

        if (!empty($request->document)) {
            $file = $request->document;
            $fileId = uniqid();

            $basePath = 'employee_resignation/' . $data->id;

            if (!Storage::disk('public')->exists($basePath)) {
                Storage::disk('public')->makeDirectory($basePath);
            }

            $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');
            $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $documentTypeId = DocumentType::resignation_letter;

            $existingDocument = AppDocument::where('ref_primaryid', $data->id)
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
            } else {
                AppDocument::create([
                    'ref_primaryid' => $data->id,
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
        }
        return ApiResponse::success($data,  ResMessages::UPDATED_SUCCESS);
    }

    public function delete($id)
    {
        $employeeResignation = EmployeeResignation::find($id);

        if ($employeeResignation) {
            $employeeResignation->delete();
            return ApiResponse::success($employeeResignation, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($employeeResignation, ResMessages::NOT_FOUND);
        }
    }
}
