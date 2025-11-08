<div class="modal fade" id="ownerModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="ownerLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="progress-container" style="display: none;">
                <div class="progres" style="height: 5px;">
                    <div class="indeterminate" style="background-color: var(--vz-primary);"></div>
                </div>
            </div>

            <div class="modal-header">
                <h5 class="modal-title" id="addOwnerLabel">Add New Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="ownerForm" action="javascript:void(0);" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ownerId" class="form-label">Owner ID</label>
                            <input type="text" class="form-control" id="ownerId" name="owner_id"
                                placeholder="Enter owner ID" required>
                        </div>

                        <div class="col-md-6">
                            <label for="ownerPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="ownerPassword" name="owner_password"
                                placeholder="Enter password" required>
                        </div>
                        <div class="col-md-12">
                            <label for="ownerTenants" class="form-label">Assign Tenants</label>
                            <select id="ownerTenants" name="own_tenant_ids[]" class="form-control" multiple>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ownerStatus" class="form-label">Status</label>
                            <select class="form-select" id="ownerStatus" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button"
                                    class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    id="saveOwnerBtn">
                                    <i class="ri-check-double-line label-icon align-middle rounded-pill fs-16 me-2"></i>
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
