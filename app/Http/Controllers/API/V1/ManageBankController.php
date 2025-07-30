<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use App\Http\Requests\StoreManageBankRequest;
use App\Models\Bank;
use Illuminate\Http\Request;


class ManageBankController extends Controller
{
    public function index()
    {
        $banks = Bank::all();
        return ApiResponse::success($banks, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreManageBankRequest $request)
    {
        $Data = $request->all();
        $Data['created_at'] = now();

        $bank = Bank::create($Data);

        return ApiResponse::success($bank, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $bankId = $request->manageBankId;
        $bank = Bank::find($bankId);
        if ($bank) {
            return ApiResponse::success($bank, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($bank, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreManageBankRequest $request)
    {
        $bankId = $request->manageBankId;

        $bank = Bank::find($bankId);

        if (!$bank) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $bank->fill($request->validated());
        $bank->updated_at = now();

        $bank->save();

        return ApiResponse::success($bank, ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $bank = Bank::find($id);

        if ($bank) {
            $bank->delete();
            return ApiResponse::success($bank, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($bank, ResMessages::NOT_FOUND);
        }
    }
}
