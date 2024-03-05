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
      <script src="https://kit.fontawesome.com/a076d05399.js"></script>
      <style>
         body {
         background-color: #f8f9fa;
         }
         .profile-card {
         background-color: #fff;
         border-radius: 10px;
         box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
         padding: 20px;
         margin-bottom: 30px;
         }
         .profile-picture {
         text-align: center;
         margin-bottom: 20px;
         }
         .profile-picture img {
         width: 150px;
         height: 150px;
         border-radius: 50%;
         object-fit: cover;
         border: 5px solid #fff;
         box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s ease-in-out;
         }
         .profile-picture img:hover {
         transform: scale(1.1);
         }
         .profile-info p {
         margin-bottom: 10px;
         }
         .profile-info strong {
         color: #007bff;
         }
         .profile-header {
         display: flex;
         align-items: center;
         margin-bottom: 15px;
         }
         .profile-header i {
         margin-right: 8px; 
         font-size: 20px; 
         }
         .profile-footer {
         text-align: center;
         margin-top: 20px;
         }
         .badge {
         padding: 8px 12px;
         font-size: 14px;
         border-radius: 20px;
         background-color: #007bff;
         color: #fff;
         display: inline-block; 
         margin-bottom: 10px;
         margin-right: 10px;
         }
         .profile-action-card {
         background-color: #f8f9fa;
         border-radius: 10px;
         box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
         padding: 20px;
         margin-top: 20px;
         }
         .profile-action-card button {
         margin-right: 10px;
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-4">
         <div class="row justify-content-center">
            <div class="col-md-4">
               <div class="profile-picture">
                  <img src="{{ asset($student->avatar ?? 'images/default.jpg') }}" alt="Profile Picture">
                  <div class="profile-footer">
                     <div class="profile-card">
                        <button class="btn btn-primary mr-2" @if($student->id === Auth::id()) disabled style="opacity: 0.6; pointer-events: none; background-color: #dcdcdc; border-color: #c0c0c0; color: #6c757d;" @endif>
                        <i class="fas fa-user-plus"></i> Send Request
                        </button>
                        <button class="btn btn-info" @if($student->id === Auth::id()) disabled style="opacity: 0.6; pointer-events: none; background-color: #dcdcdc; border-color: #c0c0c0; color: #6c757d;" @endif>
                        <i class="far fa-envelope"></i> Send Message
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-8">
               <div class="profile-card">
                  <h4 class="profile-header"><i class="fas fa-user"></i>&nbsp;User Details</h4>
                  <div class="profile-info">
                     <p><strong>Username:</strong> {{ $student->username }}</p>
                     <p><strong>Email:</strong> {{ $student->email }}</p>
                     <p><strong>Bio:</strong> {{ $student->bio ? $student->bio : "This student doesn't have a bio" }}</p>
                     <p><strong>Member Since:</strong> {{ \Carbon\Carbon::parse($student->created_at)->isoFormat('Do MMMM YYYY') }}</p>
                     <p><strong>University:</strong> {{ $student->university ? $student->university : 'Not Applicable' }}</p>
                  </div>
               </div>
            </div>
         </div>
         <div class="row justify-content-center">
            <div class="col-md-4">
               <div class="profile-card">
                  <h4 class="profile-header"><i class="fas fa-award"></i>&nbsp;Badges</h4>
                  <!-- Code for displaying badges will go here -->
                  <div class="d-flex flex-wrap justify-content-center"> 
                     <span class="badge bg-warning"><i class="fas fa-medal"></i> New User</span> 
                     <span class="badge bg-secondary"><i class="fas fa-users"></i> Joined a Society</span> 
                     <span class="badge bg-info"><i class="fas fa-edit"></i> Made a Post</span>
                     <span class="badge bg-success"><i class="far fa-comment"></i> Made a Comment</span>
                  </div>
               </div>
            </div>
            <div class="col-md-8">
               <div class="profile-card">
                  <h4 class="profile-header"><i class="fas fa-user-friends"></i>&nbsp;Friends</h4>
                  <!-- Code for displaying friends will go here -->
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
   </body>
</html>