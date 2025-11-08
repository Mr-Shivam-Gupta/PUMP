<div class="modal fade" id="permissionGroupModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="permissionGroupLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="progress-container" style="display: none;">
                <div class="progres" style="height: 5px;">
                    <div class="indeterminate" style="background-color: var(--vz-primary);"></div>
                </div>
            </div>
            <div class="modal-header">
                <h5 class="modal-title" id="addPermissionGroupLabel">Add New Permission Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" id="permissionGroupForm" novalidate>
                    <div class="row g-3">
                        <div class="col-xxl-6 col-md-12">
                            <label for="group_name" class="form-label">Group Name</label>
                            <input type="text" class="form-control" id="group_name" name="group_name"
                                placeholder="Enter group name" required />
                        </div>
                        <div class="col-xxl-6 col-md-12">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="Administration">Administration</option>
                                <option value="Navigation">Navigation</option>
                                <option value="Analytics">Analytics</option>
                                <option value="Communication">Communication</option>
                                <option value="Reports">Reports</option>
                            </select>
                        </div>
                        <div class="col-xxl-6 col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="is_active" type="checkbox" checked role="switch"
                                    id="is_active" onchange="toggleSwitchText()" />
                                <label class="form-check-label" for="is_active" id="is_active_label">Active</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button"
                                    class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    id="savePermissionGroupBtn">
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