<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\SolarDetail;
use App\Models\Subsidy;
use App\Models\LoanBankDetail;
use App\Models\CustomerBankDetail;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateRoleRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index()
    {
        $quotations = DB::table('quotations')
            ->leftJoin('customers', 'quotations.customer_id', '=', 'customers.id')
            ->select(
                'customers.id',
                'customers.customer_name',
                'customers.mobile',
                'customers.age',
                'quotations.status'
            )
            ->where('quotations.status', '=', 'Agreed')
            ->whereNull('quotations.deleted_at')
            ->orderBy('quotations.id', 'desc')
            ->get();

        return ApiResponse::success($quotations, ResMessages::RETRIEVED_SUCCESS);
    }

    public function accept(Request $request)
    {
        $customerId = $request->input('id');
        if (!$customerId) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 400);
        }

        $customer = DB::table('customers')->where('id', $customerId)->first();
        if (!$customer) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        if ($customer->assign_to === JWTUtils::getCurrentUserByUuid()->id) {
            return ApiResponse::error(null, "You have already accepted this customer.");
        }

        if ($customer->assign_to) {
            return ApiResponse::error(null, "This customer has already been accepted another Registrar.");
        }

        // Update the customer status to 'Accepted'
        DB::table('customers')
            ->where('id', $customerId)
            ->update(['assign_to' => JWTUtils::getCurrentUserByUuid()->id]);

        return ApiResponse::success(null, ResMessages::UPDATED_SUCCESS);
    }


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Store customer data
            $customer = SolarDetail::create([
                'customer_id'                => $request->input('customer_id'),
                'roof_type'                  => $request->input('roof_type'),
                'roof_area'                  => $request->input('roof_area'),
                'usage_pattern'              => $request->input('usage_pattern'),
                'capacity'                   => $request->input('solar_capacity'),
                'solar_company'              => $request->input('solar_company'),
                'inverter_company'           => $request->input('inverter_company'),
                'jan_samarth_id'             => $request->input('jan_samarth_id'),
                'acknowledge_no'             => $request->input('acknowledge_no'),
                'loan_required'              => $request->input('loan_'),
                'payment_mode'               => $request->input('payment_mode'),
                'cancel_cheque'              => $request->file('cancel_cheque')?->store('cheques'), // Optional
                'light_bill'                 => $request->file('light_bill')?->store('bills'),     // Optional
                'consumer_no'                => $request->input('light_bill_no'),
                'application_ref_no'         => $request->input('application_ref_no'),
                'channel_partner_id'         => $request->input('channel_partner'),
                'registration_date'          => $request->input('registration_date'),
                'solar_total_amount'         => $request->input('solar_total_amount'),
                'installers'                 => $request->input('installers'),
                'customer_address'           => $request->input('customer_address'),
                'customer_residential_address' => $request->input('customer_residential_address'),
            ]);

            $subsidy = Subsidy::create([
                'customer_id'     => $request->input('customer_id'),
                'subsidy_amount'  => $request->input('subsidy_amount'),
                'subsidy_status'  => $request->input('subsidy_status'),
            ]);

            $loan = LoanBankDetail::create([
                'customer_id'            => $request->input('customer_id'),
                'solar_detail_id'        => $customer->id,
                'bank_name'              => $request->input('bank_name_loan'),
                'bank_branch'            => $request->input('bank_branch_loan'),
                'account_number'         => $request->input('account_number_loan'),
                'ifsc_code'              => $request->input('ifsc_code_loan'),
                'branch_manager_phone'   => $request->input('branch_manager_phone_loan'),
                'loan_manager_phone'     => $request->input('loan_manager_phone_loan'),
                'loan_status'            => $request->input('loan_status'),
            ]);

            $bank = CustomerBankDetail::create([
                'customer_id'    => $request->input('customer_id'),
                'bank_name'      => $request->input('bank_name'),
                'bank_branch'    => $request->input('bank_branch'),
                'account_number' => $request->input('account_number'),
                'ifsc_code'      => $request->input('ifsc_code'),
            ]);


            DB::commit();

            return ApiResponse::success(null, ResMessages::CREATED_SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error('Failed to store quotation: ' . $e->getMessage(), 500);
        }
    }
}
