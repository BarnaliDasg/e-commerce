//for preview the post img

var input=document.querySelector("#select_post_img");

input.addEventListener("change",preview);

function preview(){
    var fileobject = this.files[0];
    var filereader = new FileReader();

    filereader.readAsDataURL(fileobject);

    filereader.onload = function(){
        var img_src = filereader.result;
        var img = document.querySelector("#post_img");
        img.setAttribute('src',img_src);
        img.setAttribute('style','display:');
    }
}

//for follow the user
$(".followbtn").click(function () {
    var u_id_v = $(this).data("user-id");
    var button = $(this);
    $(button).attr('disabled',true);

    $.ajax({
        url: "assets/php/ajax.php?follow",
        method: "POST",
        dataType: "json",
        data: { u_id: u_id_v },
        success: function (response) {
            if (response.status) {
                button.html('<i class="fas fa-check-circle text-white"></i> Following');
                button.removeClass("btn-primary").addClass("bg-primary text-white px-3 py-1 rounded border-0");
            }else{
                $(button).attr('disabled',false);
                alert('something is wrong, try again after sometime');
            }
        }
    });
});

//for unfollow the user
$(".unfollowbtn").click(function () {
    var u_id_v = $(this).data("user-id");
    var button = $(this);
    $(button).attr('disabled',true);

    $.ajax({
        url: "assets/php/ajax.php?unfollow",
        method: "POST",
        dataType: "json",
        data: { u_id: u_id_v },
        success: function (response) {
            if (response.status) {
                button.html('<i class="fas fa-check-circle text-white"></i> Unfollowed');
                button.removeClass("btn-primary").addClass("bg-danger text-white px-3 py-1 rounded border-0");
            }else{
                $(button).attr('disabled',false);
                alert('something is wrong, try again after sometime');
            }
        }
    });
});

//for like the post
$(".like_btn").click(function () {
    var post_id_v = $(this).data("postId");
    var button = $(this);

    $(button).prop("disabled", true);

    $.ajax({
        url: "assets/php/ajax.php?like",
        method: "POST",
        dataType: "json",
        data: { post_id: post_id_v },
        success: function (response) {
            console.log(response);

            if (response.status) {
                $(button).attr("disabled", false);
                $(button).hide();
                $(button).siblings('.unlike_btn').show();
                location.reload();
            } else {
                $(button).attr("disabled", false);
                alert("Something went wrong, please try again later.");
            }
        }
    });
});

// Handle Unlike Button Click
$(".unlike_btn").click(function () {
    var post_id_v = $(this).data("postId");
    var button = $(this);

    $(button).prop("disabled", true);

    $.ajax({
        url: "assets/php/ajax.php?unlike",
        method: "POST",
        dataType: "json",
        data: { post_id: post_id_v },
        success: function (response) {
            console.log(response);

            if (response.status) {
                $(button).attr("disabled", false);
                $(button).hide();
                $(button).siblings('.like_btn').show();
                location.reload();
            } else {
                $(button).attr("disabled", false);
                alert("Something went wrong, please try again later.");
            }
        }
    });
});

//comment
$(document).on("submit", "#commentForm", function (e) {
    e.preventDefault();

    let post_id = $(this).data("post-id");
    let comment_text = $(this).find("textarea").val().trim();

    if (!comment_text) {
        alert("Comment cannot be empty!");
        return;
    }

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: { post_id: post_id, comment_text: comment_text },
        dataType: "json",
        success: function (response) {
            console.log(response); // Debugging
            if (response.status === "success") {
                alert("Comment added successfully!");
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: ", status, error);
        }
    });
});

