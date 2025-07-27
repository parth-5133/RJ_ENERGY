<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'cc_mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'is_active',
        'deleted_at',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
