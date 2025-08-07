<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\SolarDetail;
use App\Models\Sequence;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
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
            $sequence = Sequence::where('type', 'customerNumber')->first();
            $newSequenceNo = $sequence->sequenceNo + 1;
            $customerNumber = $sequence->prefix . '-' . str_pad($newSequenceNo, 4, '0', STR_PAD_LEFT);
            // 1. Store customer data
            $customer = Customer::create([
                'customer_number'   => $customerNumber,
                'customer_name'     => $request->input('customer_name'),
                'age'               => $request->input('age'),
                'gender'            => $request->input('gender'),
                'marital_status'    => $request->input('marital_status'),
                'mobile'            => $request->input('mobile'),
                'alternate_mobile'  => $request->input('alternate_mobile'),
                'assign_to'         => 0,
                'created_at'        => now(),
            ]);
            Sequence::where('type', 'customerNumber')->update(['sequenceNo' => $newSequenceNo]);

            // 2. Store quotation data
            $quotation = Quotation::create([
                'customer_id' => $customer->id,
                'required'    => $request->input('quotation_'),
                'amount'      => $request->input('quotation_amount'),
                'date'        => $request->input('quotation_date'),
                'by'          => $request->input('quotation_by'),
                'status'      => $request->input('quotation_status'),
                'created_at'  => now(),
            ]);

            // 2. Store quotation data
            $SolarDetail = SolarDetail::create([
                'customer_id' => $customer->id,
                'capacity' => $request->input('solar_capacity'),
                'roof_area' => $request->input('rooftop_size'),
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
        $isCustomer = $request->is_customer;

        $query = DB::table('quotations')
            ->leftJoin('customers', 'quotations.customer_id', '=', 'customers.id')
            ->leftJoin('solar_details', 'quotations.customer_id', '=', 'solar_details.customer_id')
            ->select(
                'quotations.id',
                'customers.customer_name',
                'customers.age',
                'customers.gender',
                'customers.marital_status',
                'customers.mobile',
                'customers.alternate_mobile',
                'quotations.required',
                'solar_details.capacity',
                'solar_details.roof_area',
                'quotations.amount',
                'quotations.date',
                'quotations.by',
                'quotations.status'
            );

        if ($isCustomer == 1) {
            $query->where('quotations.customer_id', $quotationId);
        } else {
            $query->where('quotations.id', $quotationId);
        }

        $quotation = $query->first();

        if ($quotation) {
            return ApiResponse::success($quotation, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
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
                'gender'            => $request->input('gender'),
                'marital_status'    => $request->input('marital_status'),
                'mobile'            => $request->input('mobile'),
                'alternate_mobile'  => $request->input('alternate_mobile'),
                'assign_to'         => 0,
                'updated_at'        => now(),
            ]);

            // 4. Update quotation data
            $quotation->update([
                'required'    => $request->input('quotation_'),
                'amount'      => $request->input('quotation_amount'),
                'date'        => $request->input('quotation_date'),
                'by'          => $request->input('quotation_by'),
                'status'      => $request->input('quotation_status'),
                'updated_at'  => now(),
            ]);

            $SolarDetail = SolarDetail::where('customer_id', $customer->id)->first();
            $SolarDetail->update([
                'capacity' => $request->input('solar_capacity'),
                'roof_area' => $request->input('rooftop_size'),
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
                DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name')
            )
            ->where('users.role_id', 4)
            ->whereNull('users.deleted_at')
            ->get();

        return ApiResponse::success($quotations, ResMessages::RETRIEVED_SUCCESS);
    }
}
