<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'relative_path',
        'file_id',
        'extension',
        'file_display_name',
        'created_at',
        'updated_at'
    ];
}
