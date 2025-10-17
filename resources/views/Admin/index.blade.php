@extends('layouts.app')

@section('title', 'Member Login Page')

@section('styles')
<style>
    .card {
        border-radius: 1rem;
    }

    .hero {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .hero h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .attendance-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .attendance-btn {
        display: block;
        width: 100%;
        padding: 0.9rem;
        font-size: 1.05rem;
        font-weight: 600;
        border-radius: 0.7rem;
        transition: all 0.2s ease;
    }

    .attendance-btn i {
        font-size: 1.1rem;
        vertical-align: middle;
        margin-right: 6px;
    }

    .attendance-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .text-muted small {
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="card shadow-sm mx-auto p-5 mt-5" style="max-width: 500px;">
    <div class="hero">
        <h1 class="display-6 fw-bold">{{ $club->club_name }}</h1>
    </div>

    <div class="attendance-grid">
        <a href="{{ route('user-signin', ['club_id' => Helper::encoded($club->id)]) }}"
           class="attendance-btn btn btn-outline-primary">
            <i class="bi bi-person-check"></i> Members
        </a>

        <a href="{{ route('substitute-signin', ['club_id' => Helper::encoded($club->id)]) }}"
           class="attendance-btn btn btn-outline-warning">
            <i class="bi bi-people-fill"></i> Substitutes
        </a>

        <a href="{{ route('guest-signin', ['club_id' => Helper::encoded($club->id)]) }}"
           class="attendance-btn btn btn-outline-success">
            <i class="bi bi-person-plus"></i> Visitors
        </a>
    </div>

    <div class="text-center mt-4">
        <small class="text-muted">Attendance for: <strong>{{ $club->club_name }}</strong></small>
    </div>
</div>
@endsection
