<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'uuid',
        'role_id',
        'company_id',
        'employee_id',
        'aadhaar',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'employee_id',
        'ip_address',
        'last_logged_in_at',
    ];

    // Automatically generate a UUID when creating a new model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'iss' => env('APP_URL'),
            'uuid' => $this->uuid,
        ];
    }

    public function hasPermission($action, $menuId = null)
    {
        $roleId = $this->role_id;

        if ($roleId) {
            $permissionColumn = $this->mapActionToPermissionColumn($action);

            $query = RolePermissionMapping::where('role_id', $roleId)
                ->where($permissionColumn, 1);

            if ($menuId) {
                $query->where('menu_id', $menuId);
            }

            if ($query->exists()) {
                return true;
            }
        }
        return false;
    }

    protected function mapActionToPermissionColumn($action)
    {
        $actionMap = [
            'view' => 'canView',
            'add' => 'canAdd',
            'edit' => 'canEdit',
            'delete' => 'canDelete'
        ];

        return $actionMap[$action] ?? null;
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
