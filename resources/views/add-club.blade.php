<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Club</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
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
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 fw-bold text-primary">Add New Club</h2>

                    <form action="{{ route('add-club') }}" method="POST">
                        @csrf
                       <input type="hidden" name="club_id" value="{{ $club->id ?? '' }}">
                        <div class="mb-3">
                            <label for="club_name" class="form-label fw-semibold">Club Name</label>
                            <input type="text" class="form-control form-control-lg" id="club_name"
                                   name="club_name" placeholder="Enter club name" 
                                   <?php if(isset($club)) echo 'value="'.$club->club_name.'"'; ?>
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="meeting_day" class="form-label fw-semibold">Day</label>
                            <select class="form-select form-select-lg" id="meeting_day" name="meeting_day" required>
                                <option  value="" selected disabled>Select a day</option>
                                <option <?php if(isset($club)){if($club->meeting_day == 'Monday'){echo 'selected';}} ?> value="Monday">Monday</option>
                                <option <?php if(isset($club)){if($club->meeting_day == 'Tuesday'){echo 'selected';}} ?> value="Tuesday">Tuesday</option>
                                <option <?php if(isset($club)){if($club->meeting_day == 'Wednesday'){echo 'selected';}} ?> value="Wednesday">Wednesday</option>
                                <option <?php if(isset($club)){if($club->meeting_day == 'Thursday'){echo 'selected';}} ?> value="Thursday">Thursday</option>
                                <option <?php if(isset($club)){if($club->meeting_day == 'Friday'){echo 'selected';}} ?> value="Friday">Friday</option>
                                <option <?php if(isset($club)){if($club->meeting_day == 'Saturday'){echo 'selected';}} ?> value="Saturday">Saturday</option>
                                <option <?php if(isset($club)){if($club->meeting_day == 'Sunday'){echo 'selected';}} ?> value="Sunday">Sunday</option>
                            </select>
                        </div>

                        <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ isset($club) ? 'Update Club' : 'Add Club' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <a href="{{ route('admin-listing') }}" class="btn btn-dark" style="margin-left: 2rem;">Back</a>
</body>
</html>