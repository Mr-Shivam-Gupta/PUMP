$(document).ready(function () {
    // Function to get query parameter
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Valid main tabs
    const validTabs = ["general", "appearance", "notifications"];
    // Valid sub-tabs for general settings
    const validSubTabs = ["general-sub", "seo-sub", "social-sub", "mail-sub", "system-sub"];

    // Initialize Select2 for dropdowns
    $("#time_zone, #default_language, #maintenance_mode, #enable_registration, #currency, #date_format").select2({
        width: '100%'
    });

    // Get active main tab from URL or default to 'general'
    let activeTab = getQueryParam("tab") || "general";
    if (!validTabs.includes(activeTab)) {
        const newUrl = `${window.location.pathname}?tab=general`;
        window.history.replaceState({ tab: "general" }, "", newUrl);
        activeTab = "general";
    }

    // Get active sub-tab from URL or default to 'general-sub'
    let activeSubTab = getQueryParam("subtab") || "general-sub";
    if (!validSubTabs.includes(activeSubTab)) {
        activeSubTab = "general-sub";
    }

    // Set initial active main tab
    $(".nav-link").removeClass("active");
    $(".tab-pane").removeClass("show active");
    $(`#${activeTab}-tab`).addClass("active");
    $(`#${activeTab}`).addClass("show active");

    // Set initial active sub-tab for general tab
    if (activeTab === "general") {
        $(".nav-link", "#generalSettingsTabs").removeClass("active");
        $(".tab-pane", "#generalSettingsTabsContent").removeClass("show active");
        $(`#${activeSubTab}-tab`).addClass("active");
        $(`#${activeSubTab}`).addClass("show active");
    }

    // Handle main tab click and update URL
    $(".nav-link").on("click", function (e) {
        e.preventDefault();
        const tabId = $(this).attr("href")?.split("#")[1];
        console.log(tabId);
        console.log(validTabs);
        if (tabId && validTabs.includes(tabId)) {
            const newUrl = `${window.location.pathname}?tab=${tabId}${tabId === "general" ? "&subtab=general-sub" : ""}`;
            window.history.pushState({ tab: tabId, subtab: tabId === "general" ? "general-sub" : null }, "", newUrl);
            $(".nav-link").removeClass("active");
            $(".tab-pane").removeClass("show active");
            $(this).addClass("active");
            $(`#${tabId}`).addClass("show active");
            if (tabId === "general") {
                $(".nav-link", "#generalSettingsTabs").removeClass("active");
                $(".tab-pane", "#generalSettingsTabsContent").removeClass("show active");
                $("#general-sub-tab").addClass("active");
                $("#general-sub").addClass("show active");
            }
        } else {
            var redirectUrl = $(this).attr("href");
            window.location.href = redirectUrl;
        }
    });

    // Handle sub-tab click and update URL
    $("#generalSettingsTabs .nav-link").on("click", function (e) {
        e.preventDefault();
        const subTabId = $(this).attr("href")?.split("#")[1];
        if (subTabId && validSubTabs.includes(subTabId)) {
            const newUrl = `${window.location.pathname}?tab=general&subtab=${subTabId}`;
            window.history.pushState({ tab: "general", subtab: subTabId }, "", newUrl);
            $(".nav-link", "#generalSettingsTabs").removeClass("active");
            $(".tab-pane", "#generalSettingsTabsContent").removeClass("show active");
            $(this).addClass("active");
            $(`#${subTabId}`).addClass("show active");
        }
    });

    // Handle browser back/forward navigation
    window.onpopstate = function () {
        let tabId = getQueryParam("tab") || "general";
        let subTabId = getQueryParam("subtab") || "general-sub";
        if (!validTabs.includes(tabId)) {
            tabId = "general";
            subTabId = "general-sub";
            const newUrl = `${window.location.pathname}?tab=general&subtab=general-sub`;
            window.history.replaceState({ tab: "general", subtab: "general-sub" }, "", newUrl);
        }
        if (tabId === "general" && !validSubTabs.includes(subTabId)) {
            subTabId = "general-sub";
            const newUrl = `${window.location.pathname}?tab=general&subtab=general-sub`;
            window.history.replaceState({ tab: "general", subtab: "general-sub" }, "", newUrl);
        }
        $(".nav-link").removeClass("active");
        $(".tab-pane").removeClass("show active");
        $(`#${tabId}-tab`).addClass("active");
        $(`#${tabId}`).addClass("show active");
        if (tabId === "general") {
            $(".nav-link", "#generalSettingsTabs").removeClass("active");
            $(".tab-pane", "#generalSettingsTabsContent").removeClass("show active");
            $(`#${subTabId}-tab`).addClass("active");
            $(`#${subTabId}`).addClass("show active");
        }
    };

    // Load general settings on general tab click
    $("#general-tab").on("click", function () {
        $.ajax({
            url: "/settings/general",
            type: "GET",
            success: function (res) {
                if (!res.success || !res.data) {
                    showAlert("danger", "ri-error-warning-line", "Error loading settings.");
                    return;
                }
                const s = res.data;

                // Populate fields
                for (let key in s) {
                    const $element = $(`#${key}`);
                    if ($element.length) {
                        if ($element.is("select")) {
                            $element.val(s[key]).trigger("change");
                        } else {
                            $element.val(s[key]);
                        }
                    }
                }

                // Logo preview
                if (s.logo_path) {
                    const logoUrl = `/storage/${s.logo_path}`;
                    $("#logoPreview").attr("src", logoUrl).show();
                    $("#viewLogoLink").attr("href", logoUrl).show();
                    $("#downloadLogoLink").attr("href", logoUrl).show();
                    $("#deleteLogoBtn").show();
                } else {
                    $("#logoPreview").hide().attr("src", "");
                    $("#viewLogoLink, #downloadLogoLink, #deleteLogoBtn").hide();
                }
            },
            error: function () {
                showAlert("danger", "ri-error-warning-line", "Error loading settings.");
            },
        });
    });

    // Trigger general tab load if active
    if (activeTab === "general") {
        $("#general-tab").trigger("click");
    }

    // Handle general settings form submission
    $("#generalSettingsForm").on("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: "/settings/general",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                showAlert("success", "ri-checkbox-circle-line", res.message || "Settings saved successfully!");
                $("#general-tab").trigger("click"); // Refresh settings
            },
            error: function (xhr) {
                showAlert("danger", "ri-error-warning-line", xhr.responseJSON?.message || "Error saving settings.");
            },
        });
    });

    // Handle logo preview on file input change
    $("#logo").on("change", function () {
        const input = this;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#logoPreview").attr("src", e.target.result).show();
                $("#viewLogoLink, #downloadLogoLink, #deleteLogoBtn").hide();
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    // View logo
    $("#viewLogoLink").on("click", function (e) {
        e.preventDefault();
        const src = $("#logoPreview").attr("src");
        if (src) window.open(src, "_blank");
    });

    // Download logo
    $("#downloadLogoLink").on("click", function (e) {
        e.preventDefault();
        const link = $(this).attr("href");
        window.open(link, "_blank");
    });

    // Delete logo
    $("#deleteLogoBtn").on("click", function (e) {
        e.preventDefault();
        if (confirm("Are you sure you want to delete the logo?")) {
            $.ajax({
                url: "/settings/general/logo",
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (res) {
                    showAlert("success", "ri-checkbox-circle-line", res.message || "Logo deleted successfully!");
                    $("#logoPreview").hide().attr("src", "");
                    $("#viewLogoLink, #downloadLogoLink, #deleteLogoBtn").hide();
                },
                error: function (xhr) {
                    showAlert("danger", "ri-error-warning-line", xhr.responseJSON?.message || "Error deleting logo.");
                },
            });
        }
    });

    // Theme settings (unchanged)
    $("#save-theme-settings").on("click", function () {
        let settings = {
            layout: $('input[name="data-layout"]:checked').val() || "vertical",
            sidebar_user_profile: $("#sidebarUserProfile").is(":checked"),
            theme: $('input[name="data-theme"]:checked').val() || "default",
            color_scheme: $('input[name="data-bs-theme"]:checked').val() || "light",
            sidebar_visibility: $('input[name="data-sidebar-visibility"]:checked').val() || "show",
            layout_width: $('input[name="data-layout-width"]:checked').val() || "fluid",
            layout_position: $('input[name="data-layout-position"]:checked').val() || "fixed",
            topbar_color: $('input[name="data-topbar"]:checked').val() || "light",
            sidebar_size: $('input[name="data-sidebar-size"]:checked').val() || "lg",
            sidebar_view: $('input[name="data-layout-style"]:checked').val() || "default",
            sidebar_color: $('input[name="data-sidebar"]:checked').val() || "light",
            sidebar_image: $('input[name="data-sidebar-image"]:checked').val() || "none",
            primary_color: $('input[name="data-theme-colors"]:checked').val() || "default",
            preloader: $('input[name="data-preloader"]:checked').val() || "disable",
            body_image: $('input[name="data-body-image"]:checked').val() || "none",
        };

        $.ajax({
            url: "/settings/theme",
            type: "POST",
            data: JSON.stringify(settings),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                showAlert("success", "ri-checkbox-circle-line", res.message || "Theme settings saved successfully!");
            },
            error: function (xhr) {
                showAlert("danger", "ri-error-warning-line", xhr.responseJSON?.message || "Error saving theme settings.");
            },
        });
    });
});