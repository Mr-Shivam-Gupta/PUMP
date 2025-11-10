<div class="modal fade" id="userModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="userLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Progress Bar --}}
            <div class="progress-container" style="display: none;">
                <div class="progres" style="height: 5px;">
                    <div class="indeterminate" style="background-color: var(--vz-primary);"></div>
                </div>
            </div>

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form action="javascript:void(0);" id="userForm" novalidate>
                    <div class="row g-3">

                        {{-- Tenant --}}
                        <div class="col-md-6">
                            <label for="tenant_id" class="form-label">Tenant</label>
                            <select class="form-select" id="tenant_id" name="tenant_id" required>
                                <option value="" disabled selected>Select Tenant</option>
                                {{-- Dynamically populated via JS or Blade --}}
                            </select>
                            <div class="invalid-feedback">Please select a tenant.</div>
                        </div>

                        {{-- Employee ID --}}
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="employee_id" name="employee_id"
                                placeholder="Enter employee ID">
                        </div>

                        {{-- Role --}}
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>Select role</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="employee">Employee</option>
                            </select>
                            <div class="invalid-feedback">Please select a role.</div>
                        </div>

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter full name" required>
                            <div class="invalid-feedback">Please enter the user's name.</div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="Enter phone number" required>
                            <div class="invalid-feedback">Phone number is required.</div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="example@email.com" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter password" required>
                            <div class="invalid-feedback">Password is required.</div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button"
                                    class="btn btn-primary btn-label waves-effect waves-light rounded-pill"
                                    id="saveUserBtn">
                                    <i
                                        class="ri-check-double-line label-icon align-middle rounded-pill fs-16 me-2"></i>
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
