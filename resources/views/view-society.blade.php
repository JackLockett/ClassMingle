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
         <a href="{{ route('societies') }}" class="btn btn-secondary btn-sm mb-3">Return To Societies</a>
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
                     @if (is_array(json_decode($society->memberList, true)) && in_array(auth()->user()->id, json_decode($society->memberList, true)))
                     <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createSocialModal">Create A Post</a>
                     @if ($society->ownerId != auth()->user()->id)
                     <a href="#" class="btn btn-danger" id="leaveSocietyBtn" data-society-id="{{ $society->id }}">Leave Society</a>
                     @endif
                     @else
                     <a href="#" class="btn btn-success" id="joinSocietyBtn" data-society-id="{{ $society->id }}">Join Society</a>
                     @endif
                     @if ($society->ownerId == auth()->user()->id)
                     <a href="#" class="btn btn-danger" id="deleteSocietyBtn" data-society-id="{{ $society->id }}">Delete Society</a>
                     <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#createAcademicModal">Edit Society Info</a>
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
                     <div class="card mb-3">
                        <div class="card-header text-muted d-flex justify-content-between align-items-center">
                           <strong>{{ $post->postTitle }}</strong>
                           <div>
                              <span>Posted by {{ $post->author->username }}</span>
                              <span class="ml-3">•</span>
                              <span class="ml-3">{{ $post->created_at->diffForHumans() }}</span>
                           </div>
                        </div>
                        <div class="card-body">
                           <p class="card-text">{{ $post->postComment }}</p>
                           <div class="text-muted">
                              <small><a href="{{ route('view-post', ['societyId' => $society->id, 'postId' => $post->id]) }}">View Post</a></small>
                              <small class="ml-3">{{ $post->comments_count }} Comment{{ $post->comments_count != 1 ? 's' : '' }}</small>
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
                  <div class="card-body text-info">
                     <p class="card-text">
                        {{ count(json_decode($society->memberList, true)) }}
                        Member{{ count(json_decode($society->memberList, true)) != 1 ? 's' : '' }}
                     </p>
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
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Return</button>
                     <button type="submit" class="btn btn-primary">Create A Post</button>
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
                 alertDiv.innerHTML = `<strong>${message}</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>`;
         
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
         });
         
      </script>
      @include('layouts.footer')
   </body>
</html>