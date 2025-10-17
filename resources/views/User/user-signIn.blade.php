@extends('layouts.app')

@section('title', 'Member-login Page')

@section('content')

<div class="container py-5">

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Oops!</strong> Please fix the following errors:
                    <ul class="mt-2 mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Attendance Card --}}
            <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 480px;">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 fw-bold text-primary">
                        <i class="bi bi-person-check-fill me-2"></i>Attendance for {{ $club->club_name }}
                    </h3>

                    {{-- Attendance Form --}}
                    <form action="" method="POST" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="text" id="phone" name="phone" pattern="\d{10}" maxlength="10"
                                class="form-control form-control-lg shadow-sm"
                                placeholder="Enter 10-digit phone number" required>
                            <div id="phoneError" class="form-text text-danger small mt-1"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>Submit Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Extra Buttons (Outside Form) --}}
            {{-- <div class="text-center mt-4"> --}}
                {{-- Substitute Attendance --}}
                {{-- <a href="{{ route('substitute-signin', ['club_id' => Helper::encoded($club->id)]) }}"
                class="btn btn-outline-success btn-lg mx-2 rounded-3 shadow-sm">
                    <i class="bi bi-person-plus-fill me-2"></i>Substitute Attendance
                </a> --}}

                {{-- Guest Attendance --}}
                {{-- <a href="{{ route('guest-signin', ['club_id' => Helper::encoded($club->id)]) }}"
                class="btn btn-outline-warning btn-lg mx-2 rounded-3 shadow-sm">
                    <i class="bi bi-person-fill-add me-2"></i>Guest Attendance
                </a> --}}
            {{-- </div> --}}

  </div>

    <script>
        document.querySelector('#phone').addEventListener('input', function(e) {

            var regex = /^[6-9][0-9]{9}$/;
            if (!regex.test(e.target.value)) {
                document.querySelector('#phoneError').innerHTML =
                    '<span class="text-danger">Please enter a valid phone number (10 digits).</span>';
                document.querySelector('.submitBtn').disabled = true;
            } else {
                document.querySelector('#phoneError').innerHTML = '';
                document.querySelector('.submitBtn').disabled = false;
            }
        });
    </script>
@endsection
