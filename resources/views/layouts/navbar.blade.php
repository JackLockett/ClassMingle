<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Include Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Include Bootstrap JavaScript -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
      <style>
         @font-face {
         font-family: 'Luckiest Guy Pro';
         src: url('{{ asset('fonts/LuckiestGuy-Regular.ttf') }}') format('truetype');
         }
         @font-face {
         font-family: 'Impress';
         src: url('{{ asset('fonts/Impress.ttf') }}') format('truetype');
         }
         body {
         margin: 0;
         padding: 0;
         display: flex;
         flex-direction: column;
         min-height: 100vh;
         background-color: #f3f3f3;
         }
         .container {
         max-width: 1200px;
         margin: 0 auto;
         padding: 20px;
         }
         .banner {
         background-color: darkred;
         color: white;
         text-align: center;
         font-family: 'Impress', sans-serif;
         font-size: 18px;
         }
         .navbar {
         background-color: #534876;
         color: #f0f3ff;
         padding: 10px 0;
         border-bottom: 1px solid #2d2c4e;
         }
         .navbar-title {
         font-size: 28px;
         font-family: 'Luckiest Guy Pro', sans-serif;
         text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.5);
         }
         .navbar-toggler {
         display: none;
         }
         /* Show the burger menu on smaller screens */
         @media (max-width: 768px) {
         .navbar-toggler {
         display: block;
         }
         .myaccount {
         display: none;
         }
         }
         .navbar a {
         color: #f0f3ff;
         text-decoration: none;
         margin: 0 15px;
         font-size: 18px;
         font-family: 'Impress', sans-serif;
         }
         .navbar-flex {
         display: flex;
         justify-content: space-between;
         align-items: center;
         }
         .dropdown-menu a {
         color: #000; /* Change this to your desired color */
         }
         .content {
         flex: 1;
         }
      </style>
   </head>
   <body>
      <div class="navbar">
         <div class="container navbar-flex">
            <span class="navbar-title">Class Mingle</span>
            <div>
               <!-- Button for the collapsible menu -->
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-links"
                  aria-controls="navbar-links" aria-expanded="false" aria-label="Toggle navigation">
               <i class="fas fa-bars" style="color: white;"></i>
               </button>
               <!-- Container for buttons and navigation links -->
               @if(Auth::check())
               <div class="d-none d-md-inline-block">
                  <a href="/societies" class="d-none d-md-inline-block"><i class="fas fa-users"></i> Societies</a>
                  <a href="#" class="d-none d-md-inline-block"><i class="fas fa-question-circle"></i> FAQ</a>
               </div>
               @else
               <div class="d-none d-md-inline-block">
                  <a href="#" class="d-none d-md-inline-block"><i class="fas fa-question-circle"></i> FAQ</a>
               </div>
               @endif
               @if(Auth::check())
               <div class="btn-group">
                  <button type="button" class="myaccount btn btn-primary dropdown-toggle" data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false">
                  My Account
                  </button>
                  <div class="dropdown-menu">
                     <h6 class="dropdown-header">Profile</h6>
                     <button type="button" class="dropdown-item" onclick="redirectToProfile()">
                     <i class="fas fa-eye"></i> View Profile
                     </button>
                     <div class="dropdown-divider"></div>
                     <button type="button" class="dropdown-item" onclick="redirectToAccount()">
                     <i class="/account"></i> My Account
                     </button>
                     <button id="logout-button" type="submit" class="dropdown-item">
                     <i class="fas fa-sign-out-alt"></i> Log Out
                     </button>
                  </div>
               </div>
               @else
               <a id="signInInline" href="/login" class="btn btn-primary d-none d-md-inline-block"><i
                  class="fas fa-sign-in-alt"></i> Sign In</a>
               @endif
            </div>
         </div>
         <!-- Collapsible menu for smaller screens -->
         <div class="collapse navbar-collapse" id="navbar-links">
            <div class="container">
               <ul class="navbar-nav">
                  <li class="nav-item">
                     <a href="/" class="nav-link"><i class="fas fa-home"></i> Home</a>
                  </li>
                  <li class="nav-item">
                     <a href="/toonplatoon" class="nav-link"><i class="fas fa-users"></i> Toon Platoon</a>
                  </li>
                  <li class="nav-item">
                     <a href="#" class="nav-link"><i class="fas fa-question-circle"></i> FAQ</a>
                  </li>
               </ul>
               @if(Auth::check())
               <div class="btn-group">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false">
                  My Account
                  </button>
                  <div class="dropdown-menu">
                     <h6 class="dropdown-header">Profile</h6>
                     <button type="button" class="dropdown-item" onclick="redirectToProfile()">
                     <i class="fas fa-eye"></i> View Profile
                     </button>
                     <button type="button" class="dropdown-item" onclick="redirectToDashboard()">
                     <i class="fas fa-chart-line"></i> Dashboard
                     </button>
                     <div class="dropdown-divider"></div>
                     <button type="button" class="dropdown-item" onclick="redirectToAccount()">
                     <i class=""></i> My Account
                     </button>
                     <button id="logout-button" type="submit" class="dropdown-item">
                     <i class="fas fa-sign-out-alt"></i> Log Out
                     </button>
                  </div>
               </div>
               @else
               <a id="signInInline" href="/login" class="btn btn-primary d-none d-md-inline-block"><i
                  class="fas fa-sign-in-alt"></i> Sign In</a>
               @endif
            </div>
         </div>
      </div>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none d-md-inline-block">
         @csrf
      </form>
      <script>
         document.addEventListener('DOMContentLoaded', function () {
             var logoutButton = document.getElementById('logout-button');
             var logoutForm = document.getElementById('logout-form');
             var navbarLinks = document.getElementById('navbar-links'); // Get the collapsible menu div
         
             if (logoutButton && logoutForm) {
                 logoutButton.addEventListener('click', function () {
                     logoutForm.submit();
                 });
             }
         
             // Function to close the navbar if it's open when the screen size becomes larger
             function closeNavbarOnLargeScreen() {
                 if (window.innerWidth > 768) { // Check if screen size is larger than 768px (adjust as needed)
                     if (navbarLinks.classList.contains('show')) {
                         navbarLinks.classList.remove('show'); // Close the navbar if it's open
                     }
                 }
             }
         
             // Add a resize event listener to the window
             window.addEventListener('resize', closeNavbarOnLargeScreen);
         
             // Call the function on page load
             closeNavbarOnLargeScreen();
         });
         
         function redirectToDashboard() {
             window.location.href = "/dashboard";
         };
         
         function redirectToAccount() {
             window.location.href = "/account";
         };

         function redirectToProfile() {
             window.location.href = "/profile";
         };

      </script>
   </body>
</html>