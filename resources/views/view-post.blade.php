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
               <h2 class="card-title">{{ $post->postTitle }}</h2>
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
               <div class="d-flex justify-content-between align-items-end">
                  <button id="bookmarkButton" class="btn btn-sm {{ $post->isBookmarked() ? 'btn-primary' : 'btn-outline-primary' }}">
                  {{ $post->isBookmarked() ? 'Unbookmark Post' : 'Bookmark Post' }}
                  </button>
                  @if (is_array($society->moderatorList) && in_array(auth()->user()->id, $society->moderatorList))
                  <a href="#" class="btn btn-sm btn-danger ml-2" data-toggle="modal" data-target="#confirmDeletePost">
                  <i class="fas fa-trash"></i> Delete Post
                  </a>
                  @endif
               </div>
            </div>
            <div class="card-footer">
               <div>
                  <button id="likeButton" class="btn btn-sm btn-link" onclick="likePost('{{ $post->id }}')">
                  <i class="fas fa-thumbs-up"></i> {{ $post->likes }}
                  </button>
                  <button id="dislikeButton" class="btn btn-sm btn-link" onclick="dislikePost('{{ $post->id }}')">
                  <i class="fas fa-thumbs-down"></i> {{ $post->dislikes }}
                  </button>
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
                        <button class="btn btn-sm {{ $comment->isSaved() ? 'btn-primary' : 'btn-outline-primary' }} ml-2 saveButton" data-comment-id="{{ $comment->id }}">
                        {{ $comment->isSaved() ? 'Unsave' : 'Save' }}
                        </button>
                        @if (is_array($society->moderatorList) && in_array(auth()->user()->id, $society->moderatorList))
                        <a href="#" class="btn btn-sm btn-danger ml-2 delete-comment-btn" data-toggle="modal" data-target="#confirmDeleteComment" data-comment-id="{{ $comment->id }}">
                        <i class="fas fa-trash"></i> Delete
                        </a>
                        @endif
                     </div>
                  </div>
                  <p>{{ $comment->comment }}</p>
                  @if ($comment->responses->count() > 0)
                  <div class="mb-3">
                     <a href="{{ route('view-comment', ['societyId' => $society->id, 'postId' => $post->id, 'commentId' => $comment->id]) }}" class="btn btn-sm btn-link">Respond</a>
                     <span class="ml-3 text-muted">•</span>
                     <small class="text-muted">
                     {{ $comment->responses->count() }} Response{{ $comment->responses->count() != 1 ? 's' : '' }}
                     </small>
                  </div>
                  @else
                  <a href="{{ route('view-comment', ['societyId' => $society->id, 'postId' => $post->id, 'commentId' => $comment->id]) }}" class="btn btn-sm btn-link">Respond</a>
                  @endif
                  @if (!$loop->last)
                  <hr>
                  @endif
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
                     $('#likeButton').html('<i class="fas fa-thumbs-up"></i> ' + response.likes);
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
                     $('#dislikeButton').html('<i class="fas fa-thumbs-down"></i> ' + response.dislikes);
                 }
             });
         }
      </script>
      @include('layouts.footer')
   </body>
</html>