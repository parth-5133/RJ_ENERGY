<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Installer;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateInstallerRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InstallersController extends Controller
{
    public function index()
    {
        $installers = Installer::all();
        return ApiResponse::success($installers, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreUpdateInstallerRequest $request)
    {
        $Data = $request->all();
        $Data['created_at'] = now();

        $installer = Installer::create($Data);

        return ApiResponse::success($installer, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $installersId = $request->installersId;
        $installer = Installer::find($installersId);
        if ($installer) {
            return ApiResponse::success($installer, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($installer, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreUpdateInstallerRequest $request)
    {
        $installersId = $request->installersId;

        $installer = Installer::find($installersId);

        if (!$installer) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $installer->fill($request->validated());
        $installer->updated_at = now();
        $installer->save();

        return ApiResponse::success($installer, ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $installer = Installer::find($id);

        if ($installer) {
            $installer->delete();
            return ApiResponse::success($installer, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($installer, ResMessages::NOT_FOUND);
        }
    }
}
