<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class JobPostController extends Controller
{
    public function index()
    {
        return view('employer.jobs');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'job_category' => 'required|array|min:1',
            'job_category.*' => 'string|max:100',
            'job_location' => 'required|array|min:1',
            'job_location.*' => 'string|max:100',
            'job_type' => 'required|string|in:Full-time,Part-time,Contract,Freelance',
            'job_salary_range' => 'required|string|max:50',
            'job_skills' => 'required|array|min:1',
            'job_skills.*' => 'string|max:100',
            'job_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $skills = array_map('strtolower', $request->job_skills);
            $job = JobPost::create([
                'employer_id' => Auth::id(),
                'title' => $request->job_title,
                'category' => json_encode($request->job_category),
                'location' => json_encode($request->job_location),
                'skills' => json_encode($skills),
                'job_type' => $request->job_type,
                'salary_range' => $request->job_salary_range,
                'description' => $request->job_description,
                'status' => 'active',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Job posted successfully',
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create job: '.$e->getMessage(),
            ], 500);
        }
    }

    public function get_created_jobs(Request $request)
    {
        $jobs = JobPost::query();

        if ($request->ajax()) {
            return DataTables::of($jobs)
                ->addIndexColumn()
                ->addColumn('job_title', function ($row) {
                    return '<div class="text-truncate" style="max-width:200px; overflow-x:auto;">'.$row->title.'</div>';
                })
                ->addColumn('job_description', function ($row) {
                    return '<div style="max-width:300px; max-height:60px; overflow-y:auto;">'.nl2br($row->description).'</div>';
                })
                ->addColumn('category', function ($row) {
                    $categories = is_array($row->category) ? $row->category : json_decode($row->category, true);
                    if ($categories) {
                        $html = '';
                        foreach ($categories as $cat) {
                            $html .= '<span class="badge bg-info me-1 mb-1">'.e($cat).'</span>';
                        }

                        return '<div style="max-height:70px; overflow-y:auto;">'.$html.'</div>';
                    }

                    return '-';
                })
                ->addColumn('location', function ($row) {
                    $locations = is_array($row->location) ? $row->location : json_decode($row->location, true);
                    if ($locations) {
                        $html = '';
                        foreach ($locations as $loc) {
                            $html .= '<span class="badge bg-secondary me-1 mb-1">'.e($loc).'</span>';
                        }

                        return '<div style="max-height:70px; overflow-y:auto;">'.$html.'</div>';
                    }

                    return '-';
                })
                ->addColumn('skills', function ($row) {
                    $skills = is_array($row->skills) ? $row->skills : json_decode($row->skills, true);
                    if ($skills) {
                        $html = '';
                        foreach ($skills as $skill) {
                            $html .= '<span class="badge bg-warning text-dark me-1 mb-1">'.e($skill).'</span>';
                        }

                        return '<div style="max-height:70px; overflow-y:auto;">'.$html.'</div>';
                    }

                    return '-';
                })

                ->addColumn('job_type', fn ($row) => $row->job_type ?? '-')
                ->addColumn('salary_range', fn ($row) => $row->salary_range ?? '-')
                ->addColumn('status', function ($row) {
                    $color = $row->status === 'active' ? 'success' : 'danger';

                    return '<span class="badge bg-'.$color.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('posted_date', fn ($row) => $row->created_at->format('d M Y'))
                ->addColumn('action', function ($row) {
                    $viewUrl = route('employer.jobs.view', $row->id); // Named route to the view page

                    return '<div class="d-flex gap-2">
                                <a href="'.$viewUrl.'" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-primary edit-job" data-id="'.$row->id.'">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-job" data-id="'.$row->id.'">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['job_title', 'job_description', 'category', 'location', 'skills', 'status', 'action'])
                ->make(true);
        }
    }

    public function get_job_fetch_by_id(Request $request, $id)
    {
        $applications = JobApplication::with('employee')
            ->where('job_post_id', $id);

        return DataTables::of($applications)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->employee->name ?? '-';
            })
            ->addColumn('email', function ($row) {
                return $row->employee->email ?? '-';
            })
            ->addColumn('resume', function ($row) {
                if ($row->resume) {
                    return '<a href="'.asset('storage/'.$row->resume).'" target="_blank" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>';
                }

                return '-';
            })
            ->addColumn('comments', function ($row) {
                return $row->comments ?? 'N/A';
            })
            ->addColumn('applied_date', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('status', function ($row) {
                $status = $row->status;
                $badgeClass = '';

                switch ($status) {
                    case 'applied':
                        $badgeClass = 'badge bg-primary';
                        break;
                    case 'reviewed':
                        $badgeClass = 'badge bg-info';
                        break;
                    case 'shortlisted':
                        $badgeClass = 'badge bg-warning text-dark';
                        break;
                    case 'rejected':
                        $badgeClass = 'badge bg-danger';
                        break;
                    case 'hired':
                        $badgeClass = 'badge bg-success';
                        break;
                    default:
                        $badgeClass = 'badge bg-secondary';
                }

                return '<span class="'.$badgeClass.'">'.ucfirst($status).'</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex gap-1">
                            <button class="btn btn-sm btn-primary change-application-staus" data-id="'.$row->id.'">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                    </div>';
            })
            ->rawColumns(['resume', 'action','status'])
            ->make(true);
    }

    public function edit($id)
    {
        try {
            $job = JobPost::findOrFail($id);
            if ($job->employer_id != Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_job_id' => 'required|integer|exists:job_posts,id',
            'edit_job_title' => 'required|string|max:255',
            'edit_job_category' => 'required|array|min:1',
            'edit_job_category.*' => 'string|max:100',
            'edit_job_location' => 'required|array|min:1',
            'edit_job_location.*' => 'string|max:100',
            'edit_job_type' => 'required|string|in:Full-time,Part-time,Contract,Freelance',
            'edit_job_salary_range' => 'required|string|max:50',
            'edit_job_skills' => 'required|array|min:1',
            'edit_job_skills.*' => 'string|max:100',
            'edit_job_description' => 'required|string',
            'edit_job_status' => 'required|in:active,closed,draft',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $job = JobPost::findOrFail($request->edit_job_id);

            $skills = array_map('strtolower', $request->edit_job_skills);

            $job->update([
                'title' => $request->edit_job_title,
                'category' => json_encode($request->edit_job_category),
                'location' => json_encode($request->edit_job_location),
                'skills' => json_encode($skills),
                'job_type' => $request->edit_job_type,
                'salary_range' => $request->edit_job_salary_range,
                'description' => $request->edit_job_description,
                'status' => $request->edit_job_status,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Job updated successfully',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update job: '.$e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $job = JobPost::findOrFail($id);

        return view('employer.view-jobs', compact('job'));
    }

    public function destroy($id)
    {
        try {
            $job = JobPost::findOrFail($id);
            if ($job->employer_id != Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }

            $job->delete();

            return response()->json([
                'status' => true,
                'message' => 'Job deleted successfully.',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete job: '.$e->getMessage(),
            ], 500);
        }
    }
}
