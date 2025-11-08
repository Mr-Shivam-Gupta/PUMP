@extends('layouts.app')
@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 mt-5">
                <div class="card-body py-5">
                    <h1 class="display-1 text-danger fw-bold">403</h1>
                    <h3 class="mb-4 text-dark">Access Denied</h3>
                    <p class="text-muted mb-4">
                        You do not have permission to access this page or perform this action.
                    </p>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left-circle"></i> Go Back
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-primary ms-2">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection