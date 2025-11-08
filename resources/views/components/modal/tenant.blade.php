<div class="modal fade" id="tenantModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="tenantLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="progress-container" style="display: none;">
                <div class="progres" style="height: 5px;">
                    <div class="indeterminate" style="background-color: var(--vz-primary);"></div>
                </div>
            </div>
            <div class="modal-header">
                <h5 class="modal-title" id="addTenantLabel">Add New Tenant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="javascript:void(0);" id="tenantForm" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tenantName" class="form-label">Tenant Name</label>
                            <input type="text" class="form-control" id="tenantName" name="name"
                                placeholder="Enter tenant name" required />
                        </div>

                        <div class="col-md-6">
                            <label for="tenantEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="tenantEmail" name="email"
                                placeholder="example@email.com" required />
                        </div>

                        <div class="col-md-6">
                            <label for="tenantContact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="tenantContact" name="contact"
                                placeholder="Enter contact number" required />
                        </div>

                        <div class="col-md-6">
                            <label for="tenantDomain" class="form-label">Domain</label>
                            <input type="text" class="form-control" id="tenantDomain" name="domain"
                                placeholder="e.g. tenant1.example.com" />
                        </div>

                        <div class="col-md-6">
                            <label for="tenantIsolation" class="form-label">Isolation Type</label>
                            <select class="form-select" id="tenantIsolation" name="isolation" required>
                                <option value="" disabled selected>Select isolation</option>
                                <option value="shared_schema">Shared Schema</option>
                                <option value="database">Separate DB</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="tenantPlan" class="form-label">Plan</label>
                            <input type="text" class="form-control" id="tenantPlan" name="plan"
                                placeholder="Enter plan name" />
                        </div>

                        <div class="col-md-6">
                            <label for="tenantPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="tenantPassword" name="password"
                                placeholder="Set tenant password" required />
                        </div>

                        <div class="col-md-6">
                            <label for="tenantAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="tenantAddress" name="address"
                                placeholder="Enter address" />
                        </div>

                        <div class="col-md-6">
                            <label for="tenantGst" class="form-label">GST Number</label>
                            <input type="text" class="form-control" id="tenantGst" name="gst_number"
                                placeholder="Enter GST No." />
                        </div>

                        <div class="col-md-6">
                            <label for="tenantLicense" class="form-label">License Number</label>
                            <input type="text" class="form-control" id="tenantLicense" name="license_number"
                                placeholder="Enter License No." />
                        </div>

                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button"
                                    class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    id="saveTenantBtn">
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
