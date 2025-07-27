<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'project_name',
        'project_id',
        'start_date',
        'end_date',
        'priority',
        'client',
        'description',
        'is_active',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    protected $casts = [
        'team_members' => 'array',
        'team_leaders' => 'array',
    ];
}   
