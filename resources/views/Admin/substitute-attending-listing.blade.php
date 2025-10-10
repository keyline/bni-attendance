@extends('layouts.app')

@section('title', 'Member-attendance-list Page')

@section('content')

    <div class="container mt-4">

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Page Heading --}}
        <div class="text-center mb-4">
            <h1 class="text-warning">
                @foreach ($clubs as $club)
                    @if ($club->id == $user->club_id)
                        {{ $club->club_name }}
                    @endif
                @endforeach
            </h1>
            <h4 class="text-secondary">
                Welcome {{ $substituteName ?? '' }}  Substitute of {{ $user->name ?? '' }}
            </h4>
            <h5 class="text-success">

                {{-- @foreach ($attendances as $attendance) --}}
                {{-- Attendance Date --}}
                <h6 class="text-success">
                    {{-- Attendence time: {{ \Carbon\Carbon::parse($attendance->time)->format('l, jS F, Y') }} --}}
                    Attendence time: {{ \Carbon\Carbon::parse(now())->format('l, jS F, Y') }}
                </h6>

                {{-- Attendance Time --}}
                <h2 class="fw-bold">
                    {{ \Carbon\Carbon::parse(now())->format('h.i A') }}
                </h2>
                {{-- @endforeach --}}
            </h5>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-4 text-center">
            <a href="{{ route('user-signin') }}" class="btn btn-outline-danger px-4">close</a>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search attendance..."
                }
            });
        });
    </script>
@endsection
