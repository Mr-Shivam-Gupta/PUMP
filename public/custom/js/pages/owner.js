$(document).ready(function () {
    function makeUrl(path) {
        return "/" + path.replace(/^\/+/, "");
    }

    let tenantChoices = null;
    let isEditMode = false;

    // Initialize DataTable
    $("#ownersTable").DataTable({
        ordering: false,
        searching: true,
        paging: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
    });

    // Initialize Choicess
    function initTenantChoices() {
        const element = document.getElementById("ownerTenants");
        if (!element) return false;

        if (tenantChoices) {
            tenantChoices.destroy();
            tenantChoices = null;
        }

        tenantChoices = new Choices(element, {
            removeItemButton: true,
            searchPlaceholderValue: "Search tenants...",
            placeholderValue: "Select tenants...",
            shouldSort: false,
            searchEnabled: true,
        });

        return true;
    }

    // Load tenant list dynamically
    function loadTenants(selectedIds = []) {
        if (!tenantChoices) return;
        $.ajax({
            url: makeUrl("super-admin/tenants/list"),
            type: "GET",
            dataType: "json",
            success: function (tenants) {
                tenantChoices.clearStore();

                const options = tenants.map((t) => ({
                    value: String(t.id),
                    label: `${t.name} (${t.domain ?? "-"})`,
                    selected: selectedIds.includes(String(t.id)),
                }));

                tenantChoices.setChoices(options, "value", "label", true);
            },
            error: function () {
                showAlert(
                    "danger",
                    "ri-error-warning-line",
                    "Failed to load tenants list."
                );
            },
        });
    }

    // Reset form
    function resetOwnerForm() {
        $("#ownerForm")[0].reset();
        if (tenantChoices) tenantChoices.clearStore();
    }

    // Modal lifecycle
    $("#ownerModal").on("shown.bs.modal", function () {
        if (!tenantChoices) initTenantChoices();
        if (!isEditMode) {
            resetOwnerForm();
            loadTenants();
        }
    });

    $("#ownerModal").on("hidden.bs.modal", function () {
        isEditMode = false;
        if (tenantChoices) {
            tenantChoices.destroy();
            tenantChoices = null;
        }
        resetOwnerForm();
    });

    // ADD OWNER
    $("#addOwnerBtn").on("click", function () {
        isEditMode = false;
        $("#addOwnerLabel").text("Add New Owner");
        $("#saveOwnerBtn").removeAttr("data-owner-id");
    });

    // EDIT OWNER
    $(document).on("click", ".edit-owner", function () {
        const id = $(this).data("id");
        isEditMode = true;
        $("#addOwnerLabel").text("Edit Owner");

        $.ajax({
            url: makeUrl(`super-admin/owners/${id}/edit`),
            type: "GET",
            dataType: "json",
            success: (res) => {
                if (res.success) {
                    const o = res.data;
                    $("#addOwnerLabel").text(`Edit Owner - ${o.owner_id}`);
                    $("#ownerId").val(o.owner_id);
                    $("#ownerPassword").val("");
                    $("#ownerStatus").val(o.status);
                    $("#saveOwnerBtn").attr("data-owner-id", o.id);

                    const selectedIds = o.own_tenant_ids
                        ? o.own_tenant_ids.map(String)
                        : [];

                    setTimeout(() => {
                        if (!tenantChoices) initTenantChoices();
                        loadTenants(selectedIds);
                    }, 250);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
        });
    });

    // SAVE OWNER
    $("#saveOwnerBtn").on("click", function (e) {
        e.preventDefault();
        const button = $(this);
        const ownerId = button.data("owner-id");
        const selectedTenants = tenantChoices
            ? tenantChoices.getValue(true)
            : [];

        const formData = {
            owner_id: $("#ownerId").val(),
            owner_password: $("#ownerPassword").val(),
            own_tenant_ids: selectedTenants,
            status: $("#ownerStatus").val(),
        };

        const url = ownerId
            ? makeUrl(`super-admin/owners/${ownerId}`)
            : makeUrl("super-admin/owners");
        const method = ownerId ? "PUT" : "POST";

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
                const response = xhr.responseJSON;

                $("#ownerForm .is-invalid").removeClass("is-invalid");
                $("#ownerForm .invalid-feedback").remove();

                if (response && response.errors) {
                    $.each(response.errors, function (key, messages) {
                        const input = $(`[name="${key}"]`);

                        if (input.length) {
                            input.addClass("is-invalid");

                            if (
                                input.hasClass("form-control") &&
                                input.attr("id") === "ownerTenants"
                            ) {
                                const choiceEl = input.closest(".choices");
                                choiceEl.after(
                                    `<div class="invalid-feedback d-block">${messages[0]}</div>`
                                );
                            } else {
                                input.after(
                                    `<div class="invalid-feedback">${messages[0]}</div>`
                                );
                            }
                        }
                    });

                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message
                    );
                } else {
                    const msg = response?.message || "Failed to save owner.";
                    showAlert("danger", "ri-error-warning-line", msg);
                }
            },
            complete: () => button.prop("disabled", false),
        });
    });

    // ✅ DELETE OWNER
    $(document).on("click", ".delete-owner", function () {
        const id = $(this).data("id");
        if (!confirm("Are you sure you want to delete this owner?")) return;

        $.ajax({
            url: makeUrl(`super-admin/owners/${id}`),
            type: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
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
                const response = xhr.responseJSON;

                $("#ownerForm .is-invalid").removeClass("is-invalid");
                $("#ownerForm .invalid-feedback").remove();

                if (response && response.errors) {
                    $.each(response.errors, function (key, messages) {
                        const input = $(`[name="${key}"]`);

                        if (input.length) {
                            input.addClass("is-invalid");

                            if (
                                input.hasClass("form-control") &&
                                input.attr("id") === "ownerTenants"
                            ) {
                                const choiceEl = input.closest(".choices");
                                choiceEl.after(
                                    `<div class="invalid-feedback d-block">${messages[0]}</div>`
                                );
                            } else {
                                input.after(
                                    `<div class="invalid-feedback">${messages[0]}</div>`
                                );
                            }
                        }
                    });

                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message
                    );
                } else {
                    const msg = response?.message || "Failed to save owner.";
                    showAlert("danger", "ri-error-warning-line", msg);
                }
            },
        });
    });

    // ✅ TOGGLE STATUS
    $(document).on("click", ".toggle-status", function () {
        const id = $(this).data("id");
        $.ajax({
            url: makeUrl(`super-admin/owners/status/${id}`),
            type: "GET",
            dataType: "json",
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
                const response = xhr.responseJSON;

                $("#ownerForm .is-invalid").removeClass("is-invalid");
                $("#ownerForm .invalid-feedback").remove();

                if (response && response.errors) {
                    $.each(response.errors, function (key, messages) {
                        const input = $(`[name="${key}"]`);

                        if (input.length) {
                            input.addClass("is-invalid");

                            if (
                                input.hasClass("form-control") &&
                                input.attr("id") === "ownerTenants"
                            ) {
                                const choiceEl = input.closest(".choices");
                                choiceEl.after(
                                    `<div class="invalid-feedback d-block">${messages[0]}</div>`
                                );
                            } else {
                                input.after(
                                    `<div class="invalid-feedback">${messages[0]}</div>`
                                );
                            }
                        }
                    });

                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message
                    );
                } else {
                    const msg = response?.message || "Failed to save owner.";
                    showAlert("danger", "ri-error-warning-line", msg);
                }
            },
        });
    });
});
