<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
        <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-start" role="alert"
                style="position: relative;">
                <button type="button" class="btn-close me-2" data-bs-dismiss="alert" aria-label="Close"
                    style="position: absolute; right: 10px; top: 10px;"></button>
                <span class="ms-4">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert"
                style="position: relative;">
                <button type="button" class="btn-close me-2" data-bs-dismiss="alert" aria-label="Close"
                    style="position: absolute; right: 10px; top: 10px;"></button>
                <span class="ms-4">{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert"
                style="position: relative;">
                <button type="button" class="btn-close me-2" data-bs-dismiss="alert" aria-label="Close"
                    style="position: absolute; right: 10px; top: 10px;"></button>
                <ul class="mb-0 ms-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
    <div class="container">
        <form action="" style="width: 45%;margin: 0 auto; padding-top: 20px;" method="POST">
            <input type="hidden" id="member_id" value="{{ $member->id ?? '' }}">
            @csrf
            <div class="form-header text-center mb-4">
                <h3 class="text-center">Member Registration</h3>
            </div>
            <div class="form-group">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" <?php if(isset($member)) echo 'value="'.$member->name.'"'; ?>
                required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" 
                    class="form-control"
                    value="{{ $member->email ?? '' }}" required>
                <small id="email-error" class="text-danger d-none">Email already exists</small>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-control"  <?php if(isset($member)) echo 'value="'.$member->phone.'"'; ?>
                required>
            <small id="phone-error" class="text-danger d-none">Phone already exists</small>
            </div>
            @if($admin->member_type == 3)
            <div class="form-group d-none" id="passwordDiv">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" >
            </div>
                <div class="form-group ">
                    <label class="form-label">User Type:</label>
                    <div class="form-check">
                        <input  type="radio" name="user_type"  value="1" <?php if(isset($member)){if($member->member_type == '1'){echo 'checked';}} ?>>
                        <label class="form-check-label" for="user_type_admin">Admin</label>
                        <input  type="radio" name="user_type"  value="2" <?php if(isset($member)){if($member->member_type == '2'){echo 'checked';}}else{echo 'checked';} ?>>
                        <label class="form-check-label" for="user_type_member">Member</label>
                    </div>
                </div>
            @else
                <div class="form-group d-none">
                    <label class="form-label">User Type:</label>
                    <div class="form-check">
                        <input  type="radio" name="user_type" id="user_type_admin" value="1">
                        <label class="form-check-label" for="user_type_admin">Admin</label>
                        <input  type="radio" name="user_type" id="user_type_member" value="2" checked>
                        <label class="form-check-label" for="user_type_member">Member</label>
                    </div>
                </div>

            @endif

            <button type="submit" class="mt-5 btn btn-primary submitBtn">Submit</button>
        </form>
    </div>
     <a href="{{ route('admin-listing') }}" class="btn btn-dark" style="margin-left: 2rem;">Back</a>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
        const passwordDiv = document.getElementById('passwordDiv');
        const passwordInput = passwordDiv.querySelector('input');

        userTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === '1') { // Admin selected
                    passwordDiv.classList.remove('d-none');
                    passwordInput.setAttribute('required', 'required');
                } else { // Member selected
                    passwordDiv.classList.add('d-none');
                    passwordInput.removeAttribute('required');
                }
            });
        });
    });

    </script>
       <script>
        $(document).ready(function() {
            $("#email").on("blur", function() {
                let email = $(this).val();
                let memberId = $("#member_id").val();
                let token = $("meta[name='csrf-token']").attr("content"); // include CSRF
               console.log('Hello World!');
                if (email.length > 0) {
                    $.ajax({
                        url: "{{ route('check.email') }}",
                        type: "POST",
                        data: {
                            _token: token,
                            email: email,
                            member_id: memberId
                        },
                        success: function(response) {
                            if (response.exists) {
                                $("#email-error").removeClass("d-none");
                                $("#email").addClass("is-invalid");
                                setTimeout(() => {
                                    $("#email").val('');
                                }, 2000);
                            } else {
                                $("#email-error").addClass("d-none");
                                $("#email").removeClass("is-invalid");
                            }
                        }
                    });
                }
            });

            $("#phone").on("blur", function() {
                let phone = $(this).val();
                let memberId = $("#member_id").val();
                let token = $("meta[name='csrf-token']").attr("content"); // include CSRF
               console.log('Hello World!');
                if (phone.length > 0) {
                    $.ajax({
                        url: "{{ route('check.phone') }}",
                        type: "POST",
                        data: {
                            _token: token,
                            phone: phone,
                            member_id: memberId
                        },
                        success: function(response) {
                            if (response.exists) {
                                $("#phone-error").removeClass("d-none");
                                $("#phone").addClass("is-invalid");
                                setTimeout(() => {
                                    $("#phone").val('');
                                }, 2000);
                            } else {
                                $("#phone-error").addClass("d-none");
                                $("#phone").removeClass("is-invalid");
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
