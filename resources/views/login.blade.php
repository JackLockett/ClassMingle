<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    @include('layouts.navbar')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" id="login-form">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label">Email</label>

                                <div class="col-md-9">
                                    <input id="email" type="email" class="form-control @error('invalidCredentials') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-3 col-form-label">Password</label>

                                <div class="col-md-9">
                                    <input id="password" type="password" class="form-control @error('invalidCredentials') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('invalidCredentials')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 text-md-right">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-block btn-primary" id="login-button" disabled>
                                        Login
                                    </button><br>

                                    <!-- Loading Animation -->
                                    <div id="loading" class="text-center" style="display: none;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <p></p>
                                    </div>

                                    <hr>

                                    <a href="/register" class="btn btn-block btn-link">
                                        Don't Have An Account?
                                    </a>

                                    <a class="btn btn-block btn-link">
                                        Forgot Your Password?
                                    </a>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

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


@include('layouts.footer')
    
</body>
</html>
