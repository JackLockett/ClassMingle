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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
         @if ($errors->any())
         <div id="dangerAlert" class="alert alert-danger">
            {{ $errors->first() }}
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
               setTimeout(function() {
                     $('#dangerAlert').fadeOut('slow');
               }, 5000);
            });
         </script>
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
                              <th scope="col" style="text-align: right;">Action</th>
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
                     <!-- Academic Societies Page navigation -->
                     <nav aria-label="Academic Societies Page navigation">
                        <ul class="pagination">
                           <!-- Previous Button -->
                           <li class="page-item {{ $academicSocieties->previousPageUrl() ? '' : 'disabled' }}">
                              <a class="page-link" href="{{ $academicSocieties->previousPageUrl() }}">Previous</a>
                           </li>
                           <!-- Page Numbers -->
                           @for($i = 1; $i <= $academicSocieties->lastPage(); $i++)
                           <li class="page-item {{ $i == $academicSocieties->currentPage() ? 'active' : '' }}">
                              <a class="page-link" href="{{ $academicSocieties->url($i) }}">{{ $i }}</a>
                           </li>
                           @endfor
                           <!-- Next Button -->
                           <li class="page-item {{ $academicSocieties->nextPageUrl() ? '' : 'disabled' }}">
                              <a class="page-link" href="{{ $academicSocieties->nextPageUrl() }}">Next</a>
                           </li>
                        </ul>
                     </nav>
                     @else
                     <p class="card-text">There aren't any Academic Societies available at this time.</p>
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
                     <!-- Social Societies Page navigation -->
                     <nav aria-label="Social Societies Page navigation">
                        <ul class="pagination">
                           <!-- Previous Button -->
                           <li class="page-item {{ $socialSocieties->previousPageUrl() ? '' : 'disabled' }}">
                              <a class="page-link" href="{{ $socialSocieties->previousPageUrl() }}">Previous</a>
                           </li>
                           <!-- Page Numbers -->
                           @for($i = 1; $i <= $socialSocieties->lastPage(); $i++)
                           <li class="page-item {{ $i == $socialSocieties->currentPage() ? 'active' : '' }}">
                              <a class="page-link" href="{{ $socialSocieties->url($i) }}">{{ $i }}</a>
                           </li>
                           @endfor
                           <!-- Next Button -->
                           <li class="page-item {{ $socialSocieties->nextPageUrl() ? '' : 'disabled' }}">
                              <a class="page-link" href="{{ $socialSocieties->nextPageUrl() }}">Next</a>
                           </li>
                        </ul>
                     </nav>
                     </nav>
                     </nav>
                     </nav>
                     </nav>
                     @else
                     <p class="card-text">There aren't any Social Societies available at this time.</p>
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
                           <option value="">Select a Subject</option>
                           @foreach($subjects as $subject)
                           <option value="{{ $subject }}">{{ $subject }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="form-group">
                        <label for="academicSocietyDescription">Description:</label>
                        <textarea id="academicSocietyDescription" class="form-control" name="academicSocietyDescription" maxlength="250" required autocomplete="societyDescription" style="resize: none; height: 125px;"></textarea>
                     </div>
                     <div class="form-group text-muted">
                        Characters remaining: <span id="characterCountAcademicSociety"></span>
                     </div>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">
                     <i class="fas fa-arrow-left"></i> Return
                     </button>
                     <button type="submit" id="submitAcademicSocietyBtn" class="btn btn-success">
                     <i class="fas fa-check"></i> Request New Academic Society
                     </button>
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
                        <div class="input-group">
                           <input type="text" class="form-control" id="societyName" name="societyName" maxlength="20" required>
                           <div class="input-group-append">
                              <span class="input-group-text" id="basic-addon2">Society</span>
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="socialSocietyDescription">Description:</label>
                        <textarea id="socialSocietyDescription" class="form-control" name="socialSocietyDescription" maxlength="250" required autocomplete="socialSocietyDescription" style="resize: none; height: 125px;"></textarea>
                     </div>
                     <div class="form-group text-muted">
                        Characters remaining: <span id="characterCountSocialSociety"></span>
                     </div>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">
                     <i class="fas fa-arrow-left"></i> Return
                     </button>
                     <button type="submit" id="submitSocialSocietyBtn" class="btn btn-success">
                     <i class="fas fa-check"></i> Request New Social Society
                     </button>
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
      <script>
         // Function to update character count and disable button if input is empty (for social society description)
         function updateSocialSocietyCharacterCount() {
             var commentLength = $('#socialSocietyDescription').val().length;
             var totalLength = commentLength;
             var remainingCharacters = 250 - totalLength;
         
             $('#characterCountSocialSociety').text(remainingCharacters);
         
             if (totalLength > 0) {
                 $('#submitSocialSocietyBtn').prop('disabled', false);
             } else {
                 $('#submitSocialSocietyBtn').prop('disabled', true);
             }
         }
         
         // Update character count on input change (for social society description)
         $('#socialSocietyDescription').on('input', function() {
             updateSocialSocietyCharacterCount();
         });
         
         // Initialize character count on document ready (for social society description)
         $(document).ready(function() {
             updateSocialSocietyCharacterCount();
         });
         
         // Function to update character count and disable button if input is empty (for academic society description)
         function updateAcademicSocietyCharacterCount() {
             var commentLength = $('#academicSocietyDescription').val().length;
             var totalLength = commentLength;
             var remainingCharacters = 250 - totalLength;
         
             $('#characterCountAcademicSociety').text(remainingCharacters);
         
             if (totalLength > 0) {
                 $('#submitAcademicSocietyBtn').prop('disabled', false);
             } else {
                 $('#submitAcademicSocietyBtn').prop('disabled', true);
             }
         }
         
         // Update character count on input change (for academic society description)
         $('#academicSocietyDescription').on('input', function() {
             updateAcademicSocietyCharacterCount();
         });
         
         // Initialize character count on document ready (for academic society description)
         $(document).ready(function() {
             updateAcademicSocietyCharacterCount();
         });
      </script>
      @include('layouts.footer')
   </body>
</html>