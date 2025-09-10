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
use App\Models\AppDocument;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateRoleRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

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
                DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as customer_name"),
                'customers.mobile',
                'customers.alternate_mobile',
                'customers.email',
                DB::raw("CONCAT(assign_user.first_name, ' ', assign_user.last_name) as assign_to_name"),
                'installers.name as installer_name',
                'solar_details.created_at',
                'solar_details.is_completed',
                'solar_details.capacity',
                'solar_details.solar_company',
                'solar_details.channel_partner_id',
                'channel_partners.legal_name as channel_partner_name',
                'quotations.amount',
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
                'first_name'     => $request->input('first_name'),
                'middle_name'     => $request->input('middle_name'),
                'last_name'     => $request->input('last_name'),
                'email'     => $request->input('email'),
                'pan_number'     => $request->input('pan_number'),
                'aadhar_number'     => $request->input('aadhar_number'),
                'age'               => $request->input('age'),
                'gender'            => $request->input('gender'),
                'marital_status'    => $request->input('marital_status'),
                'mobile'            => $request->input('mobile'),
                'alternate_mobile'  => $request->input('alternate_mobile'),
                'PerAdd_state'  => $request->input('PerAdd_state'),
                'district'  => $request->input('district'),
                'PerAdd_city'  => $request->input('PerAdd_city'),
                'PerAdd_pin_code'  => $request->input('PerAdd_pin_code'),
                'customer_address'           => $request->input('customer_address'),
                'customer_residential_address' => $request->input('customer_residential_address'),
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
                'solar_type'                  => $request->input('solar_type'),
                'panel_type'                  => $request->input('panel_type'),
                'panel_voltage'                  => $request->input('panel_voltage'),
                'number_of_panels'                  => $request->input('number_of_panels'),
                'inverter_serial_number'                  => $request->input('inverter_serial_number'),
                'inverter_capacity'                  => $request->input('inverter_capacity'),
                'jan_samarth_id'             => $request->input('jan_samarth_id'),
                'jan_samarth_registration_date'             => $request->input('jan_samarth_registration_date'),
                'roof_type'                  => $request->input('roof_type'),
                'roof_area'                  => $request->input('roof_area'),
                'capacity'                   => $request->input('solar_capacity'),
                'solar_company'              => $request->input('solar_company'),
                'inverter_company'           => $request->input('inverter_company'),
                'payment_mode'               => $request->input('payment_mode'),
                'light_bill_no'                => $request->input('light_bill_no'),
                'application_ref_no'         => $request->input('application_ref_no'),
                'channel_partner_id'         => $request->input('channel_partner'),
                'registration_date'          => $request->input('registration_date'),
                'solar_total_amount'         => $request->input('solar_total_amount'),
                'installers'                 => $request->input('installers'),
                'installation_date'          => $request->input('installation_date'),
                'total_received_amount'      => $request->input('total_received_amount'),
                'date_full_payment'          => $request->input('date_full_payment'),
                'structure_department_name'          => $request->input('structure_department_name'),
                'wiring_department_name'          => $request->input('wiring_department_name'),
                'sr_number'          => $request->input('sr_number'),
                'meter_payment_receipt_number'          => $request->input('meter_payment_receipt_number'),
                'meter_payment_date'          => $request->input('meter_payment_date'),
                'meter_payment_amount'          => $request->input('meter_payment_amount'),
                'panel_serial_numbers'          => $request->input('panel_serial_numbers'),
                'dcr_certificate_number'          => $request->input('dcr_certificate_number'),
                'is_completed'               => $request->input('is_completed'),
                'created_at'  => now(),
            ]);

            // 4. Store subsidy data
            $subsidy = Subsidy::create([
                'customer_id'     => $customer->id,
                'token_id'  => $request->input('token_id'),
                'subsidy_amount'  => $request->input('subsidy_amount'),
                'subsidy_status'  => $request->input('subsidy_status'),
                'created_at'  => now(),
            ]);

            // 5. Store loan bank detail data
            $loan = LoanBankDetail::create([
                'customer_id'             => $customer->id,
                'solar_detail_id'         => $solarDetail->id,
                'loan_type'               => $request->input('loan_type'),
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
                'first_name'     => $request->input('first_name'),
                'middle_name'     => $request->input('middle_name'),
                'last_name'     => $request->input('last_name'),
                'email'     => $request->input('email'),
                'pan_number'     => $request->input('pan_number'),
                'aadhar_number'     => $request->input('aadhar_number'),
                'age'               => $request->input('age'),
                'gender'            => $request->input('gender'),
                'marital_status'    => $request->input('marital_status'),
                'mobile'            => $request->input('mobile'),
                'alternate_mobile'  => $request->input('alternate_mobile'),
                'PerAdd_state'  => $request->input('PerAdd_state'),
                'district'  => $request->input('district'),
                'PerAdd_city'  => $request->input('PerAdd_city'),
                'PerAdd_pin_code'  => $request->input('PerAdd_pin_code'),
                'customer_address'           => $request->input('customer_address'),
                'customer_residential_address' => $request->input('customer_residential_address'),
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
                    'solar_type'                  => $request->input('solar_type'),
                    'panel_type'                  => $request->input('panel_type'),
                    'panel_voltage'                  => $request->input('panel_voltage'),
                    'number_of_panels'                  => $request->input('number_of_panels'),
                    'inverter_serial_number'                  => $request->input('inverter_serial_number'),
                    'inverter_capacity'                  => $request->input('inverter_capacity'),
                    'roof_type'                  => $request->input('roof_type'),
                    'roof_area'                  => $request->input('roof_area'),
                    'capacity'                   => $request->input('solar_capacity'),
                    'solar_company'              => $request->input('solar_company'),
                    'inverter_company'           => $request->input('inverter_company'),
                    'jan_samarth_id'             => $request->input('jan_samarth_id'),
                    'jan_samarth_registration_date'             => $request->input('jan_samarth_registration_date'),
                    'payment_mode'               => $request->input('payment_mode'),
                    'light_bill_no'                => $request->input('light_bill_no'),
                    'application_ref_no'         => $request->input('application_ref_no'),
                    'channel_partner_id'         => $request->input('channel_partner'),
                    'registration_date'          => $request->input('registration_date'),
                    'solar_total_amount'         => $request->input('solar_total_amount'),
                    'installers'                 => $request->input('installers'),
                    'installation_date'          => $request->input('installation_date'),
                    'total_received_amount'      => $request->input('total_received_amount'),
                    'date_full_payment'          => $request->input('date_full_payment'),
                    'structure_department_name'          => $request->input('structure_department_name'),
                    'wiring_department_name'          => $request->input('wiring_department_name'),
                    'sr_number'          => $request->input('sr_number'),
                    'meter_payment_receipt_number'          => $request->input('meter_payment_receipt_number'),
                    'meter_payment_date'          => $request->input('meter_payment_date'),
                    'meter_payment_amount'          => $request->input('meter_payment_amount'),
                    'panel_serial_numbers'          => $request->input('panel_serial_numbers'),
                    'dcr_certificate_number'          => $request->input('dcr_certificate_number'),
                    'is_completed'               => $request->input('is_completed'),
                    'updated_at'  => now(),
                ];

                $solarDetail->update($updateData);
            }

            Subsidy::updateOrCreate(
                ['customer_id' => $customer->id],
                [
                    'token_id'  => $request->input('token_id'),
                    'subsidy_amount' => $request->input('subsidy_amount'),
                    'subsidy_status' => $request->input('subsidy_status'),
                    'updated_at'     => now(),
                ]
            );
            LoanBankDetail::updateOrCreate(
                ['customer_id' => $customer->id],
                [
                    'solar_detail_id' => $solarDetail->id,
                    'loan_type'               => $request->input('loan_type'),
                    'bank_name'              => $request->input('bank_name_loan'),
                    'bank_branch'            => $request->input('bank_branch_loan'),
                    'account_number'         => $request->input('account_number_loan'),
                    'ifsc_code'              => $request->input('ifsc_code_loan'),
                    'branch_manager_phone'   => $request->input('branch_manager_phone_loan'),
                    'loan_manager_phone'     => $request->input('loan_manager_phone_loan'),
                    'loan_status'            => $request->input('loan_status'),
                    'loan_sanction_date'     => $request->input('loan_sanction_date'),
                    'loan_disbursed_date'    => $request->input('loan_disbursed_date'),
                    'managed_by'             => $request->input('managed_by'),
                    'updated_at'             => now(),
                ]

            );
            CustomerBankDetail::updateOrCreate(
                ['customer_id' => $customer->id], // search criteria
                [
                    'bank_name'      => $request->input('bank_name'),
                    'bank_branch'    => $request->input('bank_branch'),
                    'account_number' => $request->input('account_number'),
                    'ifsc_code'      => $request->input('ifsc_code'),
                    'updated_at'     => now(),
                ]
            );

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

        $quotation = Quotation::where('customer_id', $customer->id)->first();
        $appDocument = AppDocument::where('ref_primaryid', $customer->id)->get();

        $solarDetail = SolarDetail::query()
            ->leftJoin('channel_partners', 'solar_details.channel_partner_id', '=', 'channel_partners.id')
            ->leftJoin('installers', 'solar_details.installers', '=', 'installers.id')
            ->where('solar_details.customer_id', $customer->id)
            ->select('solar_details.*', 'channel_partners.legal_name as channel_partner_name', 'installers.name as installer_name')
            ->first();


        $subsidy = Subsidy::where('customer_id', $customer->id)->first();

        $loanBankDetail = LoanBankDetail::query()
            ->leftJoin('banks', 'loan_bank_details.bank_name', '=', 'banks.id')
            ->leftJoin('users', 'loan_bank_details.managed_by', '=', 'users.id')
            ->where('loan_bank_details.customer_id', $customer->id)
            ->select(
                'loan_bank_details.*',
                'banks.bank_name as bank_name',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as managed_by_name")
            )
            ->first();

        $customerBankDetail = CustomerBankDetail::query()
            ->leftJoin('banks', 'customer_bank_details.bank_name', '=', 'banks.id')
            ->where('customer_bank_details.customer_id', $customer->id)
            ->select('customer_bank_details.*', 'banks.bank_name as bank_name')
            ->first();

        $data = [
            'customer' => $customer,
            'quotation' => $quotation,
            'solar_detail' => $solarDetail,
            'subsidy' => $subsidy,
            'loan_bank_detail' => $loanBankDetail,
            'customer_bank_detail' => $customerBankDetail,
            'appDocument' => $appDocument,
        ];

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function uploadDocuments(Request $request)
    {
        $customerId = $request->input('clientId');

        $documents = [
            'aadhar'         => 'Aadhar',
            'pan'            => 'PAN',
            'light_bill'     => 'Light Bill',
            'bank_details'   => 'Bank Details',
            'bank_statement' => 'Bank Statement',
        ];

        $savedDocs = [];

        foreach ($documents as $field => $label) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/client_documents/' . $customerId, $fileName, 'public');

                $document = \App\Models\AppDocument::where('ref_primaryid', $customerId)
                    ->where('file_display_name', $label)
                    ->first();

                if (!$document) {
                    $document = new \App\Models\AppDocument();
                    $document->ref_primaryid = $customerId;
                    $document->user_id       = $customerId;
                    $document->file_id       = uniqid();
                    $document->created_by    = auth()->id() ?? 1;
                } else {
                    $document->updated_by = auth()->id() ?? 1;
                }

                // always update these fields
                $document->document_type     = "client_documents";
                $document->relative_path     = '/storage/' . $filePath;
                $document->extension         = $file->getClientOriginalExtension();
                $document->file_display_name = $label;
                $document->is_active         = 1;
                $document->save();

                $savedDocs[] = $document;
            }
        }

        if (count($savedDocs) > 0) {
            return ApiResponse::success($savedDocs, ResMessages::CREATED_SUCCESS);
        } else {
            return ApiResponse::error(null, 'No documents were uploaded.');
        }
    }
    public function downloadAnnexure2(Request $request)
    {
        $coustmerData = DB::table('customers')
            ->where('id', $request->id)
            ->select(
                'customers.*',
                DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as full_name")
            )
            ->first(); // use first() instead of get()

        if (!$coustmerData) {
            return ApiResponse::error('Customer not found');
        }

        $pdf = Pdf::loadView('client.annexure2', compact('coustmerData'));

        $directoryPath = storage_path('app/public/agreements');
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $date = now()->format('Y-m-d');
        $filename = "Annexure-2-Agreement-{$date}.pdf";

        $filePath = $directoryPath . "/{$filename}";
        $pdf->save($filePath);

        $fileUrl = asset("storage/agreements/{$filename}");

        return ApiResponse::success($fileUrl, 'Annexure 2 generated successfully');
    }
}
