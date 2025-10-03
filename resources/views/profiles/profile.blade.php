@extends('layouts.app')
@push('styles')
    <style>
        .update-profile {
            transition: all 0.3s ease;
        }

        .update-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1>Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>
    <div class="container mt-2">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white">
                <h3 class="mb-0">Complete Employer Profile</h3>
                <p class="mb-0">Fill in your company details to complete your profile</p>
            </div>
            <div class="card-body">
                <form id="updateProfile" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label fw-bold">Company Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="company_name" name="company_name"
                                value="{{ old('company_name', optional(Auth::user()->employerProfile)->company_name) }}" >
                            <div class="invalid-feedback" id="company_name_error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="company_website" class="form-label fw-bold">Company Website</label>
                            <input type="url" class="form-control form-control-lg" id="company_website"
                                name="company_website"
                                value="{{ old('company_website', optional(Auth::user()->employerProfile)->company_website) }}">
                            <div class="invalid-feedback" id="company_website_error"></div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <label for="company_logo" class="form-label fw-bold">Company Logo</label>
                            <input type="file" class="form-control" id="company_logo" name="company_logo" accept=".jpg,.jpeg,.png">
                            <div class="form-text">Accepted formats: jpg, jpeg, png. Max size: 2MB.</div>
                        </div>
                        <div class="col-md-6">
                            @if (optional(Auth::user()->employerProfile)->company_logo)
                                <img src="{{ asset('storage/' . Auth::user()->employerProfile->company_logo) }}"
                                    alt="Logo" class="img-thumbnail mt-2" style="max-height: 100px;">
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="company_address" class="form-label fw-bold">Company Address</label>
                        <textarea class="form-control text-area" id="company_address" name="company_address" rows="2"
                            placeholder="Enter company address">{{ old('company_address', optional(Auth::user()->employerProfile)->company_address) }}</textarea>
                        <div class="invalid-feedback" id="company_address_error"></div>
                    </div>

                    <div class="mb-4">
                        <label for="company_description" class="form-label fw-bold">Company Description</label>
                        <textarea class="form-control text-area" id="company_description" name="company_description" rows="4"
                            placeholder="Write a brief about your company">{{ old('company_description', optional(Auth::user()->employerProfile)->company_description) }}</textarea>
                        <div class="invalid-feedback" id="company_description_error"></div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-lg btn-primary update-profile shadow-sm">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.update-profile').click(function() {
                let company_name = $('#company_name').val().trim();
                if(!company_name){
                    toastr.error('Companny Name is required');
                    return;
                }
                let form = $('#updateProfile')[0];
                let formData = new FormData(form);

                formData.append('user_id', '{{ Auth::user()->id }}');

                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');

                $.ajax({
                    url: "{{ route('employer.profiles.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        NProgress.start();
                        $('.update-profile').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        } else {
                            console.log("Error :", response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '_error').text(value[0]);
                            });

                        } else {
                            console.log("Something went wrong.");
                        }
                    },
                    complete: function() {
                        NProgress.done();
                        $('.update-profile').prop('disabled', false).text('Save Profile');
                    }
                });
            });

        });
    </script>
@endpush
