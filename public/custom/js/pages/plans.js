$(document).ready(function () {
    // Helper to build consistent super-admin URLs and normalize multiple slashes
    function makeUrl(path) {
        const base = "";
        return (base + "/" + path).replace(/\/+/, "/").replace(/\/{2,}/g, "/");
    }
    // Initialize DataTable for plans
    $("#plansTable").DataTable({
        ordering: false,
        searching: true,
        paging: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
    });

    // Reset form when adding a new plan
    $("#addPlanBtn").on("click", function () {
        resetPlanForm();
        $("#addPlanLabel").text("Add New Plan");
        $("#savePlanBtn").removeAttr("data-plan-id");
    });

    // Save (create/update) plan
    $("#savePlanBtn").on("click", function (event) {
        event.preventDefault();
        const button = event.currentTarget;

        const formData = {
            name: $("#planName").val(),
            duration_days: $("#durationDays").val(),
            isolation: $("#isolation").val(),
            max_users: $("#maxUsers").val(),
            price: $("#price").val(),
            id: $(button).data("plan-id") || null,
        };

        const requestType = formData.id ? "PUT" : "POST";
        const path = formData.id ? `plans/${formData.id}` : "plans";
        const url = makeUrl(path);

        $.ajax({
            url: url,
            type: requestType,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            dataType: "json",
            beforeSend: function () {
                startLoader({ currentTarget: button });
            },
            success: function (response) {
                if (response.success) {
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        response.message || "Plan saved successfully!"
                    );
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message || "Failed to save plan."
                    );
                }
            },
            error: function (xhr) {
                let errorMsg = "Failed to save plan.";
                if (xhr.responseJSON?.message)
                    errorMsg = xhr.responseJSON.message;
                showAlert("danger", "ri-error-warning-line", errorMsg);
            },
            complete: function () {
                endLoader({ currentTarget: button });
            },
        });
    });

    // Edit plan
    $(document).on("click", ".edit-plan", function (event) {
        event.preventDefault();
        const planId = $(this).data("id");
        const button = event.currentTarget;
        const path = `plans/${planId}/edit`;
        const url = makeUrl(path);

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                startLoader({ currentTarget: button });
            },
            success: function (response) {
                if (response.success) {
                    const plan = response.data;
                    $("#addPlanLabel").text(`Edit Plan - ${plan.name}`);
                    $("#planName").val(plan.name);
                    $("#durationDays").val(plan.duration_days);
                    $("#isolation").val(plan.isolation);
                    $("#maxUsers").val(plan.max_users);
                    $("#price").val(plan.price);
                    $("#savePlanBtn").attr("data-plan-id", plan.id);
                } else {
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message || "Error fetching plan."
                    );
                }
            },
            error: function (xhr) {
                const errorMsg =
                    xhr.responseJSON?.message || "Failed to fetch plan.";
                showAlert("danger", "ri-error-warning-line", errorMsg);
            },
            complete: function () {
                endLoader({ currentTarget: button });
            },
        });
    });

    // Delete plan
    $(document).on("click", ".delete-plan", function (event) {
        event.preventDefault();
        const planId = $(this).data("id");
        if (!confirm("Are you sure you want to delete this plan?")) return;

        $.ajax({
            url: makeUrl(`plans/${planId}`),
            type: "DELETE",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        response.message || "Plan deleted successfully!"
                    );
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message || "Failed to delete plan."
                    );
                }
            },
            error: function (xhr) {
                const errorMsg =
                    xhr.responseJSON?.message || "Failed to delete plan.";
                showAlert("danger", "ri-error-warning-line", errorMsg);
            },
        });
    });

    // Helper to reset form
    function resetPlanForm() {
        $("#planForm")[0].reset();
        $("#isolation").val("");
    }
});
