<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>View Post</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <style>
         .comment {
         margin-bottom: 10px;
         }
         .comment .d-flex {
         align-items: baseline;
         }
         .comment .text-muted {
         margin-left: 10px;
         }
         .comment p {
         max-width: 85%; 
         overflow-wrap: break-word; 
         }
         .form-group textarea {
         resize: none;
         }
         .button-group {
         display: inline-flex;
         align-items: center;
         }
         .button-group .btn {
         margin-right: 5px;
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-3">
         <div class="row justify-content-center">
            <div class="col-md-8">
               <h3 class="text-center">{{ $society->societyName }} - Society Post</h3>
            </div>
         </div>
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
         <a href="{{ route('view-society', ['id' => $society->id]) }}" class="btn btn-secondary btn-sm mb-3">
         <i class="fas fa-arrow-left"></i> Return to Society
         </a>
         <div class="card">
            <div class="card-body">
               <h3 class="card-title">{{ $post->postTitle }}</h3>
               <hr>
               <p class="card-text">{{ $post->postComment }}</p>
               <p class="card-text">
                  <small class="text-muted">
                  Posted by 
                  @if($post->author)
                  <a href="{{ route('user.profile', ['id' => $post->author->id]) }}">
                  {{ $post->author->username }}
                  </a>
                  @else
                  <i>Deleted_Account</i>
                  @endif
                  • {{ $post->created_at->diffForHumans() }}
                  </small>
               </p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
               <div>
                  <button id="likeButton_{{ $post->id }}" class="btn btn-sm btn-link" onclick="likePost('{{ $post->id }}')" style="color: #666;">
                  <i class="fas fa-thumbs-up"></i> {{ $post->likes }}
                  </button>
                  <button id="dislikeButton_{{ $post->id }}" class="btn btn-sm btn-link" onclick="dislikePost('{{ $post->id }}')" style="color: #666;">
                  <i class="fas fa-thumbs-down"></i> {{ $post->dislikes }}
                  </button>
               </div>
               <div class="d-flex align-items-center">
                  <button id="bookmarkButton" class="btn btn-sm {{ $post->isBookmarked() ? 'btn-primary' : 'btn-outline-primary' }}">
                  {{ $post->isBookmarked() ? 'Unbookmark Post' : 'Bookmark Post' }}
                  </button>
                  @if (
                  (is_array($society->moderatorList) && in_array(auth()->user()->id, $society->moderatorList)) || 
                  ($post->authorId == auth()->user()->id) ||
                  (auth()->user()->role == 'admin')
                  )
                  <a href="#" class="btn btn-sm btn-danger ml-2" data-toggle="modal" data-target="#confirmDeletePost">
                  <i class="fas fa-trash"></i> Delete Post
                  </a>
                  @endif
                  @if($post->authorId != auth()->user()->id)
                  <button id="reportButton" class="btn btn-sm btn-warning ml-2" data-toggle="modal" data-target="#reportPostModal" data-society-id="{{ $society->id }}">
                  <i class="fas fa-exclamation-triangle"></i>
                  </button>
                  @endif
               </div>
            </div>
         </div>
         <br>
         <div class="card">
            <div class="card-header">
               <h5 class="mb-0">Comments ({{ $post->comments->count() }})</h5>
            </div>
            <div class="card-body">
               @if ($post->comments->count() > 0)
               @foreach ($post->comments->where('parent_comment_id', null) as $key => $comment)
               <div class="comment mb-3">
                  <div class="d-flex justify-content-between align-items-center">
                     <div>
                        <strong>
                        @if($comment->user)
                        <a href="{{ route('user.profile', ['id' => $comment->user->id]) }}">
                        {{ $comment->user->username }}
                        </a>
                        @else
                        <i>Deleted_Account</i>
                        @endif
                        </strong> said:
                     </div>
                     <div class="text-muted" style="display: inline-block;">
                        <small>{{ $comment->created_at->diffForHumans() }}</small>
                        <div class="button-group ml-2">
                           <button class="btn btn-sm {{ $comment->isSaved() ? 'btn-primary' : 'btn-outline-primary' }} saveButton" data-comment-id="{{ $comment->id }}">
                           {{ $comment->isSaved() ? 'Unsave' : 'Save' }}
                           </button>
                           @if (
                           (is_array($society->moderatorList) && in_array(auth()->user()->id, $society->moderatorList)) || 
                           ($comment->user_id == auth()->user()->id) ||
                           (auth()->user()->role == 'admin')
                           )
                           <a href="#" class="btn btn-sm btn-danger delete-comment-btn" data-toggle="modal" data-target="#confirmDeleteComment" data-comment-id="{{ $comment->id }}">
                           <i class="fas fa-trash"></i> Delete
                           </a>
                           @endif
                           @if($comment->user_id != auth()->user()->id)
                           <button id="reportButton" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#reportCommentModal">
                           <i class="fas fa-exclamation-triangle"></i>
                           </button>
                           @endif
                        </div>
                     </div>
                  </div>
                  <p>{{ $comment->comment }}</p>
                  <div class="mb-3">
                     <button id="likeButton_comment_{{ $comment->id }}" class="btn btn-sm btn-link d-inline-block" onclick="likeComment('{{ $comment->id }}')" style="color: #666;">
                     <i class="fas fa-thumbs-up"></i> {{ $comment->likes }}
                     </button>
                     <button id="dislikeButton_comment_{{ $comment->id }}" class="btn btn-sm btn-link d-inline-block" onclick="dislikeComment('{{ $comment->id }}')" style="color: #666;">
                     <i class="fas fa-thumbs-down"></i> {{ $comment->dislikes }}
                     </button>
                     <a href="{{ route('view-comment', ['societyId' => $society->id, 'postId' => $post->id, 'commentId' => $comment->id]) }}">
                     @if ($comment->responses->count() > 0)
                     @if($comment->user_id != auth()->user()->id)
                     <span class="btn btn-sm btn-link">Respond</span>
                     @endif
                     <small class="ml-2 text-muted">
                     {{ $comment->responses->count() }} Response{{ $comment->responses->count() != 1 ? 's' : '' }}
                     </small>
                     @else
                     <span class="btn btn-sm btn-link">Respond</span>
                     @endif
                     </a>
                  </div>
               </div>
               @endforeach
               @else
               <p>No comments yet. Be the first to comment!</p>
               @endif
            </div>
            <div class="card-footer">
               <form action="{{ route('add-comment', ['postId' => $post->id]) }}" method="POST">
                  @csrf
                  <div class="form-group">
                     <label for="comment">Add a Comment:</label>
                     <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                     <small>Characters remaining: <span id="charCount">250</span></small>
                  </div>
                  <button type="submit" id="submitComment" class="btn btn-primary">
                  <i class="fas fa-comment"></i> Submit Comment
                  </button>
               </form>
            </div>
         </div>
      </div>
      <!-- Modal for Reporting Posts -->
      <div class="modal fade" id="reportPostModal" tabindex="-1" role="dialog" aria-labelledby="reportPostModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="reportPostModalLabel">Report Post</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <form action="{{ route('report-post', ['postId' => $post->id]) }}" method="POST">
                     @csrf
                     <div class="form-group">
                        <p>Please provide details about why you are reporting this post:</p>
                        <input type="hidden" id="societyId" name="societyId" value="{{ $society->id }}">
                        <textarea class="form-control" id="reportReasonPost" name="reportReasonPost" rows="3" maxlength="250" style="resize: none; height: 125px;" required></textarea>
                     </div>
                     <div class="form-group text-muted">
                        Characters remaining: <span id="characterCountReportPost"></span>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-arrow-left"></i> Return
                        </button>
                        <button type="submit" class="btn btn-warning" id="reportPost" name="reportPost">
                        <i class="fas fa-exclamation-triangle"></i> Report Post
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Modal for Reporting Comments -->
      <div class="modal fade" id="reportCommentModal" tabindex="-1" role="dialog" aria-labelledby="reportCommentModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="reportCommentModalLabel">Report Comment</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  @if(isset($comment))
                  <form action="{{ route('report-comment', ['postId' => $comment->post_id, 'commentId' => $comment->parent_comment_id ?: $comment->id]) }}" method="POST">
                     @endif
                     @csrf
                     <div class="form-group">
                        <p>Please provide details about why you are reporting this comment:</p>
                        <input id="societyId" type="hidden" value="{{ $society->id }}" name="societyId">
                        <textarea class="form-control" id="reportReasonComment" name="reportReasonComment" rows="3" maxlength="250" style="resize: none; height: 125px;" required></textarea>
                     </div>
                     <div class="form-group text-muted">
                        Characters remaining: <span id="characterCountReportComment"></span>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-arrow-left"></i> Return
                        </button>
                        <button type="submit" class="btn btn-warning" id="reportComment" name="reportComment">
                        <i class="fas fa-exclamation-triangle"></i> Report Comment
                        </button>
                     </div>
               </div>
               </form>
            </div>
         </div>
      </div>
      <!-- Modal for Confirming Post Deletion -->
      <div class="modal fade" id="confirmDeletePost" tabindex="-1" role="dialog" aria-labelledby="confirmDeletePostLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="confirmDeletePostLabel">Confirm Delete Post</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <p>Are you sure you want to delete this post?</p>
               </div>
               <div class="modal-footer">
                  <form action="{{ route('delete-post', ['postId' => $post->id]) }}" method="POST">
                     @csrf
                     <button type="submit" class="btn btn-danger">
                     <i class="fas fa-trash"></i> Delete Post
                     </button>
                  </form>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
               </div>
            </div>
         </div>
      </div>
      @if(isset($comment))
      <!-- Modal for Confirming Comment Deletion -->
      <div class="modal fade" id="confirmDeleteComment" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCommentLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="confirmDeleteCommentLabel">Confirm Delete Comment</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <p>Are you sure you want to delete this comment?</p>
                  <p>{{ $comment->id }}</p>
               </div>
               <div class="modal-footer">
                  <form action="{{ route('delete-comment', ['commentId' => $comment->id]) }}" method="POST" style="display: inline-block;">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-danger">
                     <i class="fas fa-trash"></i> Delete
                     </button>
                  </form>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
               </div>
            </div>
         </div>
      </div>
      @endif
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script>
         document.addEventListener("DOMContentLoaded", function () {
             const commentTextarea = document.getElementById("comment");
             const maxCharCount = 250;
         
             updateCharCount();
         
             commentTextarea.addEventListener("input", function () {
                 updateCharCount();
             });
         
             function updateCharCount() {
                 const charCount = commentTextarea.value.length;
                 const remainingChars = maxCharCount - charCount;
                 const charCountSpan = document.getElementById("charCount");
         
                 charCountSpan.textContent = remainingChars;
                 charCountSpan.style.color = remainingChars >= 0 ? "black" : "red";
         
                 const submitButton = document.getElementById("submitComment");
                 submitButton.disabled = remainingChars < 0 || remainingChars === maxCharCount;
             }
         
             const bookmarkButton = document.getElementById("bookmarkButton");
            const postId = {{ $post->id }};
         
            const deleteCommentButtons = document.querySelectorAll('.delete-comment-btn');
         
         deleteCommentButtons.forEach(button => {
         button.addEventListener('click', function () {
         const commentId = this.getAttribute('data-comment-id');
         const modal = document.getElementById('confirmDeleteComment');
         const modalBody = modal.querySelector('.modal-body');
         modalBody.innerHTML = `<p>Are you sure you want to delete this comment?</p>`;
         
         const deleteCommentForm = modal.querySelector('form');
         deleteCommentForm.action = `/delete-comment/${commentId}`;
         
         // Check if the comment has responses
         const hasResponses = this.getAttribute('data-has-responses');
         
         // If it has responses, set a hidden input in the form to indicate this
         if (hasResponses === 'true') {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'delete_responses';
            hiddenInput.value = 'true';
            deleteCommentForm.appendChild(hiddenInput);
         }
         });
         });
            
            bookmarkButton.addEventListener("click", function () {
               fetch(`/bookmark/${postId}`, {
                     method: "POST",
                     headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                     },
                     body: JSON.stringify({
                        postId: postId
                     })
               })
               .then(response => {
                     if (response.ok) {
                        bookmarkButton.classList.toggle("btn-outline-primary");
                        bookmarkButton.classList.toggle("btn-primary");
                        bookmarkButton.textContent = bookmarkButton.classList.contains("btn-primary") ? "Unbookmark Post" : "Bookmark Post";
                     } else {
                        throw new Error("Network response was not ok");
                     }
               })
               .catch(error => {
                     console.error("There was a problem with the fetch operation:", error);
               });
            });
         
            const saveButtons = document.querySelectorAll('.saveButton');
         
            saveButtons.forEach(saveButton => {
            saveButton.addEventListener("click", function () {
               const commentId = this.dataset.commentId;
               fetch(`/save-comment/${commentId}`, {
                     method: "POST",
                     headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                     },
                     body: JSON.stringify({
                        commentId: commentId
                     })
               })
               .then(response => {
                     if (response.ok) {
                        // Invert the class toggling and text content
                        if (saveButton.classList.contains("btn-primary")) {
                           saveButton.classList.remove("btn-primary");
                           saveButton.classList.add("btn-outline-primary");
                           saveButton.textContent = "Save";
                        } else {
                           saveButton.classList.remove("btn-outline-primary");
                           saveButton.classList.add("btn-primary");
                           saveButton.textContent = "Unsave";
                        }
                     } else {
                        throw new Error("Network response was not ok");
                     }
               })
               .catch(error => {
                     console.error("There was a problem with the fetch operation:", error);
               });
            });
         });
         
         });
         
         
      </script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script>
         function likePost(postId) {
             $.ajax({
                 type: 'POST',
                 url: '/like-post/' + postId,
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 success: function(response) {
                     $('#likeButton_' + postId).html('<i class="fas fa-thumbs-up"></i> ' + response.likes);
                 }
             });
         }
         
         function dislikePost(postId) {
             $.ajax({
                 type: 'POST',
                 url: '/dislike-post/' + postId,
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 success: function(response) {
                     $('#dislikeButton_' + postId).html('<i class="fas fa-thumbs-down"></i> ' + response.dislikes);
                 }
             });
         }
         
         function likeComment(commentId) {
             $.ajax({
                 type: 'POST',
                 url: '/like-comment/' + commentId,
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 success: function(response) {
                     $('#likeButton_comment_' + commentId).html('<i class="fas fa-thumbs-up"></i> ' + response.likes);
                 }
             });
         }
         
         function dislikeComment(commentId) {
             $.ajax({
                 type: 'POST',
                 url: '/dislike-comment/' + commentId,
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 success: function(response) {
                     $('#dislikeButton_comment_' + commentId).html('<i class="fas fa-thumbs-down"></i> ' + response.dislikes);
                 }
             });
         }
         
         
              
              function updatePostReportCharacterCount() {
                  var commentLength = $('#reportReasonPost').val().length;
                  var totalLength = commentLength;
                  var remainingCharacters = 250 - totalLength;
              
                  $('#characterCountReportPost').text(remainingCharacters);
              
                  if (totalLength > 0) {
                      $('#reportPost').prop('disabled', false);
                  } else {
                      $('#reportPost').prop('disabled', true);
                  }
              }
              
              // Update character count on input change (for posts)
              $('#reportReasonPost').on('input', function() {
                 updatePostReportCharacterCount();
              });
              
              // Initialize character count on document ready (for posts)
              $(document).ready(function() {
                 updatePostReportCharacterCount();
              });
              
              function updateCommentReportCharacterCount() {
                  var commentLength = $('#reportReasonComment').val().length;
                  var totalLength = commentLength;
                  var remainingCharacters = 250 - totalLength;
              
                  $('#characterCountReportComment').text(remainingCharacters);
              
                  if (totalLength > 0) {
                      $('#reportComment').prop('disabled', false);
                  } else {
                      $('#reportComment').prop('disabled', true);
                  }
              }
              
              // Update character count on input change (for posts)
              $('#reportReasonComment').on('input', function() {
                 updateCommentReportCharacterCount();
              });
              
              // Initialize character count on document ready (for posts)
              $(document).ready(function() {
                 updateCommentReportCharacterCount();
              });
           
      </script>
      @include('layouts.footer')
   </body>
</html>