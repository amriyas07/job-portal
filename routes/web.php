<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('employer')->name('employer.')->middleware(['employer.check.profile-complete','role:employer'])->group(function () {
        Route::get('/jobs', [JobPostController::class, 'index'])->name('jobs');
        Route::post('/jobs/store', [JobPostController::class, 'store'])->name('jobs.store');
        Route::get('/fetch/created/jobs', [JobPostController::class, 'get_created_jobs'])->name('job-applications.created_jobs.fetch');
        Route::delete('/jobs/delete/{id}', [JobPostController::class, 'destroy'])->name('jobs.delete');
        Route::get('/jobs/fetch/{id}', [JobPostController::class, 'edit'])->name('jobs.edit');
        Route::post('/jobs/update', [JobPostController::class, 'update'])->name('jobs.update');

        Route::get('/view/jobs/{id}', [JobPostController::class, 'show'])->name('jobs.view');
        Route::get('/view/jobs/fetch/{id}', [JobPostController::class, 'get_job_fetch_by_id'])->name('jobs.get_job_fetch_by_id');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profiles');
        Route::post('/update-profile', [ProfileController::class, 'update'])->name('profiles.update');

        Route::get('/fetch/application/status/{id}', [JobApplicationController::class, 'fetchApplicationStatus'])->name('job-applications.fetch_status');
        Route::post('/change/application/status', [JobApplicationController::class, 'changeApplicationStatus'])->name('job-applications.change_status');
    });

    Route::prefix('jobseeker')->name('jobseeker.')->middleware(['role:employee'])->group(function () {
        Route::get('/jobs', [JobApplicationController::class, 'view_jobs_page'])->name('jobs.page');
        Route::get('/jobs/fetch', [JobApplicationController::class, 'list_jobs'])->name('jobs.fetch');
        Route::get('/jobs/fetch/{id}', [JobApplicationController::class, 'get_job_details'])->name('jobs.fetch_details');
        Route::post('/jobs/apply', [JobApplicationController::class, 'apply_for_job'])->name('jobs.apply');
        Route::get('/view/applications', [JobApplicationController::class, 'view_my_applications'])->name('applications.view');
        Route::get('/fetch/applications', [JobApplicationController::class, 'fetch_my_applications'])->name('applications.fetch');
    });
});

require __DIR__.'/auth.php';
