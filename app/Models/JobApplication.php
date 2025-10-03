<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JobPost;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'employee_id',
        'comments',
        'resume',
        'status',
    ];

    // Relationships
    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
