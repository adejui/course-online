<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseKeypoint extends Model
{
    use HasFactory, SoftDeletes;
    // cara pertama dalam mempersiapkan mass assigment
    protected $fillable = [
        'name',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
