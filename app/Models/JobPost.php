<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JobApplication;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'location',
        'category',
        'skills',
        'salary_range',
        'job_type',
        'status',
    ];

    protected $casts = [
        'location' => 'array',
        'category' => 'array',
        'skills' => 'array',
    ];

    // Relationships
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_post_id');
    }
}
