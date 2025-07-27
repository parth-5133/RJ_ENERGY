<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'app_documents';

    protected $fillable = [
        'ref_primaryid',
        'user_id',
        'document_type',
        'relative_path',
        'file_id',
        'extension',
        'file_display_name',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
