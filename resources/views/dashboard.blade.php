@extends('layouts.app')
@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<div class="stats-cards">
    @role('employer')
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-briefcase"></i></div>
            <div class="stat-info"><h3>{{ $total_jobs }}</h3><p>Total Jobs</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info"><h3>{{ $active_jobs }}</h3><p>Active Jobs</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-pencil-alt"></i></div>
            <div class="stat-info"><h3>{{ $draft_jobs }}</h3><p>Draft Jobs</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-xmark-circle"></i></div>
            <div class="stat-info"><h3>{{ $closed_jobs }}</h3><p>Closed Jobs</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
            <div class="stat-info"><h3>{{ $candidates }}</h3><p>Candidates</p></div>
        </div>
    @endrole

    @role('employee')
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-paper-plane"></i></div>
            <div class="stat-info"><h3>{{ $applied }}</h3><p>Applied</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-eye"></i></div>
            <div class="stat-info"><h3>{{ $reviewed }}</h3><p>Reviewed</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-star"></i></div>
            <div class="stat-info"><h3>{{ $shortlisted }}</h3><p>Shortlisted</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="stat-info"><h3>{{ $rejected }}</h3><p>Rejected</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-user-check"></i></div>
            <div class="stat-info"><h3>{{ $hired }}</h3><p>Hired</p></div>
        </div>
    @endrole
</div>
@endsection
