<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermissionMapping extends Model
{
    use HasFactory;
    protected $table = 'role_permission_mapping';

    protected $fillable = [
        'menu_id',
        'role_id',
        'canAdd',
        'canView',
        'canEdit',
        'canDelete',
        'created_at',
        'updated_at'
    ];
}
