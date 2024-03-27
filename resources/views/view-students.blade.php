<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>View Students</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <style>
         .search-input {
         border-radius: 20px;
         }
         .card {
         border: none;
         border-radius: 10px;
         background-color: #fff;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         transition: box-shadow 0.3s ease;
         }
         .card:hover {
         box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
         }
         .avatar-wrapper {
         width: 70px;
         height: 70px;
         overflow: hidden;
         border-radius: 50%;
         margin: 0 auto;
         }
         .avatar-img {
         width: 100%;
         height: auto;
         display: block;
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-5">
         <div class="row justify-content-center">
            <div class="col-md-12">
               <h3 class="text-center mb-4">Students</h3>
               <div class="alert alert-info text-center" role="alert">
                  <strong>Note:</strong> Only students from <strong>{{ $currentUserUniversity }}</strong> will be displayed here.
               </div>
               <div class="input-group mb-3">
                  <input type="text" class="form-control search-input" id="searchUsername" placeholder="Search by username" {{ count($students) > 0 ? '' : 'disabled' }}>
               </div>
               <div class="row" id="studentList">
                  @if(count($students) > 0)
                  @foreach($students as $student)
                  <div class="col-md-4 mb-4">
                     <div class="card">
                        <div class="card-body">
                           <div class="text-center mb-3">
                              <div class="avatar-wrapper">
                                 <img src="{{ $student->avatar }}" alt="Avatar" class="avatar-img">
                              </div>
                           </div>
                           <h5 class="card-title text-center">{{ $student->username }}</h5>
                           <p class="card-text text-center"><strong>Member Since:</strong> {{ \Carbon\Carbon::parse($student->created_at)->isoFormat('Do MMMM YYYY') }}</p>
                           <a href="{{ route('user.profile', ['id' => $student->id]) }}" class="btn btn-primary btn-block">
                           <i class="fas fa-user"></i> View Profile
                           </a>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @else
                  <div class="col-md-12">
                     @if($currentUserUniversity)
                     <div class="alert alert-warning text-center" role="alert">
                        No students were found from <strong>{{ $currentUserUniversity }}</strong>.
                     </div>
                     @else
                     <div class="alert alert-danger text-center" role="alert">
                        You need to join a university first before looking for other students. Please update your profile settings.
                     </div>
                     @endif
                  </div>
                  @endif
               </div>
               <div class="row justify-content-center">
                  <div class="col-md-12">
                     <nav aria-label="Page navigation example">
                        <ul class="pagination">
                           {{-- Previous Page Link --}}
                           <li class="page-item {{ $students->onFirstPage() ? 'disabled' : '' }}">
                              <a class="page-link" href="{{ $students->previousPageUrl() }}" aria-label="Previous">Previous</a>
                           </li>
                           {{-- Pagination Elements --}}
                           @for ($i = 1; $i <= $students->lastPage(); $i++)
                           <li class="page-item {{ $i === $students->currentPage() ? 'active' : '' }}">
                              <a class="page-link" href="{{ $students->url($i) }}">{{ $i }}</a>
                           </li>
                           @endfor
                           {{-- Next Page Link --}}
                           <li class="page-item {{ $students->hasMorePages() ? '' : 'disabled' }}">
                              <a class="page-link" href="{{ $students->nextPageUrl() }}" aria-label="Next">Next</a>
                           </li>
                        </ul>
                     </nav>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script src="https://kit.fontawesome.com/a076d05399.js"></script>
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script>
         $(document).ready(function(){
             var $noResultsMessage = $('<div class="col-md-12 no-results"><p class="text-center">No results found.</p></div>').hide();
             $('#studentList').append($noResultsMessage);
         
             $('#searchUsername').on('input', function(){
                 var searchUsername = $('#searchUsername').val().toLowerCase();
                 var resultsFound = false;
         
                 $('.card').each(function(){
                     var username = $(this).find('.card-title').text().toLowerCase();
         
                     if(username.includes(searchUsername)){
                         $(this).parent().show();
                         resultsFound = true;
                     } else {
                         $(this).parent().hide();
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
      @include('layouts.footer')
   </body>
</html>