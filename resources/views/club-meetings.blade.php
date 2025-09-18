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
        body {
            padding: 2rem;
        }
    </style>
</head>
@php
    use Carbon\Carbon;
@endphp

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
    @if ($admin->member_type !== 3)
        <h1 class="text-center mt-2" style="width: 100%;color: #e0b439;">Club :
            @foreach ($clubs as $club)
                @if ($club->id == $admin->club_id)
                    {{ $club->club_name }}
                @endif
            @endforeach
        </h1>
    @endif
</div>
<div class="row">
    @if ($admin->member_type == 3)
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
    @if ($admin->member_type !== 3)
        <h5 class="text-center mt-2" style="width: 100%; color: #89e6a5;">Meeting Day :
            @foreach ($clubs as $club)
                @if ($club->id == $admin->club_id)
                    {{ $club->meeting_day }}
                    <?php $club = $club; ?>
                @endif
            @endforeach
        </h5>
    @endif
</div>
<div class="row">
    <h3>All meetings of <strong>{{ $selected_club->club_name }}</strong></h3>
    <h4>Meeting Day : <strong>{{ $selected_club->meeting_day }}</strong></h4>
</div>
<table id="myTable" class="display">
    <thead>
        <tr>
            <th>Sl. No.</th>
            <th>Meeting Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        {{-- @php
         $i = 1; 
          $descOrderedDates = collect($dates)->sortDesc()->values();
        @endphp
        @foreach ($descOrderedDates as $date)
         @php
         $dayName = Carbon::parse($date)->format('l');  // e.g. Monday, Tuesday
        //  echo $date;  
         @endphp
         @if ($dayName == $selected_club->meeting_day)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $date; }}</td>
                <td><a href="{{route('club-meeting-attend-member', ['selected_club' => $selected_club->id, 'club_meeting_date' => $date])}}">View</a></td>
            </tr>
            @php $i++; @endphp
        @endif
    @endforeach --}}
        @php $i = 1; @endphp
        @foreach ($dates as $date)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $date }}</td>
                <td><a
                        href="{{ route('club-meeting-attend-member', ['selected_club' => $selected_club->id, 'club_meeting_date' => $date]) }}">View</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
{{-- <h3>Old meetings</h3>
<table id="myOldTable" class="display">
    <thead>
        <tr>
            <th>Sl. No.</th>
            <th>Meeting Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach ($oldDates as $date)
         @php
         $dayName = Carbon::parse($date)->format('l');  // e.g. Monday, Tuesday
         @endphp
         @if ($dayName == $selected_club->meeting_day)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $date; }}</td>
                <td><a href="{{route('club-meeting-attend-member', ['selected_club' => $selected_club->id, 'club_meeting_date' => $date])}}">show</a></td>
            </tr>
        @endif
    @php $i++; @endphp
    @endforeach
    </tbody>
</table> --}}

@if ($admin)
    @if ($admin->member_type == 3)
        <a href="{{ route('add-club') }}" class="btn btn-success">Add New Club</a>
    @endif
@endif
@if ($admin)
    @if ($admin->member_type == 3)
        <a href="{{ route('club-listing') }}" class="btn btn-outline-success">View all clubs</a>
    @endif
@endif
<a href="{{ route('members.add', $selected_club->id) }}" class="btn btn-dark">Add member</a>
@if ($admin)
    @if ($admin->member_type == 3)
        <a href="{{ route('admin-listing') }}" class="btn btn-outline-dark">View all members</a>
    @endif
@endif
{{-- <a href="{{ route('members.add') }}" class="btn btn-primary">Add Member</a> --}}
@if ($admin)
    @if ($admin->member_type == 1)
        <a href="{{ route('admin-listing') }}" class="btn btn-outline-dark">View members</a>
    @endif
@endif
@if ($admin)
    @if ($admin->member_type == 3)
        <a href="{{ route('club-listing') }}" class="btn btn-dark">Back</a>
    @endif
@endif
{{-- <a href="{{ route('club-meeting-day', ['club_id' => $admin->club_id]) }}" class="btn btn-dark">Back</a> --}}
<a href="{{ route('member.logout') }}" class="btn btn-outline-danger">Log out</a>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

<body>

</body>

</html>
