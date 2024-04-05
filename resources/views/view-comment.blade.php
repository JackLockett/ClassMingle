<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>View Comment</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
               <h3 class="text-center">View Comment</h3>
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
         <a href="{{ url('/societies/' . $society->id . '/posts/' . $post->id) }}" class="btn btn-secondary btn-sm mb-3">
         <i class="fas fa-arrow-left"></i> Return to Post
         </a>
         <div class="card">
            <div class="card-body">
               <div class="comment">
                  <strong>
                  @if($comment->user)
                  <a href="{{ route('user.profile', ['id' => $comment->user->id]) }}">
                  {{ $comment->user->username }}
                  </a>
                  @else
                  <i>Deleted_Account</i>
                  @endif
                  </strong> said:
                  <p>{{ $comment->comment }}</p>
               </div>
               <p class="card-text">
                  <small class="text-muted">
                  Commented by 
                  @if($comment->user)
                  <a href="{{ route('user.profile', ['id' => $comment->user->id]) }}">
                  {{ $comment->user->username }}
                  </a>
                  @else
                  <i>Deleted_Account</i>
                  @endif
                  • {{ $comment->created_at->diffForHumans() }}
                  </small>
               </p>
            </div>
         </div>
         @if ($comment->responses->count() > 0)
         <div class="card mt-3">
            <h5 class="card-header">Responses ({{ $comment->responses->count() }})</h5>
            <div class="card-body">
               @foreach($comment->responses as $response)
               <div class="response mt-3">
                  <div class="d-flex justify-content-between align-items-center">
                     <div>
                        <strong>
                        @if($response->user)
                        <a href="{{ route('user.profile', ['id' => $response->user->id]) }}">
                        {{ $response->user->username }}
                        </a>
                        @else
                        <i>Deleted_Account</i>
                        @endif
                        </strong> responded:
                        <p>{{ $response->comment }}</p>
                     </div>
                     <div class="text-muted">
                        <small>{{ $response->created_at->diffForHumans() }}</small>
                        <div class="button-group ml-2">
                           <button class="btn btn-sm {{ $response->isSaved() ? 'btn-primary' : 'btn-outline-primary' }} saveButton" data-comment-id="{{ $response->id }}">
                           {{ $response->isSaved() ? 'Unsave' : 'Save' }}
                           </button>
                           @if (
                           (is_array($society->moderatorList) && in_array(auth()->user()->id, $society->moderatorList)) || 
                           ($response->user_id == auth()->user()->id) ||
                           (auth()->user()->role == 'admin')
                           )
                           <button class="btn btn-sm btn-danger delete-response-btn" data-toggle="modal" data-target="#confirmDeleteResponse" data-response-id="{{ $response->id }}">
                           <i class="fas fa-trash"></i> Delete
                           </button>
                           @endif
                           @if($response->user_id != auth()->user()->id)
                           <button id="reportButton" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#reportCommentModal" data-society-id="{{ $society->id }}">
                           <i class="fas fa-exclamation-triangle"></i>
                           </button>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
            <div class="card-footer">
               <form action="{{ route('respond-to-comment', ['societyId' => $society->id, 'postId' => $post->id, 'commentId' => $comment->id]) }}" method="POST">
                  @csrf
                  <div class="form-group mt-3">
                     <label for="response">Your Response:</label>
                     <textarea class="form-control" id="response" name="response" rows="3" required></textarea>
                     <small>Characters remaining: <span id="charCount">250</span></small>
                  </div>
                  <button type="submit" id="submitResponse" class="btn btn-primary">
                  <i class="fas fa-reply"></i> Submit Response
                  </button>
               </form>
            </div>
         </div>
         @else
         <div class="card mt-3">
            <div class="card-body">
               <p>No responses yet. Be the first to respond!</p>
            </div>
            <div class="card-footer">
               <form action="{{ route('respond-to-comment', ['societyId' => $society->id, 'postId' => $post->id, 'commentId' => $comment->id]) }}" method="POST">
                  @csrf
                  <div class="form-group">
                     <label for="comment">Your Response:</label>
                     <textarea class="form-control" id="response" name="response" rows="3" required></textarea>
                     <small>Characters remaining: <span id="charCount">250</span></small>
                  </div>
                  <button type="submit" id="submitResponse" class="btn btn-primary">
                  <i class="fas fa-reply"></i> Submit Response
                  </button>
               </form>
            </div>
         </div>
         @endif
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
                  <form action="{{ route('report-comment', ['postId' => $comment->post_id, 'commentId' => $comment->parent_comment_id ?: $comment->id]) }}" method="POST">
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
      <!-- Modal for Confirming Response Deletion -->
      <div class="modal fade" id="confirmDeleteResponse" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteResponseLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="confirmDeleteResponseLabel">Confirm Delete Response</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <p>Are you sure you want to delete this response?</p>
               </div>
               <div class="modal-footer">
                  <form id="deleteResponseForm" action="" method="POST">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-danger">
                     <i class="fas fa-trash"></i> Delete Response
                     </button>
                  </form>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
               </div>
            </div>
         </div>
      </div>
      <script>
         document.addEventListener("DOMContentLoaded", function () {
             const responseTextarea = document.getElementById("response");
             const maxCharCount = 250;
         
             updateCharCount();
         
             responseTextarea.addEventListener("input", function () {
                 updateCharCount();
             });
         
             function updateCharCount() {
                 const charCount = responseTextarea.value.length;
                 const remainingChars = maxCharCount - charCount;
                 const charCountSpan = document.getElementById("charCount");
         
                 charCountSpan.textContent = remainingChars;
                 charCountSpan.style.color = remainingChars >= 0 ? "black" : "red";
         
                 const submitButton = document.getElementById("submitResponse");
                 submitButton.disabled = remainingChars < 0 || remainingChars === maxCharCount;
             }
         
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
         
             const deleteResponseButtons = document.querySelectorAll('.delete-response-btn');
             const deleteResponseForm = document.getElementById('deleteResponseForm');
             deleteResponseButtons.forEach(button => {
                 button.addEventListener('click', function () {
                     const responseId = this.getAttribute('data-response-id');
                     deleteResponseForm.action = `/delete-comment/${responseId}`;
                 });
             });
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