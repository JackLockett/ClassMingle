<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Class Mingle</title>
      <!-- Bootstrap CSS -->
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <!-- Custom CSS for rounded borders and image background -->
      <style>
         /* Add some styles for better visual presentation */
         body {
         background-color: #f8f9fa; /* Light gray background */
         }
         .card {
         border: none;
         border-radius: 10px;
         margin: 20px;
         box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
         transition: 0.3s;
         }
         .card-body {
         padding: 30px;
         }
         .card-title {
         font-size: 36px;
         font-weight: bold;
         margin-bottom: 30px;
         color: #007bff; /* Blue color for title */
         }
         .card-text {
         font-size: 18px;
         line-height: 1.6;
         color: #495057; /* Dark gray color for text */
         }
         .btn-primary {
         background-color: #007bff;
         border-color: #007bff;
         }
         .btn-primary:hover {
         background-color: #0056b3; /* Darker blue on hover */
         border-color: #0056b3;
         }
         /* Adjust margins and paddings */
         .mt-5 {
         margin-top: 50px;
         }
         .mb-5 {
         margin-bottom: 50px;
         }
         .pt-5 {
         padding-top: 50px;
         }
         .pb-5 {
         padding-bottom: 50px;
         }
      </style>
   </head>
   <body>
      <!-- Navigation Bar -->
      @include('layouts.navbar')
      <!-- Main Content -->
      <!-- Main Content -->
      <div class="container mt-5">
         <div class="row justify-content-center">
            <div class="col-md-8 text-center">
               <h1 class="display-4">Welcome to Class Mingle</h1>
               <p class="lead">Your go-to social media platform for university students!</p>
            </div>
         </div>
         <div class="card">
            <div class="row no-gutters">
               <div class="col-md-12">
                  <div class="card-body">
                     <h2 class="card-title">Welcome!</h2>
                     <p class="card-text">
                        @if (auth()->check())
                        @php
                        $currentTime = date('H');
                        $greeting = '';
                        if ($currentTime >= 5 && $currentTime < 12) {
                        $greeting = 'Good morning';
                        } elseif ($currentTime >= 12 && $currentTime < 18) {
                        $greeting = 'Good afternoon';
                        } else {
                        $greeting = 'Good evening';
                        }
                        @endphp
                        {{ $greeting }}{{ auth()->user()->name }}! You are currently logged in as <strong>{{ auth()->user()->email }}</strong>. Explore various features of Class Mingle, such as creating and joining societies, participating in discussions, and discovering events tailored to your interests. Connect with classmates, share insights, and make the most out of your university experience.
                        @else
                        Class Mingle is a vibrant community where university students can connect, engage, and share experiences. Whether you're seeking academic support, looking for social events, or simply want to connect with like-minded individuals, Class Mingle has everything you need to enhance your university experience.
                        @endif
                     </p>
                     @if (!auth()->check())
                     <a href="/login" class="btn btn-primary btn-lg">Join Now!</a>
                     @endif
                  </div>
               </div>
            </div>
         </div>
         <!-- Section for FAQ -->
         <div class="text-center mt-4">
            <p>Check out our FAQ for more information: <a href="/faq">FAQ</a></p>
         </div>
      </div>
      <!-- Footer -->
      @include('layouts.footer')
      <!-- Bootstrap JS and jQuery -->
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   </body>
</html>