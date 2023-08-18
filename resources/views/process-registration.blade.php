<!DOCTYPE html>
<html>
<head>
    <title>Process Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">

    <form action="/process-registration" method="POST" class="border p-4 rounded">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input minlength="3" type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone number:</label>
            <input type="tel" name="phone_number" pattern="[0-9]{10}" class="form-control" required>
            <small class="form-text text-muted">Enter 10 digits of the number without spaces or signs</small>
        </div>

        <button type="submit" class="btn btn-primary">Register and get a unique link</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
