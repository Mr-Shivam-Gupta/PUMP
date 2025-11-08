@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        {{-- Header --}}
                        <div class="card-header align-items-center d-flex gap-2">
                            <h4 class="card-title mb-0 flex-grow-1">Tenant List</h4>
                            <div class="flex-shrink-0 d-flex gap-2">
                                {{-- Add Tenant Button (opens modal) --}}
                                <button type="button" class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#tenantModal" id="addTenantBtn">
                                    <i class="ri-add-circle-fill label-icon align-middle rounded-pill fs-16 me-2"></i>
                                    Add New Tenant
                                </button>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div class="card-body">
                            <table id="tenantsTable"
                                class="table nowrap dt-responsive align-middle table-hover table-bordered"
                                style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>S No.</th>
                                        <th style="text-align: left;">Name</th>
                                        <th style="text-align: left;">Domain</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Isolation</th>
                                        <th>Database</th>
                                        <th>Plan</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tenants as $key => $tenant)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td style="text-align: left;">{{ $tenant->name }}</td>
                                            <td style="text-align: left;">{{ $tenant->domain ?? '-' }}</td>
                                            <td>{{ $tenant->email }}</td>
                                            <td>{{ $tenant->contact }}</td>
                                            <td>
                                                @if ($tenant->isolation === 'shared_schema')
                                                    <span class="badge bg-info-subtle text-info badge-border">Shared
                                                        Schema</span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success badge-border">Separate
                                                        DB</span>
                                                @endif
                                            </td>
                                            <td>{{ $tenant->database ?? '-' }}</td>
                                            <td>{{ $tenant->plan ?? '-' }}</td>
                                            <td>
                                                @if ($tenant->status)
                                                    <span
                                                        class="badge bg-success-subtle text-success badge-border">Active</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger-subtle text-danger badge-border">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{-- Edit --}}
                                                <button type="button" class="btn btn-primary btn-sm edit-tenant"
                                                    data-id="{{ $tenant->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#tenantModal">
                                                    <i class="ri-edit-2-fill"></i>
                                                </button>

                                                {{-- Toggle Status --}}
                                                <button type="button" class="btn btn-warning btn-sm toggle-status"
                                                    data-id="{{ $tenant->id }}">
                                                    <i class="ri-refresh-line"></i>
                                                </button>

                                                {{-- Delete --}}
                                                <button type="button" class="btn btn-danger btn-sm delete-tenant"
                                                    data-id="{{ $tenant->id }}">
                                                    <i class="ri-delete-bin-5-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($tenants->isEmpty())
                                        <tr>
                                            <td colspan="10" class="text-center text-danger">No tenants found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tenant Modal (used for both Add/Edit) --}}
    <x-modal.tenant />
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/pages/tenant.js') }}"></script>
@endpush
