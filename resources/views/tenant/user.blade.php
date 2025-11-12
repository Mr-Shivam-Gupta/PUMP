@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">User List</h4>
                    <div class="d-flex gap-2">
                        <select id="filterTenant" class="form-select">
                            <option value="">All Tenants</option>
                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                            @endforeach
                        </select>
                        <select id="filterRole" class="form-select">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="employee">Employee</option>
                        </select>
                        <select id="filterStatus" class="form-select">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#userModal" id="addUserBtn">
                            <i class="ri-add-circle-fill me-1"></i> Add User
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <table id="usersTable" class="table table-bordered table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Tenant</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <x-modal.user />
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/pages/user.js') }}"></script>
@endpush
