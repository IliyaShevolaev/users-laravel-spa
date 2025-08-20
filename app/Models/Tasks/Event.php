<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'start', 'end', 'department_id'];
}
