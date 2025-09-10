<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeeInfo;
use App\Models\EmployeeAddress;
use App\Models\BloodGroup;
use App\Models\MaritalStatus;
use App\Models\Country;
use App\Models\State;
use App\Models\EmployeeFinancial;
use App\Models\EmployeeEducation;
use App\Models\EmployeeExperience;
use App\Models\EmployeeVehicle;
use App\Models\AppDocument;
use App\Models\Department;
use App\Models\EmployeeJob;
use App\Models\Designation;
use App\Helpers\JWTUtils;
use App\Helpers\ApiResponse;
use App\Helpers\UserHelper;
use App\Constants\ResMessages;
use Illuminate\Http\Request;
use App\Http\Requests\StorePersonalInfoRequest;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\StoreBankDetailsRequest;
use App\Http\Requests\StoreEmployeeEducationRequest;
use App\Http\Requests\StoreEmployeeExperienceRequest;
use App\Http\Requests\StoreEmployeeVehicleRequest;
use App\Http\Requests\StoreEmployeeDocumentRequest;
use App\Http\Requests\StoreJobInformationRequest;
use Illuminate\Support\Facades\Storage;
use App\Enums\DocumentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class EmployeeProfileController extends Controller
{

    public function index(Request $request)
    {
        $uuid = $request->input('id');

        if ($uuid === null) {
            $uuid = $request->query('id');
        }

        $userId = UserHelper::getUserIdByUuid($uuid);

        $userData = User::select('id', 'first_name', 'middle_name', 'last_name')
            ->where('id',  $userId)
            ->first();

        $cachedData = Cache::remember('employee_related_data', 60, function () {
            return [
                'state' => State::select('id', 'name', 'country_id')->get(),
                'nationalities' => Country::select('id', 'name')->get(),
<<<<<<< HEAD
=======
                'employeeDepartment' => Department::select('id', 'name')->where('is_active', 1)->get(),
                'employeeDesignation' => Designation::select('id', 'name')->where('is_active', 1)->get(),
<<<<<<< Updated upstream
=======
>>>>>>> ae0de8c0b3495c6af2b125eaedc8fc553ae5043e
>>>>>>> Stashed changes
                'allUser' => User::select('id', 'first_name', 'last_name')
                    ->where('is_active', 1)
                    ->get()
                    ->map(function ($user) {
                        $user->full_name = trim("{$user->first_name} {$user->last_name}");
                        return $user;
                    })
            ];
        });

        $params = trim($request->get('Params'), "'");

        if ($params === 'Personal') {
            $employeeInfo = EmployeeInfo::where('user_id', $userId)->first();
        }
<<<<<<< Updated upstream
=======
<<<<<<< HEAD
=======
>>>>>>> Stashed changes
        if ($params === 'Document') {
            $employeeDocument = AppDocument::where('user_id', $userId)->get();

            $employeeDocument = $employeeDocument->map(function ($document) {
                $documentType = DocumentType::from($document->document_type);
                $document->document_type_name = $documentType->name;
                return $document;
            });
        }
<<<<<<< Updated upstream
=======
>>>>>>> ae0de8c0b3495c6af2b125eaedc8fc553ae5043e
>>>>>>> Stashed changes
        $data = [
            'user' => $userData,
            'state' => $cachedData['state'],
            'allUser' => $cachedData['allUser'],
<<<<<<< Updated upstream
=======
<<<<<<< HEAD
            'nationalities' => $cachedData['nationalities'],
=======
>>>>>>> ae0de8c0b3495c6af2b125eaedc8fc553ae5043e
>>>>>>> Stashed changes
            'employeeInfo' => $employeeInfo ?? null,
            'employeeFinancial' => $employeeFinancial ?? null,
            'employeeJob' => $employeeJob ?? null,
        ];

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }

    public function addEditImg(Request $request)
    {
        try {
            $request->validate([
                'profile_img' => 'required|image|mimes:jpeg,png,gif|max:200',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::error($e->errors());
        }

        $uuid = $request->input('user_uuid');
        $userId = UserHelper::getUserIdByUuid($uuid);

        Cache::forget('profile_header_' . $userId);

        $employeeInfo = EmployeeInfo::firstOrNew(['user_id' => $userId]);

        if ($request->hasFile('profile_img')) {
            $image = $request->file('profile_img');

            $fileId = uniqid();
            $fileExtension = $image->getClientOriginalExtension();

            // Combine the file ID and extension to create the full file name
            $fileName = $fileId . '.' . $fileExtension;

            $imagePath = $image->storeAs('profile_images', $fileName, 'public');

            if ($employeeInfo->profile_image) {
                Storage::disk('public')->delete('profile_images/' . $employeeInfo->profile_image);
            }

            $employeeInfo->profile_image = $fileName;
            $employeeInfo->save();

            return ApiResponse::success($employeeInfo, ResMessages::CREATED_SUCCESS);
        }

        return ApiResponse::error('No image uploaded.', 400);
    }
    public function experienceList($id)
    {
        $userData = User::select('id')
            ->where('uuid', $id)
            ->first();

        if (!$userData) {
            return ApiResponse::error(ResMessages::NOT_FOUND);
        }

        $id = $userData->id;

        $employeeExperience = DB::table('employee_experiences as ee')
            ->leftJoin('countries as c', 'ee.country', '=', 'c.id')
            ->leftJoin('states as s', 'ee.state', '=', 's.id')
            ->select(
                'ee.id',
                'ee.organization_name',
                'ee.from_date',
                'ee.to_date',
                'c.name as country_name',
                's.name as state_name',
                'ee.designation',
                'ee.city',
                'ee.file_display_name',
                'ee.experience_letter',
            )
            ->where('ee.user_id', $id)
            ->get()
            ->map(function ($experience) {
                // Format dates to dd/mm/yyyy
                $experience->from_date = \Carbon\Carbon::parse($experience->from_date)->format('d/m/Y');
                $experience->to_date = \Carbon\Carbon::parse($experience->to_date)->format('d/m/Y');
                return $experience;
            });

        return ApiResponse::success($employeeExperience, ResMessages::RETRIEVED_SUCCESS);
    }
    public function experienceDelete($id)
    {
        $EmployeeExperience = EmployeeExperience::find($id);

        if ($EmployeeExperience) {
            $EmployeeExperience->delete();
            return ApiResponse::success($EmployeeExperience, ResMessages::DELETED_SUCCESS, 1);
        } else {
            return ApiResponse::error($EmployeeExperience, ResMessages::NOT_FOUND);
        }
    }
    public function experienceView($id)
    {
        $employeeExperience = EmployeeExperience::findOrFail($id); // Will throw a 404 if not found
        $nationalities = Country::select('*')->get();
        $state = State::select('*')->get();

        $data = [
            'state' => $state,
            'nationalities' => $nationalities,
            'employeeExperience' => $employeeExperience,
        ];

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function vehicleList($id)
    {
        $userData = User::select('id')
            ->where('uuid',  $id)
            ->first();

        if (!$userData) {
            return ApiResponse::error(ResMessages::NOT_FOUND);
        }
        $id = $userData->id;
        $employeeVehicle = EmployeeVehicle::where('user_id', $id)->get();
        return ApiResponse::success($employeeVehicle, ResMessages::RETRIEVED_SUCCESS);
    }
    public function vehicleView($id)
    {
        $employeeVehicle = EmployeeVehicle::findOrFail($id);
        $data = ['employeeVehicle' => $employeeVehicle,];

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function vehicleDelete($id)
    {

        $employeeVehicle = EmployeeVehicle::find($id);

        if ($employeeVehicle) {
            $employeeVehicle->delete();
            return ApiResponse::success($employeeVehicle, ResMessages::DELETED_SUCCESS, 1);
        } else {
            return ApiResponse::error($employeeVehicle,  ResMessages::NOT_FOUND,);
        }
    }
    public function addEditProfile(StorePersonalInfoRequest $request)
    {
        $uuid = $request->input('user_uuid');
        $userId = UserHelper::getUserIdByUuid($uuid);

        $currentUser = JWTUtils::getCurrentUserByUuid();


        if (!$userId) {
            return ApiResponse::error(null,  ResMessages::NOT_FOUND);
        }

        $data = $request->all();
        $data['user_id'] = $userId;

        if ($userId !== $currentUser->id) {
            $data['updated_by'] = $currentUser->id;
            $data['updated_at'] = now();
        } else {
            $data['created_by'] = $userId;
            $data['created_at'] = now();
            $data['updated_at'] = null;
        }

        $personalInfo = EmployeeInfo::updateOrCreate(['user_id' => $userId], $data);

        return ApiResponse::success($personalInfo, ResMessages::CREATED_SUCCESS);
    }
    public function addEditAddress(StoreAddressRequest $request)
    {
        // Get the UUID from the request query
        $uuid = $request->query('id');
        // Get the user ID by UUID
        $userId = UserHelper::getUserIdByUuid($uuid);
        // Get the current authenticated user
        $currentUser = JWTUtils::getCurrentUserByUuid();
        // Get all the request data
        $addresses = $request->all();

        // Define a mapping for address types
        $addressTypes = [
            'PerAdd' => 'Permanent',
            'PreAdd' => 'Present',
            'EmgAdd' => 'Emergency'
        ];

        // Loop through each address type (Permanent, Present, Emergency)
        foreach ($addressTypes as $addressPrefix => $addressType) {
            $addressData = [];

            foreach ($addresses as $key => $value) {
                if (strpos($key, $addressPrefix) === 0) {
                    $newKey = substr($key, strlen($addressPrefix) + 1);
                    $addressData[$newKey] = $value;
                }
            }

            $addressData['user_id'] = $userId;
            $addressData['type'] = $addressType;

            // Apply the requested conditional logic
            if ($userId !== $currentUser->id) {
                $addressData['updated_by'] = $currentUser->id;
                $addressData['updated_at'] = now();
            } else {
                $addressData['created_by'] = $userId;
                $addressData['created_at'] = now();
                $addressData['updated_at'] = null;
            }

            $data = EmployeeAddress::updateOrCreate(
                ['user_id' => $userId, 'type' => $addressType],
                $addressData
            );
        }

        return ApiResponse::success($data, ResMessages::CREATED_SUCCESS);
    }
    public function addEditBankDetails(StoreBankDetailsRequest $request)
    {
        $uuid = $request->input('user_uuid');
        $userId = UserHelper::getUserIdByUuid($uuid);

        if (!$userId) {
            return ApiResponse::error(null,  ResMessages::NOT_FOUND);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $data = $request->all();
        $data['user_id'] = $userId;

        if ($userId !== $currentUser->id) {
            $data['updated_by'] = $currentUser->id;
            $data['updated_at'] = now();
        } else {
            $data['created_by'] = $userId;
            $data['created_at'] = now();
            $data['updated_at'] = null;
        }

        $BankDetails = EmployeeFinancial::updateOrCreate(['user_id' => $userId], $data);

        return ApiResponse::success($BankDetails, ResMessages::CREATED_SUCCESS);
    }
    public function addEditEducation(StoreEmployeeEducationRequest $request)
    {
        $uuid = $request->input('user_uuid');
        $userId = UserHelper::getUserIdByUuid($uuid);

        $currentUser = JWTUtils::getCurrentUserByUuid();

        if (!$userId) {
            return ApiResponse::error(null,  ResMessages::NOT_FOUND);
        }

        $data = $request->all();
        $data['user_id'] = $userId;

        if ($userId !== $currentUser->id) {
            $data['updated_by'] = $currentUser->id;
            $data['updated_at'] = now();
        } else {
            $data['created_by'] = $userId;
            $data['created_at'] = now();
            $data['updated_at'] = null;
        }

        $EducationDetails = EmployeeEducation::updateOrCreate(['user_id' => $userId], $data);

        return ApiResponse::success($EducationDetails, ResMessages::CREATED_SUCCESS);
    }
    public function addEditExperience(StoreEmployeeExperienceRequest $request)
    {
        $data = $request->validated();
        $uuid = $request->input('user_uuid');
        $userId = UserHelper::getUserIdByUuid($uuid);
        $currentUser = JWTUtils::getCurrentUserByUuid();

        if (!$userId) {
            return ApiResponse::error(null,  ResMessages::NOT_FOUND);
        }
        if ($data['id'] === null) {
            // Check if the same date range already exists for this user
            $existingExperience = EmployeeExperience::where('user_id', $userId)
                ->where(function ($query) use ($data) {
                    $query->whereBetween('from_date', [$data['from_date'], $data['to_date']])
                        ->orWhereBetween('to_date', [$data['from_date'], $data['to_date']])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('from_date', '<=', $data['from_date'])
                                ->where('to_date', '>=', $data['to_date']);
                        });
                })
                ->exists();

            if ($existingExperience) {
                return ApiResponse::error(null, 'This date range already exists in your existing experience data. Please select another date.');
            }
        }

        if ($request->hasFile('experience_letter')) {
            $file = $request->file('experience_letter');
            $originalFileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            // Extract file name without extension
            $fileDisplayName = pathinfo($originalFileName, PATHINFO_FILENAME);

            // Store the file and get the path
            $filePath = $file->storeAs('experience_letters', $originalFileName, 'public');
        }

        $data['user_id'] = $userId;
        if ($userId !== $currentUser->id) {
            $data['updated_by'] = $currentUser->id;
            $data['updated_at'] = now();
        } else {
            $data['created_by'] = $userId;
            $data['created_at'] = now();
            $data['updated_at'] = null;
        }
        $data['experience_letter'] = $filePath ?? null;
        $data['file_display_name'] = $fileDisplayName ?? null;

        $EmployeeExperience = EmployeeExperience::updateOrCreate(
            ['id' => $data['id'] ?? null, 'user_id' => $userId],
            $data
        );

        return ApiResponse::success($EmployeeExperience, ResMessages::CREATED_SUCCESS);
    }
    public function addEditVehicle(StoreEmployeeVehicleRequest $request)
    {
        $uuid = $request->input('user_uuid');
        $userId = UserHelper::getUserIdByUuid($uuid);

        $currentUser = JWTUtils::getCurrentUserByUuid();

        if (!$userId) {
            return ApiResponse::error(null,  ResMessages::NOT_FOUND);
        }

        $data = $request->validated();
        $data['user_id'] = $userId;

        if ($userId !== $currentUser->id) {
            $data['updated_by'] = $currentUser->id;
            $data['updated_at'] = now();
        } else {
            $data['created_by'] = $userId;
            $data['created_at'] = now();
            $data['updated_at'] = null;
        }

        $EmployeeVehicle = EmployeeVehicle::updateOrCreate(
            ['id' => $data['id'] ?? null, 'user_id' => $userId],
            $data
        );

        return ApiResponse::success($EmployeeVehicle, ResMessages::CREATED_SUCCESS);
    }
    public function addEditDocuments(StoreEmployeeDocumentRequest $request)
    {
        $uuid = $request->input('user_uuid');
        $userId = UserHelper::getUserIdByUuid($uuid);

        $currentUser = JWTUtils::getCurrentUserByUuid();

        if (!$userId) {
            return ApiResponse::error(null,  ResMessages::NOT_FOUND);
        }

        // Define a map between input names and enum values
        $fileEnums = [
            'resume_cv' => DocumentType::resume_cv,
            'offer_letter' => DocumentType::offer_letter,
            'contracts' => DocumentType::contracts,
            'id_proofs' => DocumentType::id_proofs,
            'work_permits_visa' => DocumentType::work_permits_visa,
        ];

        foreach ($fileEnums as $inputName => $enum) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);

                // Generate unique file ID and store the file
                $fileId = uniqid();
                $filePath = $file->storeAs('documents', $fileId . '.' . $file->getClientOriginalExtension(), 'public');

                // Get the file name without the extension
                $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $data = [
                    'user_id' => $userId,
                    'document_type' => $enum->value,
                    'relative_path' => $filePath,
                    'file_id' => $fileId,
                    'extension' => $file->getClientOriginalExtension(),
                    'file_display_name' => $fileDisplayName,
                    'is_active' => true,
                ];

                if ($userId !== $currentUser->id) {
                    $data['updated_by'] = $currentUser->id;
                    $data['updated_at'] = now();
                } else {
                    $data['created_by'] = $userId;
                    $data['created_at'] = now();
                    $data['updated_at'] = null;
                }

                AppDocument::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'document_type' => $enum->value
                    ],
                    $data
                );
            }
        }
        return ApiResponse::success(null, ResMessages::CREATED_SUCCESS);
    }
    public function addEditJob(StoreJobInformationRequest $request)
    {
        $uuid = $request->input('user_uuid');
        $userId = UserHelper::getUserIdByUuid($uuid);
        Cache::forget('profile_header_' . $userId);

        $currentUser = JWTUtils::getCurrentUserByUuid();

        if (!$userId) {
            return ApiResponse::error(null,  ResMessages::NOT_FOUND);
        }
        $data = $request->validated();
        $data['user_id'] = $userId;

        if ($userId !== $currentUser->id) {
            $data['updated_by'] = $currentUser->id;
            $data['updated_at'] = now();
        } else {
            $data['created_by'] = $userId;
            $data['created_at'] = now();
            $data['updated_at'] = null;
        }

        $jobDetails = EmployeeJob::updateOrCreate(
            ['user_id' => $userId],
            $data
        );

        return ApiResponse::success($jobDetails, ResMessages::CREATED_SUCCESS);
    }

    public function getPaymentMode()
    {
        $paymentMode = DB::table('payment_modes')
            ->select('id', 'name')
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->get();

        if ($paymentMode->isEmpty()) {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
        return ApiResponse::success($paymentMode, ResMessages::RETRIEVED_SUCCESS);
    }
}
