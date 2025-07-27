<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use App\Http\Controllers\API\V1\TaskController;
use App\Http\Controllers\API\V1\ProjectsController as ApiProjectsController;
use Illuminate\Http\Request;
use App\Helpers\JWTUtils;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.PROJECTS')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return view('projects.projects_index', ['permissions' => $permissions, 'menuName' => $menuName]);
    }
    public function create(Request $request)
    {
        $projectId = $request->input('id');
        $params_id = $request->input('params_id');

        return view('projects.projects_create', compact('projectId', 'params_id'));
    }
    public function showDetails(Request $request, $project_id)
    {
        $request->merge(['project_id' => $project_id]);

        $apiController = new ApiProjectsController();
        $response = $apiController->showDetails($request);

        $responseData = $response->getData(true);

        $Data = $responseData['data'] ?? [];
        $projectFilesData = $responseData['data']['projectFilesData'] ?? [];

        $apiController = new TaskController();
        $response = $apiController->viewTask($request);

        $responseData = $response->getData(true);

        $tasksData = $responseData['data']['tasks'] ?? [];
        $completedTasksCount = (int) ($responseData['data']['completedTasksCount'] ?? 0);

        $totalTasks = count($tasksData);

        return view('projects.projects_Details', ['Data' => $Data, 'tasksData' => $tasksData, 'totalTasks' => $totalTasks, 'completedTasksCount' => $completedTasksCount, 'projectFilesData' => $projectFilesData]);
    }
    public function uploadDocuments(Request $request)
    {
        $projectId = $request->input('id');
        return view('projects.projects_documents', compact('projectId'));
    }
    public function kanbanView(Request $request)
    {
        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        $request->merge(['AccessCode' => config('menuAccessCode.KANBANVIEW')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        $request->merge(['userId' => $userId]);

        $apiController = new ApiProjectsController();
        $response = $apiController->getCurrentUserProId($request);

        $responseData = $response->getData(true);
        $projectData = $responseData['data'] ?? 0;

        return view('kanban.kanban_index', compact('userId', 'projectData', 'role_code'), ['permissions' => $permissions, 'menuName' => $menuName]);
    }
    public function editKanbanBoard(Request $request)
    {
        $Id = $request->input('id');
        return view('Kanban.kanban_edit', compact('Id'));
    }
    public function taskComment(Request $request)
    {
        $taskId = $request->input('id');

        $request->merge(['taskId' => $taskId]);

        $apiController = new TaskController();
        $response = $apiController->viewTask($request);
        $responseData = $response->getData(true);

        $taskDescription = isset($responseData['data']['description']) ? (string) $responseData['data']['description'] : '';
        $task_id = isset($responseData['data']['task_id']) ? (string) $responseData['data']['task_id'] : '';
        $taskTitle = isset($responseData['data']['title']) ? (string) $responseData['data']['title'] : '';

        return view('kanban.kanban_task_comment', compact('taskId', 'taskDescription', 'taskTitle', 'task_id'));
    }
}
