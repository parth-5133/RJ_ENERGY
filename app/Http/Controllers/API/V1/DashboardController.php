<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\GetCompanyId;
use App\Helpers\JWTUtils;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;

class DashboardController extends Controller
{
    public function UploadFiles(Request $request)
    {
        $file = $request->file('upload');

        $fileId = uniqid();

        $basePath = 'ReadWriteData';

        if (!Storage::disk('public')->exists($basePath)) {
            Storage::disk('public')->makeDirectory($basePath);
        }

        try {
            $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');
        } catch (\Exception $e) {
            return response()->json(['uploaded' => 0, 'error' => ['message' => 'File upload failed: ' . $e->getMessage()]]);
        }

        $fileUrl = env('APP_URL') . '/storage/' . $filePath;

        return response()->json(['uploaded' => 1, 'fileName' => $fileId . '.' . $file->getClientOriginalExtension(), 'url' => $fileUrl, 'error' => ['message' => 'File successfully uploaded']]);
    }
    public function getFinancialYears()
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companyId = GetCompanyId::GetCompanyId();

        if ($companyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        if ($companyId == null) {
            $companyId = env('APP_BASE_COMPANYID');
        }

        $years = DB::table('financial_years')
            ->select('id', 'from_date', 'to_date', 'display_year', 'is_currentYear', 'is_active', 'company_id');

        if ($companyId) {
            $years->where('financial_years.company_id', $companyId);
        }

        $years = $years->orderBy('from_date', 'desc')->get();

        return response()->json(['data' => $years]);
    }
    public function getCompanies()
    {
        $data = DB::table('companies')
            ->select('id', 'legal_name')
            ->where('deleted_at', null)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json(['data' => $data]);
    }
}
