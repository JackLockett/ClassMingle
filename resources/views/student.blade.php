<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>User Profile</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script src="https://kit.fontawesome.com/a076d05399.js"></script>
      <style>
         body {
         background-color: #f8f9fa;
         }
         .profile-card {
         background-color: #fff;
         border-radius: 10px;
         box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
         padding: 20px;
         margin-bottom: 30px;
         }
         .profile-picture {
         text-align: center;
         margin-bottom: 20px;
         }
         .profile-picture img {
         width: 150px;
         height: 150px;
         border-radius: 50%;
         object-fit: cover;
         border: 5px solid #fff;
         box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s ease-in-out;
         }
         .profile-picture img:hover {
         transform: scale(1.1);
         }
         .profile-info p {
         margin-bottom: 10px;
         }
         .profile-info strong {
         color: #007bff;
         }
         .profile-header {
         display: flex;
         align-items: center;
         margin-bottom: 15px;
         }
         .profile-header i {
         margin-right: 8px; 
         font-size: 20px; 
         }
         .profile-footer {
         text-align: center;
         margin-top: 20px;
         }
         .badge {
         padding: 8px 12px;
         font-size: 14px;
         border-radius: 20px;
         background-color: #007bff;
         color: #fff;
         display: inline-block; 
         margin-bottom: 10px;
         margin-right: 10px;
         }
         .profile-action-card {
         background-color: #f8f9fa;
         border-radius: 10px;
         box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
         padding: 20px;
         margin-top: 20px;
         }
         .profile-action-card button {
         margin-right: 10px;
         }
         .friends-list a {
         display: inline-block;
         color: #333;
         text-decoration: none;
         transition: color 0.3s ease;
         }
         .friends-list a:hover {
         color: #007bff;
         }
         .profile-card {
         position: relative;
         }
         .block-btn {
         position: absolute;
         top: 10px;
         right: 10px;
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-4">
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
         <div class="row justify-content-center">
            <div class="col-md-4">
               <div class="profile-picture">
                  <img src="{{ asset($student->avatar ?? 'images/default.jpg') }}" alt="Profile Picture">
                  <div class="profile-footer">
                     <div class="profile-card">
                        <button id="sendRequestButton" class="btn btn-primary mr-2" 
                        @if($student->id === Auth::id() || $isFriend) 
                        disabled style="opacity: 0.6; pointer-events: none; background-color: #dcdcdc; border-color: #c0c0c0; color: #6c757d;"
                        @endif
                        onclick="toggleFriendRequest({{ $student->id }}, {{ $isPendingRequest ? 'true' : 'false' }})">
                        @if($student->id === Auth::id() && $student->id == $authId) 
                        <i class="fas fa-user-plus"></i> Send Request
                        @elseif($isFriend)
                        Already Friends
                        @elseif($isPendingRequest) 
                        Pending
                        @else 
                        <i class="fas fa-user-plus"></i> Send Request
                        @endif
                        </button>
                        <button data-toggle="modal" data-target="#sendMessageModal" class="btn btn-info" @if(!$isFriend) disabled style="opacity: 0.6; pointer-events: none; background-color: #dcdcdc; border-color: #c0c0c0; color: #6c757d;" @endif>
                        <i class="far fa-envelope"></i> Send Message
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-8">
               <div class="profile-card">
                  @if($student->id != Auth::id())
                  @if($isBlocked)
                  <button id="blockButton" class="btn btn-success btn-sm block-btn" data-toggle="modal" data-target="#unblockUserModal">
                  <i class="fas fa-lock-open"></i> Unblock User
                  </button>
                  @else
                  <button id="blockButton" class="btn btn-danger btn-sm block-btn" data-toggle="modal" data-target="#blockUserModal" data-user-id="{{ $authId }}" data-blocked-id="{{ $student->id }}">
                  <i class="fas fa-ban"></i> Block User
                  </button>
                  @endif
                  @endif
                  <h4 class="profile-header"><i class="fas fa-user"></i>&nbsp;User Details</h4>
                  <div class="profile-info">
                     <br>
                     <p><strong>Username:</strong> {{ $student->username }}</p>
                     <p><strong>Bio:</strong> {{ $student->bio ? $student->bio : "This student doesn't have a bio" }}</p>
                     <p><strong>Member Since:</strong> {{ \Carbon\Carbon::parse($student->created_at)->isoFormat('Do MMMM YYYY') }}</p>
                     <p><strong>University:</strong> {{ $student->university ? $student->university : 'Not Applicable' }}</p>
                  </div>
               </div>
            </div>
         </div>
         <div class="row justify-content-center">
            <div class="col-md-4">
               <div class="profile-card">
                  <h4 class="profile-header"><i class="fas fa-award"></i>&nbsp;Badges ({{ count($badges) }})</h4>
                  <!-- Code for displaying badges -->
                  @if($badges->isNotEmpty())
                  <div class="d-flex flex-wrap justify-content-center">
                     @foreach($badges as $badge)
                     @switch($badge->badgeType)
                     @case('New User')
                     <span class="badge" style="background-color: #2980b9; color: #fff;"><i class="fas fa-medal"></i> {{ $badge->badgeType }}</span> 
                     @break
                     @case('Created a Society')
                     <span class="badge" style="background-color: #c0392b; color: #fff;"><i class="fas fa-flag"></i> {{ $badge->badgeType }}</span> 
                     @break
                     @case('Joined a Society')
                     <span class="badge" style="background-color: #27ae60; color: #fff;"><i class="fas fa-users"></i> {{ $badge->badgeType }}</span> 
                     @break
                     @case('Made a Post')
                     <span class="badge" style="background-color: #8e44ad; color: #fff;"><i class="fas fa-edit"></i> {{ $badge->badgeType }}</span>
                     @break
                     @case('Made a Comment')
                     <span class="badge" style="background-color: #f39c12; color: #fff;"><i class="far fa-comment"></i> {{ $badge->badgeType }}</span>
                     @break
                     <!-- Add more cases for other badge types as needed -->
                     @endswitch
                     @endforeach
                  </div>
                  @else
                  <p>No badges found for this user.</p>
                  @endif
               </div>
            </div>
            <div class="col-md-8">
               <div class="profile-card">
                  <h4 class="profile-header"><i class="fas fa-user-friends"></i>&nbsp;Friends ({{ count($student->friends) }}) </h4>
                  @if($student->friends->isEmpty())
                  <p>No friends to display.</p>
                  @else
                  <div class="friends-list">
                     @php
                     $friendLinks = [];
                     foreach($student->friends as $friend) {
                     $friendLinks[] = '<a href="' . route('user.profile', ['id' => $friend->id]) . '">' . $friend->username . '</a>';
                     }
                     echo implode(',&nbsp; &nbsp;', $friendLinks);
                     @endphp
                  </div>
                  @endif
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Sending Message -->
      <div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="createMessageModalLabel">Send A Message - {{ $student->username }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form id="sendMessageForm" action="{{ route('send-message', ['id' => $student->id]) }}" method="POST">
                     @csrf
                     <div class="form-group">
                        <label for="messageField">Message:</label>
                        <textarea id="messageField" class="form-control" name="messageField" maxlength="250" autocomplete="messageField" style="resize: none; height: 125px;" required></textarea>
                     </div>
                     <div class="form-group text-muted">
                        Characters remaining: <span id="characterCount"></span>
                     </div>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">
                     <i class="fas fa-arrow-left"></i> Return
                     </button>
                     <button type="submit" id="sendMessageBtn" class="btn btn-success">
                     <i class="fas fa-check"></i> Send Message
                     </button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Blocking User -->
      <div class="modal fade" id="blockUserModal" tabindex="-1" role="dialog" aria-labelledby="blockUserModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="blockUserModalLabel">Block User - {{ $student->username }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <p>Are you sure you want to block this user?</p>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">
                  <i class="fas fa-times"></i> Cancel
                  </button>
                  <!-- Add this inside your modal -->
                  <button type="button" id="confirmBlockButton" class="btn btn-danger" onclick="confirmBlockUser()">
                  <i class="fas fa-ban"></i> Block
                  </button>
               </div>
            </div>
         </div>
      </div>
      </div>
      <!-- Modal for Unblocking User -->
      <div class="modal fade" id="unblockUserModal" tabindex="-1" role="dialog" aria-labelledby="unblockUserModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="unblockUserModalLabel">Unblock User - {{ $student->username }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <p>Are you sure you want to unblock this user?</p>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">
                  <i class="fas fa-times"></i> Cancel
                  </button>
                  <button type="button" class="btn btn-success" onclick="unblockUser({{ $student->id }})">
                  <i class="fas fa-lock-open"></i> Unblock
                  </button>
               </div>
            </div>
         </div>
      </div>
      </div>
      <script>
         // Function to update character count and disable button if input is empty
         function updateCharacterCount() {
            var commentLength = $('#messageField').val().length;
            var totalLength = commentLength;
            var remainingCharacters = 250 - totalLength;
         
            $('#characterCount').text(remainingCharacters);
         
            if (totalLength > 0) {
                  $('#sendMessageBtn').prop('disabled', false);
            } else {
                  $('#sendMessageBtn').prop('disabled', true);
            }
         }
         
         // Update character count on input change
         $('#messageField').on('input', function() {
            updateCharacterCount();
         });
         
         // Initialize character count on document ready
         $(document).ready(function() {
            updateCharacterCount();
         });
      </script>
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <script>
         function toggleFriendRequest(userId, isPending) {
             if (isPending) {
                 cancelFriendRequest(userId);
             } else {
                 sendFriendRequest(userId);
             }
         }
         
         function sendFriendRequest(userId) {
             $.ajax({
                 type: 'POST',
                 url: '{{ route("sendFriendRequest") }}',
                 data: {
                     '_token': '{{ csrf_token() }}',
                     'receiver_id': userId
                 },
                 success: function(response) {
                     location.reload();
                 },
                 error: function(xhr, status, error) {
                     console.error(xhr.responseText);
                 }
             });
         }
         
         function cancelFriendRequest(userId) {
             $.ajax({
                 type: 'POST',
                 url: '{{ route("cancelFriendRequest") }}',
                 data: {
                     '_token': '{{ csrf_token() }}',
                     'receiver_id': userId
                 },
                 success: function(response) {
                     location.reload();
                 },
                 error: function(xhr, status, error) {
                     console.error(xhr.responseText);
                 }
             });
         }
         
         function blockUser(userId, blockedId) {
            $.ajax({
               type: 'POST',
               url: '{{ route("block-user") }}',
               data: {
                     '_token': '{{ csrf_token() }}',
                     'user_id': userId,
                     'blocked_id': blockedId 
               },
               success: function(response) {
                     location.reload();
               },
               error: function(xhr, status, error) {
                     console.error(xhr.responseText);
               }
            });
         }
         
         function unblockUser(userId) {
            $.ajax({
               type: 'POST',
               url: '{{ route("unblock-user") }}',
               data: {
                     '_token': '{{ csrf_token() }}',
                     'user_id': userId,
               },
               success: function(response) {
                     location.reload();
               },
               error: function(xhr, status, error) {
                     console.error(xhr.responseText);
               }
            });
         }
         
         $(document).ready(function() {
         $('#blockButton').click(function() {
         // Get the user ID and blocked ID from data attributes of the button
         var userId = $(this).data('user-id');
         var blockedId = $(this).data('blocked-id');
         
         // Set the user ID and blocked ID in the modal's confirmation button
         $('#confirmBlockButton').data('user-id', userId);
         $('#confirmBlockButton').data('blocked-id', blockedId);
         });
         });
         
         function confirmBlockUser() {
         // Retrieve user ID and blocked ID from the confirmation button's data attributes
         var userId = $('#confirmBlockButton').data('user-id');
         var blockedId = $('#confirmBlockButton').data('blocked-id');
         
         // Perform the AJAX call to block the user
         $.ajax({
         type: 'POST',
         url: '{{ route("block-user") }}',
         data: {
            '_token': '{{ csrf_token() }}',
            'user_id': userId,
            'blocked_id': blockedId
         },
         success: function(response) {
            location.reload();
         },
         error: function(xhr, status, error) {
            console.error(xhr.responseText);
         }
         });
         }
         
         
         
         
      </script>
      @include('layouts.footer')
   </body>
</html>