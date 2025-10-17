@extends('layouts.app')

@section('title', 'Member-login Page')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/signIn/index.css') }}">
@endsection

@section('content')

<div class="container py-5">
    <div class="hero">
        <h1 class="display-6 fw-bold">{{ $club->club_name }}</h1>
        <p class="lead">Choose the correct attendance flow for this club — each option takes you to the appropriate sign-in screen pre-set for <strong>{{ $club->club_name ?? 'the selected club' }}</strong>.</p>
    </div>

    <div class="attendance-grid">
        <!-- Member Attendance -->
        <div class="att-card" role="region" aria-label="Member Attendance">
            <div>
                <div class="att-icon icon-member">
                    <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                </div>
                <div class="card-title">Member Attendance</div>
            </div>

            <div>
                <a href="{{ route('user-signin', ['club_id' => Helper::encoded($club->id)]) }}"
                   class="btn btn-lg btn-outline-primary">
                    <i class="bi bi-arrow-right-circle me-2"></i>Go to Member Attendance
                </a>
            </div>
        </div>

        <!-- Substitute Attendance -->
        <div class="att-card" role="region" aria-label="Substitute Attendance">
            <div>
                <div class="att-icon icon-substitute">
                    <i class="bi bi-person-badge-fill" aria-hidden="true"></i>
                </div>
                <div class="card-title">Substitute Attendance</div>
            </div>

            <div>
                <a href="{{ route('substitute-signin', ['club_id' => Helper::encoded($club->id)]) }}"
                   class="btn btn-lg btn-outline-warning">
                    <i class="bi bi-arrow-right-circle me-2"></i>Go to Substitute Attendance
                </a>
            </div>
        </div>

        <!-- Guest Attendance -->
        <div class="att-card" role="region" aria-label="Guest Attendance">
            <div>
                <div class="att-icon icon-guest">
                    <i class="bi bi-person-fill-add" aria-hidden="true"></i>
                </div>
                <div class="card-title">Guest Attendance</div>
            </div>

            <div>
                <a href="{{ route('guest-signin', ['club_id' => Helper::encoded($club->id)]) }}"
                   class="btn btn-lg btn-outline-success">
                    <i class="bi bi-arrow-right-circle me-2"></i>Go to Guest Attendance
                </a>
            </div>
        </div>
    </div>

    {{-- optional small footer / helper text --}}
    <div class="text-center mt-4">
        <small class="text-muted">Attendance for: <strong>{{ $club->club_name }}</strong></small>
    </div>
</div>
@endsection
