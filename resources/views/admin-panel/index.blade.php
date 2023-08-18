
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h1>Admin Panel - User List</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="d-flex justify-content-between p-3">
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">Create User</button>
        <button  class="btn btn-secondary mb-3" onclick="goBack()" >Back</button>
    </div>


    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Phone Number</th>
            <th>UniqueLink</th>
            <th>Actions</th>

        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone_number }}</td>
                <td>
                    @foreach($user->uniqueLinks as $uniqueLink)
                        /generate-link/{{ $uniqueLink->link }}<br>
                    @endforeach
                </td>
                <td>
                    <button class="btn btn-sm btn-info edit-button" data-id="{{ $user->id }}" data-link="{{ $uniqueLink->link }}" data-toggle="modal" data-target="#editModal">Edit</button>
                    <button class="btn btn-sm btn-danger delete-button" data-id="{{ $user->id }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    @csrf
                    <div class="form-group">
                        <label for="createName">Name</label>
                        <input type="text" class="form-control" id="createName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="createPhoneNumber">Phone Number</label>
                        <input type="text" class="form-control" id="createPhoneNumber" name="phone_number" required pattern="[0-9]{10}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="createUserButton" >Create</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editPhoneNumber">Phone Number</label>
                        <input type="text" class="form-control" id="editPhoneNumber" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="editUniqueLink">Link</label>
                        <input type="text" class="form-control" id="editUniqueLink" name="uniqueLink" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="editUserButton">Save Changes</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="deleteUserButton" >Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#createUserButton').click(function () {
            $.ajax({
                type: 'POST',
                url: '{{ route("admin-panel.store") }}',
                data: $('#createForm').serialize(),
                success: function (data) {
                    $('#createModal').modal('hide');
                    location.reload();
                }
            });
        });


        $('.edit-button').click(function () {
            var userId = $(this).data('id');
            var link = $(this).data('link')
            $.ajax({
                type: 'GET',
                url: '/admin-panel/edit/' + userId,
                success: function (data) {
                    var user = data.user;
                    $('#editModal #editUserId').val(user.id);
                    $('#editModal #editName').val(user.name);
                    $('#editModal #editPhoneNumber').val(user.phone_number);
                    $('#editModal #editUniqueLink').val(link);
                    $('#editModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    alert('An error occurred while loading user data.');
                }
            });
        });


        $(document).on('click', '#editUserButton', function () {
            var userId = $('#editUserId').val();
            $.ajax({
                type: 'PUT',
                url: '{{ route("admin-panel.update", ["id" => "_id_"]) }}'.replace('_id_', userId),
                data: $('#editForm').serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {

                    $('#editModal').modal('hide');
                    location.reload();
                },
                error: function (xhr, status, error) {

                    console.log(xhr.responseText);
                    alert('An error occurred while updating the user.');
                }
            });
        });


        $('.delete-button').click(function () {
            var userId = $(this).data('id');
            $('#deleteUserButton').attr('data-id', userId);
            $('#deleteModal').modal('show');
        });


        $('#deleteUserButton').click(function () {
            var userId = $(this).data('id');
            $.ajax({
                type: 'DELETE',
                url: '/admin-panel/destroy/' + userId,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#deleteModal').modal('hide');
                    location.reload();
                },
                error: function (xhr, status, error) {

                    console.log(xhr.responseText);
                    alert('An error occurred while deleting the user.');
                }
            });
        });
    });
    function goBack() {
        window.history.back();
    }
</script>
</body>
