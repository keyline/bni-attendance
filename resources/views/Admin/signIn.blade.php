@extends('layouts.app')

@section('title', 'Admin-login Page')

@section('content')

    <div class="container">
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

        <form action="" style="width: 45%;margin: 0 auto; padding-top: 20px;" method="POST">
            @csrf
            <div class="form-header mb-4">
                <h3 class="text-center">Sign in</h3>
            </div>

            <div class="form-group">
                <label for="phoneOrEmail" class="form-label"> Email or Phone:</label>
                <input type="text" id="phoneOrEmail" name="phoneOrEmail" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="mt-5 btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
