$(document).ready(function () {
    $("#permissionGroupsMasterTable").DataTable({
        ordering: false,
        searching: true,
        paging: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
    });

    $("#savePermissionGroupBtn").click(function (event) {
        event.preventDefault();
        const button = event.currentTarget;
        const formData = {
            group_name: document.getElementById("group_name").value,
            category: document.getElementById("category").value,
            is_active: document.getElementById("is_active").checked ? 1 : 0,
            id: $(button).data("group-id") || null,
        };

        const requestType = formData.id ? "PUT" : "POST";
        const url = formData.id ? `permission-groups/${formData.id}` : "permission-groups";

        $.ajax({
            url: url,
            type: requestType,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            dataType: "json",
            beforeSend: function () {
                startLoader({
                    currentTarget: button,
                });
            },
            success: function (response) {
                if (response.success) {
                    showAlert(
                        "success",
                        "ri-checkbox-circle-line",
                        response.message || "Permission group saved successfully!"
                    );
                    setTimeout(() => {
                        window.location.href = window.location.href;
                    }, 2000);
                } else {
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message || "An error occurred while saving."
                    );
                }
            },
            error: function (xhr, status, error) {
                let errorMsg = "Failed to save permission group.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showAlert("danger", "ri-error-warning-line", errorMsg);
            },
            complete: function () {
                endLoader({
                    currentTarget: button,
                });
            },
        });
    });

    $(".delete-permission-group").click(function (event) {
        event.preventDefault();
        const groupId = $(this).data("id");
        const confirmation = confirm(
            "Are you sure you want to delete this permission group?"
        );
        if (confirmation) {
            $.ajax({
                url: "permission-groups/" + groupId,
                type: "DELETE",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        showAlert(
                            "success",
                            "ri-checkbox-circle-line",
                            response.message || "Permission group deleted successfully!"
                        );
                        setTimeout(() => {
                            window.location.href = window.location.href;
                        }, 2000);
                    } else {
                        showAlert(
                            "danger",
                            "ri-error-warning-line",
                            response.message ||
                                "An error occurred while deleting."
                        );
                    }
                },
                error: function (xhr, status, error) {
                    let errorMsg = "Failed to delete permission group.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showAlert("danger", "ri-error-warning-line", errorMsg);
                },
            });
        }
    });

    $(".edit-permission-group").click(function (event) {
        event.preventDefault();
        const groupId = $(this).data("id");
        const button = event.currentTarget;

        $.ajax({
            url: "permission-groups/" + groupId + "/edit",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                startLoader({
                    currentTarget: button,
                });
            },
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const group = response.data;
                    $("#addPermissionGroupLabel").text("Edit Permission Group - " + group.name);
                    $("#group_name").val(group.name);
                    $("#category").val(group.category);
                    $("#is_active").prop("checked", group.status);
                    $("#savePermissionGroupBtn").attr("data-group-id", group.id);
                } else {
                    showAlert(
                        "danger",
                        "ri-error-warning-line",
                        response.message || "Error fetching permission group."
                    );
                }
            },
            error: function (xhr) {
                const errorMsg =
                    xhr.responseJSON?.message || "Failed to fetch permission group.";
                showAlert("danger", "ri-error-warning-line", errorMsg);
            },
            complete: function () {
                endLoader({
                    currentTarget: button,
                });
            },
        });
    });

    $(document).on("click", "#addPermissionGroupBtn", function (event) {
        event.preventDefault();
        $("#addPermissionGroupLabel").text("Add New Permission Group");
        $("#group_name").val("");
        $("#category").val("Uncategorized");
        $("#is_active").prop("checked", true);
        $("#savePermissionGroupBtn").removeAttr("data-group-id");
    });
});