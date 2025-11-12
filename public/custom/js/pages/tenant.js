$(document).ready(function () {
    // ------------------------------
    // ✅ Helper: Normalize URL
    // ------------------------------
    function makeUrl(path) {
        return "/" + path.replace(/^\/+/, "");
    }

    // ------------------------------
    // ✅ Helper: Show validation errors
    // ------------------------------
    function showValidationErrors(formSelector, errors) {
        const form = $(formSelector);
        form.find(".is-invalid").removeClass("is-invalid");
        form.find(".invalid-feedback").remove();

        $.each(errors, function (key, messages) {
            const input = form.find(`[name="${key}"]`);
            if (input.length) {
                input.addClass("is-invalid");
                input.after(
                    `<div class="invalid-feedback">${messages[0]}</div>`
                );
            }
        });
    }

    // ------------------------------
    // ✅ DataTable Init
    // ------------------------------
    $("#tenantsTable").DataTable({
        ordering: false,
        searching: true,
        paging: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
    });

    // ------------------------------
    // ✅ Reset Form
    // ------------------------------
    function resetTenantForm() {
        $("#tenantForm")[0].reset();
        $("#tenantForm .is-invalid").removeClass("is-invalid");
        $("#tenantForm .invalid-feedback").remove();
        $("#tenantIsolation").val("");
    }

    // ------------------------------
    // ✅ Add Tenant (Open Modal)
    // ------------------------------
    $("#addTenantBtn").on("click", function () {
        resetTenantForm();
        $("#addTenantLabel").text("Add New Tenant");
        $("#saveTenantBtn").removeAttr("data-tenant-id");
    });

    // ------------------------------
    // ✅ Save Tenant (Create or Update)
    // ------------------------------
    $("#saveTenantBtn").on("click", function (e) {
        e.preventDefault();
        const button = $(this);
        const tenantId = button.data("tenant-id");

        const formData = {
            name: $("#tenantName").val(),
            email: $("#tenantEmail").val(),
            contact: $("#tenantContact").val(),
            domain: $("#tenantDomain").val(),
            isolation: $("#tenantIsolation").val(),
            plan: $("#tenantPlan").val(),
            password: $("#tenantPassword").val(),
            address: $("#tenantAddress").val(),
            gst_number: $("#tenantGst").val(),
            license_number: $("#tenantLicense").val(),
        };

        const url = tenantId
            ? makeUrl(`super-admin/tenants/${tenantId}`)
            : makeUrl("super-admin/tenants");
        const method = tenantId ? "PUT" : "POST";

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
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        res.message
                    );
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: (xhr) => {
                const res = xhr.responseJSON;
                if (res?.errors) {
                    showValidationErrors("#tenantForm", res.errors);
                    showAlert("danger", "ri-error-warning-line", res.message);
                } else {
                    const msg = res?.message || "Failed to save tenant.";
                    showAlert("danger", "ri-error-warning-line", msg);
                }
            },
            complete: () => button.prop("disabled", false),
        });
    });

    // ------------------------------
    // ✅ Edit Tenant
    // ------------------------------
    $(document).on("click", ".edit-tenant", function () {
        const id = $(this).data("id");
        resetTenantForm();

        $.ajax({
            url: makeUrl(`super-admin/tenants/${id}/edit`),
            type: "GET",
            dataType: "json",
            success: (res) => {
                if (res.success) {
                    const t = res.data;
                    $("#addTenantLabel").text(`Edit Tenant - ${t.name}`);
                    $("#tenantName").val(t.name);
                    $("#tenantEmail").val(t.email);
                    $("#tenantContact").val(t.contact);
                    $("#tenantDomain").val(t.domain);
                    $("#tenantIsolation").val(t.isolation);
                    $("#tenantPlan").val(t.plan);
                    $("#tenantAddress").val(t.address);
                    $("#tenantGst").val(t.gst_number);
                    $("#tenantLicense").val(t.license_number);
                    $("#saveTenantBtn").attr("data-tenant-id", t.id);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: (xhr) => {
                const res = xhr.responseJSON;
                const msg = res?.message || "Failed to fetch tenant details.";
                showAlert("danger", "ri-error-warning-line", msg);
            },
        });
    });

    // ------------------------------
    // ✅ Delete Tenant
    // ------------------------------
    $(document).on("click", ".delete-tenant", function () {
        const id = $(this).data("id");
        if (!confirm("Are you sure you want to delete this tenant?")) return;

        $.ajax({
            url: makeUrl(`super-admin/tenants/${id}`),
            type: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (res) => {
                if (res.success) {
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        res.message
                    );
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: (xhr) => {
                const res = xhr.responseJSON;
                const msg = res?.message || "Failed to delete tenant.";
                showAlert("danger", "ri-error-warning-line", msg);
            },
        });
    });

    // ------------------------------
    // ✅ Toggle Tenant Status
    // ------------------------------
    $(document).on("click", ".toggle-status", function () {
        const id = $(this).data("id");

        $.ajax({
            url: makeUrl(`super-admin/tenants/status/${id}`),
            type: "GET",
            success: (res) => {
                if (res.success) {
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        res.message
                    );
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: (xhr) => {
                const res = xhr.responseJSON;
                const msg = res?.message || "Failed to change status.";
                showAlert("danger", "ri-error-warning-line", msg);
            },
        });
    });
});
