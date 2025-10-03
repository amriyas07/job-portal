@extends('layouts.app')
@push('styles')
    <style>
        .job-card {
            width: 100%;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
            margin-bottom: 15px;
        }

        .job-card:hover {
            transform: translateY(-2px);
        }

        .job-card h5 {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .job-card p {
            margin: 0;
            font-size: 0.9rem;
            color: #555;
        }

        .job-card .skills span {
            background: #f1f1f1;
            border-radius: 3px;
            padding: 2px 6px;
            margin-right: 5px;
            font-size: 0.8rem;
        }

        .job-card .status {
            float: right;
            font-weight: bold;
        }
    </style>
@endpush
@section('content')
    <div class="page-header">
        <h1>Applied Jobs</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Applied</li>
            </ol>
        </nav>
    </div>
    <div class="container mt-4">
        <h3 class="mb-5">My Applications</h3>
        <div id="applicationsList" class="row g-3">
        </div>
        <div id="applicationsPagination" class="mt-3"></div>
    </div>
@endsection

@push('scripts')
    <script>
        function fetchApplications(page = 1) {
            $.ajax({
                url: "{{ route('jobseeker.applications.fetch') }}?page=" + page,
                type: 'GET',
                success: function(res) {
                    let html = '';
                    if (res.data.length > 0) {
                        res.data.forEach(app => {
                            html += `
                        <div class="job-card col-12 mt-2">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5>${app.job_title}</h5>
                                <span class="status badge ${getStatusClass(app.status)}">${app.status}</span>
                            </div>
                            <p>${app.job_description}</p>
                            <div class="skills my-2">
                                ${app.skills.map(skill => `<span>${skill}</span>`).join('')}
                            </div>
                            <small class="text-muted">Applied on: ${app.applied_date}</small>
                        </div>
                    `;
                        });
                    } else {
                        html = '<p class="text-center">No applications found.</p>';
                    }
                    $('#applicationsList').html(html);
                    $('#applicationsPagination').html(res.pagination);
                },
                error: function(err) {
                    console.error(err);
                    $('#applicationsList').html('<p class="text-danger">Failed to load applications.</p>');
                }
            });
        }

        // Map status to badge class
        function getStatusClass(status) {
            switch (status.toLowerCase()) {
                case 'applied':
                    return 'bg-primary';
                case 'reviewed':
                    return 'bg-info';
                case 'shortlisted':
                    return 'bg-warning text-dark';
                case 'rejected':
                    return 'bg-danger';
                case 'hired':
                    return 'bg-success';
                default:
                    return 'bg-secondary';
            }
        }

        // Handle pagination click
        $(document).on('click', '#applicationsPagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetchApplications(page);
        });

        $(document).ready(function() {
            fetchApplications();
        });
    </script>
@endpush
