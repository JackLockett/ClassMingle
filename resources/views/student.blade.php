<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .profile-card {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .profile-header {
      text-align: center;
    }
    .profile-info {
      margin-top: 20px;
    }
    .profile-info p {
      margin-bottom: 10px;
    }
    .profile-picture {
      text-align: center;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .profile-picture img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
    }
    .profile-friends {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .profile-friends h4 {
      text-align: center;
      margin-bottom: 10px;
    }
    .profile-badges {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .profile-badges h4 {
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
@include('layouts.navbar')

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="profile-picture">
        <div class="profile-header">
          <h4>Profile Picture</h4>
        </div>
        <!-- Replace 'profile_picture.jpg' with the actual path to the profile picture -->
        <img src="https://pbs.twimg.com/media/FzEjZL4aYAU4Vzj.jpg" alt="Profile Picture">
      </div>
    </div>
    <div class="col-md-8">
      <div class="profile-card">
        <div class="profile-header">
          <h4>User Details</h4>
        </div>
        <div class="profile-info">
          <p><strong>Username:</strong> {{ $student->username }}</p>
          <p><strong>Email:</strong> {{ $student->email }}</p>
          <p><strong>Bio:</strong> {{ $student->bio }}</p>
          <p><strong>University:</strong> {{ $student->university ? $student->university : 'Not Applicable' }}</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="profile-friends">
        <h4>Friends</h4>
        <!-- Insert code for displaying friends here -->
      </div>
    </div>
    <div class="col-md-8">
      <div class="profile-badges">
        <h4>Badges</h4>
        <!-- Insert code for displaying badges here -->
      </div>
    </div>
  </div>
  </div>

<!-- Footer -->
@include('layouts.footer')

</body>
</html>
