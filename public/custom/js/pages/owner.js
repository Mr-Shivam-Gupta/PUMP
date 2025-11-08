$(document).ready(function () {
    function makeUrl(path) {
        return "/" + path.replace(/^\/+/, "");
    }

    let tenantChoices = null;

    function initTenantChoices() {
        const element = document.getElementById("ownerTenants");
        if (!element) {
            console.warn("ownerTenants element not found");
            return false;
        }

        // Destroy old instance if it exists
        if (tenantChoices) {
            try {
                tenantChoices.destroy();
            } catch (e) {
                console.warn("Error destroying Choices:", e);
            }
            tenantChoices = null;
        }

        // Remove any leftover Choices wrapper elements
        const choicesWrapper = element.parentElement?.querySelector(".choices");
        if (choicesWrapper && choicesWrapper !== element) {
            choicesWrapper.remove();
        }

        // Ensure element is visible and not hidden
        if (!element.offsetParent) {
            console.warn("ownerTenants element is not visible");
            return false;
        }

        try {
            // Initialize fresh instance
            tenantChoices = new Choices(element, {
                removeItemButton: true,
                searchPlaceholderValue: "Search tenants...",
                placeholder: true,
                placeholderValue: "Select tenants...",
                shouldSort: false,
                searchEnabled: true,
            });
            return true;
        } catch (e) {
            console.error("Failed to initialize Choices:", e);
            return false;
        }
    }

    function loadTenants(selectedIds = []) {
        if (!tenantChoices) {
            console.warn("tenantChoices not initialized");
            return;
        }

        $.ajax({
            url: makeUrl("super-admin/tenants/list"),
            type: "GET",
            dataType: "json",
            success: function (tenants) {
                if (!tenantChoices) return;

                try {
                    // Clear existing choices
                    tenantChoices.clearStore();

                    // Populate tenant options
                    const choices = tenants.map((t) => ({
                        value: String(t.id),
                        label: `${t.name} (${t.domain ?? "-"})`,
                        selected: selectedIds.includes(String(t.id)),
                    }));

                    tenantChoices.setChoices(choices, "value", "label", true);
                } catch (e) {
                    console.error("Error loading tenants:", e);
                }
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

    function resetOwnerForm() {
        $("#ownerForm")[0].reset();
        if (tenantChoices) {
            try {
                tenantChoices.clearStore();
            } catch (e) {
                console.warn("Error clearing choices:", e);
            }
        }
    }

    // ✅ Modal lifecycle - wait for modal to be fully shown
    $("#ownerModal").on("shown.bs.modal", function () {
        // Small delay to ensure DOM is fully ready
        setTimeout(() => {
            const initialized = initTenantChoices();
            if (initialized) {
                resetOwnerForm();
                loadTenants();
            }
        }, 100);
    });

    $("#ownerModal").on("hidden.bs.modal", function () {
        // Destroy instance to prevent duplicates
        if (tenantChoices) {
            try {
                tenantChoices.destroy();
            } catch (e) {
                console.warn("Error destroying Choices on modal hide:", e);
            }
            tenantChoices = null;
        }
    });

    // ✅ Add Owner
    $("#addOwnerBtn").on("click", function () {
        $("#addOwnerLabel").text("Add New Owner");
        $("#saveOwnerBtn").removeAttr("data-owner-id");
        const modal = new bootstrap.Modal(
            document.getElementById("ownerModal")
        );
        modal.show();
    });

    // ✅ Edit Owner
    $(document).on("click", ".edit-owner", function () {
        const id = $(this).data("id");
        const modal = new bootstrap.Modal(
            document.getElementById("ownerModal")
        );
        modal.show();

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

                    // Wait for Choices to be initialized
                    setTimeout(() => {
                        if (tenantChoices) {
                            loadTenants(selectedIds);
                        }
                    }, 300);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
        });
    });

    // ✅ Save Owner
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
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert("danger", "ri-error-warning-line", res.message);
                }
            },
            error: (xhr) => {
                const msg =
                    xhr.responseJSON?.message || "Failed to save owner.";
                showAlert("danger", "ri-error-warning-line", msg);
            },
            complete: () => button.prop("disabled", false),
        });
    });
});