//search
$(document).ready(function () {
    $("#pincodeSearchForm").submit(function (event) {
        event.preventDefault(); // Prevent page reload

        var pincode = $("#pincodeInput").val().trim();

        if (pincode.length === 0) {
            $("#searchResults").html('<p class="text-danger">Please enter a pincode.</p>');
            return;
        }

        $.ajax({
            url: "assets/php/ajax.php?searchPincode",
            method: "POST",
            dataType: "json",
            data: { pincode: pincode },
            success: function (response) {
                if (response.status) {
                    var usersHTML = "";
                    $.each(response.users, function (index, user) {
                        usersHTML += `
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center p-2">
                                    <img src="assets/images/profile/${user.profile_pic}" alt="" height="40" width="40" class="rounded-circle border">
                                    <div>&nbsp;&nbsp;</div>
                                    <a href="?u=${user.uname}" class="text-decoration-none text-dark">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 style="margin: 0px; font-size: small;">${user.fname} ${user.lname}</h6>
                                            <p style="margin:0px; font-size:small" class="text-muted">@${user.uname}</p>
                                            <h6 style="margin: 0px; font-size: small; padding: 2px 5px; background-color:rgb(154, 189, 224); display: inline-block;">
                                                ${user.role}
                                            </h6>

                                        </div>
                                    </a>
                                </div>
                            </div>`;
                    });

                    $("#searchResults").html(usersHTML);
                } else {
                    $("#searchResults").html('<p class="text-muted">No users found for this pincode.</p>');
                }

                $("#userSearchModal").modal("show"); // Show modal dynamically
            }
        });
    });
});

//messages|
$(document).on("submit", "#messageForm", function (e) {
    e.preventDefault();

    let receiver_id = $(this).data("receiver-id");
    let message_text = $(this).find("textarea").val().trim();

    if (!message_text) {
        alert("Message cannot be empty!");
        return;
    }

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: { receiver_id: receiver_id, message_text: message_text },
        dataType: "json",
        success: function (response) {
            console.log(response); // Debugging
            if (response.status === "success") {
                alert("Message sent successfully!");
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: ", status, error);
        }
    });
});

$(document).on("click", "#openSidebar", function () {
    $("#sidebar").addClass("active");
    $("#overlay").fadeIn();
});

$(document).on("click", "#closeSidebar, #overlay", function () {
    $("#sidebar").removeClass("active");
    $("#overlay").fadeOut();
});



