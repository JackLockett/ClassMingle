<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>{{ $society->societyName }}</title>
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
               <h3 class="text-center">{{ $society->societyName }} Society</h3>
            </div>
         </div>
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
         <a href="{{ route('societies') }}" class="btn btn-secondary btn-sm mb-3">
         <i class="fas fa-arrow-left"></i> Return To Societies
         </a>
         <br>
         <div id="alertContainer"></div>
         <div class="row">
            <div class="col-md-12">
               <div class="card mb-3">
                  <div class="card-header">Society Information</div>
                  <div class="card-body">
                     <h5 class="card-title">About {{ $society->societyName }}</h5>
                     <p class="card-text">
                        {{ $society->societyDescription }}
                     </p>
                     <hr>
                     @if (is_array($society->memberList) && in_array(auth()->user()->id, $society->memberList))
                     <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createSocialModal">
                     <i class="fas fa-pencil-alt"></i> Create A Post
                     </a>
                     @if ($society->ownerId != auth()->user()->id)
                     <a href="#" class="btn btn-danger" id="leaveSocietyBtn" data-society-id="{{ $society->id }}">
                     <i class="fas fa-sign-out-alt"></i> Leave Society
                     </a>
                     @endif
                     @else
                     <a href="#" class="btn btn-success" id="joinSocietyBtn" data-society-id="{{ $society->id }}">
                     <i class="fas fa-user-plus"></i> Join Society
                     </a>
                     @endif
                     @if ($society->ownerId == auth()->user()->id)
                     <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteSociety">
                     <i class="fas fa-trash-alt"></i> Delete Society
                     </a>
                     <a href="#" class="btn btn-success" data-toggle="modal" data-target="#editSocietyModal">
                     <i class="fas fa-edit"></i> Edit Society Description
                     </a>
                     <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#manageModeratorsModal">
                     <i class="fas fa-user-cog"></i> Manage Moderators
                     </a>
                     @endif
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-9">
               <div class="card mb-3">
                  <div class="card-header">Society Feed</div>
                  <div class="card-body">
                     @if ($society->posts && count($society->posts) > 0)
                     @foreach ($society->posts as $post)
                     <div class="card mb-3{{ $post->pinned ? ' border-warning' : '' }}">
                        <div class="card-header text-muted d-flex justify-content-between align-items-center">
                           <strong>{{ $post->postTitle }}</strong>
                           <div style="display: inline-block;">
                              <span>Posted by 
                              <a href="{{ route('user.profile', ['id' => $post->author->id]) }}" style="color: #3d7475;">
                              {{ $post->author->username }}
                              </a>
                              </span>
                              <span class="ml-3">•</span>
                              <span class="ml-3">{{ $post->created_at->diffForHumans() }}</span>
                              @if (is_array($society->moderatorList) && in_array(auth()->user()->id, $society->moderatorList))
                              <form action="{{ route('pin-post', ['postId' => $post->id]) }}" method="POST" style="display: inline-block;">
                                 @csrf
                                 <button type="submit" class="btn btn-sm
                                    @if ($post->pinned)
                                    btn-warning
                                    @else
                                    btn-info
                                    @endif
                                    ml-2">
                                 <i class="fas fa-thumbtack"></i>
                                 @if ($post->pinned)
                                 Unpin
                                 @else
                                 Pin
                                 @endif
                                 </button>
                              </form>
                              @endif
                           </div>
                        </div>
                        <div class="card-body">
                           <p class="card-text">{{ $post->postComment }}</p>
                           <div class="text-muted">
                              <small><a href="{{ route('view-post', ['societyId' => $society->id, 'postId' => $post->id]) }}">View Post</a></small>
                              @if ($post->comments_count > 0)
                              <small class="ml-3">{{ $post->comments_count }} Comment{{ $post->comments_count != 1 ? 's' : '' }}</small>
                              @endif
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <p>No posts available in this society.</p>
                     @endif
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card mb-3">
                  <div class="card-header">Member Info</div>
                  <div class="card-body text-primary">
                     <i>
                        <p class="card-text">
                           {{ count($society->moderatorList) }}
                           Moderator{{ count($society->moderatorList) != 1 ? 's' : '' }}
                           <br>
                           {{ count($society->memberList) }}
                           Member{{ count($society->memberList) != 1 ? 's' : '' }}
                        </p>
                     </i>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Create Post -->
      <div class="modal fade" id="createSocialModal" tabindex="-1" role="dialog" aria-labelledby="createSocialModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="createSocialModalLabel">Create A Post - {{ $society->societyName }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form id="createSocialForm" action="{{ route('create-post', ['societyId' => $society->id]) }}" method="POST">
                     @csrf
                     <div class="form-group">
                        <input type="hidden" name="societyType" value="Social">
                        <label for="postTitle">Post Title:</label>
                        <input type="text" class="form-control" id="postTitle" name="postTitle" required>
                     </div>
                     <div class="form-group">
                        <label for="postComment">Comment:</label>
                        <textarea id="postComment" class="form-control" name="postComment" required autocomplete="postComment" style="resize: none; height: 150px;"></textarea>
                     </div>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">
                     <i class="fas fa-arrow-left"></i> Return
                     </button>
                     <button type="submit" class="btn btn-success">
                     <i class="fas fa-check"></i> Submit Post
                     </button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Manage Moderators -->
      <div class="modal fade" id="manageModeratorsModal" tabindex="-1" role="dialog" aria-labelledby="manageModeratorsModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="manageModeratorsModalLabel">Manage Moderators - {{ $society->societyName }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <ul class="nav nav-tabs" id="manageModeratorsTabs" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link active" id="promote-tab" data-toggle="tab" href="#promote" role="tab" aria-controls="promote" aria-selected="true">Add Moderator(s)</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="demote-tab" data-toggle="tab" href="#demote" role="tab" aria-controls="demote" aria-selected="false">Remove Moderator(s)</a>
                     </li>
                  </ul>
                  <div class="tab-content" id="manageModeratorsTabContent">
                     <div class="tab-pane fade show active" id="promote" role="tabpanel" aria-labelledby="promote-tab">
                        <form id="promoteToModeratorForm">
                           @csrf
                           <div class="form-group">
                              <br>
                              <label for="moderatorUser">Select User to Promote:</label>
                              <select class="form-control" id="moderatorUser" name="moderatorUser">
                                 @php
                                 $usersToAdd = [];
                                 if ($society->moderatorList !== null) {
                                 foreach ($society->memberList as $member) {
                                 if ($member != $society->ownerId && !in_array($member, $society->moderatorList)) {
                                 $usersToAdd[] = $member;
                                 }
                                 }
                                 } else {
                                 // All members except owner are eligible to be added as moderators if there are no moderators yet
                                 foreach ($society->memberList as $member) {
                                 if ($member != $society->ownerId) {
                                 $usersToAdd[] = $member;
                                 }
                                 }
                                 }
                                 @endphp
                                 @if (count($usersToAdd) > 0)
                                 @foreach ($usersToAdd as $userId)
                                 @php
                                 $user = App\Models\User::find($userId);
                                 @endphp
                                 @if ($user)
                                 <option value="{{ $userId }}">{{ $userId }} - {{ $user->username }}</option>
                                 @endif
                                 @endforeach
                                 @else
                                 <option value="" disabled>No users to add as moderator</option>
                                 @endif
                              </select>
                              <br>
                           </div>
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">
                           <i class="fas fa-arrow-left"></i> Return
                           </button>
                           <button type="submit" class="btn btn-primary" id="promoteToModeratorBtn" onclick="closeModal()">
                           <i class="fas fa-user-plus"></i> Promote to Moderator
                           </button>
                        </form>
                     </div>
                     <div class="tab-pane fade" id="demote" role="tabpanel" aria-labelledby="demote-tab">
                        <form id="demoteModeratorForm">
                           @csrf
                           <div class="form-group">
                              <br>
                              <label for="demotedModerator">Select Moderator to Remove:</label>
                              <select class="form-control" id="demotedModerator" name="demotedModerator">
                                 @php
                                 $moderatorsToRemove = [];
                                 if ($society->moderatorList !== null) {
                                 foreach ($society->memberList as $member) {
                                 if ($member != $society->ownerId && in_array($member, $society->moderatorList)) {
                                 $moderatorsToRemove[] = $member;
                                 }
                                 }
                                 }
                                 @endphp
                                 @if (count($moderatorsToRemove) > 0)
                                 @foreach ($moderatorsToRemove as $moderatorId)
                                 @php
                                 $moderator = App\Models\User::find($moderatorId);
                                 @endphp
                                 @if ($moderator)
                                 <option value="{{ $moderatorId }}">{{ $moderatorId }} - {{ $moderator->username }}</option>
                                 @endif
                                 @endforeach
                                 @else
                                 <option value="" disabled>No moderators to remove</option>
                                 @endif
                              </select>
                              <br>
                           </div>
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">
                           <i class="fas fa-arrow-left"></i> Return
                           </button>
                           <button type="submit" class="btn btn-danger" id="demoteModeratorBtn" onclick="closeModal()">
                           <i class="fas fa-user-minus"></i> Remove Moderator
                           </button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Confirming Society Deletion -->
      <div class="modal fade" id="confirmDeleteSociety" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteSocietyLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="confirmDeleteSocietyLabel">Delete Society - {{ $society->societyName }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <p>Are you absolutely sure you want to delete this society? <strong>This action can't be undone.</strong></p>
                  <form id="deleteSocietyForm" action="{{ route('delete-society', ['societyId' => $society->id]) }}" method="POST">
                     @csrf
                     <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-arrow-left"></i> Return
                        </button>
                        <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Society
                        </button>
                  </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Editing Society Information -->
      <div class="modal fade" id="editSocietyModal" tabindex="-1" role="dialog" aria-labelledby="editSocietyModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="editSocietyModalLabel">Edit Society Details - {{ $society->societyName }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form id="editSocietyForm" action="{{ route('edit-society', ['societyId' => $society->id]) }}" method="POST">
                     @csrf
                     <div class="form-group">
                        <label for="societyDesc">Description:</label>
                        <textarea id="societyDesc" class="form-control" name="societyDesc" required autocomplete="societyDesc" style="resize: none; height: 150px;">{{ $society->societyDescription }}</textarea>
                     </div>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">
                     <i class="fas fa-arrow-left"></i> Return
                     </button>
                     <button type="submit" class="btn btn-success">
                     <i class="fas fa-check"></i> Update
                     </button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <script>
         document.addEventListener('DOMContentLoaded', function () {
             const handleSocietyAction = (e, action) => {
                 e.preventDefault();
         
                 const buttonId = action === 'join' ? 'joinSocietyBtn' : 'leaveSocietyBtn';
                 const actionMessage = action === 'join' ? 'Successfully joined the society!' : 'You have left this society!';
                 const errorMessage = action === 'join' ? 'Failed to join the society.' : 'Failed to leave the society.';
         
                 console.log(`${action.charAt(0).toUpperCase() + action.slice(1)} button clicked`);
         
                 const societyId = document.getElementById(buttonId).getAttribute('data-society-id');
         
                 fetch(`/${action}-society/${societyId}`, {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     },
                 })
                     .then(response => response.json())
                     .then(data => {
                         console.log(data);
         
                         showAlert(data.success, data.success ? actionMessage : errorMessage);
         
                         location.reload();
                     })
                     .catch(error => {
                         console.error(error);
                     });
             };
         
             const showAlert = (success, message) => {
                 const alertClass = success ? 'alert-success' : 'alert-danger';
                 const alertContainer = document.getElementById('alertContainer');
                 const alertDiv = document.createElement('div');
         
                 alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
                 alertDiv.role = 'alert';
                 alertDiv.innerHTML = `${message}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>`;
         
                 alertContainer.appendChild(alertDiv);
             };
         
             const addSocietyButtonListener = (buttonId, action) => {
                 const societyBtn = document.getElementById(buttonId);
                 if (societyBtn) {
                     societyBtn.addEventListener('click', e => handleSocietyAction(e, action));
                 }
             };
         
             addSocietyButtonListener('joinSocietyBtn', 'join');
             addSocietyButtonListener('leaveSocietyBtn', 'leave');
         
             const promoteToModeratorForm = document.getElementById('promoteToModeratorForm');
             promoteToModeratorForm.addEventListener('submit', function (e) {
                 e.preventDefault();
         
                 const selectedUserId = document.getElementById('moderatorUser').value;
         
                 fetch(`/promote-to-moderator/{{ $society->id }}`, {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     },
                     body: JSON.stringify({ moderatorUser: selectedUserId }),
                 })
                     .then(response => response.json())
                     .then(data => {
                        console.log(data);
                        showAlert(data.success, data.message);
                        if (data.success) {
                           if (data.reload) {
                                 location.reload(); // Reload the page
                           } else {
                                 $('#manageModeratorsModal').modal('hide'); // Close the modal
                           }
                        }
                     })
                     .catch(error => {
                         console.error(error);
                     });
             });
         
         
             // Add an event listener to handle form submission for demoting a moderator
             const demoteModeratorForm = document.getElementById('demoteModeratorForm');
             demoteModeratorForm.addEventListener('submit', function (e) {
                 e.preventDefault();
         
                 // Retrieve the selected user ID from the dropdown menu
                 const selectedUserId = document.getElementById('demotedModerator').value;
         
                 // Send a POST request to the demoteModerator route
                 fetch(`/demote-moderator/{{ $society->id }}`, {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     },
                     body: JSON.stringify({ demotedModerator: selectedUserId }),
                 })
                     .then(response => response.json())
                     .then(data => {
                        console.log(data);
                        showAlert(data.success, data.message);
                        if (data.success) {
                           if (data.reload) {
                                 location.reload(); // Reload the page
                           } else {
                                 $('#manageModeratorsModal').modal('hide'); // Close the modal
                           }
                        }
                     })
                     .catch(error => {
                         console.error(error);
                     });
             });
         
         });
         
         function closeModal() {
            $('#manageModeratorsModal').modal('hide');
         }
         
                  
               
      </script>
      @include('layouts.footer')
   </body>
</html>