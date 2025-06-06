<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseStudent extends Model
{
    use HasFactory, SoftDeletes;
    // cara pertama dalam mempersiapkan mass assigment
    protected $fillable = [
        'user_id',
        'course_id',
    ];
}
