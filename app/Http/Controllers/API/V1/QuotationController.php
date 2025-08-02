<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Customer;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use App\Constants\ResMessages;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateQuotationRequest;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = DB::table('quotations')
            ->leftJoin('customers', 'quotations.customer_id', '=', 'customers.id')
            ->leftJoin('users', 'users.id', '=', 'quotations.by')
            ->select(
                'quotations.id',
                'customers.customer_name',
                'quotations.required',
                'quotations.amount',
                'quotations.date',
                'quotations.status',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as prepared_by"),
            )
            ->whereNull('quotations.deleted_at')
            ->orderBy('quotations.id', 'desc')
            ->get();

        return ApiResponse::success($quotations, ResMessages::RETRIEVED_SUCCESS);
    }

    public function store(StoreUpdateQuotationRequest $request)
    {
        DB::beginTransaction();

        try {
            // 1. Store customer data
            $customer = Customer::create([
                'customer_name'     => $request->input('customer_name'),
                'age'               => $request->input('age'),
                'mobile'            => $request->input('mobile'),
                'alternate_mobile'  => $request->input('alternate_mobile'),
                'aadhar'            => $request->input('aadhar'),
                'pan'               => $request->input('pan'),
                'created_at'        => now(),
            ]);

            // 2. Store quotation data
            $quotation = Quotation::create([
                'customer_id' => $customer->id,
                'solar_capacity' => $request->input('solar_capacity'),
                'rooftop_size' => $request->input('rooftop_size'),
                'required'    => $request->input('quotation_'),
                'amount'      => $request->input('quotation_amount'),
                'date'        => $request->input('quotation_date'),
                'by'          => $request->input('quotation_by'),
                'status'      => $request->input('quotation_status'),
                'created_at'  => now(),
            ]);

            DB::commit();

            return ApiResponse::success($quotation, ResMessages::CREATED_SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error('Failed to store quotation: ' . $e->getMessage(), 500);
        }
    }
    public function view(Request $request)
    {
        $quotationId = $request->quotesId;

        $quotation = DB::table('quotations')
            ->leftJoin('customers', 'quotations.customer_id', '=', 'customers.id')
            ->select(
                'quotations.id',
                'customers.customer_name',
                'customers.age',
                'customers.mobile',
                'customers.alternate_mobile',
                'customers.aadhar',
                'customers.pan',
                'quotations.required',
                'quotations.solar_capacity',
                'quotations.rooftop_size',
                'quotations.amount',
                'quotations.date',
                'quotations.by',
                'quotations.status'
            )
            ->where('quotations.id', $quotationId)
            ->first();

        if ($quotation) {
            return ApiResponse::success($quotation, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($quotation, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreUpdateQuotationRequest $request)
    {
        DB::beginTransaction();

        try {
            // 1. Fetch quotation
            $quotation = Quotation::findOrFail($request->input('quotesId'));

            // 2. Fetch associated customer
            $customer = Customer::findOrFail($quotation->customer_id);

            // 3. Update customer data
            $customer->update([
                'customer_name'     => $request->input('customer_name'),
                'age'               => $request->input('age'),
                'mobile'            => $request->input('mobile'),
                'alternate_mobile'  => $request->input('alternate_mobile'),
                'aadhar'            => $request->input('aadhar'),
                'pan'               => $request->input('pan'),
                'updated_at'        => now(),
            ]);

            // 4. Update quotation data
            $quotation->update([
                'required'    => $request->input('quotation_') === 'Yes' ? 1 : 0,
                'solar_capacity' => $request->input('solar_capacity'),
                'rooftop_size' => $request->input('rooftop_size'),
                'amount'      => $request->input('quotation_amount'),
                'date'        => $request->input('quotation_date'),
                'by'          => $request->input('quotation_by'),
                'status'      => $request->input('quotation_status'),
                'updated_at'  => now(),
            ]);

            DB::commit();

            return ApiResponse::success($quotation, 'Updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error('Update failed: ' . $e->getMessage(), 500);
        }
    }
    public function delete($id)
    {
        $quotation = Quotation::find($id);
        $customer = Customer::where('id', $quotation->customer_id)->first();
        $customer->delete();

        if ($quotation) {
            $quotation->delete();
            return ApiResponse::success($quotation, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($quotation, ResMessages::NOT_FOUND);
        }
    }
    public function getAllAccountantList()
    {
        $quotations = DB::table('users')
            ->select(
                'users.id',
                DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),
            )
            ->where('users.role_id', '=', 4)
            ->whereNull('users.deleted_at')
            ->get();

        return ApiResponse::success($quotations, ResMessages::RETRIEVED_SUCCESS);
    }
}
