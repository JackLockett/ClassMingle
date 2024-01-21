<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Societies</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    <div class="container mt-3">
         <div class="row justify-content-center">
            <div class="col-md-8">
               <h3 class="text-center">Student Societies</h3>
            </div>
         </div>

         <br>
         
         <div class="row">
         <div class="col-md-6">
            <div class="card border-info mb-3">
                  <div class="card-header">Academic Societies</div>
                  <div class="card-body text-info">
                     <h5 class="card-title">About Academic Societies</h5>
                     <p class="card-text">
                     Academic Societies provide students with a place to discuss their chosen subject field in University.
                     </p>
                     <a href="#" class="btn btn-success">Create Academic Society</a>
                  </div>
            </div>
         </div>

         <div class="col-md-6">
            <div class="card border-info mb-3">
                  <div class="card-header">Social Societies</div>
                  <div class="card-body text-info">
                     <h5 class="card-title">About Social Societies</h5>
                     <p class="card-text">
                     Social Societies provide an environment for students at university to connect and combine interests beyond academics.
                     </p>
                     <a href="#" class="btn btn-success">Create Social Society</a>
                  </div>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="card bg-light mb-3">
                  <div class="card-body">
                     <p class="card-text">There isn't any Academic Societies available at this time.</p>
                  </div>
            </div>
         </div>

         <div class="col-md-6">
            <div class="card bg-light mb-3">
                  <div class="card-body">
                     <p class="card-text">There isn't any Social Societies available at this time.</p>
                  </div>
            </div>
         </div>
      </div>

      </div>

      <script>
      document.getElementById('createButton').addEventListener('click', function() {
         window.location.href = '/create-society';
      });
      </script>
    @include('layouts.footer')

</body>
</html>
