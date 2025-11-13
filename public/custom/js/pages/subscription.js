$(document).ready(function () {
    function makeUrl(path) {
        const base = "";
        return (base + "/" + path).replace(/\/+/, "/").replace(/\/{2,}/g, "/");
    }

    // Initialize DataTable with AJAX
    const table = $("#subscriptionTable").DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        searching: true,
        paging: true,
        info: true,
        ajax: {
            url: makeUrl("subscription/list"),
            data: function (d) {
                d.search = d.search.value;
            },
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: "tenant",
                defaultContent: "-",
                render: function (data, type, row) {
                    if (data && data.name) {
                        return data.name + (data.email ? `<div class="text-muted small">${data.email}</div>` : "");
                    }
                    return "-";
                },
            },
            {
                data: "plan",
                defaultContent: "-",
                render: function (data) {
                    return data && data.name ? data.name : "-";
                },
            },
            {
                data: "is_trial",
                render: function (data) {
                    if (data === 1) {
                        return `<span class="badge bg-success-subtle text-success badge-border">Yes</span>`;
                    }
                    return `<span class="badge bg-danger-subtle text-danger badge-border">No</span>`;
                },
            },
            { data: "start_date", defaultContent: "-" },
            { data: "end_date", defaultContent: "-" },
            {
                data: "subscription_status",
                render: function (data) {
                    if (data === 1) {
                        return `<span class="badge bg-success-subtle text-success badge-border">Active</span>`;
                    }
                    return `<span class="badge bg-danger-subtle text-danger badge-border">Inactive</span>`;
                },
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary edit-subscription" data-id="${row.id}">
                            <i class="ri-edit-2-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-subscription" data-id="${row.id}">
                            <i class="ri-delete-bin-5-fill"></i>
                        </button>
                    `;
                },
            },
        ],
    });

    // Reset form for adding new
    $("#addSubscriptionBtn").on("click", function () {
        $("#subscriptionForm")[0].reset();
        $("#saveSubscriptionBtn").removeAttr("data-id");
        $("#addSubscriptionLabel").text("Add New Subscription");
    });

    // Save Subscription
    $("#subscriptionForm").on("submit", function (e) {
        e.preventDefault();
        const button = $("#saveSubscriptionBtn");
        const id = button.data("id");

        const formData = {
            tenant_id: $("#tenant_id").val(),
            plan: $("#plan").val(),
            start_date: $("#start_date").val(),
            end_date: $("#end_date").val(),
        };

        const url = id
            ? makeUrl(`subscription/${id}`)
            : makeUrl("subscription");
        const method = id ? "PUT" : "POST";

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
                    $("#subscriptionModal").modal("hide");
                    table.ajax.reload();
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
                    xhr.responseJSON?.message || "Failed to save subscription.";
                showAlert("danger", "ri-error-warning-line", msg);
            },
            complete: function () {
                button.prop("disabled", false);
            },
        });
    });

    // Edit Subscription
    $(document).on("click", ".edit-subscription", function () {
        const id = $(this).data("id");
        $.ajax({
            url: makeUrl(`subscription/${id}/edit`),
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const s = response.data;
                    $("#tenant_id").val(s.tenant_id);
                    $("#plan").val(s.plan);
                    $("#start_date").val(s.start_date);
                    $("#end_date").val(s.end_date);
                    $("#saveSubscriptionBtn").attr("data-id", s.id);
                    $("#addSubscriptionLabel").text("Edit Subscription");
                    $("#subscriptionModal").modal("show");
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

    // Delete Subscription
    $(document).on("click", ".delete-subscription", function () {
        const id = $(this).data("id");
        if (!confirm("Are you sure you want to delete this subscription?"))
            return;

        $.ajax({
            url: makeUrl(`subscription/${id}`),
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
                    table.ajax.reload();
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
});
