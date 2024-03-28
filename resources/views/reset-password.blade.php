<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Reset Password</title>
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
         #reset-button {
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
                  <div class="card-header">Reset Password</div>
                  <div class="card-body">
                     <form method="POST" action="{{ route('reset-password') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                           <label for="email">Email</label>
                           <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="form-group">
                           <label for="password">New Password</label>
                           <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                           <label for="password_confirmation">Confirm Password</label>
                           <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="reset-button">Reset Password</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
   </body>
</html>