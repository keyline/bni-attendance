@extends('layouts.app')

@section('title', 'Member-login Page')

@section('content')

    <div class="container py-5">

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">

                <ul class="mt-2 mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Attendance Form --}}
        <div class="card shadow-sm mx-auto" style="max-width: 500px;">
            <div class="card-body p-4">
                <h3 class="text-center mb-4 fw-bold">Attendance</h3>

                <form action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" id="phone" name="phone" pattern="\d{10}" maxlength="10"
                            class="form-control" placeholder="Enter 10-digit phone number" required>
                        <div id="phoneError" class="form-text text-danger"></div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                    </div>
                </form>
            </div>
        </div>
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
