<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\TaskHistory;
use App\Models\KanbanColumn;
use App\Models\User;
use App\Mail\TaskStatusChangedMail;
use Illuminate\Support\Facades\Mail;
use App\Helpers\NotificationHelper;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Events\MessageSent;
use App\Helpers\GetCompanyId;
use App\Models\Role;
use App\Models\TaskTimeLog;
use Illuminate\Support\Facades\Auth;
use App\Helpers\UserHelper;

class KanbanController extends Controller
{
    public function kanbanViewData($projectId, $userId)
    {
        $roleId = User::where('id', $userId)->pluck('role_id')->first();
        $role = DB::table('roles')->where('id', $roleId)->value('code');

        $project = DB::table('projects')
            ->join('kanban_columns', 'projects.id', '=', 'kanban_columns.project_id')
            ->where('projects.id', $projectId)
            ->whereNull('kanban_columns.deleted_at')
            ->select('projects.project_name', 'kanban_columns.id', 'kanban_columns.column_name')
            ->orderBy('kanban_columns.position')
            ->get();

        $projectWithTasks = $project->map(function ($column) use ($userId, $role) {
            $tasksQuery = DB::table('tasks')
                ->where('status', $column->id)
                ->whereNull('deleted_at')
                ->select('title', 'id');
            if ($role === $this->employeeRoleCode) {
                $tasksQuery->where('user_id', $userId);
            }

            $column->tasks = $tasksQuery->get();
            return $column;
        });

        $kanbanData = $projectWithTasks->map(function ($column) {
            return [
                'id' => 'column-' . $column->id,
                'title' => $column->column_name,
                'item' => $column->tasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'comments' => null,
                        'badge-text' => null,
                        'badge' => null,
                        'due-date' => null,
                        'attachments' => null,
                        'assigned' => [],
                        'members' => [],
                    ];
                })->toArray(),
            ];
        })->toArray();

        return response()->json(['data' => $kanbanData]);
    }
    public function kanbanUpdateTask(Request $request)
    {
        $task_id = $request->task_id;
        $target_board_id = $request->target_board_id;
        $source_board_id = $request->source_board_id;
        $moved_by = $request->moved_by;

        $userId = UserHelper::getUserIdByUuid($moved_by);

        preg_match('/column-(\d+)/', $target_board_id, $targetBoardIdMatches);
        preg_match('/column-(\d+)/', $source_board_id, $sourceBoardIdMatches);

        $targetBoardId = isset($targetBoardIdMatches[1]) ? (int) $targetBoardIdMatches[1] : 0;
        $sourceBoardId = isset($sourceBoardIdMatches[1]) ? (int) $sourceBoardIdMatches[1] : 0;

        $task = Task::find($task_id);

        if ($task) {
            $oldValue = $task->status;

            $task->status = $targetBoardId;
            $task->updated_at = now();

            $task->save();

            if ($task) {
                $lastMovedLog = TaskTimeLog::where('task_id', $task->id)
                    ->orderBy('moved_at', 'desc')
                    ->first();

                $duration = null;
                if ($lastMovedLog && $lastMovedLog->moved_at) {
                    $duration = now()->diffInSeconds($lastMovedLog->moved_at);
                }

                TaskTimeLog::create([
                    'task_id' => $task->id,
                    'from_column_id' => $sourceBoardId,
                    'to_column_id' => $targetBoardId,
                    'moved_by' => $userId,
                    'entered_start_time' => $request->start_time,
                    'entered_end_time' => $request->end_time,
                    'duration_seconds' => $duration,
                    'is_manual' => false,
                    'moved_at' => now(),
                ]);
            }

            TaskHistory::create([
                'task_id' => $task->id,
                'changed_by' => $userId,
                'field_changed' => 'status',
                'old_value' => $oldValue,
                'new_value' => $task->status,
                'change_date' => now(),
                'created_at' => now(),
                'updated_at' => null,
            ]);

            $notify = NotificationHelper::canSendNotification('task_status_update');

            if ($notify['browser']) {

                $template = NotificationTemplate::where('template_name', 'task_status_changed')->first();

                $CompanyId = GetCompanyId::GetCompanyId();

                $user = Role::where('roles.company_id', $CompanyId)
                    ->where('roles.code', $this->AdminRoleCode)
                    ->whereNull('roles.deleted_at')
                    ->where('roles.is_active', true)
                    ->leftJoin('users', 'roles.id', '=', 'users.role_id')
                    ->whereNull('users.deleted_at')
                    ->first();

                $newStatus = KanbanColumn::where('position', $task->status)
                    ->where('project_id', $task->project_id)
                    ->value('column_name') ?? 'Unknown';

                $message = str_replace(
                    ['{task_title}', '{task_status}'],
                    [$task->title, $newStatus],
                    $template->message
                );

                Notification::create([
                    'company_id' => $CompanyId,
                    'user_id' => $user->id,
                    'title' => $template->title,
                    'message' => $message,
                    'has_view_button' => true,
                    'created_at' => now(),
                    'created_by' => $user->id,
                ]);

                event(new MessageSent($message, $user->id));
            }

            if ($notify['email']) {

                $Email = DB::table('email_settings')
                    ->where('company_id', $CompanyId)
                    ->where('is_active', true)
                    ->value('cc_mail_username');

                $ccEmail = $Email ? $Email :  env('CC_MAIL_USERNAME');

                try {
                    Mail::to($ccEmail)->send(new TaskStatusChangedMail($task, $oldValue, $task->status));
                } catch (\Exception $e) {
                    return ApiResponse::error($e->getMessage(), 'Your request has been sent successfully, but failed to send email notifications. Please contact support.');
                }
            }

            return ApiResponse::success($task, ResMessages::UPDATED_SUCCESS);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
    }
    public function kanbanCreateBoard(Request $request)
    {
        $lastColumn = KanbanColumn::where('project_id', $request->project_id)
            ->orderBy('position', 'desc')
            ->first();

        $newPosition = $lastColumn ? $lastColumn->position + 1 : 1;

        $kanbanColumn = KanbanColumn::create([
            'project_id' => $request->project_id,
            'column_name' => $request->title,
            'position' => $newPosition,
            'created_at' => now(),
            'updated_at' => null
        ]);

        return ApiResponse::success($kanbanColumn, ResMessages::CREATED_SUCCESS);
    }
    public function kanbanDeleteBoard($id)
    {
        $kanbanColumn = KanbanColumn::find($id);

        if ($kanbanColumn) {
            $kanbanColumn->delete();
            return ApiResponse::success($kanbanColumn, ResMessages::DELETED_SUCCESS, $kanbanColumn->project_id);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
    }
    public function kanbanUpdateBoardPosition(Request $request)
    {
        $target_board_id = $request->target_board_id;
        $source_board_id = $request->source_board_id;

        preg_match('/column-(\d+)/', $target_board_id, $targetBoardIdMatches);
        preg_match('/column-(\d+)/', $source_board_id, $sourceBoardIdMatches);

        $targetBoardId = isset($targetBoardIdMatches[1]) ? (int) $targetBoardIdMatches[1] : 0;
        $sourceBoardId = isset($sourceBoardIdMatches[1]) ? (int) $sourceBoardIdMatches[1] : 0;

        // Get the source and target boards
        $sourceBoard = KanbanColumn::find($sourceBoardId);
        $targetBoard = KanbanColumn::find($targetBoardId);

        if (!$sourceBoard || !$targetBoard) {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }

        // Get all boards for the project
        $projectBoards = KanbanColumn::where('project_id', $sourceBoard->project_id)
            ->orderBy('position')
            ->get();

        // Update positions
        $newPosition = $targetBoard->position;
        $oldPosition = $sourceBoard->position;

        // If moving forward in the list
        if ($oldPosition < $newPosition) {
            $projectBoards->where('position', '>', $oldPosition)
                ->where('position', '<=', $newPosition)
                ->each(function ($board) {
                    $board->position--;
                    $board->save();
                });
        }
        // If moving backward in the list
        else {
            $projectBoards->where('position', '>=', $newPosition)
                ->where('position', '<', $oldPosition)
                ->each(function ($board) {
                    $board->position++;
                    $board->save();
                });
        }

        // Update the source board's position
        $sourceBoard->position = $newPosition;
        $sourceBoard->save();

        return ApiResponse::success($sourceBoard, ResMessages::UPDATED_SUCCESS);
    }
    public function viewKanban(Request $request)
    {
        $id = $request->Id;

        $data = KanbanColumn::find($id);
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
    public function kanbanUpdateBoard(Request $request)
    {
        $id = $request->id;
        $data = KanbanColumn::find($id);
        if (!$data) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }
        if ($data) {
            $data->column_name = $request->title;
            $data->position = $request->display_order;
            $data->updated_at = now();
            $data->save();
            return ApiResponse::success($data, ResMessages::UPDATED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
}
