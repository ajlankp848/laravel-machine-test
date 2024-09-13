<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Datatables CSS -->
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .select2-container--default .select2-results__option--selected {
            background-color: #d3d3d3;
        }

        .select2-container--default .select2-results__option {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Users Data</h2>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>
        </div>
        <table id="users-table" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact No</th>
                    <th>Hobbies</th>
                    <th>Category</th>
                    <th>Profile Pic</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <span id="nameError" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact No</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                            <span id="contact_numberError" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="hobbies">Hobbies</label>
                            <select class="form-control select2" id="hobbies" name="hobbies[]" multiple="multiple"
                                required>
                                <option value="">Select Hobbies</option>
                                @foreach($hobbies as $hobby)
                                <option value="{{ $hobby->id }}">{{ $hobby->hobby_name }}</option>
                                @endforeach
                            </select>
                            <span id="hobbiesError" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <span id="category_idError" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="profile_photo">Profile Photo URL</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" required>
                            <span id="profile_photoError" class="text-danger"></span>
                        </div>
                        <button type="submit" class="btn btn-primary add-user">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        @csrf
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="form-group">
                            <label for="edit_name">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                            <span id="edit_nameError" class="edit text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit_contact_number">Contact No</label>
                            <input type="text" class="form-control" id="edit_contact_number" name="edit_contact_number" required>
                            <span id="edit_contact_numberError" class=" edit text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit_hobbies">Hobbies</label>
                            <select class="form-control select2" id="edit_hobbies" name="edit_hobbies[]" multiple="multiple" required>
                                <option value="">Select Hobbies</option>
                                @foreach($hobbies as $hobby)
                                <option value="{{ $hobby->id }}">{{ $hobby->hobby_name }}</option>
                                @endforeach
                            </select>
                            <span id="edit_hobbiesError" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit_category_id">Category</label>
                            <select class="form-control" id="edit_category_id" name="edit_category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <span id="edit_category_idError" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit_profile_photo">Profile Photo URL</label>
                            <input type="file" class="form-control" id="edit_profile_photo" name="edit_profile_photo">
                            <span id="edit_profile_photoError" class="text-danger"></span>
                        </div>
                        <button type="submit" class="btn btn-primary update-user">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap, and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        var userDataRoute   = "{{ route('user.data') }}";
        var userStoreRoute  = "{{ route('user.store') }}";
        var userUpdateRoute = "{{ route('user.update') }}"
        var csrfToken       = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/user.js') }}"></script>
</body>
</html>
