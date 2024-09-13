$(document).ready(function () {
    var table = $("#users-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: userDataRoute,
            type: "POST",
            data: {
                _token: csrfToken,
            },
        },
        columns: [
            {
                data: null,
                defaultContent: "",
                name: "index",
                orderable: false,
                searchable: false,
            },
            { data: "name", name: "name" },
            { data: "contact_number", name: "contact_number" },
            { data: "hobbies", name: "hobbies" },
            { data: "category", name: "category" },
            {
                data: "profile_photo",
                name: "profile_photo",
                orderable: false,
                searchable: false,
            },
            {
                data: "actions",
                name: "actions",
                orderable: false,
                searchable: false,
            },
            {
                data: "created_at",
                name: "created_at",
                orderable: true,
                searchable: false,
                visible: false,
            },
        ],
        order: [[7, "desc"]],
        drawCallback: function () {
            var api = this.api();
            api.rows().every(function (rowIdx, tableLoop, rowLoop) {
                var index = api.row(rowIdx).index() + 1;
                $(api.cell(rowIdx, 0).node()).html(index);
            });
        },
    });

    $("#addUserForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: userStoreRoute,
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "User added successfully!",
                    confirmButtonText: "Ok",
                }).then(function () {
                    $("#addUserModal").modal("hide");
                    table.ajax.reload();
                });
            },
            error: function (xhr) {
                $(".text-danger").text("");
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            var errorMessages = errors[key];
                            $("#" + key + "Error").text(
                                errorMessages.join(", ")
                            );
                        }
                    }
                } else {
                    console.log("Error adding user");
                }
            },
        });
    });

    $("#users-table").on("click", ".btn-edit", function () {
        var data = table.row($(this).parents("tr")).data();
        console.log(data);
        $("#edit_user_id").val(data.id);
        $("#edit_name").val(data.name);
        $("#edit_contact_number").val(data.contact_number);
        $("#edit_category_id").val(data.category_id).trigger("change");
        var hobbies = data.hobbies_id ? data.hobbies_id.split(",") : [];
        $("#edit_hobbies").val(hobbies).trigger("change");
        $("#editUserModal").modal("show");
    });

    $("#editUserForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: userUpdateRoute,
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "User updated successfully!",
                    confirmButtonText: "Ok",
                }).then(function () {
                    $("#editUserModal").modal("hide");
                    table.ajax.reload();
                });
            },
            error: function (xhr) {
                $(".text-danger").text("");
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            var errorMessages = errors[key];
                            $("#" + key + "Error").text(
                                errorMessages.join(", ")
                            );
                        }
                    }
                } else {
                    console.log("Error updating user");
                }
            },
        });
    });

    $("#users-table").on("click", ".btn-delete", function () {
        var userId = $(this).data("user_id");
        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/users/" + userId,
                    type: "DELETE",
                    data: {
                        _token: csrfToken,
                    },
                    success: function (response) {
                        Swal.fire("Deleted!", response.success, "success");
                        table.ajax.reload();
                    },
                    error: function () {
                        Swal.fire("Error!", "Something went wrong!", "error");
                    },
                });
            }
        });
    });

    $("#hobbies, #edit_hobbies").select2({
        placeholder: "Select hobbies",
        allowClear: true,
        width: "100%",
        templateResult: formatHobbyOption,
        templateSelection: formatHobbySelection,
    });

    function formatHobbyOption(option) {
        if (!option.id) {
            return option.text;
        }
        var $option = $(
            '<div><input type="checkbox" class="select2-checkbox" /> ' +
                option.text +
                "</div>"
        );
        return $option;
    }

    function formatHobbySelection(option) {
        return option.text;
    }
});
