<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EmployerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'company_name',
        'company_website',
        'company_logo',
        'company_address',
        'company_description',
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
}
