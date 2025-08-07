<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\SolarDetail;
use App\Models\Subsidy;
use App\Models\LoanBankDetail;
use App\Models\CustomerBankDetail;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Sequence;
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
    public function index(Request $request)
    {

        $cookieData = json_decode($request->cookie('user_data'), true);
        $roleCode = $cookieData['role_code'] ?? null;
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        $quotationsQuery = DB::table('customers')
            ->leftJoin('quotations', 'quotations.customer_id', '=', 'customers.id')
            ->leftJoin('subsidies', 'subsidies.customer_id', '=', 'customers.id')
            ->leftJoin('loan_bank_details', 'loan_bank_details.customer_id', '=', 'customers.id')
            ->leftJoin('solar_details', 'solar_details.customer_id', '=', 'customers.id')
            ->leftJoin('channel_partners', 'solar_details.channel_partner_id', '=', 'channel_partners.id')
            ->leftJoin('installers', 'solar_details.installers', '=', 'installers.id')
            ->leftJoin('users as assign_user', 'customers.assign_to', '=', 'assign_user.id')
            ->select(
                'customers.id',
                'customers.customer_number',
                'customers.customer_name',
                'customers.mobile',
                'customers.age',
                DB::raw("CONCAT(assign_user.first_name, ' ', assign_user.last_name) as assign_to_name"),
                'quotations.status',
                'subsidies.subsidy_status',
                'loan_bank_details.loan_status',
                'solar_details.loan_required',
                'installers.name as installer_name',
                'channel_partners.legal_name as channel_partner_name',
                'solar_details.solar_total_amount',
                'solar_details.is_completed',
            )
            ->where('quotations.status', '=', 'Agreed')
            ->whereNull('quotations.deleted_at');

        // Role-based filter
        if ($roleCode === $this->employeeRoleCode && $userId) {
            $quotationsQuery->where(function ($query) use ($userId) {
                $query->where('customers.assign_to', $userId)
                    ->orWhere('customers.assign_to', 0);
            });
        }

        $quotations = $quotationsQuery
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
        $cookieData = json_decode($request->cookie('user_data'), true);
        $roleCode = $cookieData['role_code'] ?? null;
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        if ($roleCode === $this->employeeRoleCode) {
            $id = $userId;
        } else {
            $id = 0;
        }
        DB::beginTransaction();

        try {
            $sequence = Sequence::where('type', 'customerNumber')->first();
            $newSequenceNo = $sequence->sequenceNo + 1;
            $customerNumber = $sequence->prefix . '-' . str_pad($newSequenceNo, 4, '0', STR_PAD_LEFT);
            // 1. Store customer data
            $customer = Customer::create([
                'customer_number' => $customerNumber,
                'customer_name'     => $request->input('customer_name'),
                'age'               => $request->input('age'),
                'gender'            => $request->input('gender'),
                'marital_status'    => $request->input('marital_status'),
                'mobile'            => $request->input('mobile'),
                'alternate_mobile'  => $request->input('alternate_mobile'),
                'assign_to'         => $id,
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

            // 3. Store solar detail data
            $solarDetail = SolarDetail::create([
                'customer_id'                => $customer->id,
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
                'cancel_cheque'              => $request->file('cancel_cheque')?->store('cheques'),
                'light_bill'                 => $request->file('light_bill')?->store('bills'),
                'consumer_no'                => $request->input('light_bill_no'),
                'application_ref_no'         => $request->input('application_ref_no'),
                'channel_partner_id'         => $request->input('channel_partner'),
                'registration_date'          => $request->input('registration_date'),
                'solar_total_amount'         => $request->input('solar_total_amount'),
                'installers'                 => $request->input('installers'),
                'customer_address'           => $request->input('customer_address'),
                'customer_residential_address' => $request->input('customer_residential_address'),
                'installation_date'          => $request->input('installation_date'),
                'total_received_amount'      => $request->input('total_received_amount'),
                'date_full_payment'          => $request->input('date_full_payment'),
                'is_completed'               => $request->input('is_completed'),
                'created_at'  => now(),
            ]);

            // 4. Store subsidy data
            $subsidy = Subsidy::create([
                'customer_id'     => $customer->id,
                'subsidy_amount'  => $request->input('subsidy_amount'),
                'subsidy_status'  => $request->input('subsidy_status'),
                'created_at'  => now(),
            ]);

            // 5. Store loan bank detail data
            $loan = LoanBankDetail::create([
                'customer_id'             => $customer->id,
                'solar_detail_id'         => $solarDetail->id,
                'bank_name'               => $request->input('bank_name_loan'),
                'bank_branch'             => $request->input('bank_branch_loan'),
                'account_number'          => $request->input('account_number_loan'),
                'ifsc_code'               => $request->input('ifsc_code_loan'),
                'branch_manager_phone'    => $request->input('branch_manager_phone_loan'),
                'loan_manager_phone'      => $request->input('loan_manager_phone_loan'),
                'loan_status'             => $request->input('loan_status'),
                'loan_sanction_date'      => $request->input('loan_sanction_date'),
                'loan_disbursed_date'     => $request->input('loan_disbursed_date'),
                'managed_by'              => $request->input('managed_by'),
                'created_at'  => now(),
            ]);

            // 6. Store customer bank detail data
            $bank = CustomerBankDetail::create([
                'customer_id'    => $customer->id,
                'bank_name'      => $request->input('bank_name'),
                'bank_branch'    => $request->input('bank_branch'),
                'account_number' => $request->input('account_number'),
                'ifsc_code'      => $request->input('ifsc_code'),
                'created_at'  => now(),
            ]);

            DB::commit();

            return ApiResponse::success($customer, ResMessages::CREATED_SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error('Failed to store quotation: ' . $e->getMessage(), 500);
        }
    }
    public function view(Request $request)
    {
        $customerId = $request->customerId;

        $customer = Customer::find($customerId);

        if (!$customer) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        try {
            // Get all related data for the customer
            $quotation = Quotation::where('customer_id', $customer->id)->first();
            $solarDetail = SolarDetail::where('customer_id', $customer->id)->first();
            $subsidy = Subsidy::where('customer_id', $customer->id)->first();
            $loanBankDetail = LoanBankDetail::where('customer_id', $customer->id)->first();
            $customerBankDetail = CustomerBankDetail::where('customer_id', $customer->id)->first();

            // Prepare comprehensive response data
            $responseData = [
                'customer' => $customer,
                'quotation' => $quotation,
                'solar_detail' => $solarDetail,
                'subsidy' => $subsidy,
                'loan_bank_detail' => $loanBankDetail,
                'customer_bank_detail' => $customerBankDetail,
            ];

            return ApiResponse::success($responseData, ResMessages::RETRIEVED_SUCCESS);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve customer data: ' . $e->getMessage(), 500);
        }
    }
    public function update(Request $request)
    {
        $customerId = $request->clientId;

        $customer = Customer::find($customerId);

        if (!$customer) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        DB::beginTransaction();

        try {
            // 1. Update customer data
            $customer->update([
                'customer_name'     => $request->input('customer_name'),
                'age'               => $request->input('age'),
                'gender'            => $request->input('gender'),
                'marital_status'    => $request->input('marital_status'),
                'mobile'            => $request->input('mobile'),
                'alternate_mobile'  => $request->input('alternate_mobile'),
                'updated_at'        => now(),
            ]);

            // 2. Update quotation data
            $quotation = Quotation::where('customer_id', $customer->id)->first();
            if ($quotation) {
                $quotation->update([
                    'required'    => $request->input('quotation_'),
                    'amount'      => $request->input('quotation_amount'),
                    'date'        => $request->input('quotation_date'),
                    'by'          => $request->input('quotation_by'),
                    'status'      => $request->input('quotation_status'),
                    'updated_at'  => now(),
                ]);
            }

            // 3. Update solar detail data
            $solarDetail = SolarDetail::where('customer_id', $customer->id)->first();
            if ($solarDetail) {
                $updateData = [
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
                    'consumer_no'                => $request->input('light_bill_no'),
                    'application_ref_no'         => $request->input('application_ref_no'),
                    'channel_partner_id'         => $request->input('channel_partner'),
                    'registration_date'          => $request->input('registration_date'),
                    'solar_total_amount'         => $request->input('solar_total_amount'),
                    'installers'                 => $request->input('installers'),
                    'customer_address'           => $request->input('customer_address'),
                    'customer_residential_address' => $request->input('customer_residential_address'),
                    'is_completed'               => $request->input('is_completed'),
                    'updated_at'  => now(),
                ];

                // Handle file uploads if new files are provided
                if ($request->hasFile('cancel_cheque')) {
                    $updateData['cancel_cheque'] = $request->file('cancel_cheque')->store('cheques');
                }
                if ($request->hasFile('light_bill')) {
                    $updateData['light_bill'] = $request->file('light_bill')->store('bills');
                }

                $solarDetail->update($updateData);
            }

            // 4. Update subsidy data
            $subsidy = Subsidy::where('customer_id', $customer->id)->first();
            if ($subsidy) {
                $subsidy->update([
                    'subsidy_amount'  => $request->input('subsidy_amount'),
                    'subsidy_status'  => $request->input('subsidy_status'),
                    'updated_at'  => now(),
                ]);
            }

            // 5. Update loan bank detail data
            $loan = LoanBankDetail::where('customer_id', $customer->id)->first();
            if ($loan) {
                $loan->update([
                    'bank_name'              => $request->input('bank_name_loan'),
                    'bank_branch'            => $request->input('bank_branch_loan'),
                    'account_number'         => $request->input('account_number_loan'),
                    'ifsc_code'              => $request->input('ifsc_code_loan'),
                    'branch_manager_phone'   => $request->input('branch_manager_phone_loan'),
                    'loan_manager_phone'     => $request->input('loan_manager_phone_loan'),
                    'loan_status'            => $request->input('loan_status'),
                    'updated_at'  => now(),
                ]);
            }

            // 6. Update customer bank detail data
            $bank = CustomerBankDetail::where('customer_id', $customer->id)->first();
            if ($bank) {
                $bank->update([
                    'bank_name'      => $request->input('bank_name'),
                    'bank_branch'    => $request->input('bank_branch'),
                    'account_number' => $request->input('account_number'),
                    'ifsc_code'      => $request->input('ifsc_code'),
                    'updated_at'  => now(),
                ]);
            }

            DB::commit();

            return ApiResponse::success($solarDetail, ResMessages::UPDATED_SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error('Failed to update customer data: ' . $e->getMessage(), 500);
        }
    }
    public function ClientDetails(Request $request)
    {

        $customerId = $request->id;

        $customer = Customer::find($customerId);

        if (!$customer) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $data = DB::table('customers')
            ->leftJoin('quotations', 'quotations.customer_id', '=', 'customers.id')
            ->leftJoin('subsidies', 'subsidies.customer_id', '=', 'customers.id')
            ->leftJoin('loan_bank_details', 'loan_bank_details.customer_id', '=', 'customers.id')
            ->leftJoin('solar_details', 'solar_details.customer_id', '=', 'customers.id')
            ->leftJoin('channel_partners', 'solar_details.channel_partner_id', '=', 'channel_partners.id')
            ->leftJoin('installers', 'solar_details.installers', '=', 'installers.id')
            ->leftJoin('users as assign_user', 'customers.assign_to', '=', 'assign_user.id')
            ->select(
                'customers.id',
                'customers.customer_number',
                'customers.customer_name',
                'customers.mobile',
                'customers.age',
                DB::raw("CONCAT(assign_user.first_name, ' ', assign_user.last_name) as assign_to_name"),
                'quotations.status',
                'subsidies.subsidy_status',
                'loan_bank_details.loan_status',
                'solar_details.loan_required',
                'installers.name as installer_name',
                'channel_partners.legal_name as channel_partner_name',
                'solar_details.*',
            )
            ->whereNull('customers.deleted_at')
            ->where('customers.id', $customerId)
            ->get();

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
}
