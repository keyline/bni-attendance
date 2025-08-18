<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

</head>
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
<div class="row">
    <h1 class="text-center mt-2" style="width: 100%;color: #e0b439;"> Club :
        @if ($club)
            {{ $club->club_name }}
        @endif
    </h1>
</div>
<div class="row">
    <h4 class="text-center mt-2" style="width: 100%; color: #b9825d;">User :
        @if ($user)
            {{ $user->name }}
        @endif
    </h4>
</div>
<div class="row">
    <h5 class="text-center mt-2" style="width: 100%; color: #89e6a5;">Meeting Day :
        @if ($club)
            {{ $club->meeting_day }}
        @endif
    </h5>
</div>

{{-- <table id="myTable" class="display">
    <thead>
        <tr>
            <th colspan="4">Your club members</th>
        </tr>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
            <tr>
                <td>{{ $member->name }} @if ($member->id == session('user')->id)
                        (You)
                        @endif @if ($member->member_type == 1)
                            (Admin)
                        @endif
                </td>
                <td>{{ $member->email }}</td>
                <td>{{ $member->phone }}</td>
            </tr>
        @endforeach
    </tbody>
</table> --}}

<a href="{{ route('attending') }}" class="btn btn-primary">Go to Attendance</a>
<a href="{{ route('attending-listing') }}" class="btn btn-outline-dark">View your Attendance</a>
<a href="{{ route('member.logout') }}" class="btn btn-outline-danger">Log out</a>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

<body>

</body>

</html>
