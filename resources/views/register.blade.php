<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Register</title>
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
         #register-button {
         border-radius: 25px;
         }
         #loading {
         margin-top: 20px;
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
               <div class="card">
                  <div class="card-header">Register</div>
                  <div class="card-body">
                     <form method="POST" action="{{ route('register') }}" id="register-form">
                        @csrf
                        <div class="form-group row">
                           <label for="email" class="col-md-3 col-form-label">Email</label>
                           <div class="col-md-9">
                              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" maxlength="32" name="email" value="{{ old('email') }}" required autocomplete="email">
                              @error('email')
                              <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                        </div>
                        <div class="form-group row">
                           <label for="username" class="col-md-3 col-form-label">Username</label>
                           <div class="input-group col-md-9">
                              <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" maxlength="24" value="{{ old('username') }}" required autocomplete="username">
                              <div class="input-group-append">
                                 <button type="button" id="generateButton" class="btn btn-outline-success" onclick="generateUsername()">Generate</button>
                              </div>
                              @error('username')
                              <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                        </div>
                        <div class="form-group row">
                           <label for="university" class="col-md-3 col-form-label">University</label>
                           <div class="input-group col-md-9">
                              <select class="form-control" id="university" name="university" required>
                                 <option value="">Select University</option>
                                 @foreach($ukUniversities as $uni)
                                 <option value="{{ $uni }}">{{ $uni }}</option>
                                 @endforeach
                              </select>
                              @error('university')
                              <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                        </div>
                        <div class="form-group row">
                           <label for="password" class="col-md-3 col-form-label">Password</label>
                           <div class="col-md-9">
                              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" maxlength="32" name="password" required autocomplete="current-password">
                              @error('password')
                              <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                        </div>
                        <div class="form-group row mb-0">
                           <div class="col-md-12">
                              <button type="submit" id="register-button" class="btn btn-block btn-success">
                              Register
                              </button>
                              <div id="loading" class="text-center" style="display: none;">
                                 <div class="spinner-border text-success" role="status">
                                    <span class="sr-only"></span>
                                 </div>
                              </div>
                              <hr>
                              <a href="/login" class="btn btn-block btn-link">
                              Already Have An Account?
                              </a>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
      <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
      <script>
         function generateUsername() {
             var generateButton = document.getElementById('generateButton');
             generateButton.disabled = true;
         
             var usernameInput = document.getElementById('username');
         
             function getRandomElementFromArray(array) {
                 return array[Math.floor(Math.random() * array.length)];
             }
         
             function getRandomUsername() {
               return fetch('words.json')
                  .then(response => response.json())
                  .then(data => {
                        var words = data;
                        var randomAdjective = getRandomElementFromArray(words.adjectives);
                        var randomNoun = getRandomElementFromArray(words.nouns);
                        var randomNumber = Math.floor(Math.random() * 1000);
                        return randomAdjective + '_' + randomNoun + '_' + randomNumber;
                  });
            }
         
            function getRandomElementFromArray(arr) {
               return arr[Math.floor(Math.random() * arr.length)];
            }
         
             function setGeneratedUsername(username) {
                 usernameInput.value = username;
                 enableGenerateButton();
             }
         
             function enableGenerateButton() {
                 generateButton.disabled = false;
             }
         
             getRandomUsername()
                 .then(username => {
                     setGeneratedUsername(username);
                     return username;
                 })
                 .then(username => checkUsernameAvailability(username));
         }
         
         function checkUsernameAvailability(username) {
             fetch('/check-username-availability/' + username)
                 .then(response => response.json())
                 .then(data => handleUsernameAvailability(data.isAvailable, username))
                 .catch(error => console.error('Error checking username availability:', error));
         }
         
         function handleUsernameAvailability(isAvailable, username) {
             if (!isAvailable) {
                 generateUsername(); // If not available, regenerate the username
             }
         }
      </script>
      <script>
         document.addEventListener("DOMContentLoaded", function () {
             const registerForm = document.getElementById("register-form");
             const loading = document.getElementById("loading");
             const registerButton = document.getElementById("register-button");
         
             // Enable the login button on page load
             registerButton.disabled = false;
         
             registerForm.addEventListener("submit", function () {
                 loading.style.display = "block"; // Show loading animation
                 registerButton.disabled = true; // Disable the login button
             });
         });
      </script>
   </body>
</html>