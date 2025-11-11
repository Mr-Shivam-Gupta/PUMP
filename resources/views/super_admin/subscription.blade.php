@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        {{-- Header --}}
                        <div class="card-header align-items-center d-flex gap-2">
                            <h4 class="card-title mb-0 flex-grow-1">Subscription List</h4>

                            <div class="flex-shrink-0 d-flex gap-2">
                                {{-- Add Subscription Button --}}
                                <button type="button" class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#subscriptionModal" id="addSubscriptionBtn">
                                    <i class="ri-add-circle-fill label-icon align-middle rounded-pill fs-16 me-2"></i>
                                    Add New Subscription
                                </button>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div class="card-body">
                            <table id="subscriptionTable"
                                class="table nowrap dt-responsive align-middle table-hover table-bordered"
                                style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>S No.</th>
                                        <th>Tenant</th>
                                        <th>Plan</th>
                                        <th>Trial</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Subscription Modal --}}
    <div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="addSubscriptionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="subscriptionForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubscriptionLabel">Add New Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tenant_id" class="form-label">Tenant ID</label>
                            <input type="text" class="form-control" id="tenant_id" name="tenant_id"
                                placeholder="Enter Tenant ID">
                        </div>
                        <div class="mb-3">
                            <label for="plan" class="form-label">Plan</label>
                            <input type="text" class="form-control" id="plan" name="plan"
                                placeholder="Enter Plan Name">
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="saveSubscriptionBtn" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/pages/subscription.js') }}"></script>
@endpush
