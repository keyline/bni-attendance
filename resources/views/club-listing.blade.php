<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Club listing</title>
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
        body {
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
    @if ($supadmin->member_type !== 3)
        <h1 class="text-center mt-2" style="width: 100%;color: #e0b439;">Club :
            @if ($club)
                {{ $club->club_name }}
            @endif
        </h1>
    @endif
</div>
<div class="row">
    @if ($supadmin->member_type == 3)
        <h4 class="text-center mt-2" style="width: 100%; color: #b9825d;">Super Admin :
            @if ($supadmin)
                {{ $supadmin->name }}
            @endif
        </h4>
    @else
        <h4 class="text-center mt-2" style="width: 100%; color: #b9825d;">Admin :
            @if ($supadmin)
                {{ $supadmin->name }}
            @endif
        </h4>
    @endif
</div>
<div class="row">
    @if ($supadmin->member_type !== 3)
        <h5 class="text-center mt-2" style="width: 100%; color: #89e6a5;">Meeting Day :
            @if ($meeting)
                {{ $meeting->day }}
            @endif
        </h5>
    @endif
</div>
<div class="row">
    <h3>All clubs </h3>
</div>
<table id="myTable" class="display">
    <thead>
        <tr>
            <th>Sl. No.</th>
            <th>Name</th>
            <th>Admin</th>
            <th>Meeting Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach ($clubs as $club)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $club->club_name }}</td>
                <td>
                    @foreach ($admins as $admin)
                        @if ($club->id == $admin->club_id)
                            {{ $admin->name }} &nbsp; {{ '(' . $admin->phone . ')' }}
                        @endif
                    @endforeach

                </td>
                <td>{{ $club->meeting_day }}</td>
                <td>
                    <a href="{{ route('club-meeting-day', ['club_id' => $club->id]) }}">View</a>
                    <a href="{{ route('add-club', $club->id) }}">Edit</a>
                    <a href="{{ route('members.add', $club->id) }}">Add member</a>
                    <form action="" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @php $i++; @endphp
        @endforeach
    </tbody>
</table>
@if ($supadmin)
    @if ($supadmin->member_type == 3)
        <a href="{{ route('add-club') }}" class="btn btn-success">Add New Club</a>
    @endif
@endif
@if ($supadmin)
    @if ($supadmin->member_type == 3)
        <a href="{{ route('club-listing') }}" class="btn btn-dark">View all clubs</a>
    @endif
@endif
@if ($supadmin)
    @if ($supadmin->member_type == 3)
        <a href="{{ route('admin-listing') }}" class="btn btn-dark">View all members</a>
    @endif
@endif
{{-- <a href="{{ route('members.add') }}" class="btn btn-primary">Add Member</a> --}}
{{-- <a href="{{ route('attending-listing') }}" class="btn btn-outline-dark">View member's Attendance</a> --}}
<a href="{{ route('member.logout') }}" class="btn btn-outline-danger">Log out</a>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

<body>

</body>

</html>
