<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Society</title>
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
                    <div class="card-header">Create Society</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" id="login-form">
                            @csrf

                            <div class="form-group row">
                            <label for="societyType" class="col-md-3 col-form-label">Society Type</label>

                            <div class="col-md-9">
                                    <select id="societyType" class="form-control @error('invalidCredentials') is-invalid @enderror" name="societyType" required>
                                        <option value="Academic" {{ old('societyType') == 'Academic' ? 'selected' : '' }}>Academic</option>
                                        <option value="Social" {{ old('societyType') == 'Social' ? 'selected' : '' }}>Social</option>
                                    </select>

                                    @error('invalidCredentials')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="societyName" class="col-md-3 col-form-label">Society Name</label>

                                <div class="col-md-9">
                                    <input id="societyName" type="text" class="form-control @error('invalidCredentials') is-invalid @enderror" name="societyName" required autocomplete="societyName">

                                    @error('invalidCredentials')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="societyDescription" class="col-md-3 col-form-label">Description</label>

                                <div class="col-md-9">
                                    <textarea id="societyDescription" class="form-control @error('invalidCredentials') is-invalid @enderror" name="societyDescription" required autocomplete="societyDescription" style="resize: none; height: 100px;"></textarea>

                                    @error('invalidCredentials')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-block btn-success" id="login-button" disabled>
                                        Request New Society
                                    </button><br>

                                    <!-- Loading Animation -->
                                    <div id="loading" class="text-center" style="display: none;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                        <p></p>
                                    </div>

                                    <hr>

                                    <a href="/societies" class="btn btn-block btn-link">
                                        Return To Societies
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
