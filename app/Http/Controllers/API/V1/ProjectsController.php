<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Helpers\GetCompanyId;
use App\Enums\DocumentType;
use App\Enums\TeamType;
use App\Constants\ResMessages;
use App\Helpers\JWTUtils;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectDocumentUploadRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\KanbanColumn;
use App\Models\AppDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\Sequence;
use Illuminate\Support\Facades\Auth;
use App\Helpers\TaskTimeHelper;

class ProjectsController extends Controller
{
    public function GetAssignedProjects()
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        if ($role_code == $this->clientRoleCode) {
            $projectsId = DB::table('projects')
                ->where('client', $currentUser->id)
                ->where('company_id', $companiesId)
                ->pluck('id')
                ->toArray();
        } elseif ($role_code == $this->AdminRoleCode || $role_code == $this->superAdminRoleCode) {
            $projectsId = DB::table('projects')
                ->where('company_id', $companiesId)
                ->pluck('id')
                ->toArray();
        } else {
            $projectsId = DB::table('project_team_mappings')
                ->where('user_id', $currentUser->id)
                ->pluck('project_id')
                ->toArray();
        }
        if (empty($projectsId)) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $projectsData = DB::table('projects')
            ->join('project_team_mappings', function ($join) {
                $join->on('projects.id', '=', 'project_team_mappings.project_id')
                    ->where('project_team_mappings.team_type', TeamType::team_leader);
            })
            ->leftJoin('users as updater', 'projects.updated_by', '=', 'updater.id')
            ->leftJoin('users as team_leader_user', 'project_team_mappings.user_id', '=', 'team_leader_user.id')
            ->whereNull('projects.deleted_at')
            ->whereIn('projects.id', $projectsId)
            ->when($companiesId, function ($query) use ($companiesId) {
                $query->where('projects.company_id', $companiesId);
            })
            ->groupBy(
                'projects.id',
                'projects.company_id',
                'projects.project_name',
                'projects.project_id',
                'projects.start_date',
                'projects.end_date',
                'projects.priority',
                'projects.client',
                'projects.description',
                'projects.is_active',
                'projects.deleted_at',
                'projects.created_at',
                'projects.updated_at',
                'projects.created_by',
                'projects.updated_by',
                DB::raw("CONCAT(updater.first_name, ' ', updater.last_name)")
            )
            ->select(
                'projects.*',
                DB::raw("CONCAT(updater.first_name, ' ', updater.last_name) as updated_name"),
                DB::raw("GROUP_CONCAT(DISTINCT CONCAT(team_leader_user.first_name, ' ', team_leader_user.last_name) SEPARATOR ', ') as team_leader_name"),
                DB::raw("DATE_FORMAT(projects.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                DB::raw("DATE_FORMAT(projects.end_date, '%d/%m/%Y') as end_date_formatted")
            )
            ->orderByDesc('projects.id')
            ->get();

        return ApiResponse::success($projectsData, ResMessages::RETRIEVED_SUCCESS);
    }
    public function index()
    {
        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;


        $isAdminOrSuperAdmin = in_array($role_code, [$this->AdminRoleCode, $this->superAdminRoleCode]);
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $companiesId = GetCompanyId::GetCompanyId();

        if ($role_code == $this->clientRoleCode) {

            $projectsData = DB::table('projects')
                ->leftJoin('users', 'projects.updated_by', '=', 'users.id')
                ->where('projects.company_id', $companiesId)
                ->where('projects.client', $currentUser->id)
                ->whereNull('projects.deleted_at')
                ->select(
                    'projects.*',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                    DB::raw("DATE_FORMAT(projects.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                    DB::raw("DATE_FORMAT(projects.end_date, '%d/%m/%Y') as end_date_formatted")
                )
                ->orderByDesc('projects.id')
                ->get();

            return ApiResponse::success($projectsData, ResMessages::RETRIEVED_SUCCESS);
        }

        if ($isAdminOrSuperAdmin) {
            $projectsData = DB::table('projects')
                ->leftJoin('users', 'projects.updated_by', '=', 'users.id')
                ->whereNull('projects.deleted_at')
                ->when($companiesId, fn($q) => $q->where('projects.company_id', $companiesId))
                ->select(
                    'projects.*',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                    DB::raw("DATE_FORMAT(projects.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                    DB::raw("DATE_FORMAT(projects.end_date, '%d/%m/%Y') as end_date_formatted")
                )
                ->orderByDesc('projects.id')
                ->get();
        } else {
            $projectsId = DB::table('project_team_mappings')
                ->where('user_id', $currentUser->id)
                ->pluck('project_id')
                ->toArray();

            $projectsData = DB::table('projects')
                ->leftJoin('users', 'projects.updated_by', '=', 'users.id')
                ->whereIn('projects.id', $projectsId)
                ->whereNull('projects.deleted_at')
                ->when($companiesId, fn($q) => $q->where('projects.company_id', $companiesId))
                ->select(
                    'projects.*',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                    DB::raw("DATE_FORMAT(projects.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                    DB::raw("DATE_FORMAT(projects.end_date, '%d/%m/%Y') as end_date_formatted")
                )
                ->orderByDesc('projects.id')
                ->get();
        }

        return ApiResponse::success($projectsData, ResMessages::RETRIEVED_SUCCESS);
    }
    public function createProjects(ProjectRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $projectData = $request->except(['team_members', 'team_leaders']);
        $projectData['created_by'] = $currentUser->id;
        $projectData['created_at'] = now();
        $projectData['updated_at'] = null;
        $projectData['company_id'] = $CompanyId;

        $sequence = Sequence::where('type', 'ProjectID')->first();
        $newSequenceNo = $sequence->sequenceNo + 1;
        $projectId = $sequence->prefix . '-' . str_pad($newSequenceNo, 4, '0', STR_PAD_LEFT);
        $projectData['project_id'] = $projectId;

        $project = Project::create($projectData);

        Sequence::where('type', 'ProjectID')->update(['sequenceNo' => $newSequenceNo]);

        if ($project) {
            $defaultColumns = [
                ['column_name' => 'To Do', 'position' => 1],
                ['column_name' => 'In Progress', 'position' => 2],
                ['column_name' => 'On Hold', 'position' => 3],
                ['column_name' => 'Completed', 'position' => 4],
            ];
            $kanbanData = [];
            foreach ($defaultColumns as $column) {
                $kanbanData[] = [
                    'project_id' => $project->id,
                    'column_name' => $column['column_name'],
                    'position' => $column['position'],
                    'created_at' => now(),
                    'updated_at' => null,
                ];
            }
            DB::table('kanban_columns')->insert($kanbanData);
        }

        if (!empty($request->document)) {
            $file = $request->document;

            $fileId = uniqid();

            $basePath = 'attachments/project/' . $project->project_id;

            if (!Storage::disk('public')->exists($basePath)) {
                Storage::disk('public')->makeDirectory($basePath);
            }

            $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');


            $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $documentTypeId = DocumentType::project_document;

            $EmployeeDocument = AppDocument::create([
                'ref_primaryid' => $project->id,
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

        if (!empty($request->team_members)) {

            $teamMember_id = TeamType::team_member;

            $teamMembersData = array_map(function ($memberId) use ($project, $teamMember_id) {
                return [
                    'team_type' => $teamMember_id,
                    'project_id' => $project->id,
                    'user_id' => $memberId,
                    'created_at' => now(),
                    'updated_at' => null,
                ];
            }, $request->team_members);

            DB::table('project_team_mappings')->insert($teamMembersData);
        }

        if (!empty($request->team_leaders)) {

            $teamLeader_id = TeamType::team_leader;

            $teamLeadersData = array_map(function ($leaderId) use ($project, $teamLeader_id) {
                return [
                    'team_type' => $teamLeader_id,
                    'project_id' => $project->id,
                    'user_id' => $leaderId,
                    'created_at' => now(),
                    'updated_at' => null,
                ];
            }, $request->team_leaders);

            DB::table('project_team_mappings')->insert($teamLeadersData);
        }

        return ApiResponse::success($project, ResMessages::CREATED_SUCCESS);
    }
    public function viewProjects(Request $request)
    {
        $projectId = $request->projectId;

        $project = DB::table('projects')
            ->where('id', $projectId)
            ->first();

        if ($project) {

            $teamMappings = DB::table('project_team_mappings')
                ->where('project_id', $projectId)
                ->get();

            $documents = DB::table('app_documents')
                ->where('ref_primaryid', $project->id)
                ->get();

            $response = [
                'id' => $project->id,
                'project_name' => $project->project_name,
                'project_id' => $project->project_id,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'priority' => $project->priority,
                'client' => $project->client,
                'description' => $project->description,
                'is_active' => $project->is_active,
                'deleted_at' => $project->deleted_at,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
                'created_by' => $project->created_by,
                'updated_by' => $project->updated_by,
                'documents' => $documents,
                'projectTeamMappings' => $teamMappings
            ];

            return ApiResponse::success($response, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error([], ResMessages::NOT_FOUND);
        }
    }
    public function updateProjects(ProjectRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $projectId = $request->project_Id;
        $project = Project::find($projectId);

        if ($project) {
            $projectData = $request->except(['team_members', 'team_leaders']);
            $projectData['updated_by'] = $currentUser->id;
            $projectData['updated_at'] = now();

            $project->update($projectData);

            if (!empty($request->document)) {
                $file = $request->document;
                $fileId = uniqid();

                $basePath = 'attachments/project/' . $project->id;

                if (!Storage::disk('public')->exists($basePath)) {
                    Storage::disk('public')->makeDirectory($basePath);
                }

                $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');
                $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $documentTypeId = DocumentType::project_document;

                $existingDocument = AppDocument::where('ref_primaryid', $project->id)
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
                        'ref_primaryid' => $project->id,
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

            DB::table('project_team_mappings')->where('project_id', $project->id)
                ->where('team_type', TeamType::team_member)
                ->delete();

            if (!empty($request->team_members)) {
                $teamMemberData = array_map(function ($memberId) use ($project) {
                    return [
                        'team_type' => TeamType::team_member,
                        'project_id' => $project->id,
                        'user_id' => $memberId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $request->team_members);

                DB::table('project_team_mappings')->insert($teamMemberData);
            }

            DB::table('project_team_mappings')->where('project_id', $project->id)
                ->where('team_type', TeamType::team_leader)
                ->delete();

            if (!empty($request->team_leaders)) {
                $teamLeaderData = array_map(function ($leaderId) use ($project) {
                    return [
                        'team_type' => TeamType::team_leader,
                        'project_id' => $project->id,
                        'user_id' => $leaderId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $request->team_leaders);

                DB::table('project_team_mappings')->insert($teamLeaderData);
            }

            return ApiResponse::success($project, ResMessages::UPDATED_SUCCESS);
        } else {
            return ApiResponse::error([], ResMessages::NOT_FOUND);
        }
    }
    public function deleteProjects($id)
    {
        $Project = Project::find($id);

        if ($Project) {

            KanbanColumn::where('project_id', $id)->delete();

            DB::table('project_team_mappings')->where('project_id', $id)->delete();

            $Project->delete();

            return ApiResponse::success($Project, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($Project, ResMessages::NOT_FOUND);
        }
    }
    public function showDetails(Request $request)
    {
        $project_id = $request->project_id;

        $tasks = DB::table('tasks')
            ->where('project_id', $project_id)
            ->whereNull('deleted_at')
            ->first();

        $projectData = DB::table('projects')
            ->leftJoin('users', 'projects.created_by', '=', 'users.id')
            ->leftJoin('users as client_user', 'projects.client', '=', 'client_user.id')
            ->whereNull('projects.deleted_at')
            ->where('projects.id', $project_id)
            ->select(
                'projects.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as created_name"),
                DB::raw("CONCAT(client_user.first_name, ' ', client_user.last_name) as client_name"),
                DB::raw("DATE_FORMAT(projects.created_at, '%d/%m/%Y') as created_at_formatted"),
                DB::raw("DATE_FORMAT(projects.end_date, '%d/%m/%Y') as end_date_formatted"),
                DB::raw("DATE_FORMAT(projects.start_date, '%d/%m/%Y') as start_date_formatted")
            )
            ->orderByDesc('projects.id')
            ->first();

        if (!$projectData) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        if ($tasks) {
            $projectData->total_hours_of_work = TaskTimeHelper::calculateTotalTaskTime($tasks->id);
        } else {
            $projectData->total_hours_of_work = '00:00:00';
        }

        $teamMember_id = TeamType::team_member;

        $projectTeam = DB::table('project_team_mappings')
            ->leftJoin('users', 'project_team_mappings.user_id', '=', 'users.id')
            ->where('project_team_mappings.project_id', $project_id)
            ->where('project_team_mappings.team_type', $teamMember_id)
            ->select(
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as team_member_name"),
            )
            ->get();

        if (!$projectTeam) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $teamLeader_id = TeamType::team_leader;

        $projectTeamLeader = DB::table('project_team_mappings')
            ->leftJoin('users', 'project_team_mappings.user_id', '=', 'users.id')
            ->where('project_team_mappings.project_id', $project_id)
            ->where('project_team_mappings.team_type', $teamLeader_id)
            ->select(
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as team_leader_name"),
            )
            ->get();

        if (!$projectTeamLeader) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $projectFilesData = DB::table('app_documents')
            ->where('ref_primaryid', $project_id)
            ->where('document_type', DocumentType::project_document)
            ->whereNull('deleted_at')
            ->get();

        $projectData = [
            'projectData' => $projectData,
            'projectTeam' => $projectTeam,
            'projectTeamLeader' => $projectTeamLeader,
            'projectFilesData' => $projectFilesData ?? []

        ];

        return ApiResponse::success($projectData, ResMessages::RETRIEVED_SUCCESS);
    }
    public function uploadProjectsDocuments(ProjectDocumentUploadRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $project_id = $request->projectId;

        $file = $request->document;

        $fileId = uniqid();

        $basePath = 'attachments/project/' . $project_id;

        if (!Storage::disk('public')->exists($basePath)) {
            Storage::disk('public')->makeDirectory($basePath);
        }

        $filePath = $file->storeAs($basePath, $fileId . '.' . $file->getClientOriginalExtension(), 'public');


        $fileDisplayName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $documentTypeId = DocumentType::project_document;

        $EmployeeDocument = AppDocument::create([
            'ref_primaryid' => $project_id,
            'document_type' => $documentTypeId,
            'relative_path' => $filePath,
            'file_id' => $fileId,
            'extension' => $file->getClientOriginalExtension(),
            'file_display_name' => $fileDisplayName,
            'is_active' => true,
            'created_by' => $currentUser->id,
            'created_at' => now(),
        ]);

        return ApiResponse::success($EmployeeDocument, ResMessages::CREATED_SUCCESS);
    }
    public function deleteProjectsDocuments($id)
    {
        $document = AppDocument::find($id);

        if ($document) {
            // Storage::disk('public')->delete($document->relative_path);
            $document->delete();
            return ApiResponse::success($document, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($document, ResMessages::NOT_FOUND);
        }
    }
    public function projectTaskDelete($id)
    {
        $Task = Task::find($id);

        if ($Task) {
            $Task->delete();
            return ApiResponse::success($Task, ResMessages::DELETED_SUCCESS, 1);
        } else {
            return ApiResponse::error($Task, ResMessages::NOT_FOUND);
        }
    }
    public function getCurrentUserProId(Request $request)
    {
        $userId = $request->userId;
        $user = Auth::user();

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $role = DB::table('roles')->where('id', $user->role_id)->value('code');

        if (in_array($role, [$this->AdminRoleCode, $this->superAdminRoleCode])) {
            $projectDetails = DB::table('projects')
                ->select('id', 'project_name')
                ->where('projects.company_id', $companiesId)
                ->whereNull('projects.deleted_at')
                ->get();
        } else {
            $projectIds = DB::table('project_team_mappings')
                ->where('user_id', $userId)
                ->orderBy('id', 'asc')
                ->pluck('project_id');

            $projectDetails = DB::table('projects')
                ->whereIn('id', $projectIds)
                ->where('projects.company_id', $companiesId)
                ->whereNull('projects.deleted_at')
                ->select('id', 'project_name')
                ->get();
        }

        if ($projectDetails->isEmpty() && $role == $this->clientRoleCode) {

            $projectDetails = DB::table('projects')
                ->select('id', 'project_name')
                ->where('projects.client', $userId)
                ->where('projects.company_id', $companiesId)
                ->whereNull('projects.deleted_at')
                ->get();
        }

        if (!$projectDetails->isEmpty()) {
            return ApiResponse::success($projectDetails, ResMessages::RETRIEVED_SUCCESS);
        }

        return ApiResponse::error(null, ResMessages::NOT_FOUND);
    }
    public function getTeamMembers(Request $request)
    {
        $CompanyId = GetCompanyId::GetCompanyId();
        $currentUser = JWTUtils::getCurrentUserByUuid();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $usersQuery = DB::table('users')
            ->select(
                'users.id',
                'users.role_id',
                'roles.code',
                DB::raw("CONCAT(users.first_name, ' ', IFNULL(users.middle_name, ''), ' ', users.last_name) as name")
            )
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.company_id', $CompanyId)
            ->where('roles.code', '!=', $this->clientRoleCode)
            ->where('roles.code', '!=', $this->superAdminRoleCode)
            ->whereNull('users.deleted_at');

        $users = $usersQuery->orderByDesc('users.id')->get();

        return ApiResponse::success($users, ResMessages::RETRIEVED_SUCCESS);
    }
}
