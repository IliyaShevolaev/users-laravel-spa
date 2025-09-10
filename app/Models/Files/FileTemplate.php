<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileTemplate extends Model
{
    use SoftDeletes;

    protected $fillable  = ['name', 'file_path'];
}
