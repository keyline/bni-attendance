@extends('layouts.app')

@section('title', 'Attend-member-list Page')

@section('content')

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
        <h3>All members of</h3>
        <h3>{{ $selected_club->club_name }}</h3>
        <h3> Attendance of {{ $clubMeetingDate }}</h3>
        {{-- <h3>Attendance of {{ \Carbon\Carbon::parse($clubMeetingDate)->format('l, d/m/Y') }}</h3> --}}
    </div>
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Member Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- @php
             $i = 1; 
          $attdArray = $attendMembers->pluck('time','member_id')->toArray();
        @endphp
        @foreach ($members as $member)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $member->name; }}</td>
                <td>
                    @if (isset($attdArray[$member->id]))
                        <span class="badge bg-success">{{ $attdArray[$member->id] }}</span>
                    @else
                        <span class="badge bg-danger">Absent</span>
                    @endif
                </td>
            </tr>
    @php $i++; @endphp
    @endforeach --}}
            @php $i = 1; @endphp
            {{-- @foreach ($present as $memberId => $time) --}}
            @foreach ($present as $memberId => $data)
                @php $member = $members->firstWhere('id', $memberId); @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $member->name }} <?php if ($data->is_substitute == 1) {
                        echo '(' . $data->substitute_name . ' as Substitute attended)';
                    } ?> </td>
                    <td>
                        <span class="badge bg-success">
                            {{ \Carbon\Carbon::parse($data->time)->format('H:i:s') }}
                        </span>
                    </td>
                </tr>
            @endforeach


            @foreach ($absent as $member)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $member->name }}</td>
                    <td>
                        <span class="badge bg-danger">Absent</span>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

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
    <a href="{{ route('members.add', ['club_id' => Crypt::encrypt($selected_club->id)]) }}" class="btn btn-dark">Add
        member</a>
    @if ($admin)
        @if ($admin->member_type == 3)
            <a href="{{ route('admin-listing') }}" class="btn btn-outline-dark">View all members</a>
        @endif
    @endif
    {{-- <a href="{{ route('members.add') }}" class="btn btn-primary">Add Member</a> --}}
    {{-- <a href="{{ route('attending-listing') }}" class="btn btn-outline-dark">View member's Attendance</a> --}}
    <a href="{{ route('club-meeting-day', ['club_id' => Crypt::encrypt($selected_club->id)]) }}"
        class="btn btn-dark">Back</a>
    <a href="{{ route('member.logout') }}" class="btn btn-outline-danger">Log out</a>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                lengthChange: false,
                paging: false
            });
        });
    </script>
@endsection
