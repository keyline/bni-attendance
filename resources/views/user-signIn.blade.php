<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
</head>

<body>
<div class="container py-5">

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>Please fix the following errors:</strong>
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
</body>
</html>
