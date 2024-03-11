<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>View Students</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <style>
         .multi-collapse {
         transition: height 0.3s ease-in-out;
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-3">
         <div class="row justify-content-center">
            <div class="col-md-12">
               <h3 class="text-center">Class Mingle - Students</h3>
               <br>
               <div class="row mb-3">
                  <div class="col-md-6">
                     <input type="text" class="form-control" id="searchUsername" placeholder="Search by username">
                  </div>
                  <div class="col-md-6">
                     <input type="text" class="form-control" id="filterUniversity" placeholder="Filter by university">
                  </div>
               </div>
               <div class="row" id="studentList">
                  @if(count($students) > 0)
                  @foreach($students as $student)
                  <div class="col-md-4 mb-3 universityFilter" data-university="{{ $student->university }}">
                     <div class="card">
                        <div class="card-body">
                           <h5 class="card-title">{{ $student->username }}</h5>
                           <p class="card-text">University: {{ $student->university ? $student->university : 'Not Applicable' }}</p>
                           <a href="{{ route('user.profile', ['id' => $student->id]) }}" class="btn btn-primary">
                           <i class="fas fa-user"></i> View Profile
                           </a>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @endif
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
      <script>
         $(document).ready(function(){
             var $noResultsMessage = $('<div class="col-md-12 no-results"><p class="text-center">No results found.</p></div>').hide();
             $('#studentList').append($noResultsMessage);
         
             $('#searchUsername, #filterUniversity').on('input', function(){
                 var searchUsername = $('#searchUsername').val().toLowerCase();
                 var filterUniversity = $('#filterUniversity').val().toLowerCase();
                 
                 var resultsFound = false; 
         
                 $('.universityFilter').each(function(){
                     var username = $(this).find('.card-title').text().toLowerCase();
                     var university = $(this).data('university').toLowerCase();
         
                     if(username.includes(searchUsername) && (filterUniversity === '' || university.includes(filterUniversity))){
                         $(this).show();
                         resultsFound = true; 
                     } else {
                         $(this).hide();
                     }
                 });
         
                 if (!resultsFound) {
                     $noResultsMessage.show();
                 } else {
                     $noResultsMessage.hide();
                 }
             });
         });
      </script>
   </body>
</html>