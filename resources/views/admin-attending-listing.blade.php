<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
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
         <style>
        body{
            padding: 2rem;
        }
    </style>
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
    @if($admin->member_type !== 3)
    <h1 class="text-center mt-2" style="width: 100%;color: #e0b439;">Club :
        @foreach($clubs as $club)
        @if ($club->id == $admin->club_id)
            {{ $club->club_name }}
        @endif
        @endforeach
    </h1>
    @endif
</div>
<div class="row">
    @if($admin->member_type == 3)
        <h4 class="text-center mt-2" style="width: 100%; color: #b9825d;">Super Admin :
        @if ($admin)
            {{ $admin->name }}
        @endif
       </h4>
    @else
    <h4 class="text-center mt-2" style="width: 100%; color: #b9825d;">Admin :
        @if ($admin)
            {{ $admin->name }}
        @endif
    </h4>
    @endif
</div>
<div class="row">
    @if($admin->member_type !== 3)
        <h5 class="text-center mt-2" style="width: 100%; color: #89e6a5;">Meeting Day :
        @foreach($clubs as $club)
        @if ($club->id == $admin->club_id)
            {{ $club->meeting_day }}
        @endif
        @endforeach
        </h5>
    @endif
</div>
<div class="row">
      <h3>All member's Attendance </h3>
</div>

<table id="myTable" class="display">
    <thead>
        <tr>
            <th>Sl. No.</th>
            <th>Name</th>
            <th>Club Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach ($attendances as $attendance)
            <tr>
                <td>{{$i}}</td>
                <td>
                    @foreach ($members as $member)
                        @if ($member->id == $attendance->member_id)
                            {{ $member->name }}
                        @endif
                    @endforeach
                </td>
                <td>
{{ $clubs->firstWhere('id', $attendance->club_id)->club_name ?? '' }}
                </td>
                <td>{{ $attendance->date }}</td>
                <td>{{ $attendance->time }}</td>
                <td>Done</td>
            </tr>
            @php $i++; @endphp
        @endforeach
    </tbody>
</table>
<a href="{{ route('admin-listing') }}" class="btn btn-dark">Back</a>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

<body>

</body>

</html>
