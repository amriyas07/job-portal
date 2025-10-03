@extends('layouts.app')
@section('content')
    <div class="page-header">
        <h1>Jobs</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Jobs</li>
            </ol>
        </nav>
    </div>
    <div class="table-section">
        <div class="table-header">
            <h5>Job Listings</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJobModal">
                <i class="fas fa-plus me-2"></i>Add New Job
            </button>
        </div>

        <div class="table-responsive">
            <table id="jobsTable" class="table table-hover table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Job Title</th>
                        <th>Description</th>
                        <th style="min-width: 150px;">Category</th>
                        <th style="min-width: 150px;">Location</th>
                        <th>Job Type</th>
                        <th>Salary Range</th>
                        <th style="min-width: 150px;">Skills</th>
                        <th>Status</th>
                        <th>Posted Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="addJobModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addJobForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Job Title <span class="text-danger">*</span></label>
                                <input type="text" name="job_title" class="form-control" id="job_title"
                                    placeholder="ex. Senior Developer" required>
                                <div class="invalid-feedback" id="job_title_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select multi-select" name="job_category[]" id="job_category"
                                    multiple="multiple" required>
                                    <option value="IT">IT & Software</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Design">Design</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Human Resources">Human Resources</option>
                                    <option value="Customer Support">Customer Support</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Education">Education & Training</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Hospitality">Hospitality</option>
                                    <option value="Legal">Legal</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Retail">Retail</option>
                                    <option value="Logistics">Logistics & Supply Chain</option>
                                    <option value="Media">Media & Entertainment</option>
                                    <option value="Research">Research & Development</option>
                                    <option value="Real Estate">Real Estate</option>
                                    <option value="Agriculture">Agriculture</option>
                                </select>
                                <div class="invalid-feedback" id="job_category_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Location <span class="text-danger">*</span></label>
                                <select class="form-select multi-select" name="job_location[]" id="job_location"
                                    multiple="multiple" required>
                                    <option value="Ariyalur">Ariyalur</option>
                                    <option value="Chennai">Chennai</option>
                                    <option value="Coimbatore">Coimbatore</option>
                                    <option value="Cuddalore">Cuddalore</option>
                                    <option value="Dharmapuri">Dharmapuri</option>
                                    <option value="Dindigul">Dindigul</option>
                                    <option value="Erode">Erode</option>
                                    <option value="Kanchipuram">Kanchipuram</option>
                                    <option value="Kanyakumari">Kanyakumari</option>
                                    <option value="Karur">Karur</option>
                                    <option value="Krishnagiri">Krishnagiri</option>
                                    <option value="Madurai">Madurai</option>
                                    <option value="Nagapattinam">Nagapattinam</option>
                                    <option value="Namakkal">Namakkal</option>
                                    <option value="Perambalur">Perambalur</option>
                                    <option value="Pudukkottai">Pudukkottai</option>
                                    <option value="Ramanathapuram">Ramanathapuram</option>
                                    <option value="Salem">Salem</option>
                                    <option value="Sivaganga">Sivaganga</option>
                                    <option value="Tenkasi">Tenkasi</option>
                                    <option value="Thanjavur">Thanjavur</option>
                                    <option value="Theni">Theni</option>
                                    <option value="Thoothukudi">Thoothukudi</option>
                                    <option value="Tiruchirappalli">Tiruchirappalli</option>
                                    <option value="Tirunelveli">Tirunelveli</option>
                                    <option value="Tiruppur">Tiruppur</option>
                                    <option value="Tiruvallur">Tiruvallur</option>
                                    <option value="Tiruvarur">Tiruvarur</option>
                                    <option value="Vellore">Vellore</option>
                                    <option value="Viluppuram">Viluppuram</option>
                                    <option value="Virudhunagar">Virudhunagar</option>
                                </select>
                                <div class="invalid-feedback" id="job_location_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Job Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="job_type" id="job_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Freelance">Freelance</option>
                                </select>
                                <div class="invalid-feedback" id="job_type_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Salary Range <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="job_salary_range" id="job_salary_range"
                                    placeholder="ex: ₹80,000 - ₹1,00,000" required>
                                <div class="invalid-feedback" id="job_salary_range_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Skills <span class="text-danger">*</span></label>
                                <select class="form-select multi-select" name="job_skills[]" id="job_skills"
                                    multiple="multiple" required>
                                    <option value="">Select Skills</option>
                                </select>
                                <div class="invalid-feedback" id="job_skills_error"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Job Description <span class="text-danger">*</span></label>
                                <textarea class="form-control text-area" rows="5" name="job_description" id="job_description"
                                    placeholder="Enter job description..." required></textarea>
                                <div class="invalid-feedback" id="job_description_error"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary add-job-btn">Save Job</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editJobModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editJobForm">
                        @csrf
                        <input type="hidden" name="edit_job_id" id="edit_job_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Job Title <span class="text-danger">*</span></label>
                                <input type="text" name="edit_job_title" class="form-control" id="edit_job_title"
                                    required>
                                <div class="invalid-feedback" id="edit_job_title_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select multi-select" name="edit_job_category[]"
                                    id="edit_job_category" multiple required>
                                    <option value="IT">IT & Software</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Design">Design</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Human Resources">Human Resources</option>
                                    <option value="Customer Support">Customer Support</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Education">Education & Training</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Hospitality">Hospitality</option>
                                    <option value="Legal">Legal</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Retail">Retail</option>
                                    <option value="Logistics">Logistics & Supply Chain</option>
                                    <option value="Media">Media & Entertainment</option>
                                    <option value="Research">Research & Development</option>
                                    <option value="Real Estate">Real Estate</option>
                                    <option value="Agriculture">Agriculture</option>
                                </select>
                                <div class="invalid-feedback" id="edit_job_category_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Location <span class="text-danger">*</span></label>
                                <select class="form-select multi-select" name="edit_job_location[]"
                                    id="edit_job_location" multiple required>
                                    <option value="Ariyalur">Ariyalur</option>
                                    <option value="Chennai">Chennai</option>
                                    <option value="Coimbatore">Coimbatore</option>
                                    <option value="Cuddalore">Cuddalore</option>
                                    <option value="Dharmapuri">Dharmapuri</option>
                                    <option value="Dindigul">Dindigul</option>
                                    <option value="Erode">Erode</option>
                                    <option value="Kanchipuram">Kanchipuram</option>
                                    <option value="Kanyakumari">Kanyakumari</option>
                                    <option value="Karur">Karur</option>
                                    <option value="Krishnagiri">Krishnagiri</option>
                                    <option value="Madurai">Madurai</option>
                                    <option value="Nagapattinam">Nagapattinam</option>
                                    <option value="Namakkal">Namakkal</option>
                                    <option value="Perambalur">Perambalur</option>
                                    <option value="Pudukkottai">Pudukkottai</option>
                                    <option value="Ramanathapuram">Ramanathapuram</option>
                                    <option value="Salem">Salem</option>
                                    <option value="Sivaganga">Sivaganga</option>
                                    <option value="Tenkasi">Tenkasi</option>
                                    <option value="Thanjavur">Thanjavur</option>
                                    <option value="Theni">Theni</option>
                                    <option value="Thoothukudi">Thoothukudi</option>
                                    <option value="Tiruchirappalli">Tiruchirappalli</option>
                                    <option value="Tirunelveli">Tirunelveli</option>
                                    <option value="Tiruppur">Tiruppur</option>
                                    <option value="Tiruvallur">Tiruvallur</option>
                                    <option value="Tiruvarur">Tiruvarur</option>
                                    <option value="Vellore">Vellore</option>
                                    <option value="Viluppuram">Viluppuram</option>
                                    <option value="Virudhunagar">Virudhunagar</option>
                                </select>
                                <div class="invalid-feedback" id="edit_job_location_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Job Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="edit_job_type" id="edit_job_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Freelance">Freelance</option>
                                </select>
                                <div class="invalid-feedback" id="edit_job_type_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Salary Range <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="edit_job_salary_range"
                                    id="edit_job_salary_range" required>
                                <div class="invalid-feedback" id="edit_job_salary_range_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Skills <span class="text-danger">*</span></label>
                                <select class="form-select multi-select" name="edit_job_skills[]" id="edit_job_skills"
                                    multiple required>
                                    <option value="">Select Skills</option>
                                </select>
                                <div class="invalid-feedback" id="edit_job_skills_error"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="edit_job_status" id="edit_job_status" required>
                                    <option value="active">Active</option>
                                    <option value="draft">draft</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <div class="invalid-feedback" id="edit_job_status_error"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Job Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="edit_job_description" id="edit_job_description" required></textarea>
                                <div class="invalid-feedback" id="edit_job_description_error"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary update-job-btn">Update Job</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.multi-select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select options',
                allowClear: false,
                tags: true,
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function capitalizeFirstLetter(str) {
                if (!str) return '';
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            let jobsTable = $('#jobsTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                responsive: false,
                ajax: "{{ route('employer.job-applications.created_jobs.fetch') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'job_title',
                        name: 'title'
                    },
                    {
                        data: 'job_description',
                        name: 'description'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'job_type',
                        name: 'job_type'
                    },
                    {
                        data: 'salary_range',
                        name: 'salary_range'
                    },
                    {
                        data: 'skills',
                        name: 'skills'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'posted_date',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [9, 'desc']
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf'
                ],
            });

            $(document).on('click', '.add-job-btn', function() {
                let form = $('#addJobForm')[0];
                let formData = new FormData(form);

                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                $.ajax({
                    url: "{{ route('employer.jobs.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        NProgress.start();
                        $('.add-job-btn').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        } else {
                            toastr.error(response.message || 'Something went wrong');
                        }
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
                        $('#addJobModal').modal('hide');
                        jobsTable.ajax.reload(null, false);
                        NProgress.done();
                        $('.add-job-btn').prop('disabled', false).text('Save Job');
                    }
                });
            });

            $(document).on('click', '.delete-job', function() {
                let dataId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won’t be able to delete this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('employer.jobs.delete', ':id') }}".replace(':id',
                                dataId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            beforeSend: function() {
                                NProgress.start();
                            },
                            success: function(response) {
                                if (response.status) {
                                    toastr.success(response.message);
                                    jobsTable.ajax.reload(null, false);
                                } else {
                                    toastr.error(response.message ||
                                        'Something went wrong');
                                }
                            },
                            error: function() {
                                toastr.error('Something went wrong.');
                            },
                            complete: function() {
                                NProgress.done();
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.edit-job', function() {
                let dataId = $(this).data('id');

                $.ajax({
                    url: "{{ route('employer.jobs.edit', ':id') }}".replace(':id', dataId),
                    type: "GET",
                    beforeSend: function() {
                        NProgress.start();
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#edit_job_id').val(response.data.id);
                            $('#edit_job_title').val(response.data.title);
                            $('#edit_job_salary_range').val(response.data.salary_range);
                            $('#edit_job_description').val(response.data.description);

                            let category = JSON.parse(response.data.category || "[]");
                            let location = JSON.parse(response.data.location || "[]");
                            let skills = JSON.parse(response.data.skills || "[]");

                            $('#edit_job_skills').empty();

                            skills.forEach(skill => {
                                $('#edit_job_skills').append(new Option(skill, skill,
                                    true, true));
                            });

                            $('#edit_job_category').val(category).trigger('change');
                            $('#edit_job_location').val(location).trigger('change');

                            $('#edit_job_skills').trigger('change');

                            $('#edit_job_type').val(capitalizeFirstLetter(response.data
                                .job_type)).trigger('change');
                            $('#edit_job_status').val(response.data.status).trigger('change');


                            // Show modal
                            $('#editJobModal').modal('show');
                        } else {
                            toastr.error('Job not found.');
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong.');
                    },
                    complete: function() {
                        NProgress.done();
                    }
                });
            });

            $(document).on('click', '.update-job-btn', function() {
                let form = $('#editJobForm')[0];
                let formData = new FormData(form);

                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                $.ajax({
                    url: "{{ route('employer.jobs.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        NProgress.start();
                        $('.update-job-btn').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || 'Something went wrong');
                        }
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
                        $('#editJobModal').modal('hide');
                        jobsTable.ajax.reload(null, false);
                        NProgress.done();
                        $('.update-job-btn').prop('disabled', false).text('Update Job');
                    }
                });
            });




        });
    </script>
@endpush
