@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        {{-- Header --}}
                        <div class="card-header align-items-center d-flex gap-2">
                            <h4 class="card-title mb-0 flex-grow-1">Owner List</h4>
                            <div class="flex-shrink-0 d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#ownerModal" id="addOwnerBtn">
                                    <i class="ri-add-circle-fill label-icon align-middle rounded-pill fs-16 me-2"></i>
                                    Add New Owner
                                </button>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div class="card-body">
                            <table id="ownersTable"
                                class="table nowrap dt-responsive align-middle table-hover table-bordered"
                                style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Owner ID</th>
                                        <th>Tenant IDs</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($owners as $key => $owner)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $owner->owner_id }}</td>
                                            <td>
                                                @if (!empty($owner->tenant_names))
                                                    @foreach ($owner->tenant_names as $tenant)
                                                        <span
                                                            class="badge bg-primary-subtle text-primary badge-border me-1 mb-1">
                                                            {{ $tenant }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($owner->status)
                                                    <span
                                                        class="badge bg-success-subtle text-success badge-border">Active</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger-subtle text-danger badge-border">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm edit-owner"
                                                    data-id="{{ $owner->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#ownerModal">
                                                    <i class="ri-edit-2-fill"></i>
                                                </button>

                                                <button type="button" class="btn btn-warning btn-sm toggle-status"
                                                    data-id="{{ $owner->id }}">
                                                    <i class="ri-refresh-line"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger btn-sm delete-owner"
                                                    data-id="{{ $owner->id }}">
                                                    <i class="ri-delete-bin-5-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <x-modal.owner />
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/pages/owner.js') }}"></script>
@endpush
