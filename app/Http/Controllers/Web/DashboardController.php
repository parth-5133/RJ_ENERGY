<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\ProjectsController as ApiProjectsController;
use App\Http\Controllers\API\V1\TaskController as ApiTaskController;
use App\Http\Controllers\API\V1\ClientController as ApiClientController;
use App\Http\Controllers\API\V1\superAdminDashboardController as ApiSuperAdminDashboardController;
use App\Http\Controllers\API\V1\usersController as ApiUsersController;
use App\Http\Controllers\API\V1\ConsumerApplicationController as ApiConsumerApplicationController;



class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cookieData = json_decode($request->cookie('user_data'), true);
        $roleCode = $cookieData['role_code'] ?? null;
        $name = $cookieData['name'] ?? null;
        $profileImg = $cookieData['profile_img'] ?? null;

        if ($roleCode === $this->employeeRoleCode || $roleCode === $this->AdminRoleCode) {
            $birthdayData = $this->getTodaysBirthday();
            $employeesList = $this->getEmployeesList($roleCode);
        }

        if ($roleCode === $this->AdminRoleCode) {

            $totalProjects = $this->getTotalProjects();
            $totalTasks = $this->getTotalTasks();
            $totalClient = $this->getTotalClient();
            $data = $this->getUserProjectsAndTasks($request);
            $selectedProjects = $data['selectedProjects'];
            $selectedTasks = $data['selectedTasks'];

            return view('dashboard.Admin_dashboard', compact('name', 'profileImg', 'totalProjects', 'totalTasks', 'totalClient', 'selectedProjects', 'selectedTasks', 'birthdayData', 'employeesList'));
        }

        $data = $this->getUserProjectsAndTasks($request);
        $selectedProjects = $data['selectedProjects'];
        $selectedTasks = $data['selectedTasks'];

        if ($roleCode === $this->superAdminRoleCode) {

            $data = $this->getCompanyOverview();

            return view('dashboard.superAdmin_dashboard', compact('name', 'profileImg', 'data'));
        }

        if ($roleCode === $this->clientRoleCode) {

            $ApiConsumerApplicationController = new ApiConsumerApplicationController();
            $applicationId = $ApiConsumerApplicationController->gettApplictaionId();
        }

        return match ($roleCode) {
            $this->clientRoleCode => view('dashboard.client_dashboard', compact('name', 'profileImg', 'selectedProjects', 'selectedTasks', 'applicationId')),
            default => view('dashboard.employee_dashboard', compact('name', 'profileImg', 'birthdayData', 'employeesList')),
        };
    }
    public function getTotalProjects()
    {
        $apiController = new ApiProjectsController();
        $response = $apiController->index();

        $projectsData = $response->getData(true)['data'] ?? [];
        $totalProjects = count($projectsData);

        return $totalProjects;
    }
    public function getTotalTasks()
    {
        $apiController = new ApiTaskController();
        $response = $apiController->index();

        $tasksData = $response->getData(true)['data'] ?? [];
        $totalTasks = count($tasksData);

        return $totalTasks;
    }
    public function getTotalClient()
    {
        $apiController = new ApiClientController();
        $response = $apiController->index();

        $ClientData = $response->getData(true)['data'] ?? [];
        $totalClient = count($ClientData);

        return $totalClient;
    }
    public function getUserProjectsAndTasks(Request $request): array
    {
        $projectController = new ApiProjectsController();
        $projectResponse = $projectController->GetAssignedProjects();
        $projectData = $projectResponse->getData(true)['data'] ?? [];

        $selectedProjects = array_slice($projectData, 0, 2);
        $projectIds = array_column($selectedProjects, 'id');

        $request->merge(['project_id' => $projectIds]);

        $taskController = new ApiTaskController();
        $taskResponse = $taskController->viewTask($request);
        if ($taskResponse) {
            $taskData = $taskResponse->getData(true)['data'] ?? [];
        } else {
            $taskData = [];
        }

        $tasks = $taskData['tasks'] ?? [];
        $selectedTasks = array_slice($tasks, 0, 6);

        foreach ($selectedProjects as &$project) {
            $projectTasks = collect($tasks)->where('project_id', $project['id']);
            $project['completedTasksCount'] = $projectTasks->where('status_name', 'Completed')->count();
            $project['totalTasks'] = $projectTasks->count();
        }

        return [
            'selectedProjects' => $selectedProjects,
            'selectedTasks' => $selectedTasks,
        ];
    }
    public function getCompanyOverview()
    {
        $apiController = new ApiSuperAdminDashboardController();

        $response = $apiController->getCompanyNumber();

        return $response->getData(true)['data'] ?? [];
    }
    public function getTodaysBirthday()
    {
        $apiController = new ApiUsersController();
        $response = $apiController->getTodaysBirthday();

        return $response->getData(true)['data'] ?? [];
    }
    public function getEmployeesList($roleCode)
    {
        if ($roleCode === $this->AdminRoleCode) {
            $isEmployee = false;
        } else {
            $isEmployee = true;
        }

        $apiController = new ApiUsersController();
        $response = $apiController->getEmployeesList($isEmployee);

        return $response->getData(true)['data'] ?? [];
    }

    public function benefits()
    {
        return view('consumer.client_benefits');
    }
}
