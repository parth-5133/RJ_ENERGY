<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'company_id',
        'access_code',
        'navigation_url',
        'display_in_menu',
        'parent_menu_id',
        'menu_icon',
        'menu_class',
        'display_order',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
