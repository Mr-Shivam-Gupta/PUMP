<div class="modal fade" id="planModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="planLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="progress-container" style="display: none;">
                <div class="progres" style="height: 5px;">
                    <div class="indeterminate" style="background-color: var(--vz-primary);"></div>
                </div>
            </div>
            <div class="modal-header">
                <h5 class="modal-title" id="addPlanLabel">Add New Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" id="planForm" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="planName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="planName" name="name"
                                placeholder="Enter plan name" required />
                        </div>
                        <div class="col-md-6">
                            <label for="durationDays" class="form-label">Duration (Days)</label>
                            <input type="number" min="1" class="form-control" id="durationDays" name="duration_days"
                                placeholder="e.g. 30" required />
                        </div>
                        <div class="col-md-6">
                            <label for="isolation" class="form-label">Isolation Type</label>
                            <select class="form-select" id="isolation" name="isolation" required>
                                <option value="" disabled selected>Select isolation</option>
                                <option value="shared_schema">Shared Schema</option>
                                <option value="separate_db">Separate DB</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="maxUsers" class="form-label">Max Users</label>
                            <input type="number" min="1" class="form-control" id="maxUsers" name="max_users"
                                placeholder="e.g. 100" />
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Price (₹)</label>
                            <input type="number" min="0" step="0.01" class="form-control" id="price" name="price"
                                placeholder="e.g. 499.00" />
                        </div>
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button"
                                    class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    id="savePlanBtn">
                                    <i class="ri-check-double-line label-icon align-middle rounded-pill fs-16 me-2">
                                        <span class="loader" style="display: none;"></span>
                                    </i>
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
