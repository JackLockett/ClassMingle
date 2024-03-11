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
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-3">
         <div class="row justify-content-center">
            <div class="col-md-8">
               <h3 class="text-center">Class Mingle - Societies</h3>
            </div>
         </div>
         <br>
         @if(session('success'))
         <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <script>
            $(document).ready(function(){
                  setTimeout(function(){
                     $("#success-alert").alert('close');
                  }, 10000);
            });
         </script>
         @endif
         <div class="row">
            <div class="col-md-6">
               <div class="card border-primary mb-3">
                  <div class="card-header">Academic Societies</div>
                  <div class="card-body text-primary">
                     <i>
                        <h5 class="card-title">About Academic Societies</h5>
                     </i>
                     <p class="card-text">
                        Academic Societies provide students with a place to discuss their chosen subject field in University.
                     </p>
                     <a href="#" class="btn btn-success" data-toggle="modal" data-target="#createAcademicModal">
                     <i class="fas fa-plus-circle"></i> Create Academic Society
                     </a>
                  </div>
               </div>
            </div>
            <div class="col-md-6">
               <div class="card border-primary mb-3">
                  <div class="card-header">Social Societies</div>
                  <div class="card-body text-primary">
                     <i>
                        <h5 class="card-title">About Social Societies</h5>
                     </i>
                     <p class="card-text">
                        Social Societies provide an environment for students at university to connect and combine interests beyond academics.
                     </p>
                     <a href="#" class="btn btn-success" data-toggle="modal" data-target="#createSocialModal">
                     <i class="fas fa-plus-circle"></i> Create Social Society
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6">
               <div class="card bg-light mb-3">
                  <div class="card-body">
                     <h5 class="card-title">Academic Societies</h5>
                     @if(count($academicSocieties) > 0)
                     <table class="table">
                        <thead>
                           <tr>
                              <th scope="col">Society Name</th>
                              <th scope="col" style="text-align: right;">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($academicSocieties as $society)
                           <tr>
                              <td>{{ $society->societyName }}</td>
                              <td style="text-align: right;">
                                 <a href="{{ route('view-society', ['id' => $society->id]) }}" class="btn btn-secondary">
                                 <i class="fas fa-eye"></i> View Society
                                 </a>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                     @else
                     <p class="card-text">There isn't any Academic Societies available at this time.</p>
                     @endif
                  </div>
               </div>
            </div>
            <div class="col-md-6">
               <div class="card bg-light mb-3">
                  <div class="card-body">
                     <h5 class="card-title">Social Societies</h5>
                     @if(count($socialSocieties) > 0)
                     <table class="table">
                        <thead>
                           <tr>
                              <th scope="col">Society Name</th>
                              <th scope="col" style="text-align: right;">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($socialSocieties as $society)
                           <tr>
                              <td>{{ $society->societyName }}</td>
                              <td style="text-align: right;">
                                 <a href="{{ route('view-society', ['id' => $society->id]) }}" class="btn btn-secondary">
                                 <i class="fas fa-eye"></i> View Society
                                 </a>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                     @else
                     <p class="card-text">There isn't any Social Societies available at this time.</p>
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Create Academic Society -->
      <div class="modal fade" id="createAcademicModal" tabindex="-1" role="dialog" aria-labelledby="createAcademicModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="createAcademicModalLabel">Create Academic Society</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="alert alert-info" role="alert">
                     Your academic society will need approval from an Administrator before being displayed to the public.
                  </div>
                  <form id="createAcademicForm" action="{{ route('create-society') }}" method="POST">
                     @csrf
                     <div class="form-group">
                        <input type="hidden" id="societyType" name="societyType" value="Academic">
                        <label for="subjectList">Subject:</label>
                        <select id="subjectList" class="form-control" name="subjectList" required>
                           <option value="Computer Science">Computer Science</option>
                           <option value="Computing">Computing</option>
                        </select>
                     </div>
                     <div class="form-group">
                        <label for="societyDescription">Description:</label>
                        <textarea id="societyDescription" class="form-control" name="societyDescription" required autocomplete="societyDescription" style="resize: none; height: 150px;"></textarea>
                     </div>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Return</button>
                     <button type="submit" class="btn btn-success">Request New Academic Society</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Create Social Society -->
      <div class="modal fade" id="createSocialModal" tabindex="-1" role="dialog" aria-labelledby="createSocialModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="createSocialModalLabel">Create Social Society</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="alert alert-info" role="alert">
                     Your social society will need approval from an Administrator before being displayed to the public.
                  </div>
                  <form id="createSocialForm" action="{{ route('create-society') }}" method="POST">
                     @csrf
                     <div class="form-group">
                        <input id="societyType" name="societyType" value="Social" type="hidden">
                        <label for="societyName">Society Name:</label>
                        <input type="text" class="form-control" id="societyName" name="societyName" required>
                     </div>
                     <div class="form-group">
                        <label for="societyDescription">Description:</label>
                        <textarea id="societyDescription" class="form-control" name="societyDescription" required autocomplete="societyDescription" style="resize: none; height: 150px;"></textarea>
                     </div>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Return</button>
                     <button type="submit" class="btn btn-success">Request New Social Society</button>
                  </form>
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