@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <!-- Filter Section -->
        <div class="filter-section p-3 mb-4 bg-white rounded shadow-sm">
            <h5><i class="fas fa-filter me-2"></i>Filter Jobs</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select class="form-select multi-select" id="filterCategory" multiple>
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
                </div>
                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <select class="form-select multi-select" id="filterLocation" multiple>
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
                </div>
                <div class="col-md-4">
                    <label class="form-label">Skills</label>
                    <select class="form-select multi-select" id="filterSkills" multiple>
                        @foreach ($allSkills as $skill)
                            <option value="{{ $skill }}">{{ ucfirst($skill) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label class="form-label">Job Type</label>
                    <select class="form-select" id="filterJobType">
                        <option value="">Select Type</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Freelance">Freelance</option>
                    </select>
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <button class="btn btn-primary me-2" id="applyFilters"><i class="fas fa-search me-2"></i>Apply
                        Filters</button>
                    <button class="btn btn-outline-secondary" id="resetFilters"><i
                            class="fas fa-redo me-2"></i>Reset</button>
                </div>
            </div>
        </div>

        <!-- Apply Job Modal -->
        <div class="modal fade" id="applyJobModal" tabindex="-1" aria-labelledby="applyJobModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="applyJobForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="applyJobModalLabel">Apply for Job</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Job Info -->
                            <div id="jobInfo">
                                <h5 id="jobTitle"></h5>
                                <p id="jobDescription"></p>
                                <p><strong>Skills:</strong> <span id="jobSkills"></span></p>
                            </div>

                            <input type="hidden" name="job_post_id" id="job_post_id">

                            <!-- Comments -->
                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments (optional)</label>
                                <textarea name="comments" id="comments" class="form-control text-area" rows="5"></textarea>
                            </div>

                            <!-- Resume Upload -->
                            <div class="mb-3">
                                <label for="resume" class="form-label">Resume <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="resume" id="resume" class="form-control"
                                    accept=".pdf,.doc,.docx" required>
                                <div class="form-text">Accepted formats: pdf, doc, docx. Max size: 2MB.</div>
                                <div class="invalid-feedback" id="resume_error"></div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success submit-apply-job">Apply</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Jobs Section -->
        <div id="jobsContainer" class="row g-4">
        </div>

        <!-- Pagination -->
        <nav id="jobsPagination" class="mt-4"></nav>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.multi-select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select options',
                allowClear: false,
                tags: false
            });

            // Fetch Jobs
            function fetchJobs(page = 1) {
                let category = $('#filterCategory').val();
                let location = $('#filterLocation').val();
                let skills = $('#filterSkills').val();
                let jobType = $('#filterJobType').val();

                $.ajax({
                    url: "{{ route('jobseeker.jobs.fetch') }}",
                    type: "GET",
                    data: {
                        category,
                        location,
                        skills,
                        job_type: jobType,
                        page
                    },
                    success: function(res) {
                        $('#jobsContainer').html('');

                        if (res.data.length === 0) {
                            $('#jobsContainer').html(
                                '<div class="col-12 text-center py-5"><h5>No jobs found matching your criteria.</h5></div>'
                            );
                            $('#jobsPagination').html('');
                            return;
                        }

                        res.data.forEach(job => {
                            let categoryBadges = (job.category || []).map(c =>
                                    `<span class="badge bg-primary me-1 mb-1">${c}</span>`)
                                .join('');

                            let skillsBadges = (job.skills || []).map(s =>
                                `<span class="badge bg-info text-dark me-1 mb-1">${s}</span>`
                            ).join('');

                            let locationBadges = (job.location || []).map(l =>
                                `<span class="badge bg-warning text-dark me-1 mb-1">${l}</span>`
                            ).join('');


                            $('#jobsContainer').append(`
                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            <div class="card shadow-lg w-100 d-flex flex-column p-4" style="min-height: 500px;">

                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">${job.title}</h5>
                                    <span class="badge bg-${job.status_color}">${job.status}</span>
                                </div>

                                <div class="mb-2">
                                    <strong class="d-block mb-1">Category:</strong>
                                    ${categoryBadges || '<span class="text-muted">N/A</span>'}
                                </div>

                                <div class="mb-2">
                                    <strong class="d-block mb-1">Skills:</strong>
                                    ${skillsBadges || '<span class="text-muted">N/A</span>'}
                                </div>

                                <div class="mb-3">
                                    <strong class="d-block mb-1">Location:</strong>
                                    ${locationBadges || '<span class="text-muted">N/A</span>'}
                                </div>

                                <p class="card-text flex-grow-1 mt-2 mb-3" style="overflow: hidden; max-height: 80px;">${job.description}</p>

                                <div class="d-flex justify-content-between text-muted small mb-3">
                                    <span><i class="fas fa-calendar-alt"></i> Posted: ${job.posted_date ?? job.created_at}</span>
                                    <span><i class="fas fa-rupee-sign"></i> ${job.salary}</span>
                                </div>

                                <div class="mt-auto">
                                    <button
                                        class="btn btn-success w-100 applyJobBtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#applyJobModal"
                                        data-id="${job.id}"
                                        ${job.applied ? 'disabled' : ''}
                                    >
                                        ${job.applied ? 'Applied' : 'Apply'}
                                    </button>
                                </div>

                            </div>
                        </div>
                    `);
                        });

                        $('#jobsPagination').html(res.pagination);
                    },
                    error: function(err) {
                        console.error(err);
                        toastr.error('Failed to fetch jobs. Check console for details.');
                    }
                });
            }

            fetchJobs();

            $('#applyFilters').click(() => fetchJobs());
            $('#resetFilters').click(() => {
                $('.multi-select').val(null).trigger('change');
                $('#filterJobType').val('');
                fetchJobs();
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchJobs(page);
            });

        });

        $(document).on('click', '.applyJobBtn', function() {
            let dataId = $(this).data('id');

            $.ajax({
                url: "{{ route('jobseeker.jobs.fetch_details', ':id') }}".replace(':id',
                    dataId),
                type: 'GET',
                success: function(res) {
                    if (res.data) {
                        // Populate modal fields
                        $('#job_post_id').val(res.data.id);
                        $('#jobTitle').text(res.data.title);
                        $('#jobDescription').text(res.data.description.length > 120 ? res.data
                            .description.substring(0, 120) + '...' : res.data.description);
                        $('#jobSkills').text(Array.isArray(res.data.skills) ? res.data.skills.join(
                            ', ') : res.data.skills);
                        $('#applyJobErrors').html('');

                        // Show modal
                        $('#applyJobModal').modal('show');
                    } else {
                        toastr.error('Job details not found.');
                    }
                },
                error: function(err) {
                    console.error(err);
                    toastr.error('Failed to fetch job details. Check console.');
                }
            });
        });

        $(document).on('click', '.submit-apply-job', function() {
            let form = $('#applyJobForm')[0];
            let formData = new FormData(form);

            $('.invalid-feedback').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            $.ajax({
                url: "{{ route('jobseeker.jobs.apply') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    NProgress.start();
                    $('.submit-apply-job').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-2"></i>Applying...'
                    );
                },
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        $('#applyJobForm')[0].reset();
                        $('#applyJobModal').modal('hide');
                        setTimeout(() => window.location.reload(), 2000);
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
                    NProgress.done();
                    $('.submit-apply-job').prop('disabled', false).text('Apply');
                }
            });
        });
    </script>
@endpush