$(document).ready(function () {
    $(".nav-link").on("click", function (e) {
        e.preventDefault();
        const tableName = $(this).text().trim().replace("📁", "").trim();

        if (!tableName) {
            console.error("Table name is empty after extraction.");
            $("#tableContentArea").html("<p>Error: Table name is empty.</p>");
            return;
        }

        function loadTableData(tableName) {
            $.ajax({
                url: "assets/php/actions.php",
                type: "POST",
                data: {
                    action: "get_table_data",
                    table: tableName
                },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        if (!response.columns || !response.rows) {
                            $("#tableContentArea").html("<p>Error: Invalid data format received.</p>");
                            return;
                        }

                        let html = `<h3>📋 Table: <code>${tableName}</code></h3>`;
                        html += "<table border='1' cellpadding='5'><thead><tr>";

                        response.columns.forEach(function (col) {
                            html += `<th>${col}</th>`;
                        });

                        html += "<th>Actions</th></tr></thead><tbody>";

                        response.rows.forEach(function (row, index) {
                            html += "<tr>";
                            row.forEach(function (cell) {
                                html += `<td>${cell}</td>`;
                            });

                            const id = row[0]; // Assuming first column is primary key (like 'id')
                            html += `<td>
                                       <button class='edit-btn' data-row-index='${index}'>Edit</button>
                                       <button class='delete-btn' data-id='${id}'>Delete</button>
                                     </td>`;
                            html += "</tr>";
                        });

                        html += "</tbody></table>";
                        $("#tableContentArea").html(html);

                        // Attach events after HTML load
                       $(".edit-btn").on("click", function () {
                            const rowId = $(this).data("id");
                            const rowIndex = $(this).data("row-index");
                            handleEdit(tableName, rowId, response.columns, response.rows[rowIndex]);
                        });


                        $(".delete-btn").on("click", function () {
                            const id = $(this).data("id");
                            handleDelete(tableName, id);
                        });

                    } else {
                        $("#tableContentArea").html(`<p>Error: ${response.message}</p>`);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $("#tableContentArea").html("<p>Error loading table data.</p>");
                }
            });
        }
        function enableRowEditing(tableName, rowId, columns, rowData) {
        // Find the row by matching the rowId in the first cell
        const row = $("#tableContentArea table tbody tr").filter(function() {
            return $(this).find("td:first").text() == rowId;
        });

        if (row.length === 0) {
            alert("Row not found.");
            return;
        }

        // Prevent multiple rows being edited simultaneously
        if (row.hasClass("editing")) {
            alert("This row is already being edited.");
            return;
        }
        
        // Mark this row as being edited
        row.addClass("editing");

        // Backup original cells html to revert on cancel
        const originalHtml = row.html();

        // Replace all columns except the first (ID) and last (Actions) with inputs
        row.find("td").each(function(index) {
            if (index === 0) {
            // ID cell — keep as plain text or readonly input
            const idVal = $(this).text();
            $(this).html(`<input type="text" value="${idVal}" readonly style="background:#eee; border:none; width:100%;">`);
            } else if (index === row.find("td").length - 1) {
            // Actions cell — replace buttons with Save and Cancel
            $(this).html(`
                <button class="save-edit-btn">Save</button>
                <button class="cancel-edit-btn">Cancel</button>
            `);
            } else {
            // Editable cells
            const val = $(this).text();
            $(this).html(`<input type="text" value="${val}" style="width:100%;">`);
            }
        });

        // Save button handler
        row.find(".save-edit-btn").on("click", function() {
            const updatedData = {};
            row.find("td").each(function(index) {
            if (index > 0 && index < row.find("td").length - 1) {
                const colName = columns[index]; // assuming columns array aligns with td order
                const val = $(this).find("input").val();
                updatedData[colName] = val;
            }
            });

            $.ajax({
            url: "assets/php/actions.php",
            method: "POST",
            dataType: "json",
            data: {
                action: "edit_row_full",
                table: tableName,
                row_id: rowId,
                updated_data: updatedData
            },
            success: function(response) {
                if (response.status === "success") {
                alert("Row updated successfully!");
                loadTableData(tableName); // reload table or update row
                } else {
                alert("Error updating row: " + response.message);
                row.html(originalHtml);
                row.removeClass("editing");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error);
                alert("AJAX error. Check console.");
                row.html(originalHtml);
                row.removeClass("editing");
            }
            });
        });

        // Cancel button handler — revert changes
        row.find(".cancel-edit-btn").on("click", function() {
            row.html(originalHtml);
            row.removeClass("editing");
        });
        }



        function handleDelete(tableName, id) {
            if (confirm("Are you sure you want to delete this row?")) {
                $.ajax({
                    url: "assets/php/actions.php",
                    type: "POST",
                    data: {
                        action: "delete_row",
                        table: tableName,
                        id: id
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            alert("Row deleted successfully!");
                            loadTableData(tableName);
                        } else {
                            alert("Error deleting row: " + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error (delete):", status, error);
                        alert("AJAX Error (delete): Check console");
                    }
                });
            }
        }

        // Initial call
        loadTableData(tableName);
    });
});


// Load Add Product page dynamically when clicked in sidebar
$(document).on("click", "a[data-page='add_product']", function (e) {
    e.preventDefault();

    var container = $("#tableContentArea");
    var link = $(this);

    link.prop("disabled", true);
    container.html("<p>Loading Add Product form...</p>");

    $.ajax({
        url: "assets/pages/add_product.php", // Adjust path as needed
        method: "GET",
        success: function (response) {
            container.html(response);
            link.prop("disabled", false);
        },
        error: function () {
            container.html("<p>Error loading form. Please try again later.</p>");
            link.prop("disabled", false);
        }
    });
});


$(document).on("submit", "#addProductForm", function (e) {
    e.preventDefault();

    var form = $(this)[0]; // Get the form element
    var formData = new FormData(form); // Create FormData with file support

    $.ajax({
        url: "assets/php/actions.php?action=addproduct",
        method: "POST",
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting contentType
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                alert("✅ Product added successfully!");
                $("#tableContentArea").load("assets/pages/admin_table_view.php");
            } else {
                alert("⚠️ " + (response.message || "Error adding product."));
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("❌ Something went wrong.");
        }
    });
});



