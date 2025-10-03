@extends('layouts.app')
@section('content')
    <div class="page-header">
        <h1>Jobs</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employer.jobs') }}" class="text-decoration-none">Jobs</a></li>
                <li class="breadcrumb-item active">{{ $job->title }}</li>
            </ol>
        </nav>
        <div class="bg-white p-3 rounded shadow-sm">
            <p><strong>Description:</strong> {{ $job->description }}</p>
            <p><strong>Salary Range:</strong> {{ $job->salary_range }}</p>
            <p><strong>Job Type:</strong> {{ $job->job_type }}</p>
            <p><strong>Category:</strong> {{ implode(', ', json_decode($job->category ?? '[]')) }}</p>
            <p><strong>Location:</strong> {{ implode(', ', json_decode($job->location ?? '[]')) }}</p>
            <p><strong>Skills:</strong> {{ implode(', ', json_decode($job->skills ?? '[]')) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($job->status) }}</p>
        </div>
    </div>
    <div class="table-section">
        <div class="table-header">
            <h5>Applicants for Job: {{ $job->title }}</h5>
        </div>

        <div class="table-responsive">
            <table id="applicantsTable" class="table table-hover table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Employee Name</th>
                        <th>Email</th>
                        <th>Resume</th>
                        <th>Comments</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="changeApplicantStatus" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Change Applicant Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="changeApplicantStatusForm">
                        @csrf
                        <input type="hidden" name="id" id="job_applied_id">
                        <div class="col-md-6">
                            <label class="form-label">Applied Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="applied_status" id="applied_status" required>
                                {{-- <option value="applied">Applied</option> --}}
                                <option value="reviewed">Reviewed</option>
                                <option value="shortlisted">Shortlisted</option>
                                <option value="rejected">Rejected</option>
                                <option value="hired">Hired</option>
                            </select>
                            <div class="invalid-feedback" id="applied_status_error"></div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary update-applicant-status-btn">Update Status</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let jobApplicantsTable = $('#applicantsTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                responsive: false,
                ajax: "{{ route('employer.jobs.get_job_fetch_by_id', ':id') }}".replace(':id',
                    {{ $job->id }}),
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'employee.name'
                    },
                    {
                        data: 'email',
                        name: 'employee.email'
                    },
                    {
                        data: 'resume',
                        name: 'resume',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'comments',
                        name: 'comments',
                        render: function(data, type, row) {
                            return `<div class="comments-cell" title="${data}">${data ?? 'N/A'}</div>`;
                        }
                    },
                    {
                        data: 'applied_date',
                        name: 'applied_date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [5, 'desc']
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf'
                ],
                columnDefs: [{
                        targets: 4,
                        width: '250px'
                    }
                ]
            });

            $(document).on('click', '.change-application-staus', function() {
                let dataId = $(this).data('id');

                $.ajax({
                    url: "{{ route('employer.job-applications.fetch_status', ':id') }}".replace(
                        ':id',
                        dataId),
                    type: "GET",
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#job_applied_id').val(response.data.id);
                            $('#applied_status').val(response.data.status).trigger('change');

                            // Show modal
                            $('#changeApplicantStatus').modal('show');
                        } else {
                            toastr.error('Data not found.');
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong.');
                    },
                    complete: function() {
                        jobApplicantsTable.ajax.reload(null, false);
                        NProgress.done();
                    }
                });
            });

            $(document).on('click', '.update-applicant-status-btn', function() {
                let form = $('#changeApplicantStatusForm')[0];
                let formData = new FormData(form);

                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                $.ajax({
                    url: "{{ route('employer.job-applications.change_status') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        NProgress.start();
                        $('.update-applicant-status-btn').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || 'Something went wrong');
                        }
                        $('#changeApplicantStatus').modal('hide');

                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '_error').text(value[0]);
                            });
                        } else {
                            toastr.error('Something went wrong.');
                        }
                    },
                    complete: function() {
                        jobApplicantsTable.ajax.reload(null, false);
                        NProgress.done();
                        $('.update-applicant-status-btn').prop('disabled', false).text(
                            'Update Status');
                    }
                });
            });




        });
    </script>
@endpush
