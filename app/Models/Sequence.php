<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'prefix', 'suffix', 'sequenceNo', 'created_at', 'updated_at'];
}
