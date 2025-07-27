<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TasksRequest;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Helpers\GetCompanyId;
use App\Enums\DocumentType;
use App\Enums\TeamType;
use App\Constants\ResMessages;
use App\Helpers\JWTUtils;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\KanbanColumn;
use App\Models\TaskComment;
use App\Models\User;
use App\Models\Project;
use App\Models\TaskHistory;
use App\Models\AppDocument;
use App\Models\Sequence;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\TaskTimeLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Events\MessageSent;
use App\Mail\TaskAssignedMail;
use Illuminate\Support\Facades\Mail;
use App\Helpers\NotificationHelper;
use App\Helpers\TaskTimeHelper;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $isAdminOrSuperAdmin = in_array($role_code, [$this->AdminRoleCode, $this->superAdminRoleCode]);
        $isClient = in_array($role_code, [$this->clientRoleCode]);


        if ($isAdminOrSuperAdmin) {
            $tasksData = DB::table('tasks')
                ->leftJoin('users', 'tasks.updated_by', '=', 'users.id')
                ->leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
                ->leftJoin('kanban_columns', function ($join) {
                    $join->on('tasks.project_id', '=', 'kanban_columns.project_id')
                        ->on('tasks.status', '=', 'kanban_columns.id');
                })
                ->whereNull('tasks.deleted_at')
                ->select(
                    'tasks.*',
                    'projects.project_name as project_name',
                    'kanban_columns.column_name as status_name',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                    DB::raw("DATE_FORMAT(tasks.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                    DB::raw("DATE_FORMAT(tasks.due_date, '%d/%m/%Y') as due_date_formatted")
                );

            if ($companiesId) {
                $tasksData->where('tasks.company_id', $companiesId);
            }

            $tasksData = $tasksData->orderByDesc('tasks.id')->get();
        } elseif ($isClient) {

            $projectsId = DB::table('projects')
                ->where('client', $currentUser->id)
                ->where('company_id', $companiesId)
                ->pluck('id')
                ->toArray();

            $tasksData = DB::table('tasks')
                ->leftJoin('users', 'tasks.updated_by', '=', 'users.id')
                ->leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
                ->leftJoin('kanban_columns', function ($join) {
                    $join->on('tasks.project_id', '=', 'kanban_columns.project_id')
                        ->on('tasks.status', '=', 'kanban_columns.id');
                })
                ->whereNull('tasks.deleted_at')
                ->whereIn('tasks.project_id', $projectsId)
                ->select(
                    'tasks.*',
                    'projects.project_name as project_name',
                    'kanban_columns.column_name as status_name',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                    DB::raw("DATE_FORMAT(tasks.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                    DB::raw("DATE_FORMAT(tasks.due_date, '%d/%m/%Y') as due_date_formatted")
                );

            if ($companiesId) {
                $tasksData->where('tasks.company_id', $companiesId);
            }

            $tasksData = $tasksData->orderByDesc('tasks.id')->get();
        } else {
            $tasksData = DB::table('tasks')
                ->leftJoin('users', 'tasks.updated_by', '=', 'users.id')
                ->leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
                ->leftJoin('kanban_columns', function ($join) {
                    $join->on('tasks.project_id', '=', 'kanban_columns.project_id')
                        ->on('tasks.status', '=', 'kanban_columns.id');
                })
                ->whereNull('tasks.deleted_at')
                ->where('tasks.user_id', $currentUser->id)
                ->select(
                    'tasks.*',
                    'projects.project_name as project_name',
                    'kanban_columns.column_name as status_name',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                    DB::raw("DATE_FORMAT(tasks.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                    DB::raw("DATE_FORMAT(tasks.due_date, '%d/%m/%Y') as due_date_formatted")
                );

            if ($companiesId) {
                $tasksData->where('tasks.company_id', $companiesId);
            }

            $tasksData = $tasksData->orderByDesc('tasks.id')->get();
        }

        $tasksData->transform(function ($task) {
            $workedTime = TaskTimeHelper::calculateTotalTaskTime($task->id);

            $task->total_worked_time = $workedTime;

            return $task;
        });

        return ApiResponse::success($tasksData, ResMessages::RETRIEVED_SUCCESS);
    }
    public function createTask(TasksRequest $request)
    {
        $projectId = $request->project_id;

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        if ($request->statusId === null) {
            $statusId = DB::table('kanban_columns')
                ->where('project_id', $projectId)
                ->where('position', 1)
                ->select('id')
                ->first();

            $status_id = $statusId->id ?? 1;
        } else {
            $status_id = $request->statusId;
        }

        $taskData = $request->except(['team_members']);
        $taskData['user_id'] = $request->team_members;
        $taskData['created_by'] = $currentUser->id;
        $taskData['created_at'] = now();
        $taskData['updated_at'] = null;
        $taskData['status'] = $status_id;
        $taskData['company_id'] = $CompanyId;

        $sequence = Sequence::where('type', 'TaskID')->first();
        $newSequenceNo = $sequence->sequenceNo + 1;
        $taskId = $sequence->prefix . '-' . str_pad($newSequenceNo, 4, '0', STR_PAD_LEFT);
        $taskData['task_id'] = $taskId;

        Sequence::where('type', 'TaskID')->update(['sequenceNo' => $newSequenceNo]);

        $task = Task::create($taskData);

        if ($task) {
            TaskTimeLog::create([
                'task_id' => $task->id,
                'status_id' => $task->status,
                'start_time' => null,
                'end_time' => null,
            ]);
        }

        if (!empty($request->document)) {
            $file = $request->document;

            $fileId = uniqid();

            $basePath = 'attachments/project/' . $projectId . '/task/' . $task->id;

            if (!Storage::disk('public')->exists($basePath)) {
                Storage::disk('public')->makeDirectory($basePath);
            }

            $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');


            $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $documentTypeId = DocumentType::task_document;

            $EmployeeDocument = AppDocument::create([
                'ref_primaryid' => $task->id,
                'document_type' => $documentTypeId,
                'relative_path' => $filePath,
                'file_id' => $fileId,
                'extension' => $file->getClientOriginalExtension(),
                'file_display_name' => $fileDisplayName,
                'is_active' => true,
                'created_by' => $currentUser->id,
                'created_at' => now(),
            ]);
        }

        $notify = NotificationHelper::canSendNotification('task_assignment');

        if ($notify['browser']) {

            if ($request->team_members) {
                $template = NotificationTemplate::where('template_name', 'task_assigned')->first();

                $createdBy = User::where('id', $task->created_by)
                    ->selectRaw("CONCAT(first_name, ' ', last_name) AS full_name")
                    ->value('full_name');
                $projectName = Project::where('id', $task->project_id)->value('project_name');

                $title = str_replace(
                    ['{Create_by}', '{task_title}', '{project_name}'],
                    [$createdBy, $task->title, $projectName],
                    $template->title
                );

                $message = str_replace(
                    ['{Create_by}', '{task_title}', '{project_name}'],
                    [$createdBy, $task->title, $projectName],
                    $template->message
                );

                Notification::create([
                    'company_id' => $CompanyId,
                    'user_id' => $request->team_members,
                    'title' => $title,
                    'message' => $message,
                    'has_view_button' => true,
                    'created_at' => now(),
                    'created_by' => $currentUser->id,
                ]);

                event(new MessageSent($message, $request->team_members));
            }
        }

        if ($notify['email']) {

            $assignedUser = User::where('id', $request->team_members)
                ->select('email', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))
                ->first();

            $Email = DB::table('email_settings')
                ->where('company_id', $CompanyId)
                ->where('is_active', true)
                ->value('cc_mail_username');

            $ccEmail = $Email ? $Email :  env('CC_MAIL_USERNAME');

            try {
                Mail::to($assignedUser->email)->cc($ccEmail)->send(new TaskAssignedMail($task, $projectName, $createdBy, $assignedUser->full_name));
            } catch (\Exception $e) {
                return ApiResponse::error($e->getMessage(), 'Your request has been sent successfully, but failed to send email notifications. Please contact support.');
            }
        }

        return ApiResponse::success($task, ResMessages::CREATED_SUCCESS);
    }
    public function viewTask(Request $request)
    {
        $taskId = $request->taskId;
        $projectId = $request->project_id ?? null;


        if ($taskId) {
            $task = DB::table('tasks')
                ->leftJoin('kanban_columns', function ($join) {
                    $join->on('tasks.status', '=', 'kanban_columns.id')
                        ->whereRaw('tasks.project_id = kanban_columns.project_id');
                })
                ->leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
                ->leftJoin('users', 'tasks.created_by', '=', 'users.id') // Join with users table
                ->where('tasks.id', $taskId)
                ->select(
                    'tasks.*',
                    'kanban_columns.column_name as status_name',
                    'projects.project_name',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name) as created_by_name") // Concatenate first_name and last_name
                )
                ->first();

            if ($task) {
                $documents = DB::table('app_documents')
                    ->where('ref_primaryid', $task->id)
                    ->where('document_type', DocumentType::task_document)
                    ->get();

                $response = [
                    'id' => $task->id,
                    'user_id' => $task->user_id,
                    'task_id' => $task->task_id,
                    'title' => $task->title,
                    'due_date' => $task->due_date,
                    'start_time' => $task->start_time,
                    'end_time' => $task->end_time,
                    'project' => $task->project_id,
                    'project_name' => $task->project_name,
                    'status' => $task->status,
                    'status_name' => $task->status_name,
                    'priority' => $task->priority,
                    'description' => $task->description,
                    'documents' => $documents,
                    'created_by' => $task->created_by_name,
                    'created_at' => $task->created_at,
                ];

                return ApiResponse::success($response, ResMessages::RETRIEVED_SUCCESS);
            } else {
                return ApiResponse::error([], ResMessages::NOT_FOUND);
            }
        }

        if (!empty($projectId)) {

            $projectIds = is_array($projectId) ? $projectId : [$projectId];


            $tasks = DB::table('tasks')
                ->leftJoin('kanban_columns', function ($join) {
                    $join->on('tasks.status', '=', 'kanban_columns.id')
                        ->whereRaw('tasks.project_id = kanban_columns.project_id');
                })
                ->whereIn('tasks.project_id', $projectIds)
                ->whereNull('tasks.deleted_at')
                ->select(
                    'tasks.*',
                    'kanban_columns.column_name as status_name'
                )
                ->get();

            $completedTasksCount = $tasks->where('status_name', 'Completed')->count();

            $data = [
                'completedTasksCount' => $completedTasksCount,
                'tasks' => $tasks
            ];

            if ($tasks->isEmpty()) {
                return ApiResponse::error([], ResMessages::NOT_FOUND);
            }

            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        }
    }
    public function updateTask(TasksRequest $request)
    {
        $projectId = $request->project_id;
        $roleCode = $request->roleCode;

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $task = Task::findOrFail($request->input('taskId'));

        if ($roleCode == $this->employeeRoleCode) {
            $oldStatus = $task->status;

            $task = Task::where('id', $request->input('taskId'))
                ->update([
                    'status' => $request->input('status'),
                    'updated_by' => $currentUser->id,
                    'updated_at' => now(),
                ]);


            // Log status change in task_history
            if ($oldStatus !== $request->input('status')) {
                TaskHistory::create([
                    'task_id' => $task->id,
                    'changed_by' => $currentUser->id,
                    'field_changed' => 'status',
                    'old_value' => $oldStatus,
                    'new_value' => $request->input('status'),
                    'change_date' => now(),
                ]);
            }

            if ($request->input('comments')) {
                $taskComment = [
                    'task_id' => $request->input('taskId'),
                    'commented_by' => $currentUser->id,
                    'comment_text' => $request->input('comments'),
                    'created_at' => now(),
                    'updated_at' => null,
                ];
                TaskComment::create($taskComment);
            }
            return ApiResponse::success(null, ResMessages::UPDATED_SUCCESS);
        }

        if ($task) {
            $fieldsToUpdate = [
                'title' => $request->input('title'),
                'due_date' => $request->input('due_date'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'project_id' => $request->input('project_id'),
                'status' => $request->input('status'),
                'priority' => $request->input('priority'),
                'description' => $request->input('description'),
                'user_id' => $request->input('team_members'),
            ];

            $oldValues = $task->only(array_keys($fieldsToUpdate));

            $task->fill($fieldsToUpdate);
            $task->updated_by = $currentUser->id;
            $task->updated_at = now();
            $task->save();

            // Log changes in task_history
            foreach ($fieldsToUpdate as $field => $newValue) {
                if ($oldValues[$field] != $newValue) {
                    TaskHistory::create([
                        'task_id' => $task->id,
                        'changed_by' => $currentUser->id,
                        'field_changed' => $field,
                        'old_value' => $oldValues[$field],
                        'new_value' => $newValue,
                        'change_date' => now(),
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                }
            }

            if ($request->input('comments')) {
                $taskComment = [
                    'task_id' => $request->input('taskId'),
                    'commented_by' => $currentUser->id,
                    'comment_text' => $request->input('comments'),
                    'created_at' => now(),
                    'updated_at' => null,
                ];
                TaskComment::create($taskComment);
            }

            if (!empty($request->document)) {
                $file = $request->document;
                $fileId = uniqid();

                $basePath = 'attachments/project/' . $projectId . '/task/' . $task->id;

                if (!Storage::disk('public')->exists($basePath)) {
                    Storage::disk('public')->makeDirectory($basePath);
                }

                $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');
                $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $documentTypeId = DocumentType::task_document;

                $existingDocument = AppDocument::where('ref_primaryid', $task->id)
                    ->where('document_type', $documentTypeId)
                    ->first();

                if ($existingDocument) {
                    $existingDocument->update([
                        'relative_path' => $filePath,
                        'file_id' => $fileId,
                        'extension' => $file->getClientOriginalExtension(),
                        'file_display_name' => $fileDisplayName,
                        'updated_at' => now(),
                        'updated_by' => $currentUser->id,
                    ]);
                } else {
                    AppDocument::create([
                        'ref_primaryid' => $task->id,
                        'document_type' => $documentTypeId,
                        'relative_path' => $filePath,
                        'file_id' => $fileId,
                        'extension' => $file->getClientOriginalExtension(),
                        'file_display_name' => $fileDisplayName,
                        'is_active' => true,
                        'created_by' => $currentUser->id,
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                }
            }

            if ($task) {

                $durationSeconds = null;

                if ($request->input('start_time') && $request->input('end_time')) {
                    $start = Carbon::parse($request->input('start_time'));
                    $end = Carbon::parse($request->input('end_time'));
                    $durationSeconds = $end->diffInSeconds($start);
                }

                TaskTimeLog::create([
                    'task_id' => $task->id,
                    'from_column_id' => $task->status,
                    'to_column_id' => $task->status,
                    'moved_by' => $currentUser->id,
                    'entered_start_time' => $request->input('start_time'),
                    'entered_end_time' => $request->input('end_time'),
                    'duration_seconds' => $durationSeconds,
                    'is_manual' => true,
                    'moved_at' => now(),
                ]);
            }

            return ApiResponse::success($task, ResMessages::UPDATED_SUCCESS);
        } else {
            return ApiResponse::error([], ResMessages::NOT_FOUND);
        }
    }
    public function deleteTask($id)
    {
        $Task = Task::find($id);

        if ($Task) {
            $Task->delete();
            return ApiResponse::success($Task, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($Task, ResMessages::NOT_FOUND);
        }
    }
    public function getTaskStatus(Request $request)
    {
        $projectId = $request->projectId;

        $status = DB::table('kanban_columns')
            ->where('project_id', $projectId)
            ->whereNull('deleted_at')
            ->select('id', 'column_name')
            ->get();

        return ApiResponse::success($status, ResMessages::RETRIEVED_SUCCESS);
    }
    public function storeComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'taskId' => 'required|exists:tasks,id',
            'chatInput' => 'nullable|string|max:1000',
            'status' => 'nullable|exists:kanban_columns,id',
            'start_time' => 'nullable',
            'end_time' => 'nullable|after_or_equal:start_time',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error(null, $validator->errors());
        }

        $taskId = $request->input('taskId');
        $chatInput = trim($request->input('chatInput'));
        $status = $request->input('status');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $currentUser = JWTUtils::getCurrentUserByUuid();

        // Validate start and end time if status is not "To Do"
        if (!empty($status)) {
            $statusName = DB::table('kanban_columns')
                ->where('id', $status)
                ->value('column_name');

            if ($statusName !== 'To Do') {
                if (empty($startTime) || empty($endTime)) {
                    return ApiResponse::error(null, 'Start time and end time are required for this status.');
                }
            }
        }

        if (!empty($chatInput)) {
            TaskComment::create([
                'task_id' => $taskId,
                'commented_by' => $currentUser->id,
                'comment_text' => $chatInput,
                'created_at' => now(),
            ]);
        }

        if (!empty($status)) {
            DB::beginTransaction();

            try {
                // Update task
                Task::where('id', $taskId)->update([
                    'status' => $status,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'updated_at' => now(),
                    'updated_by' => $currentUser->id,
                ]);

                // Calculate duration
                $durationSeconds = null;
                if (!empty($startTime) && !empty($endTime)) {
                    $start = Carbon::parse($startTime);
                    $end = Carbon::parse($endTime);
                    $durationSeconds = $end->diffInSeconds($start);
                }

                // Log time entry
                TaskTimeLog::create([
                    'task_id' => $taskId,
                    'from_column_id' => $status,
                    'to_column_id' => $status,
                    'moved_by' => $currentUser->id,
                    'entered_start_time' => $startTime,
                    'entered_end_time' => $endTime,
                    'duration_seconds' => $durationSeconds,
                    'is_manual' => true,
                    'moved_at' => now(),
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return ApiResponse::error($e->getMessage(), ResMessages::UNPROCESSABLE_ENTITY);
            }
        }

        return ApiResponse::success([], ResMessages::CREATED_SUCCESS, 1);
    }
    public function getTaskComments(Request $request)
    {
        $taskId = $request->taskId;
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $currentUserId = $currentUser->id;

        $taskComments = TaskComment::where('task_comments.task_id', $taskId)
            ->join('users', 'task_comments.commented_by', '=', 'users.id')
            ->select(
                'task_comments.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as commented_by_name")
            )
            ->get();

        return ApiResponse::success([
            'comments' => $taskComments,
            'currentUserId' => $currentUserId
        ], ResMessages::RETRIEVED_SUCCESS);
    }

    public function taskStatusLog(Request $request)
    {
        $taskId = $request->taskId;

        $taskStatusLog = TaskTimeLog::where('task_id', $taskId)
            ->join('users', 'task_time_logs.moved_by', '=', 'users.id')
            ->join('kanban_columns as from_column', 'task_time_logs.from_column_id', '=', 'from_column.id')
            ->join('kanban_columns as to_column', 'task_time_logs.to_column_id', '=', 'to_column.id')
            ->select(
                'task_time_logs.*',
                'from_column.column_name as from_column_name',
                'to_column.column_name as to_column_name',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as moved_by_name")
            )
            ->get();

        return ApiResponse::success($taskStatusLog, ResMessages::RETRIEVED_SUCCESS);
    }
}
