<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->hasRole('employer')) {
            $employerId = $user->id;

            $total_jobs = JobPost::where('employer_id', $employerId)->count();
            $active_jobs = JobPost::where('employer_id', $employerId)
                ->where('status', 'active')
                ->count();
            $draft_jobs = JobPost::where('employer_id', $employerId)
                ->where('status', 'draft')
                ->count();
            $closed_jobs = JobPost::where('employer_id', $employerId)
                ->where('status', 'closed')
                ->count();
            $candidates = JobApplication::whereIn('job_post_id', function ($query) use ($employerId) {
                $query->select('id')
                    ->from('job_posts')
                    ->where('employer_id', $employerId);
            })->count();

            return view('dashboard', compact('total_jobs', 'active_jobs', 'draft_jobs', 'closed_jobs', 'candidates'));
        }

        if ($user->hasRole('employee')) {
            $employeeId = $user->id;

            $applied = JobApplication::where('employee_id', $employeeId)
                ->whereIn('status', ['applied', 'reviewed', 'shortlisted', 'rejected', 'hired'])->count();
            $reviewed = JobApplication::where('employee_id', $employeeId)
                ->where('status', 'reviewed')->count();
            $shortlisted = JobApplication::where('employee_id', $employeeId)
                ->where('status', 'shortlisted')->count();
            $rejected = JobApplication::where('employee_id', $employeeId)
                ->where('status', 'rejected')->count();
            $hired = JobApplication::where('employee_id', $employeeId)
                ->where('status', 'hired')->count();

            return view('dashboard', compact('applied', 'reviewed', 'shortlisted', 'rejected', 'hired'));
        }

        // Default fallback
        return view('dashboard');
    }
}
