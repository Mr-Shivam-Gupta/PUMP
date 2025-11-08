@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-2">
                            <h4 class="card-title mb-0 flex-grow-1">Plan List</h4>
                            <div class="flex-shrink-0 d-flex gap-2">
                                {{-- @can('create_plan') --}}
                                <button type="button" class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#planModal" id="addPlanBtn">
                                    <i class="ri-add-circle-fill label-icon align-middle rounded-pill fs-16 me-2"></i> Add
                                    New
                                </button>
                                {{-- @endcan --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="plansTable"
                                class="table nowrap dt-responsive align-middle table-hover table-bordered"
                                style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>S No.</th>
                                        <th style="text-align: left">Name</th>
                                        <th style="text-align: left">Duration(In Days)</th>
                                        <th>Isolation</th>
                                        <th>Max Users</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @can('list_plan') --}}
                                    @foreach ($plans as $key => $plan)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td style="text-align: left">{{ $plan->name }}</td>
                                            <td style="text-align: left">{{ $plan->duration_days }}</td>

                                            <td>
                                                @if ($plan->isolation == 'shared_schema')
                                                    <span class="badge bg-info-subtle text-info badge-border">Shared
                                                        Schema</span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success badge-border">Separate
                                                        DB</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $plan->max_users ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $plan->price !== null ? '₹' . number_format($plan->price, 2) : '-' }}
                                            </td>
                                            <td>
                                                {{-- @can('modify_plan') --}}
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#planModal"
                                                    class="btn btn-primary btn-sm edit-plan"
                                                    data-id="{{ $plan->id }}"><i class="ri-edit-2-fill"></i></button>
                                                {{-- @endcan --}}
                                                {{-- @can('remove_plan') --}}
                                                <button type="button" class="btn btn-danger btn-sm delete-plan"
                                                    data-id="{{ $plan->id }}"><i
                                                        class="ri-delete-bin-5-fill"></i></button>
                                                {{-- @endcan --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- @else
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <span class="text-danger">You do not have permission to view the user
                                                    list.</span>
                                            </td>
                                        </tr>
                                    @endcan --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-modal.plan />
@endsection
@push('scripts')
    <script src="{{ asset('custom/js/pages/plans.js') }}"></script>
@endpush
