<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Login</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <style>
         .card {
         border-radius: 15px;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         }
         .form-group {
         margin-bottom: 20px;
         padding: 5px;
         }
         #login-button {
         border-radius: 25px;
         }
         #loading {
         margin-top: 20px;
         }
         .forgot-password {
         margin-top: 10px;
         }
         @media (max-width: 576px) {
         .col-md-6 {
         max-width: 100%;
         }
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-5">
         <div class="row justify-content-center">
            <div class="col-md-6">
               @if ($errors->has('notVerified'))
               <div class="alert alert-danger">
                  {{ $errors->first('notVerified') }}
                  <a href="{{ route('resend-verification-email') }}">Click here</a> to resend verification email.
               </div>
               @endif
               @if (session('success'))
               <div id="successAlert" class="alert alert-success alert-dismissible fade show animate__animated" role="alert">
                  {{ session('success') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               @endif
               <script>
                  document.addEventListener("DOMContentLoaded", function() {
                     setTimeout(function() {
                           $('#successAlert').fadeOut('slow');
                     }, 5000);
                  });
               </script>
               <div class="card">
                  <div class="card-header">Login</div>
                  <div class="card-body">
                     <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf
                        <div class="form-group">
                           <label for="email">Email</label>
                           <input id="email" type="email" class="form-control @error('invalidCredentials') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        </div>
                        <div class="form-group">
                           <label for="password">Password</label>
                           <input id="password" type="password" class="form-control @error('invalidCredentials') is-invalid @enderror" name="password" required autocomplete="current-password">
                           @error('invalidCredentials')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="login-button">Login</button>
                        <!-- Loading Animation -->
                        <div id="loading" class="text-center" style="display: none;">
                           <div class="spinner-border text-primary" role="status">
                              <span class="sr-only"></span>
                           </div>
                        </div>
                     </form>
                     <hr>
                     <a href="/register" class="btn btn-link btn-block">Don't Have An Account?</a>
                     <a href="/forgot-password" class="btn btn-link btn-block forgot-password">Forgot Your Password?</a>
                     <a href="/resend-verification-email" class="btn btn-link btn-block forgot-password">Resend Verification Email</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script>
         document.addEventListener("DOMContentLoaded", function () {
             const loginForm = document.getElementById("login-form");
             const loading = document.getElementById("loading");
             const loginButton = document.getElementById("login-button");
         
             // Enable the login button on page load
             loginButton.disabled = false;
         
             loginForm.addEventListener("submit", function () {
                 loading.style.display = "block"; // Show loading animation
                 loginButton.disabled = true; // Disable the login button
             });
         });
      </script>
   </body>
</html>