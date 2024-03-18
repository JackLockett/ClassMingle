<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin Panel</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-5">
         <h2 class="text-center mb-4">
            <i class="fas fa-user-shield"></i> Admin Panel
         </h2>
         @if (session('success'))
         <div id="successAlert" class="alert alert-success alert-dismissible fade show animate__animated animate__fadeOutUp" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         @endif
         <script>
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(function() {
                    $('#successAlert').alert('close');
                }, 5000);
            });
         </script>
         <!-- Bootstrap Pills -->
         <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" id="pills-users-tab" data-toggle="pill" href="#pills-users" role="tab" aria-controls="pills-users" aria-selected="true">View Users</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" id="pills-societies-tab" data-toggle="pill" href="#pills-societies" role="tab" aria-controls="pills-societies" aria-selected="false">Active Societies</a>
            </li>
         </ul>
         <!-- Tab Content -->
         <div class="tab-content" id="pills-tabContent">
            <!-- Users Tab -->
            <div class="tab-pane fade show active" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
               <div class="row">
                  <!-- Filter by University -->
                  <div class="col-md-6 mb-3">
                     <select class="form-control" id="universityFilter">
                        <option value="">All Universities</option>
                        @foreach($ukUniversities as $university)
                        <option value="{{ $university }}">{{ $university }}</option>
                        @endforeach
                     </select>
                  </div>
                  <!-- Search by Username -->
                  <div class="col-md-6 mb-3 d-flex flex-column flex-md-row">
                     <input class="form-control mr-sm-2 mb-2 mb-md-0" type="search" placeholder="Search by Username" aria-label="Search" id="searchInput">
                     <button class="btn btn-outline-primary my-2 my-md-0 ml-md-2" type="button" id="searchButton">Search</button>
                  </div>
                  <!-- User Cards -->
                  @foreach($users as $user)
                  <div class="col-md-6">
                     <div class="card mb-3">
                        <div class="card-body">
                           <h5 class="card-title">{{ $user->username }}</h5>
                           <p class="card-text">University: {{ $user->university ? $user->university : 'Not Applicable' }}</p>
                           <button class="btn btn-primary view-details-button" data-toggle="modal" data-target="#userDetailsModal{{ $user->id }}">
                           <i class="fas fa-info-circle"></i> View Details
                           </button>
                        </div>
                     </div>
                  </div>
                  <!-- User Details Modal -->
                  <div class="modal fade" id="userDetailsModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel{{ $user->id }}" aria-hidden="true">
                     <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                              <div class="d-flex align-items-center">
                                 <img src="{{ $user->avatar }}" class="rounded-circle mr-3" alt="Profile Picture" style="width: 100px; height: 100px; object-fit: cover;">
                                 <h5 class="modal-title" id="userDetailsModalLabel{{ $user->id }}">User Details</h5>
                              </div>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                           </div>
                           <div class="modal-body">
                              <!-- Form for updating user details -->
                              <form action="{{ route('update-user', ['id' => $user->id]) }}" method="POST">
                                 @csrf
                                 <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email{{ $user->id }}" value="{{ $user->email }}" readonly>
                                 </div>
                                 <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="username{{ $user->id }}" value="{{ $user->username }}" readonly>
                                 </div>
                                 <div class="form-group">
                                    <label for="university">University:</label>
                                    <input readonly type="text" class="form-control" id="university{{ $user->id }}" value="{{ $user->university }}">
                                 </div>
                                 <div class="form-group">
                                    <label for="bio">Bio:</label>
                                    <textarea class="form-control" name="bio" id="bio{{ $user->id }}">{{ $user->bio }}</textarea>
                                 </div>
                                 <div class="form-group">
                                    <label for="role">Role:</label>
                                    <select class="form-control" name="role" id="role{{ $user->id }}">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                 </div>
                                 <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger" id="deleteAccount{{ $user->id }}"><i class="fas fa-trash"></i> Delete Account</button>
                                    <div>
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                       <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Changes</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
            <!-- Societies Tab -->
            <div class="tab-pane fade" id="pills-societies" role="tabpanel" aria-labelledby="pills-societies-tab">
               <div class="row">
                  <div class="col-md-6">
                     <div class="card">
                        <div class="card-body">
                           <h5 class="card-title">View Societies</h5>
                           <p class="card-text">Browse existing societies.</p>
                           <a href="/admin/view-societies" class="btn btn-primary">Go to Societies</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script>
         $(document).ready(function() {
         
             $('.view-details-button').click(function() {
                 var userId = $(this).data('userid');
                 $('#userDetailsModal' + userId).modal('show');
             });
         });
         
             document.getElementById('searchButton').addEventListener('click', function() {
                 var universityFilter = document.getElementById('universityFilter').value;
                 var searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
                 var userCards = document.querySelectorAll('.card');
                 userCards.forEach(function(card) {
                     var cardTitleElement = card.querySelector('.card-body .card-title');
                     var cardUniversityElement = card.querySelector('.card-body .card-text:nth-of-type(1)');
                     if (cardTitleElement && cardUniversityElement) {
                         var cardTitle = cardTitleElement.textContent.toLowerCase();
                         var cardUniversity = cardUniversityElement.textContent.trim().toLowerCase();
                         var universityMatches = universityFilter === '' || cardUniversity.includes(universityFilter.toLowerCase());
                         var searchTermMatches = cardTitle.includes(searchTerm);
                         if (universityMatches && searchTermMatches) {
                             card.closest('.col-md-6').style.display = 'block';
                         } else {
                             card.closest('.col-md-6').style.display = 'none';
                         }
                     } else {
                         console.error("Card title or university element not found!");
                     }
                 });
             });
      </script>
   </body>
</html>