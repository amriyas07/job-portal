<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPost;
use App\Traits\FileTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JobApplicationController extends Controller
{
    use FileTrait;

    public function fetchApplicationStatus($id)
    {
        try {
            $data = JobApplication::findOrFail($id);
            if (! $data) {
                return response()->json([
                    'status' => false,
                    'message' => 'Application not found',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch status: '.$e->getMessage(),
            ], 500);
        }
    }

    public function changeApplicationStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:job_applications,id',
            'applied_status' => 'required|in:applied,reviewed,shortlisted,rejected,hired',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $application = JobApplication::findOrFail($request->id);
            $application->status = $request->applied_status;
            $application->save();

            return response()->json([
                'status' => true,
                'message' => 'Applicant status updated successfully',
                'new_status' => $application->status,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update status: '.$e->getMessage(),
            ], 500);
        }
    }

    public function view_jobs_page()
    {
        $skills = JobPost::select('skills')->get();
        $allSkills = $skills->flatMap(function ($job) {
            return json_decode($job->skills, true);
        })->unique()->values();

        return view('employee.apply-jobs', compact('allSkills'));
    }

    public function list_jobs(Request $request)
    {
        try {
            $query = JobPost::query();

            // Fetch all first, then filter collection
            $jobs = $query->get()->filter(function ($job) use ($request) {

                if ($request->category && is_array($request->category)) {
                    $jobCategories = json_decode($job->category, true) ?: [];
                    if (! count(array_intersect($request->category, $jobCategories))) {
                        return false;
                    }
                }

                if ($request->skills && is_array($request->skills)) {
                    $jobSkills = json_decode($job->skills, true) ?: [];
                    if (! count(array_intersect($request->skills, $jobSkills))) {
                        return false;
                    }
                }

                if ($request->location && is_array($request->location)) {
                    $jobLocations = json_decode($job->location, true) ?: [];
                    if (! count(array_intersect($request->location, $jobLocations))) {
                        return false;
                    }
                }

                if ($request->job_type) {
                    if (strtolower($job->job_type) != strtolower($request->job_type)) {
                        return false;
                    }
                }

                return true;
            });

            // Pagination manually
            $page = $request->get('page', 1);
            $perPage = 6;
            $paginatedJobs = $jobs->slice(($page - 1) * $perPage, $perPage)->values();

            $data = $paginatedJobs->map(fn ($job) => [
                'id' => $job->id,
                'title' => Str::limit($job->title ?? '', 22),
                'description' => Str::limit($job->description ?? '', 120),
                'skills' => $job->skills ? (is_array($decoded = json_decode($job->skills)) ? $decoded : []) : [],
                'category' => $job->category ? (is_array($decoded = json_decode($job->category)) ? $decoded : []) : [],
                'location' => $job->location ? (is_array($decoded = json_decode($job->location)) ? $decoded : []) : [],
                'salary' => $job->salary_range,
                'applied' => JobApplication::where('job_post_id', $job->id)->where('employee_id', Auth::id())->exists(),
                'status' => ucfirst($job->status ?? ''),
                'status_color' => $job->status == 'active' ? 'success' : 'secondary',
                'posted_date' => $job->created_at->format('d M Y'),
            ]);

            // Generate pagination links
            $pagination = (new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedJobs,
                $jobs->count(),
                $perPage,
                $page
            ))->links()->render();

            return response()->json(['data' => $data, 'pagination' => $pagination]);

        } catch (Exception $e) {
            Log::error('Job fetch error: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch jobs. Check logs.',
            ], 500);
        }
    }

    public function get_job_details($id)
    {
        try {
            $job = JobPost::findOrFail($id);
            if (! $job) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $job,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch job: '.$e->getMessage(),
            ], 500);
        }
    }

    public function apply_for_job(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_post_id' => 'required|exists:job_posts,id',
            'comments' => 'nullable|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $existingApplication = JobApplication::where('job_post_id', $request->job_post_id)
                ->where('employee_id', Auth::id())
                ->first();
            if ($existingApplication) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already applied for this job.',
                ], 409);
            }

            $folder = 'uploads/employee/'.Str::slug(Auth::user()->name, '_').'_'.Auth::id().'/resume';
            $resumePath = $this->storeFile($request->file('resume'), $folder);

            $application = JobApplication::create([
                'job_post_id' => $request->job_post_id,
                'employee_id' => Auth::id(),
                'comments' => $request->comments,
                'resume' => $resumePath,
                'status' => 'applied',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Job application submitted successfully.',
                'data' => $application,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to apply for job: '.$e->getMessage(),
            ], 500);
        }
    }

    public function view_my_applications()
    {
        return view('employee.applied-jobs');
    }

    public function fetch_my_applications(Request $request)
    {
        $employeeId = Auth::id();

        $query = JobApplication::with('job')
            ->where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc');

        $applications = $query->paginate(6); // 6 per page

        $data = $applications->map(function ($app) {
            $job = $app->job;

            return [
                'id' => $app->id,
                'job_title' => $job->title ?? '',
                'job_description' => Str::limit($job->description ?? '', 150),
                'skills' => $job->skills ? json_decode($job->skills, true) : [],
                'status' => ucfirst($app->status),
                'applied_date' => $app->created_at->format('d M Y'),
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => (string) $applications->links('pagination::bootstrap-5'),
        ]);
    }
}
