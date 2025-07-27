<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use App\Http\Controllers\API\V1\TaskController as ApiTaskController;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.TASKS')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return view('tasks.tasks_index', ['permissions' => $permissions, 'menuName' => $menuName,]);
    }

    public function create(Request $request)
    {
        $taskId = $request->input('id');
        $statusId = $request->input('params_id');


        if ($taskId > 0) {
            $request->merge(['taskId' => $taskId]);

            $apiController = new ApiTaskController();
            $response = $apiController->viewTask($request);
            $responseData = $response->getData(true);

            $taskDescription = isset($responseData['data']['description']) ? (string) $responseData['data']['description'] : '';
            $task_id = isset($responseData['data']['task_id']) ? (string) $responseData['data']['task_id'] : '';
            $taskTitle = isset($responseData['data']['title']) ? (string) $responseData['data']['title'] : '';

            $cookieData = json_decode(request()->cookie('user_data'), true);
            $role_code = $cookieData['role_code'] ?? null;
        } else {
            $role_code = $this->superAdminRoleCode;
        }

        if ($role_code == $this->employeeRoleCode) {
            return view('kanban.kanban_task_comment', compact('taskId', 'taskDescription', 'taskTitle', 'task_id'));
        }

        return view('tasks.tasks_create', compact('taskId', 'role_code', 'statusId'));
    }
}
