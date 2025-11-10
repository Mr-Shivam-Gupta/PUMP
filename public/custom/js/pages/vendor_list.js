$(document).ready(function () {
    function makeUrl(path) {
        return "/" + path.replace(/^\/+/, "");
    }

    let isEditMode = false;

    // -----------------------------
    // 🧠 Initialize DataTable (Server-side)
    // -----------------------------
    const usersTable = $("#usersTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: makeUrl("super-admin/users/list"),
            data: function (d) {
                d.tenant_id = $("#filterTenant").val();
                d.role = $("#filterRole").val();
                d.status = $("#filterStatus").val();
            },
        },
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
            { data: "name", name: "name" },
            { data: "email", name: "email" },
            { data: "phone", name: "phone" },
            { data: "tenant_name", name: "tenant_name" },
            { data: "role", name: "role" },
            { 
                data: "status", 
                name: "status",
                render: function (data) {
                    return data == 1
                        ? '<span class="badge bg-success-subtle text-success badge-border">Active</span>'
                        : '<span class="badge bg-danger-subtle text-danger badge-border">Inactive</span>';
                },
            },
            {
                data: "id",
                name: "actions",
                orderable: false,
                searchable: false,
                render: function (id, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm edit-user" data-id="${id}" data-bs-toggle="modal" data-bs-target="#userModal">
                            <i class="ri-edit-2-fill"></i>
                        </button>
                        <button class="btn btn-warning btn-sm toggle-status" data-id="${id}">
                            <i class="ri-refresh-line"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-user" data-id="${id}">
                            <i class="ri-delete-bin-5-fill"></i>
                        </button>`;
                },
            },
        ],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        dom:
            '<"row mb-3"<"col-md-3"l><"col-md-6 text-center"B><"col-md-3"f>>tip',
        buttons: [
            {
                extend: "excel",
                text: '<i class="ri-file-excel-2-line me-1"></i> Excel',
                className: "btn btn-success rounded-pill",
                exportOptions: { columns: ":visible" },
            },
            {
                extend: "csv",
                text: '<i class="ri-file-line me-1"></i> CSV',
                className: "btn btn-primary rounded-pill",
                exportOptions: { columns: ":visible" },
            },
        ],
    });

    // -----------------------------
    // 🔍 Filters
    // -----------------------------
    $("#filterTenant, #filterRole, #filterStatus").on("change", function () {
        usersTable.ajax.reload();
    });

    // -----------------------------
    // ♻️ Reset Form
    // -----------------------------
    function resetUserForm() {
        $("#userForm")[0].reset();
        $("#saveUserBtn").removeAttr("data-user-id");
        $("#userForm").removeClass("was-validated");
    }

    // -----------------------------
    // 🔄 Modal lifecycle
    // -----------------------------
    $("#userModal").on("shown.bs.modal", function () {
        if (!isEditMode) resetUserForm();
    });

    $("#userModal").on("hidden.bs.modal", function () {
        isEditMode = false;
        resetUserForm();
    });

    // -----------------------------
    // ➕ Add User
    // -----------------------------
    $("#addUserBtn").on("click", function () {
        isEditMode = false;
        $("#addUserLabel").text("Add New User");
        resetUserForm();
    });

    // -----------------------------
    // ✏️ Edit User
    // -----------------------------
    $(document).on("click", ".edit-user", function () {
        const id = $(this).data("id");
        isEditMode = true;
        $("#addUserLabel").text("Edit User");

        $.ajax({
            url: makeUrl(`super-admin/users/${id}/edit`),
            type: "GET",
            dataType: "json",
            success: (res) => {
                if (res.success) {
                    const u = res.data;
                    $("#tenant_id").val(u.tenant_id);
                    $("#employee_id").val(u.employee_id);
                    $("#role").val(u.role);
                    $("#name").val(u.name);
                    $("#phone").val(u.phone);
                    $("#email").val(u.email);
                    $("#status").val(u.status);
                    $("#saveUserBtn").attr("data-user-id", u.id);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: () => {
                showAlert("danger", "ri-error-warning-line", "Failed to load user data.");
            },
        });
    });

    // -----------------------------
    // 💾 Save User
    // -----------------------------
    $("#saveUserBtn").on("click", function (e) {
        e.preventDefault();
        const form = $("#userForm")[0];
        const button = $(this);

        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        }

        const userId = button.data("user-id");
        const formData = {
            tenant_id: $("#tenant_id").val(),
            employee_id: $("#employee_id").val(),
            role: $("#role").val(),
            name: $("#name").val(),
            phone: $("#phone").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            status: $("#status").val(),
        };

        const url = userId
            ? makeUrl(`super-admin/users/${userId}`)
            : makeUrl("super-admin/users");
        const method = userId ? "PUT" : "POST";

        $.ajax({
            url,
            type: method,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            beforeSend: () => button.prop("disabled", true),
            success: (res) => {
                if (res.success) {
                    showAlert("success", "ri-checkbox-circle-line", res.message);
                    $("#userModal").modal("hide");
                    usersTable.ajax.reload(null, false);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: (xhr) => {
                const msg = xhr.responseJSON?.message || "Failed to save user.";
                showAlert("danger", "ri-error-warning-line", msg);
            },
            complete: () => button.prop("disabled", false),
        });
    });

    // -----------------------------
    // ❌ Delete User
    // -----------------------------
    $(document).on("click", ".delete-user", function () {
        const id = $(this).data("id");
        if (!confirm("Are you sure you want to delete this user?")) return;

        $.ajax({
            url: makeUrl(`super-admin/users/${id}`),
            type: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (res) => {
                if (res.success) {
                    showAlert("success", "ri-checkbox-circle-line", res.message);
                    usersTable.ajax.reload(null, false);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: (xhr) => {
                const msg = xhr.responseJSON?.message || "Failed to delete user.";
                showAlert("danger", "ri-error-warning-line", msg);
            },
        });
    });

    // -----------------------------
    // 🔁 Toggle Status
    // -----------------------------
    $(document).on("click", ".toggle-status", function () {
        const id = $(this).data("id");
        $.ajax({
            url: makeUrl(`super-admin/users/status/${id}`),
            type: "GET",
            success: (res) => {
                if (res.success) {
                    showAlert("success", "ri-checkbox-circle-line", res.message);
                    usersTable.ajax.reload(null, false);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: () => {
                showAlert("danger", "ri-error-warning-line", "Failed to change status.");
            },
        });
    });
});
