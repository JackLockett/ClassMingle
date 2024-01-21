<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
@include('layouts.navbar')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    
                    <div class="card-header">Register</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label">Email</label>

                                <div class="col-md-9">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

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
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username">
                                    <div class="input-group-append">
                                        <button type="button" id="generateButton" class="btn btn-outline-success" onclick="generateUsername()">Generate</button>
                                    </div>
                                    @error('username')
                                        <!-- ... (your existing error handling) ... -->
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-3 col-form-label">Password</label>

                                <div class="col-md-9">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
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
                                            Automatically Login
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-block btn-success">
                                        Register
                                    </button>

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

    <script>
    function generateUsername() {
        var generateButton = document.getElementById('generateButton');
        generateButton.disabled = true;

        var usernameInput = document.getElementById('username');
        var usernameError = document.getElementById('username-error');

        function getRandomElementFromArray(array) {
            return array[Math.floor(Math.random() * array.length)];
        }

        function getRandomUsername() {
            var words;
            fetch('words.json')
                .then(response => response.json())
                .then(data => {
                    words = data;
                    var randomAdjective1 = getRandomElementFromArray(words.adjectives);
                    var randomAdjective2 = getRandomElementFromArray(words.adjectives.filter(adj => adj !== randomAdjective1));
                    var randomNoun = getRandomElementFromArray(words.nouns);
                    var generatedUsername = randomAdjective1 + '_' + randomAdjective2 + '_' + randomNoun;
                    setGeneratedUsername(generatedUsername);
                })
                .catch(error => console.error('Error loading words.json:', error));
        }

        function setGeneratedUsername(username) {
            usernameInput.value = username;
            enableGenerateButton();
        }

        function enableGenerateButton() {
            setTimeout(function () {
                generateButton.disabled = false;
            }, 1000); 
        }

        function handleUsernameAvailability(isAvailable) {
            if (isAvailable) {
                setGeneratedUsername(generatedUsername);
            }
        }

        var generatedUsername = getRandomUsername();
        checkUsernameAvailability(generatedUsername, handleUsernameAvailability);
    }

    function checkUsernameAvailability(username, callback) {
        fetch('/check-username-availability/' + username)
            .then(response => response.json())
            .then(data => callback(data.isAvailable))
            .catch(() => callback(false));
    }
    </script>

    @include('layouts.footer')
</body>
</html>
