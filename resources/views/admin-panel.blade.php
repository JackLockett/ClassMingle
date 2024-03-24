<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin Panel</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <meta name="csrf-token" content="{{ csrf_token() }}">
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-5">
         <h2 class="text-center mb-4">
            <i class="fas fa-user-shield"></i> Admin Panel
         </h2>
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
            <hr>
            <li class="nav-item">
               <a class="nav-link" id="pills-society-queries-tab" data-toggle="pill" href="#pills-society-queries" role="tab" aria-controls="pills-society-queries" aria-selected="false">Society Queries</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" id="pills-approve-societies-tab" data-toggle="pill" href="#pills-approve-societies" role="tab" aria-controls="pills-approve-societies" aria-selected="false">Pending Societies</a>
            </li>
         </ul>
         <!-- Tab Content -->
         <div class="tab-content" id="pills-tabContent">
            <!-- Users Tab -->
            <div class="tab-pane fade show active" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
               <div class="row">
                  <div class="col-md-6 mb-3">
                     <select class="form-control" id="universityFilter">
                        <option value="">All Universities</option>
                        @foreach($ukUniversities as $university)
                        <option value="{{ $university }}">{{ $university }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6 mb-3 d-flex flex-column flex-md-row">
                     <input class="form-control mr-sm-2 mb-2 mb-md-0" type="search" placeholder="Search by Username" aria-label="Search" id="searchInput">
                     <button class="btn btn-outline-primary my-2 my-md-0 ml-md-2" type="button" id="searchButton">Search</button>
                  </div>
                  @if(count($users) > 0)
                  @foreach($users as $user)
                  <div class="col-md-6">
                     <div class="card mb-3">
                        <div class="card-body">
                           <h5 class="card-title">{{ $user->username }}</h5>
                           <p class="card-text">University: {{ $user->university ? $user->university : 'Not Applicable' }}</p>
                           <button class="btn btn-info view-details-button" data-toggle="modal" data-target="#userDetailsModal{{ $user->id }}">
                           <i class="fas fa-info-circle"></i> View User Details
                           </button>
                        </div>
                     </div>
                  </div>
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
                                    <textarea class="form-control" name="bio" id="bio{{ $user->id }}" rows="3" style="height: 100px; resize: none;" maxlength="250" placeholder="This user doesn't have a bio.">{{ $user->bio }}</textarea>
                                 </div>
                                 <div class="form-group">
                                    <label for="role">Role:</label>
                                    <select class="form-control" name="role" id="role{{ $user->id }}">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                 </div>
                                 <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger" onclick="deleteAccount({{ $user->id }})">
                                    <i class="fas fa-trash"></i> Delete Account
                                    </button>
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
                  @else
                  <div class="col-md-12 mt-4">
                     <p class="text-center">No users found.</p>
                  </div>
                  @endif
               </div>
            </div>
            <!-- Societies Tab -->
            <div class="tab-pane fade" id="pills-societies" role="tabpanel" aria-labelledby="pills-societies-tab">
               <div class="row">
                  <div class="col-md-6 mb-3">
                     <select class="form-control" id="categoryFilter">
                        <option value="">All Types</option>
                        @foreach($societyTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6 mb-3 d-flex flex-column flex-md-row">
                     <input class="form-control mr-sm-2 mb-2 mb-md-0" type="search" placeholder="Search by Society Name" aria-label="Search" id="societySearchInput">
                     <button class="btn btn-outline-primary my-2 my-md-0 ml-md-2" type="button" id="societySearchButton">Search</button>
                  </div>
                  @if(count($societies) > 0)
                  @foreach($societies as $society)
                  <div class="col-md-6">
                     <div class="card mb-3">
                        <div class="card-body">
                           <h5 class="card-title">{{ $society->societyName }}</h5>
                           <p class="card-text">Type: {{ $society->societyType ? $society->societyType : 'Not Specified' }}</p>
                           <button class="btn btn-info view-details-button" data-toggle="modal" data-target="#societyDetailsModal{{ $society->id }}">
                           <i class="fas fa-info-circle"></i> View Society Details
                           </button>
                        </div>
                     </div>
                  </div>
                  <div class="modal fade" id="societyDetailsModal{{ $society->id }}" tabindex="-1" role="dialog" aria-labelledby="societyDetailsModalLabel{{ $society->id }}" aria-hidden="true">
                     <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                              <div class="d-flex align-items-center">
                                 <h5 class="modal-title" id="societyDetailsModalLabel{{ $society->id }}">Society Details</h5>
                              </div>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                           </div>
                           <div class="modal-body">
                              <form action="{{ route('update-society', ['id' => $society->id]) }}" method="POST">
                                 @csrf
                                 <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name{{ $society->id }}" value="{{ $society->societyName }}" readonly>
                                 </div>
                                 <div class="form-group">
                                    <label for="category">Type:</label>
                                    <input readonly type="text" class="form-control" id="category{{ $society->id }}" value="{{ $society->societyType }}">
                                 </div>
                                 <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" name="description" id="description{{ $society->id }}" rows="3" style="height: 100px; resize: none;" maxlength="250" placeholder="This society doesn't have a description.">{{ $society->societyDescription }}</textarea>
                                 </div>
                                 <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger" onclick="deleteSociety({{ $society->id }})">
                                    <i class="fas fa-trash"></i> Delete Society
                                    </button>
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
                  @else
                  <div class="col-md-12 mt-4">
                     <p class="text-center">No societies found.</p>
                  </div>
                  @endif
               </div>
            </div>
            <!-- Approve Societies Tab -->
            <div class="tab-pane fade" id="pills-approve-societies" role="tabpanel" aria-labelledby="pills-approve-societies-tab">
               <div class="row">
                  <div class="col-md-6 mb-3">
                     <select class="form-control" id="approveCategoryFilter">
                        <option value="">All Types</option>
                        @foreach($societyTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6 mb-3 d-flex flex-column flex-md-row">
                     <input class="form-control mr-sm-2 mb-2 mb-md-0" type="search" placeholder="Search by Society Name" aria-label="Search" id="approveSocietySearchInput">
                     <button class="btn btn-outline-primary my-2 my-md-0 ml-md-2" type="button" id="approveSocietySearchButton">Search</button>
                  </div>
                  @if(count($pendingSocieties) > 0)
                  @foreach($pendingSocieties as $society)
                  <div class="col-md-6">
                     <div class="card mb-3">
                        <div class="card-body">
                           <h5 class="card-title">{{ $society->societyName }}</h5>
                           <p class="card-text">Type: {{ $society->societyType ? $society->societyType : 'Not Specified' }}</p>
                           <button class="btn btn-info view-details-button" data-toggle="modal" data-target="#societyDetailsModal{{ $society->id }}">
                           <i class="fas fa-info-circle"></i> View Society Details
                           </button>
                        </div>
                     </div>
                  </div>
                  <div class="modal fade" id="societyDetailsModal{{ $society->id }}" tabindex="-1" role="dialog" aria-labelledby="societyDetailsModalLabel{{ $society->id }}" aria-hidden="true">
                     <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                              <div class="d-flex align-items-center">
                                 <h5 class="modal-title" id="societyDetailsModalLabel{{ $society->id }}">Society Details</h5>
                              </div>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                           </div>
                           <div class="modal-body">
                              <form action="{{ route('accept-society', ['id' => $society->id]) }}" method="POST">
                                 @csrf
                                 <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name{{ $society->id }}" value="{{ $society->societyName }}" readonly>
                                 </div>
                                 <div class="form-group">
                                    <label for="category">Type:</label>
                                    <input readonly type="text" class="form-control" id="category{{ $society->id }}" value="{{ $society->societyType }}">
                                 </div>
                                 <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" name="description" id="description{{ $society->id }}" rows="3" style="height: 100px; resize: none;" maxlength="250" placeholder="This society doesn't have a description." readonly>{{ $society->societyDescription }}</textarea>
                                 </div>
                                 <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger" onclick="denySociety({{ $society->id }})">
                                    <i class="fas fa-ban"></i> Deny Society
                                    </button>
                                    <div>
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                       <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Accept Society</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @else
                  <div class="col-md-12 mt-4">
                     <p class="text-center">No pending societies found.</p>
                  </div>
                  @endif
               </div>
            </div>
            <!-- Society Queries Tab -->
            <div class="tab-pane fade" id="pills-society-queries" role="tabpanel" aria-labelledby="pills-society-queries-tab">
               <div class="row">
                  <div class="col-md-6 mb-3">
                     <select class="form-control" id="claimCategoryFilter">
                        <option value="">All Types</option>
                        @foreach($queryTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6 mb-3 d-flex flex-column flex-md-row">
                     <input class="form-control mr-sm-2 mb-2 mb-md-0" type="search" placeholder="Search by Society Name" aria-label="Search" id="claimSocietySearchInput">
                     <button class="btn btn-outline-primary my-2 my-md-0 ml-md-2" type="button" id="claimSocietySearchButton">Search</button>
                  </div>
                  @if(count($queries) > 0)
                  @foreach($queries as $query)
                  <div class="col-md-6">
                     <div class="card mb-3">
                        <div class="card-body">
                           <h5 class="card-title">{{ $query->societyName }}</h5>
                           <p class="card-text">Type: {{ $query->queryType}}</p>
                           <button class="btn btn-info view-details-button" data-toggle="modal" data-target="#claimDetailsModal{{ $society->id }}">
                           <i class="fas fa-info-circle"></i> View Claim Details
                           </button>
                        </div>
                     </div>
                  </div>
                  <div class="modal fade" id="claimDetailsModal{{ $society->id }}" tabindex="-1" role="dialog" aria-labelledby="claimDetailsModalLabel{{ $society->id }}" aria-hidden="true">
                     <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                              <div class="d-flex align-items-center">
                                 <h5 class="modal-title" id="claimDetailsModalLabel{{ $society->id }}">{{ $query->queryType }} - {{ $query->societyName }}</h5>
                              </div>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                           </div>
                           <div class="modal-body">
                              <form action="{{ route('accept-society-claim', ['societyId' => $query->society_id]) }}" method="POST">
                                 @csrf
                                 <input type="hidden" name="claimerId" value="{{ $query->user_id }}">
                                 <div class="form-group">
                                    <label for="name">Society Name:</label>
                                    <input type="text" class="form-control" id="name{{ $query->societyName }}" value="{{ $query->societyName }}" readonly>
                                 </div>
                                 <div class="form-group">
                                    <label for="name">Claimee:</label>
                                    <input type="text" class="form-control" id="name{{ $query->username }}" value="{{ $query->username }}" readonly>
                                 </div>
                                 <div class="form-group">
                                    <label for="description">Claim Reason:</label>
                                    <textarea class="form-control" name="description" id="description{{ $query->description }}" rows="3" style="height: 100px; resize: none;" maxlength="250" readonly>{{ $query->description }}</textarea>
                                 </div>
                                 <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-danger" onclick="denySocietyClaim({{ $query->id }})">
                                    <i class="fas fa-ban"></i> Deny
                                    </button>
                                    <div>
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                       <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Accept Request </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endforeach
                  @else
                  <div class="col-md-12 mt-4">
                     <p class="text-center">No active society queries.</p>
                  </div>
                  @endif
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
      <script>
         $(document).ready(function() {
         $('.view-details-button').click(function() {
            var societyId = $(this).data('societyid');
            $('#societyDetailsModal' + societyId).modal('show');
         });
         
         document.getElementById('societySearchButton').addEventListener('click', function() {
            var categoryFilter = document.getElementById('categoryFilter').value;
            var societySearchTerm = document.getElementById('societySearchInput').value.trim().toLowerCase();
            var societyCards = document.querySelectorAll('.card');
            societyCards.forEach(function(card) {
                  var cardTitleElement = card.querySelector('.card-body .card-title');
                  var cardCategoryElement = card.querySelector('.card-body .card-text:nth-of-type(1)');
                  if (cardTitleElement && cardCategoryElement) {
                     var cardTitle = cardTitleElement.textContent.toLowerCase();
                     var cardCategory = cardCategoryElement.textContent.trim().toLowerCase();
                     var categoryMatches = categoryFilter === '' || cardCategory.includes(categoryFilter.toLowerCase());
                     var searchTermMatches = cardTitle.includes(societySearchTerm);
                     if (categoryMatches && searchTermMatches) {
                        card.closest('.col-md-6').style.display = 'block';
                     } else {
                        card.closest('.col-md-6').style.display = 'none';
                     }
                  } else {
                     console.error("Card title or category element not found!");
                  }
            });
         });
         });
      </script>
      <script>
         $(document).ready(function() {
             $('.view-details-button').click(function() {
                 var societyId = $(this).data('societyid');
                 $('#societyDetailsModal' + societyId).modal('show');
             });
         
             document.getElementById('approveSocietySearchButton').addEventListener('click', function() {
                 var categoryFilter = document.getElementById('approveCategoryFilter').value;
                 var societySearchTerm = document.getElementById('approveSocietySearchInput').value.trim().toLowerCase();
                 var societyCards = document.querySelectorAll('.card');
                 societyCards.forEach(function(card) {
                     var cardTitleElement = card.querySelector('.card-body .card-title');
                     var cardCategoryElement = card.querySelector('.card-body .card-text:nth-of-type(1)');
                     if (cardTitleElement && cardCategoryElement) {
                         var cardTitle = cardTitleElement.textContent.toLowerCase();
                         var cardCategory = cardCategoryElement.textContent.trim().toLowerCase();
                         var categoryMatches = categoryFilter === '' || cardCategory.includes(categoryFilter.toLowerCase());
                         var searchTermMatches = cardTitle.includes(societySearchTerm);
                         if (categoryMatches && searchTermMatches) {
                             card.closest('.col-md-6').style.display = 'block';
                         } else {
                             card.closest('.col-md-6').style.display = 'none';
                         }
                     } else {
                         console.error("Card title or category element not found!");
                     }
                 });
             });
         });
      </script>
      <script>
         $(document).ready(function() {
             document.getElementById('claimSocietySearchButton').addEventListener('click', function() {
                 var categoryFilter = document.getElementById('claimCategoryFilter').value;
                 var societySearchTerm = document.getElementById('claimSocietySearchInput').value.trim().toLowerCase();
                 var societyCards = document.querySelectorAll('.card');
                 societyCards.forEach(function(card) {
                     var cardTitleElement = card.querySelector('.card-body .card-title');
                     var cardCategoryElement = card.querySelector('.card-body .card-text:nth-of-type(1)');
                     if (cardTitleElement && cardCategoryElement) {
                         var cardTitle = cardTitleElement.textContent.toLowerCase();
                         var cardCategory = cardCategoryElement.textContent.trim().toLowerCase();
                         var categoryMatches = categoryFilter === '' || cardCategory.includes(categoryFilter.toLowerCase());
                         var searchTermMatches = cardTitle.includes(societySearchTerm);
                         if (categoryMatches && searchTermMatches) {
                             card.closest('.col-md-6').style.display = 'block';
                         } else {
                             card.closest('.col-md-6').style.display = 'none';
                         }
                     } else {
                         console.error("Card title or category element not found!");
                     }
                 });
             });
         });
      </script>
      <script>
         function deleteSociety(id) {
             if (confirm("Are you sure you want to delete this society?")) {
                 var url = '/admin/delete-society/' + id;
                 
                 var form = document.createElement('form');
                 form.method = 'POST';
                 form.action = url;
         
                 var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                 var csrfInput = document.createElement('input');
                 csrfInput.setAttribute('type', 'hidden');
                 csrfInput.setAttribute('name', '_token');
                 csrfInput.setAttribute('value', csrfToken);
         
                 form.appendChild(csrfInput);
                 document.body.appendChild(form);
                 form.submit();
             }
         }
      </script>
      <script>
         function deleteAccount(id) {
             if (confirm("Are you sure you want to delete this account?")) {
                 var url = '/admin/delete-user/' + id;
                 
                 var form = document.createElement('form');
                 form.method = 'POST';
                 form.action = url;
         
                 var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                 var csrfInput = document.createElement('input');
                 csrfInput.setAttribute('type', 'hidden');
                 csrfInput.setAttribute('name', '_token');
                 csrfInput.setAttribute('value', csrfToken);
         
                 form.appendChild(csrfInput);
                 document.body.appendChild(form);
                 form.submit();
             }
         }
      </script>
      <script>
         function denySociety(id) {
             if (confirm("Are you sure you want to deny this society?")) {
                 var url = '/admin/deny-society/' + id;
                 
                 var form = document.createElement('form');
                 form.method = 'POST';
                 form.action = url;
         
                 var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                 var csrfInput = document.createElement('input');
                 csrfInput.setAttribute('type', 'hidden');
                 csrfInput.setAttribute('name', '_token');
                 csrfInput.setAttribute('value', csrfToken);
         
                 form.appendChild(csrfInput);
                 document.body.appendChild(form);
                 form.submit();
             }
         }
         
         function denySocietyClaim(id) {
             if (confirm("Are you sure you want to deny this society claim?")) {
                 var url = '/admin/deny-society-claim/' + id;
                 
                 var form = document.createElement('form');
                 form.method = 'POST';
                 form.action = url;
         
                 var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                 var csrfInput = document.createElement('input');
                 csrfInput.setAttribute('type', 'hidden');
                 csrfInput.setAttribute('name', '_token');
                 csrfInput.setAttribute('value', csrfToken);
         
                 form.appendChild(csrfInput);
                 document.body.appendChild(form);
                 form.submit();
             }
         }
      </script>
      <script>
         document.getElementById('bio{{ $user->id }}').addEventListener('input', function() {
            if (this.value.length > 250) {
               this.value = this.value.slice(0, 250);
            }
         });
      </script>
   </body>
</html>