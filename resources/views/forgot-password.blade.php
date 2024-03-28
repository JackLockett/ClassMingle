<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Forgot Password</title>
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
         #send-link-button {
         border-radius: 25px;
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-5">
         <div class="row justify-content-center">
            <div class="col-md-6">
               <div class="card">
                  <div class="card-header">Forgot Password</div>
                  <div class="card-body">
                     @if (session('success'))
                     <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                     </div>
                     @endif
                     <form method="POST" action="{{ url('/forgot-password') }}">
                        @csrf
                        <div class="form-group">
                           <label for="email">Email Address</label>
                           <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                           @error('email')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="send-link-button">Send Password Reset Link</button>
                        <hr>
                        <a href="/login" class="btn btn-link btn-block forgot-password">Return To Login</a>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
   </body>
</html>