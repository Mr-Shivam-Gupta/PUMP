$(document).ready(function () {
    let uploadedFiles = [];
    let currentFileIndex = 0;
    let currentZoom = 1;
    let currentRotation = 0;

    $("#claimDetailModal").on("shown.bs.modal", function () {
        loadDefaultFiles();
    });

    function loadDefaultFiles() {
        uploadedFiles = [];
        currentFileIndex = 0;
        displayThumbnails();
        if (uploadedFiles.length > 0) {
            showFile(0);
        } else {
            $("#mainViewer").html(
                '<div class="text-center">No files available.</div>'
            );
        }
    }

    function displayThumbnails() {
        const thumbnailList = $("#thumbnailList");
        thumbnailList.empty();

        if (uploadedFiles.length === 0) {
            thumbnailList.append(
                '<div class="text-center">No files to display.</div>'
            );
            return;
        }

        const shortCodeCounts = {};

        uploadedFiles.forEach((file) => {
            const shortCode = file.short_code || "Document";
            shortCodeCounts[shortCode] = (shortCodeCounts[shortCode] || 0) + 1;
        });

        const shortCodeIndices = {};
        uploadedFiles.forEach((file, index) => {
            let displayName = file.short_code || "Document";
            if (shortCodeCounts[displayName] > 1) {
                shortCodeIndices[displayName] =
                    (shortCodeIndices[displayName] || 0) + 1;
                displayName = `${displayName} (${shortCodeIndices[displayName]})`;
            }

            const thumbnail = $("<div>").addClass("thumbnail-container");

            if (file.type.startsWith("image/")) {
                const img = $("<img>")
                    .addClass("thumbnail-item")
                    .attr("data-index", index)
                    .attr("src", file.url)
                    .attr("alt", file.name);

                thumbnail.append(img);
                thumbnail.append(
                    $("<div>").addClass("thumbnail-label").text(displayName)
                );
            } else if (file.type === "application/pdf") {
                const pdfThumb = $("<div>")
                    .addClass("thumbnail-item pdf-thumbnail")
                    .attr("data-index", index)
                    .html('<i class="ri-file-pdf-line"></i>');

                thumbnail.append(pdfThumb);
                thumbnail.append(
                    $("<div>").addClass("thumbnail-label").text(displayName)
                );
            }

            thumbnailList.append(thumbnail);
        });

        if (uploadedFiles.length > 0) {
            thumbnailList.find('[data-index="0"]').addClass("active");
        }
    }

    $(document).on("click", ".thumbnail-item", function () {
        const index = parseInt($(this).attr("data-index"));
        showFile(index);
        updateActiveState(index);
    });

    function updateActiveState(index) {
        $(".thumbnail-item").removeClass("active");
        $(`.thumbnail-item[data-index="${index}"]`).addClass("active");
        currentFileIndex = index;
        updateNavigationButtons();
    }

    function showFile(index) {
        if (index >= uploadedFiles.length || index < 0) return;

        const file = uploadedFiles[index];
        const mainViewer = $("#mainViewer");

        mainViewer.html(`
                  <div class="loading-spinner">
                      <div class="spinner-border" role="status">
                          <span class="visually-hidden">Loading...</span>
                      </div>
                  </div>
              `);

        updateFileInfo(file, index);

        currentZoom = 1;
        currentRotation = 0;

        if (file.type.startsWith("image/")) {
            loadImage(file, mainViewer);
        } else if (file.type === "application/pdf") {
            loadPDF(file, mainViewer);
        }
    }

    function loadImage(file, container) {
        container.html(`
                  <div class="image-container" id="imageContainer">
                      <img src="${file.url}" id="mainImage" alt="${file.name}">
                  </div>
                  <div class="viewer-controls">
                      <button class="control-btn" id="zoomOut" title="Zoom Out">
                          <i class="ri-zoom-out-line"></i>
                      </button>
                      <div class="zoom-display" id="zoomDisplay">100%</div>
                      <button class="control-btn" id="zoomIn" title="Zoom In">
                          <i class="ri-zoom-in-line"></i>
                      </button>
                      <button class="control-btn" id="zoomReset" title="Reset Zoom">
                          <i class="ri-focus-line"></i>
                      </button>
                      <div class="control-divider"></div>
                      <button class="control-btn" id="rotateLeft" title="Rotate Left">
                          <i class="ri-anticlockwise-line"></i>
                      </button>
                      <button class="control-btn" id="rotateRight" title="Rotate Right">
                          <i class="ri-clockwise-line"></i>
                      </button>
                      <div class="control-divider"></div>
                      <button class="control-btn" id="downloadImage" title="Download">
                          <i class="ri-download-line"></i>
                      </button>
                      <button class="control-btn" id="openImage" title="Open in New Tab">
                          <i class="ri-external-link-line"></i>
                      </button>
                  </div>
              `);

        updateImageTransform();

        $("#imageContainer").on("wheel", handleMouseWheel);
    }

    function loadPDF(file, container) {
        container.html(`
                  <div style="width:100%;height:100%;padding:0px;">
                      <iframe src="${file.url}" border="0" class="pdf-viewer"></iframe>
                  </div>
                  <div class="viewer-controls">
                      <button class="control-btn" id="downloadPDF" title="Download">
                          <i class="ri-download-line"></i>
                      </button>
                      <button class="control-btn" id="openPDF" title="Open in New Tab">
                          <i class="ri-external-link-line"></i>
                      </button>
                  </div>
              `);
    }

    function updateImageTransform() {
        const img = $("#mainImage");
        img.css({
            transform: `scale(${currentZoom}) rotate(${currentRotation}deg)`,
            transformOrigin: "center center",
        });
        $("#zoomDisplay").text(`${Math.round(currentZoom * 100)}%`);
    }

    function handleMouseWheel(event) {
        event.preventDefault();
        const delta = event.originalEvent.deltaY;
        if (delta > 0) {
            currentZoom = Math.max(currentZoom / 1.1, 0.5);
        } else {
            currentZoom = Math.min(currentZoom * 1.1, 5);
        }
        updateImageTransform();
    }

    $(document).on("click", "#zoomIn", function () {
        currentZoom = Math.min(currentZoom * 1.2, 5);
        updateImageTransform();
    });

    $(document).on("click", "#zoomOut", function () {
        currentZoom = Math.max(currentZoom / 1.2, 0.5);
        updateImageTransform();
    });

    $(document).on("click", "#zoomReset", function () {
        currentZoom = 1;
        currentRotation = 0;
        updateImageTransform();
    });

    $(document).on("click", "#rotateLeft", function () {
        currentRotation -= 90;
        updateImageTransform();
    });

    $(document).on("click", "#rotateRight", function () {
        currentRotation += 90;
        updateImageTransform();
    });

    $(document).on("click", "#downloadImage, #downloadPDF", function () {
        const file = uploadedFiles[currentFileIndex];
        const link = document.createElement("a");
        link.href = file.url;
        link.download = file.name;
        link.target = "_blank";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    $(document).on("click", "#openImage, #openPDF", function () {
        const file = uploadedFiles[currentFileIndex];
        window.open(file.url, "_blank");
    });

    $("#prevBtn").on("click", function () {
        if (currentFileIndex > 0) {
            showFile(currentFileIndex - 1);
            updateActiveState(currentFileIndex - 1);
        }
    });

    $("#nextBtn").on("click", function () {
        if (currentFileIndex < uploadedFiles.length - 1) {
            showFile(currentFileIndex + 1);
            updateActiveState(currentFileIndex + 1);
        }
    });

    function updateNavigationButtons() {
        $("#prevBtn").prop("disabled", currentFileIndex === 0);
        $("#nextBtn").prop(
            "disabled",
            currentFileIndex === uploadedFiles.length - 1
        );
    }

    function updateFileInfo(file, index) {
        $("#fileName").text(file.name);
        $("#fileType").text(file.type);
        $("#fileIndex").text(`${index + 1} of ${uploadedFiles.length}`);
    }

    $(document).on("keydown", function (e) {
        if ($("#claimDetailModal").hasClass("show")) {
            if (e.key === "ArrowLeft") {
                $("#prevBtn").click();
            } else if (e.key === "ArrowRight") {
                $("#nextBtn").click();
            } else if (e.key === "+" || e.key === "=") {
                $("#zoomIn").click();
            } else if (e.key === "-") {
                $("#zoomOut").click();
            } else if (e.key === "0") {
                $("#zoomReset").click();
            }
        }
    });

    $(document).on("click", ".upload-icon", function () {
        $(this).siblings(".file-input").trigger("click");
    });

    $(document).on("change", ".file-input", function () {
        const input = this;
        const fileListDiv = $(this).siblings(".file-list");

        const existingFiles = new Set(
            fileListDiv
                .find(".file-name")
                .map(function () {
                    return $(this).data("file");
                })
                .get()
        );

        Array.from(input.files).forEach((file) => {
            if (existingFiles.has(file.name)) {
                showAlert(
                    "danger",
                    "ri-error-warning-line",
                    `File "${file.name}" is already selected.`
                );
                return;
            }

            const fileDiv = $("<div></div>");
            const fileNameLink = $('<span class="file-name"></span>')
                .text(file.name)
                .attr("data-preloaded", 0)
                .attr("data-file", file.name)
                .css("cursor", "pointer")
                .on("click", function () {
                    const fileURL = URL.createObjectURL(file);
                    window.open(fileURL, "_blank");
                });

            const removeBtn = $(
                '<i class="ri-delete-bin-line remove-file" title="Remove file"></i>'
            );
            removeBtn.on("click", function () {
                removeFile(input, file.name);
                fileDiv.remove();
            });

            fileDiv.append(fileNameLink, removeBtn);
            fileListDiv.append(fileDiv);
        });
    });

    $(document).on("click", ".remove-file", function () {
        const parentDiv = $(this).parent();
        const fileName = parentDiv.find(".file-name").data("file");
        const isPreloaded = parentDiv.find(".file-name").data("preloaded") == 1;
        const input = $(this)
            .closest(".file-upload-wrapper")
            .find(".file-input")[0];

        if (!confirm(`Are you sure you want to remove "${fileName}"?`)) {
            return;
        }

        if (isPreloaded) {
            parentDiv.attr("data-remove", "1").hide();
        } else {
            removeFile(input, fileName);
            parentDiv.remove();
        }
    });

    function removeFile(input, fileName) {
        const dt = new DataTransfer();
        Array.from(input.files).forEach((file) => {
            if (file.name !== fileName) dt.items.add(file);
        });
        input.files = dt.files;
    }

    window.updateTotals = function () {
        $("#totalClaim").val(numberFormat(sumInputs(".claim")));
        $("#totalVerified").val(numberFormat(sumInputs(".verified")));
        $("#totalApproved").val(numberFormat(sumInputs(".approved")));
        $("#totalFinance").val(numberFormat(sumInputs(".finance")));
    };

    function sumInputs(selector) {
        let total = 0;
        $(selector).each(function () {
            total += parseFloat($(this).val().replace(/,/g, "") || 0);
        });
        return total;
    }

    function numberFormat(num) {
        return num.toLocaleString("en-IN", {
            maximumFractionDigits: 2,
        });
    }

    function validateClaimForm(claimid, cgid, expid) {
        let isValid = true;
        let errorMessages = [];
        let expensesData = [];

        if (cgid == 7) {
            let totalClaimAmount = 0;

            const requiredFields = [
                { id: "bill_date", name: "Bill Date" },
                { id: "activity_type", name: "Activity Type" },
                { id: "crops", name: "Crops" },
            ];

            requiredFields.forEach((f) => {
                const value = $(`#${f.id}`).val();
                if (!value || value.length === 0) {
                    isValid = false;
                    $(`#${f.id}`).css("border", "1px solid red");
                    errorMessages.push(`${f.name} is required.`);
                } else {
                    $(`#${f.id}`).css("border", "");
                }
            });

            if ($("#trial_no").length > 0) {
                if (!$("#trial_no").val().trim()) {
                    isValid = false;
                    $("#trial_no").css("border", "1px solid red");
                    errorMessages.push("Trial No. is required.");
                } else {
                    $("#trial_no").css("border", "");
                }
            }

            $(".expense-table tbody tr").each(function () {
                const row = $(this);
                const headName = row.find("td:first").text().trim();
                if (!headName || headName === "Total:") return;

                const claimAmt = parseFloat(
                    row.find(".claim").val().replace(/,/g, "") || 0
                );
                totalClaimAmount += claimAmt;

                const fileInput = row.find(".file-input")[0];
                const preloadedFiles = [];
                const newFiles = [];

                row.find(".file-list .file-name").each(function () {
                    const fileName = $(this).data("file");
                    const isRemoved =
                        $(this).parent().attr("data-remove") == "1";
                    const isPreloaded = $(this).data("preloaded") == 1;
                    if (!isRemoved && isPreloaded) {
                        preloadedFiles.push(fileName);
                    }
                });

                if (fileInput && fileInput.files.length > 0) {
                    Array.from(fileInput.files).forEach((file) => {
                        newFiles.push(file);
                    });
                }

                let rowValid = true;
                const totalFiles = preloadedFiles.length + newFiles.length;

                if (totalFiles > 0 && claimAmt <= 0) {
                    rowValid = false;
                    errorMessages.push(
                        `Claim amount must be > 0 for "${headName}" because file is selected.`
                    );
                }

                if (claimAmt > 0 && totalFiles === 0) {
                    rowValid = false;
                    errorMessages.push(
                        `At least one file is required for "${headName}" because claim amount is > 0.`
                    );
                }

                row.css("background-color", rowValid ? "" : "#f8d7da");
                if (!rowValid) isValid = false;

                expensesData.push({
                    label: headName,
                    value: expid,
                    claim_amount: claimAmt,
                    preloaded_files: preloadedFiles,
                    new_files: newFiles,
                });
            });

            if (totalClaimAmount <= 0) {
                isValid = false;
                errorMessages.push(
                    "Total claim amount must be greater than 0."
                );
                $("#totalClaim").css("border", "1px solid red");
            } else {
                $("#totalClaim").css("border", "");
            }
        } else if (cgid == 1 && claimid == 1) {
        }

        return { isValid, errorMessages, expensesData };
    }
    $("#cancelBtn").on("click", function (e) {
        e.preventDefault();

        document.activeElement.blur();

        $("#claimDetailModal").modal("hide");

        $("#dataEntryForm")[0].reset();

        $(".expense-table tbody tr").css("background-color", "");
        $("#totalClaim, #bill_date, #activity_type, #crops, #trial_no").css(
            "border",
            ""
        );

        uploadedFiles = [];
        currentFileIndex = 0;
        currentZoom = 1;
        currentRotation = 0;

        $(".file-input").each(function () {
            this.value = "";

            $(this).siblings(".file-list").empty();
        });

        displayThumbnails();
        $("#mainViewer").html(
            '<div class="text-center">No files available.</div>'
        );

        updateNavigationButtons();

        $("#fileName").text("");
        $("#fileType").text("");
        $("#fileIndex").text("");

        $("#sltClaimTypeList").val(null).trigger("change");

        $("#imageContainer").off("wheel", handleMouseWheel);
    });

    $("#saveBtn").on("click", function (e) {
        e.preventDefault();
        const claimid = $("#sltClaimTypeList").val();
        const oldcgid = $("#claimDetailModal").attr("data-modal-cgid");
        const cgid = $(".modal-ncgid").attr("data-modal-ncgid");
        const expid = $("#claimDetailModal").attr("data-modal-expid");
        const empid = $("#claimDetailModal").attr("data-modal-empid");
        let totalAmout = $("#totalClaim").val().replace(/,/g, "");
        const { isValid, errorMessages, expensesData } = validateClaimForm(
            claimid,
            cgid,
            expid
        );

        if (!isValid) {
            showAlert(
                "danger",
                "ri-error-warning-line",
                errorMessages.join("<br>")
            );
            return;
        }

        const formData = new FormData();
        formData.append("claimid", claimid);
        formData.append("cgid", cgid);
        formData.append("expid", expid);
        formData.append("empid", empid);
        formData.append("totalAmout", totalAmout);

        const serializedForm = $("#dataEntryForm").serializeArray();
        serializedForm.forEach((item, index) => {
            formData.append(`formData[${index}][name]`, item.name);
            formData.append(`formData[${index}][value]`, item.value);
        });

        expensesData.forEach((expense, index) => {
            formData.append(`expensesData[${index}][label]`, expense.label);
            formData.append(`expensesData[${index}][value]`, expense.value);
            formData.append(
                `expensesData[${index}][claim_amount]`,
                expense.claim_amount
            );

            formData.append(
                `expensesData[${index}][preloaded_files]`,
                JSON.stringify(expense.preloaded_files)
            );

            expense.new_files.forEach((file, fIndex) => {
                formData.append(
                    `expensesData[${index}][files][${fIndex}]`,
                    file
                );
            });
        });

        console.log("FormData prepared. Sending...");

        $.ajax({
            url: "/upload-claim-files",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                showAlert(
                    "success",
                    "ri-checkbox-circle-line",
                    response.message || "Data and files uploaded successfully!"
                );

                const claimid =
                    $("#claimDetailModal").attr("data-modal-claimid");
                const expId = $("#claimDetailModal").attr("data-modal-expid");
                const cgId = $("#claimDetailModal").attr("data-modal-cgid");
                const empId = $("#claimDetailModal").attr("data-modal-empid");

                claimTypeList(claimid);
                fetchUploadedFiles(expId, claimid);
            },
            error: function (xhr) {
                const response = xhr.responseJSON || {};
                const messages = Array.isArray(response.message)
                    ? response.message
                    : [
                          response.message ||
                              "An error occurred while uploading.",
                      ];
                showAlert(
                    "danger",
                    "ri-error-warning-line",
                    messages.join("<br>")
                );
            },
        });
    });

    $("#claimDetailModal").on("hidden.bs.modal", function () {
        $("#dataEntryForm")[0].reset();
        currentFileIndex = 0;
        currentZoom = 1;
        currentRotation = 0;
        $("#imageContainer").off("wheel", handleMouseWheel);
    });

    function claimTypeList(id = null) {
        $.ajax({
            url: "get-claim-types",
            method: "GET",
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success && response.data) {
                    let $select = $("#sltClaimTypeList");
                    $select.empty();

                    $select.select2({
                        dropdownParent: $("#claimDetailModal"),
                        width: "250px",
                        placeholder: "Select Document Type",
                        allowClear: true,
                        data: response.data,
                    });

                    if (id) {
                        $select.val(id).trigger("change");
                    }
                } else {
                    showAlert(
                        "warning",
                        "ri-alert-line",
                        response.message || "No claim types found."
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("Fetch error:", {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    response: xhr.response,
                });
                showAlert(
                    "danger",
                    "ri-error-warning-line",
                    "Failed to fetch claim types: " +
                        (xhr.responseText || error)
                );
            },
        });
    }

    function fetchUploadedFiles(expid, claim_id) {
        $.ajax({
            url: `/get-uploaded-files/${expid}/${claim_id}`,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.data && response.data.uploaded_files) {
                    uploadedFiles = [];
                    if (Array.isArray(response.data.uploaded_files)) {
                        uploadedFiles = response.data.uploaded_files.map(
                            (file) => {
                                const ext = file.file_path
                                    .split(".")
                                    .pop()
                                    .toLowerCase();
                                let type = "application/octet-stream";
                                if (
                                    [
                                        "jpg",
                                        "jpeg",
                                        "png",
                                        "gif",
                                        "bmp",
                                        "webp",
                                    ].includes(ext)
                                ) {
                                    type = "image/" + ext;
                                } else if (ext === "pdf") {
                                    type = "application/pdf";
                                }

                                const shortCode =
                                    file.file_path.split("_")[0] || "Document";
                                return {
                                    url: file.file_url,
                                    name: file.file_path,
                                    type: type,
                                    short_code: shortCode,
                                };
                            }
                        );
                    } else {
                        Object.keys(response.data.uploaded_files).forEach(
                            (key) => {
                                const category =
                                    response.data.uploaded_files[key];
                                const shortCode =
                                    category.short_code || "Document";
                                category.files.forEach((file) => {
                                    const ext = file.file_path
                                        .split(".")
                                        .pop()
                                        .toLowerCase();
                                    let type = "application/octet-stream";
                                    if (
                                        [
                                            "jpg",
                                            "jpeg",
                                            "png",
                                            "gif",
                                            "bmp",
                                            "webp",
                                        ].includes(ext)
                                    ) {
                                        type = "image/" + ext;
                                    } else if (ext === "pdf") {
                                        type = "application/pdf";
                                    }
                                    uploadedFiles.push({
                                        url: file.file_url,
                                        name: file.file_path,
                                        type: type,
                                        short_code: shortCode,
                                    });
                                });
                            }
                        );
                    }
                    displayThumbnails();
                    showFile(0);
                } else {
                    alert("No uploaded files found");
                    uploadedFiles = [];
                    displayThumbnails();
                }
            },
            error: function (xhr) {
                alert(xhr.responseText || "Failed to fetch files");
                uploadedFiles = [];
                displayThumbnails();
            },
        });
    }

    $(document).on("click", ".copy-icon", function () {
        var expId = $("#expIdValue").text();
        navigator.clipboard
            .writeText(expId)
            .then(() => {
                var copiedText = $(this).siblings(".copied-text");
                copiedText.fadeIn(200).delay(1000).fadeOut(500);
            })
            .catch((err) => console.error("Failed to copy!", err));
    });

    // ✅ Reusable function to load claim details
    function loadClaimDetail(claimid, expId, triggerBtn = null) {
        if (!claimid || !expId) {
            $("#dataEntryForm").html(
                '<div class="alert alert-warning">Please select a claim type and ensure ExpId is set.</div>'
            );
            return;
        }

        $.ajax({
            url: "claim-detail",
            method: "GET",
            data: { claim_id: claimid, expid: expId },
            beforeSend: function () {
                if (triggerBtn) startLoader({ currentTarget: triggerBtn });
                $("#dataEntryForm").html(
                    '<div class="text-primary text-center py-3">Loading claim details...</div>'
                );
            },
            success: function (response) {
                if (response.html) {
                    $("#dataEntryForm").html(response.html);
                } else {
                    $("#dataEntryForm").html(
                        '<div class="alert alert-danger">No details found.</div>'
                    );
                }
            },
            error: function (xhr) {
                $("#dataEntryForm").html(
                    '<div class="alert alert-danger">Error loading claim details.</div>'
                );
                console.error("Claim detail fetch error:", xhr);
            },
            complete: function () {
                if (triggerBtn) endLoader({ currentTarget: triggerBtn });
            },
        });
    }

    // ✅ View Claim Click
    $(document).on("click", ".view-claim", function (e) {
        e.preventDefault();

        const claimid = $(this).data("claim-id");
        const expId = $(this).data("expid");
        const cgId = $(this).data("cgid");
        const empId = $(this).data("empid");

        console.log("Claim ID:", claimid);
        console.log("Expense ID:", expId);
        console.log("Category ID:", cgId);
        console.log("Employee ID:", empId);

        // ✅ Completely reset & destroy previous modal state before appending new data
        const modal = $("#claimDetailModal");

        // Remove all event listeners, data, and inner content safely
        modal.off().removeData();
        modal.find("#dataEntryForm").html(""); // Clear dynamic form content
        modal.find(".file-list").empty();
        modal
            .find("#mainViewer")
            .html('<div class="text-center">No files available.</div>');
        modal.find("#sltClaimTypeList").val(null).trigger("change");
        modal.find("#fileName, #fileType, #fileIndex, #expIdValue").text("");

        // Reset any global file states
        uploadedFiles = [];
        currentFileIndex = 0;
        currentZoom = 1;
        currentRotation = 0;

        // ✅ Assign new modal data
        modal.attr({
            "data-modal-claimid": claimid,
            "data-modal-expid": expId,
            "data-modal-cgid": cgId,
            "data-modal-empid": empId,
        });

        // ✅ Update display info
        $("#expIdValue").text(expId);

        // ✅ Load dependent data
        claimTypeList(claimid);
        fetchUploadedFiles(expId, claimid);

        // ✅ Load claim details (fresh)
        loadClaimDetail(claimid, expId);

        // ✅ Finally, show the modal
        modal.modal("show");
    });

    // ✅ Claim Type Change
    $(document).on("change", "#sltClaimTypeList", function (event) {
        const claimid = $(this).val();
        const expId = $("#claimDetailModal").attr("data-modal-expid");
        loadClaimDetail(claimid, expId, event.currentTarget);
    });
});
