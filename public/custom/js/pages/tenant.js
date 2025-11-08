$(document).ready(function () {
    // Helper: normalize URLs
    function makeUrl(path) {
        const base = "";
        return (base + "/" + path).replace(/\/+/, "/").replace(/\/{2,}/g, "/");
    }

    // Initialize DataTable
    $("#tenantsTable").DataTable({
        ordering: false,
        searching: true,
        paging: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
    });
    // Reset form when Add button clicked
    $("#addTenantBtn").on("click", function () {
        resetTenantForm();
        $("#addTenantLabel").text("Add New Tenant");
        $("#saveTenantBtn").removeAttr("data-tenant-id");
    });

    // Save (create or update)
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
            url: url,
            type: method,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            beforeSend: function () {
                button.prop("disabled", true);
            },
            success: function (response) {
                if (response.success) {
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        response.message
                    );
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message
                    );
                }
            },
            error: function (xhr) {
                const msg =
                    xhr.responseJSON?.message || "Failed to save tenant.";
                showAlert("danger", "ri-error-warning-line", msg);
            },
            complete: function () {
                button.prop("disabled", false);
            },
        });
    });

    // Edit Tenant
    $(document).on("click", ".edit-tenant", function () {
        const tenantId = $(this).data("id");
        $.ajax({
            url: makeUrl(`super-admin/tenants/${tenantId}/edit`),
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const t = response.data;
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
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message
                    );
                }
            },
        });
    });

    // Delete Tenant
    $(document).on("click", ".delete-tenant", function () {
        const id = $(this).data("id");
        if (!confirm("Are you sure you want to delete this tenant?")) return;

        $.ajax({
            url: makeUrl(`super-admin/tenants/${id}`),
            type: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        response.message
                    );
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message
                    );
                }
            },
        });
    });

    // Toggle Tenant Status
    $(document).on("click", ".toggle-status", function () {
        const id = $(this).data("id");
        $.ajax({
            url: makeUrl(`super-admin/tenant/status/${id}`),
            type: "GET",
            success: function (response) {
                if (response.success) {
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        response.message
                    );
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message
                    );
                }
            },
        });
    });
    // Reset form
    function resetTenantForm() {
        $("#tenantForm")[0].reset();
        $("#tenantIsolation").val("");
    }
});
