@php use App\Helpers\Helper; @endphp
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
                <h3 class="text-center mb-4 fw-bold">Attendance of {{$club->club_name}} as Guest </h3>

                <form action="{{ route('guest-signin', ['club_id' => Helper::encoded($club->id)]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="guestName" class="form-label fw-semibold">Name</label>
                        <input type="text" id="guestName" name="guestName" placeholder="Enter your name"
                            class="form-control" required>
                        <div id="substituteNameError" class="form-text text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="guestPhone" class="form-label fw-semibold">Phone</label>
                        {{-- <input type="number" id="guestPhone" name="guestPhone" placeholder="Enter your name"
                            class="form-control" pattern="\d{10}" maxlength="10" required> --}}
                        <input type="text" id="guestPhone" name="guestPhone" pattern="\d{10}" maxlength="10"
                                class="form-control form-control-lg shadow-sm"
                                placeholder="Enter 10-digit phone number" required>
                        <div id="guestPhoneError" class="form-text text-danger"></div>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Member's Phone Number</label>
                        <input type="text" id="phone" name="phone" pattern="\d{10}" maxlength="10"
                            class="form-control" placeholder="Enter 10-digit phone number" required>
                        <div id="phoneError" class="form-text text-danger"></div>
                    </div> --}}

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg submitBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('#guestPhone').addEventListener('input', function(e) {

            var regex = /^[6-9][0-9]{9}$/;
            if (!regex.test(e.target.value)) {
                document.querySelector('#guestPhoneError').innerHTML =
                    '<span class="text-danger">Please enter a valid phone number (10 digits).</span>';
                document.querySelector('.submitBtn').disabled = true;
            } else {
                document.querySelector('#guestPhoneError').innerHTML = '';
                document.querySelector('.submitBtn').disabled = false;
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#clubSelectInput').on('change', function() {
                var clubId = $(this).val();
                $('#memberSelectInput').html('<option value="">Loading...</option>');

                if (clubId) {
                    $.ajax({
                        url: "{{ route('get.members', '') }}/" + clubId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var options = '<option value="">Choose member name</option>';
                            if (data.length > 0) {
                                $.each(data, function(index, member) {
                                    options += '<option value="' + member.id + '">' +
                                        member.name + '</option>';
                                });
                            } else {
                                options = '<option value="">No members found</option>';
                            }
                            $('#memberSelectInput').html(options);
                        },
                        error: function(xhr, status, error) {
                            $('#memberSelectInput').html(
                                '<option value="">Error fetching members</option>');
                        }
                    });
                } else {
                    $('#memberSelectInput').html('<option value="">Choose member name</option>');
                }
            });

        });


    </script>
@endsection
