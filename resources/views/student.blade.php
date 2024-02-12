<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap Example</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .multi-collapse {
      transition: height 0.3s ease-in-out;
    }
  </style>
</head>
<body>
@include('layouts.navbar')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h3 class="text-center">View Students</h3>
            <br>
            <p><strong>Username:</strong> {{ $student->username }}</p>
            <p><strong>Email:</strong> {{ $student->email }}</p>
            <p><strong>Role:</strong> {{ $student->role }}</p>
            <p><strong>Bio:</strong> {{ $student->bio }}</p>
            <p><strong>University:</strong> {{ $student->university ? $student->university : 'Not Applicable' }}</p>
        </div>
    </div>
</div>

@include('layouts.footer')

</body>
</html>
