let currentLocation = window.location.pathname.split("/").filter(Boolean).pop();


function toaster_message(message, icon, url) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger me-2",
        },
        buttonsStyling: false,
    });

    swalWithBootstrapButtons.fire({
        text: message,
        icon: icon,
        confirmButtonText: "Okay",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            if (typeof url === "undefined" || url === "") {
                let segments = window.location.pathname.split("/").filter(Boolean);
                let currentLocation = segments[segments.length - 1];
                let tableId = "#" + currentLocation + "_tbl";

                if ($(tableId).length) {
                    $.ajax({
                        url: window.location.href,
                        type: "GET",
                        success: function (response) {
                            // Get only new tbody HTML
                            // let newBody = $(response).find(tableId + " tbody").html();
                                                    let newBody = $(response).find(tableId + " tbody").html() || "";


                            // if (newBody && newBody.trim() !== "") {
                                // ✅ Replace only tbody (keep table instance)
                                $(tableId + " tbody").html(newBody);


                                // ✅ Reinitialize tooltips
                                $('[data-bs-toggle="tooltip"]').tooltip();

                                 $('#screenModal select').select2({
                                    dropdownParent: $('#screenModal'),
                                    width: '100%',
                                    placeholder: "--Select--",
                                    allowClear: false
                                });

                                // ✅ No need to destroy/recreate DataTable
                                // It will keep pagination and features
                            // } else {
                            //     console.warn("⚠️ No tbody found in response for:", tableId);
                            // }
                        },

                        error: function (xhr) {
                            console.error("❌ Failed to refresh table:", xhr.responseText);
                        }
                    });
                }
            } else {
                window.location.href = aurl + "/" + url;
            }
        }
    });
}

function toaster_alert_action(message, icon, url) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        customClass: {
            popup: "rounded-xl shadow-lg p-3 border border-gray-200",
            title: "text-sm font-semibold text-gray-800",
        },
        didOpen: (toast) => {
            toast.style.top = "10%";
        },
    });

    Toast.fire({
        icon: icon,
        title: message
    });

    console.log("✅ Toast shown, waiting to perform action...");

    setTimeout(() => {
        console.log("⏱️ Timer finished — running post-toast action");

        if (typeof url === "undefined" || url === "") {
            let segments = window.location.pathname.split("/").filter(Boolean);
            let currentLocation = segments[segments.length - 1];
            let tableId = "#" + currentLocation + "_tbl";

            console.log("🔍 Checking for table:", tableId);

            if ($(tableId).length) {
                console.log("✅ Table found, refreshing...");

                $.ajax({
                    url: window.location.href,
                    type: "GET",
                    success: function (response) {
                        let newBody = $(response).find(tableId + " tbody").html();

                        // if (newBody && newBody.trim() !== "") {
                        //     $(tableId + " tbody").html(newBody);
                        //     $('[data-bs-toggle="tooltip"]').tooltip();
                        //     console.log("✅ Table updated successfully");
                        // } else {
                        //     console.warn("⚠️ No tbody found in response for:", tableId);
                        // }
                    },
                    error: function (xhr) {
                        console.error("❌ Failed to refresh table:", xhr.responseText);
                    }
                });
            }
        } else {
            console.log("➡️ Redirecting to:", aurl + "/" + url);
            window.location.href = aurl + "/" + url;
        }
    }, 2100);
}







