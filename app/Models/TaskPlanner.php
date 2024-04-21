<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskPlanner extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'user_id',
        'status'
    ];
}
