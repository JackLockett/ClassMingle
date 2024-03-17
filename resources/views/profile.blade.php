<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>My Profile</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <style>
         body {
         font-family: 'Arial', sans-serif;
         background-color: #f8f9fa;
         }
         .nav-pills .nav-link {
         border-radius: 25px;
         }
         .nav-pills .nav-link.active {
         background-color: #007bff;
         color: #fff;
         }
         .card {
         border-radius: 15px;
         box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
         }
         .form-control {
         border-radius: 15px;
         }
         .tab-pane {
         opacity: 0;
         transition: opacity 0.3s ease;
         }
         .tab-pane.fade.show {
         opacity: 1;
         }
         .notification-badge {
         position: relative;
         display: inline-block;
         }
         .notification-badge .badge {
         position: absolute;
         top: -8px;
         right: -2px;
         background-color: crimson;
         color: white;
         border-radius: 50%;
         padding: 5px 8px;
         font-size: 12px;
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-3">
         <div class="row justify-content-center">
            <div class="col-md-12">
               <!-- <h3 class="text-center">My Profile</h3> -->
            </div>
         </div>
         <div class="row mt-4">
            <div class="col-md-12">
               <a href="{{ route('user.profile', ['id' => Auth::id()]) }}" class="btn btn-info btn-sm mb-3">
               <i class="fas fa-user"></i> View Public Profile
               </a>
               <br><br>
               <ul class="nav nav-pills justify-content-center mb-4" id="pills-tab" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" id="profile-settings-tab" data-toggle="pill" href="#profile-settings" role="tab" aria-controls="profile-settings" aria-selected="true">Profile Settings</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="friends-tab" data-toggle="pill" href="#friends" role="tab" aria-controls="friends" aria-selected="false">My Friends</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="my-societies-tab" data-toggle="pill" href="#my-societies" role="tab" aria-controls="my-societies" aria-selected="false">My Societies</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="bookmarked-posts-tab" data-toggle="pill" href="#bookmarked-posts" role="tab" aria-controls="bookmarked-posts" aria-selected="false">Bookmarked Posts</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="saved-comments-tab" data-toggle="pill" href="#saved-comments" role="tab" aria-controls="saved-comments" aria-selected="false">Saved Comments</a>
                  </li>
                  <hr>
                  <li class="nav-item">
                     <a class="nav-link notification-badge" id="friend-requests-tab" data-toggle="pill" href="#friend-requests" role="tab" aria-controls="friend-requests" aria-selected="false">Friend Requests <span class="badge">{{ count($receivedFriendRequests) }}</span></a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link notification-badge" id="messages-tab" data-toggle="pill" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Messages <span class="badge">{{ count($receivedMessages) }}</span></a>
                  </li>
               </ul>
            </div>
         </div>
         <div class="row mt-4">
            <div class="col-md-12">
               <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade show active" id="profile-settings" role="tabpanel" aria-labelledby="profile-settings-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">Profile Settings</h5>
                        </div>
                        <div class="card-body">
                           <div class="modal fade" id="changeAvatarModal" tabindex="-1" role="dialog" aria-labelledby="changeAvatarModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="changeAvatarModalLabel">Change Avatar</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <form method="POST" action="{{ route('profile-update') }}" enctype="multipart/form-data">
                                       @csrf
                                       <div class="modal-body">
                                          <div class="text-center mb-3">
                                             @if ($avatar)
                                             <img src="{{ asset($avatar) }}" alt="Current Profile Picture" class="img-fluid rounded-circle" style="max-width: 200px;">
                                             @endif
                                          </div>
                                          <div class="form-group">
                                             <input type="file" class="form-control-file" id="avatar" name="avatar">
                                          </div>
                                       </div>
                                       <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          <button type="submit" class="btn btn-success">
                                          <i class="fas fa-save"></i> Update Avatar
                                          </button>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
                           <form method="POST" action="{{ route('profile-update') }}" enctype="multipart/form-data">
                              @csrf
                              <div class="form-group">
                                 <label for="bio">Bio:</label>
                                 <textarea class="form-control" id="bio" name="bio" rows="3" <?php if (empty($bio)) echo 'placeholder="You don\'t have a bio. Add one now!"'; ?>><?php echo $bio; ?></textarea>
                              </div>
                              <div class="form-group">
                                 <label for="university">University:</label>
                                 <select class="form-control" id="university" name="university">
                                    <option value="">Select University</option>
                                    @foreach($ukUniversities as $uni)
                                    <option value="{{ $uni }}" {{ $university == $uni ? 'selected' : '' }}>{{ $uni }}</option>
                                    @endforeach
                                 </select>
                              </div>
                              <br>
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changeAvatarModal">
                              <i class="fas fa-image"></i> Change Avatar
                              </button>
                              <button type="submit" class="btn btn-success">
                              <i class="fas fa-save"></i> Update Details
                              </button>
                           </form>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="my-societies" role="tabpanel" aria-labelledby="my-societies-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">My Societies - {{ $joinedSocieties->count() }}</h5>
                        </div>
                        <div class="card-body">
                           @if($joinedSocieties->count() > 0)
                           <div class="table-responsive">
                              <table class="table">
                                 <thead>
                                    <tr>
                                       <th>Name</th>
                                       <th>Description</th>
                                       <th>Society Role</th>
                                       <th>Actions</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($joinedSocieties as $society)
                                    <tr>
                                       <td>{{ $society->societyName }}</td>
                                       <td>{{ $society->societyDescription }}</td>
                                       <td>{{ $society->getUserRole($userId) }}</td>
                                       <td>
                                          <a href="{{ route('view-society', ['id' => $society->id]) }}" class="btn btn-primary"><i class="fas fa-eye"></i> View Society</a>
                                       </td>
                                    </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                           @else
                           <p class="card-text text-muted">You haven't joined any societies yet.</p>
                           <a href="/societies" class="btn btn-primary">
                           <i class="fas fa-search"></i> Discover Societies
                           </a>
                           @endif
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="bookmarked-posts" role="tabpanel" aria-labelledby="bookmarked-posts-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">Bookmarked Posts - {{ $bookmarks->count() }}</h5>
                        </div>
                        <div class="card-body">
                           @if($bookmarks->count() > 0)
                           @foreach($bookmarks as $bookmark)
                           <div class="card mb-3">
                              <div class="card-body">
                                 <h5 class="card-title">{{ $bookmark->post->postTitle }}</h5>
                                 <p class="card-text">Post Author: {{ $bookmark->post->author->username }}</p>
                                 <a href="{{ route('view-post', ['societyId' => $bookmark->post->societyId, 'postId' => $bookmark->post->id]) }}" class="btn btn-primary"><i class="fas fa-eye"></i> View Post</a>
                                 <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteBookmark{{$bookmark->id}}" data-message-id="{{ $bookmark->id }}">
                                 <i class="fas fa-bookmark"></i> Unbookmark
                                 </a>
                              </div>
                           </div>
                           <!-- Modal for Confirming Bookmark Deletion -->
                           <div class="modal fade" id="confirmDeleteBookmark{{$bookmark->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteBookmarkLabel{{$bookmark->id}}" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="confirmDeleteBookmarkLabel{{$bookmark->id}}">Confirm Unbookmark</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body">
                                       <p>Are you sure you want to unbookmark this post?</p>
                                    </div>
                                    <div class="modal-footer">
                                       <form id="unbookmark-post-form-{{$bookmark->post->id}}" action="{{ route('unbookmark.post', ['postId' => $bookmark->post->id]) }}" method="POST" style="display: none;">
                                          @csrf
                                          @method('DELETE')
                                       </form>
                                       <a href="#" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('unbookmark-post-form-{{$bookmark->post->id}}').submit();"><i class="fas fa-bookmark"></i> Unbookmark</a>
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endforeach
                           @else
                           <p class="card-text text-muted">You haven't bookmarked any posts yet.</p>
                           <a href="/societies" class="btn btn-primary">
                           <i class="fas fa-compass"></i> Explore Content
                           </a>
                           @endif
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="saved-comments" role="tabpanel" aria-labelledby="saved-comments-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">Saved Comments - {{ $comments->count() }}</h5>
                        </div>
                        <div class="card-body">
                           @if($comments->count() > 0)
                           @foreach($comments as $savedComment)
                           <div class="card mb-3">
                              <div class="card-body">
                                 <h5 class="card-title">{{ $savedComment->comment->comment }}</h5>
                                 <p class="card-text">Comment Author: {{ $savedComment->comment->user->username }}</p>
                                 <a href="{{ route('view-comment', ['societyId' => $savedComment->comment->post->societyId, 'postId' => $savedComment->comment->post->id, 'commentId' => $savedComment->comment->id]) }}" class="btn btn-primary"><i class="fas fa-eye"></i> View Comment</a>
                                 <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteComment{{$savedComment->comment->id}}" data-message-id="{{ $savedComment->comment->id }}">
                                 <i class="fas fa-comment"></i> Unsave Comment
                                 </a>
                              </div>
                           </div>
                           <!-- Modal for Confirming Saved Comment Deletion -->
                           <div class="modal fade" id="confirmDeleteComment{{$savedComment->comment->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCommentLabel{{$savedComment->comment->id}}" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="confirmDeleteCommentLabel{{$savedComment->comment->id}}">Confirm Unsave Comment</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body">
                                       <p>Are you sure you want to unsave this comment?</p>
                                    </div>
                                    <div class="modal-footer">
                                       <form id="unsave-comment-form-{{$savedComment->comment->id}}" action="{{ route('unsave-comment', ['commentId' => $savedComment->comment->id]) }}" method="POST" style="display: inline;">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-danger"><i class="fas fa-comment"></i> Unsave Comment</button>
                                       </form>
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endforeach
                           @else
                           <p class="card-text text-muted">You haven't saved any comments yet.</p>
                           <a href="/societies" class="btn btn-primary">
                           <i class="fas fa-compass"></i> Explore Content
                           </a>
                           @endif
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="friends" role="tabpanel" aria-labelledby="friends-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">My Friends - {{ $friends->count() }}</h5>
                        </div>
                        <div class="card-body">
                           @if($friends->count() > 0)
                           <table class="table">
                              <thead>
                                 <tr>
                                    <th>Username</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($friends as $friend)
                                 <tr>
                                    <td>{{ $friend->username }}</td>
                                    <td>
                                       <a href="{{ route('user.profile', ['id' => $friend->id]) }}" class="btn btn-primary"><i class="fas fa-user"></i> View Profile</a>
                                       <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirmRemoveFriend{{$friend->id}}" data-message-id="{{ $friend->id }}">
                                       <i class="fas fa-user-minus"></i> Remove Friend
                                       </a>
                                    </td>
                                 </tr>
                                 <!-- Modal for Confirming Friend Removal -->
                                 <div class="modal fade" id="confirmRemoveFriend{{$friend->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmRemoveFriendLabel{{$friend->id}}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h5 class="modal-title" id="confirmRemoveFriendLabel{{$friend->id}}">Confirm Remove Friend</h5>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                             </button>
                                          </div>
                                          <div class="modal-body">
                                             <p>Are you sure you want to remove this user?</p>
                                          </div>
                                          <div class="modal-footer">
                                             <button class="btn btn-danger" onclick="removeFriend({{ $friend->id }})"><i class="fas fa-user-minus"></i> Remove Friend</button>
                                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 @endforeach
                              </tbody>
                           </table>
                           @else
                           <p class="text-muted">You don't have any friends at the moment. Why not try adding someone?</p>
                           @endif
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="friend-requests" role="tabpanel" aria-labelledby="friend-requests-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">Friend Requests - {{ count($receivedFriendRequests) }}</h5>
                        </div>
                        <div class="card-body">
                           <ul class="nav nav-tabs" id="friendRequestTabs" role="tablist">
                              <li class="nav-item">
                                 <a class="nav-link active" id="received-requests-tab" data-toggle="tab" href="#received-requests" role="tab" aria-controls="received-requests" aria-selected="true">Received</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" id="sent-requests-tab" data-toggle="tab" href="#sent-requests" role="tab" aria-controls="sent-requests" aria-selected="false">Sent</a>
                              </li>
                           </ul>
                           <br>
                           <div class="tab-content" id="friendRequestTabsContent">
                              <div class="tab-pane fade show active" id="received-requests" role="tabpanel" aria-labelledby="received-requests-tab">
                                 @if(count($receivedFriendRequests) > 0)
                                 <div class="table-responsive">
                                    <table class="table table-bordered">
                                       <thead class="thead-light">
                                          <tr>
                                             <th>From</th>
                                             <th>Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          @foreach($receivedFriendRequests as $request)
                                          <tr>
                                             <td>{{ $request->sender->username ?? '' }}</td>
                                             <td>
                                                <button class="btn btn-success" onclick="acceptFriendRequest({{ $request->id }})"><i class="fas fa-check"></i> Accept</button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteReceivedFriendRequest{{$request->id}}"><i class="fas fa-times"></i> Deny</button>
                                                <!-- Modal for Confirming Received Friend Request Denial -->
                                                <div class="modal fade" id="confirmDeleteReceivedFriendRequest{{$request->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteReceivedFriendRequestLabel{{$request->id}}" aria-hidden="true">
                                                   <div class="modal-dialog modal-lg" role="document">
                                                      <div class="modal-content">
                                                         <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmDeleteReceivedFriendRequestLabel{{$request->id}}">Confirm Deny Friend Request</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                         </div>
                                                         <div class="modal-body">
                                                            <p>Are you sure you want to deny this friend request?</p>
                                                         </div>
                                                         <div class="modal-footer">
                                                            <button class="btn btn-danger" onclick="denyFriendRequest({{ $request->id }})"><i class="fas fa-times"></i> Deny</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </td>
                                          </tr>
                                          @endforeach
                                       </tbody>
                                    </table>
                                 </div>
                                 @else
                                 <p class="text-muted">You have no received friend requests right now.</p>
                                 @endif
                              </div>
                              <div class="tab-pane fade" id="sent-requests" role="tabpanel" aria-labelledby="sent-requests-tab">
                                 @if(count($sentFriendRequests) > 0)
                                 <div class="table-responsive">
                                    <table class="table table-bordered">
                                       <thead class="thead-light">
                                          <tr>
                                             <th>To</th>
                                             <th>Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          @foreach($sentFriendRequests as $request)
                                          <tr>
                                             <td>{{ $request->receiver->username ?? '' }}</td>
                                             <td>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteSentFriendRequest{{$request->id}}"><i class="fas fa-trash"></i> Delete</button>
                                                <!-- Modal for Confirming Sent Friend Request Deletion -->
                                                <div class="modal fade" id="confirmDeleteSentFriendRequest{{$request->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteSentFriendRequestLabel{{$request->id}}" aria-hidden="true">
                                                   <div class="modal-dialog modal-lg" role="document">
                                                      <div class="modal-content">
                                                         <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmDeleteSentFriendRequestLabel{{$request->id}}">Confirm Delete Sent Friend Request</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                         </div>
                                                         <div class="modal-body">
                                                            <p>Are you sure you want to delete this sent friend request?</p>
                                                         </div>
                                                         <div class="modal-footer">
                                                            <button class="btn btn-danger" onclick="deletePendingRequest({{ $request->id }})"><i class="fas fa-trash"></i> Delete</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </td>
                                          </tr>
                                          @endforeach
                                       </tbody>
                                    </table>
                                 </div>
                                 @else
                                 <p class="text-muted">You have no sent friend requests right now.</p>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">Messages</h5>
                        </div>
                        <div class="card-body">
                           <ul class="nav nav-tabs" id="messageTabs" role="tablist">
                              <li class="nav-item">
                                 <a class="nav-link active" id="received-tab" data-toggle="tab" href="#received" role="tab" aria-controls="received" aria-selected="true">Received</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" id="sent-tab" data-toggle="tab" href="#sent" role="tab" aria-controls="sent" aria-selected="false">Sent</a>
                              </li>
                           </ul>
                           <br>
                           <div class="tab-content" id="messageTabsContent">
                              <div class="tab-pane fade show active" id="received" role="tabpanel" aria-labelledby="received-tab">
                                 @if(count($receivedMessages) > 0)
                                 <div class="table-responsive">
                                    <table class="table table-bordered">
                                       <thead class="thead-light">
                                          <tr>
                                             <th>From</th>
                                             <th>Message</th>
                                             <th>Received</th>
                                             <th>Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          @foreach($receivedMessages as $message)
                                          <tr>
                                             <td><a href="{{ route('user.profile', ['id' => $message->sender->id]) }}">{{ $message->sender->username ?? '' }}</a></td>
                                             <td>{{ $message->message ?? '' }}</td>
                                             <td>{{ $message->created_at->diffForHumans() }}</td>
                                             <td>
                                                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteReceivedMessage{{$message->id}}" data-message-id="{{ $message->id }}">
                                                <i class="fas fa-trash-alt"></i> Delete Message
                                                </a>
                                             </td>
                                          </tr>
                                          <!-- Modal for Confirming Received Message Deletion -->
                                          <div class="modal fade" id="confirmDeleteReceivedMessage{{$message->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteReceivedMessageLabel{{$message->id}}" aria-hidden="true">
                                             <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                   <div class="modal-header">
                                                      <h5 class="modal-title" id="confirmDeleteReceivedMessageLabel{{$message->id}}">Confirm Delete Message</h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                      </button>
                                                   </div>
                                                   <div class="modal-body">
                                                      Are you sure you want to delete this message?
                                                   </div>
                                                   <div class="modal-footer">
                                                      <button class="btn btn-danger" onclick="deleteMessage({{ $message->id }})" data-message-id="{{ $message->id }}">
                                                      <i class="fas fa-trash-alt"></i> Delete Message
                                                      </button>
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          @endforeach
                                       </tbody>
                                    </table>
                                 </div>
                                 @else
                                 <p class="text-muted">You have no received messages right now.</p>
                                 @endif
                              </div>
                              <div class="tab-pane fade" id="sent" role="tabpanel" aria-labelledby="sent-tab">
                                 @if(count($sentMessages) > 0)
                                 <div class="table-responsive">
                                    <table class="table table-bordered">
                                       <thead class="thead-light">
                                          <tr>
                                             <th>To</th>
                                             <th>Message</th>
                                             <th>Sent</th>
                                             <th>Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          @foreach($sentMessages as $message)
                                          <tr>
                                             <td><a href="{{ route('user.profile', ['id' => $message->recipient->id]) }}">{{ $message->recipient->username ?? '' }}</a></td>
                                             <td>{{ $message->message ?? '' }}</td>
                                             <td>{{ $message->created_at->diffForHumans() }}</td>
                                             <td>
                                                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteSentMessage{{ $message->id }}" data-message-id="{{ $message->id }}">
                                                <i class="fas fa-trash-alt"></i> Delete Message
                                                </a>
                                             </td>
                                          </tr>
                                          <!-- Modal for Confirming Sent Message Deletion -->
                                          <div class="modal fade" id="confirmDeleteSentMessage{{$message->id}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteSentMessageLabel{{$message->id}}" aria-hidden="true">
                                             <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                   <div class="modal-header">
                                                      <h5 class="modal-title" id="confirmDeleteSentMessageLabel{{$message->id}}">Confirm Delete Message</h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                      </button>
                                                   </div>
                                                   <div class="modal-body">
                                                      Are you sure you want to delete this message?
                                                   </div>
                                                   <div class="modal-footer">
                                                      <button class="btn btn-danger" onclick="deleteMessage({{ $message->id }})" data-message-id="{{ $message->id }}">
                                                      <i class="fas fa-trash-alt"></i> Delete Message
                                                      </button>
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          @endforeach
                                       </tbody>
                                    </table>
                                 </div>
                                 @else
                                 <p class="text-muted">You have not sent any messages yet.</p>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <script>
         function acceptFriendRequest(friendRequestId) {
            $.ajax({
               type: 'POST',
               url: '{{ route("acceptFriendRequest") }}',
               data: {
                     '_token': '{{ csrf_token() }}',
                     'friend_request_id': friendRequestId
               },
               success: function(response) {
                     alert('Friend request accepted successfully');
                     location.reload();
               },
               error: function(xhr, status, error) {
                     console.error(xhr.responseText);
               }
            });
         }
         
         
         function denyFriendRequest(requestId) {
            $.ajax({
                  type: 'POST',
                  url: '{{ route("denyFriendRequest") }}',
                  data: {
                     '_token': '{{ csrf_token() }}',
                     'request_id': requestId
                  },
                  success: function(response) {
                     location.reload();
                  },
                  error: function(xhr, status, error) {
                     console.error(xhr.responseText);
                  }
            });
         }
         
         function deletePendingRequest(requestId) {
            $.ajax({
               type: 'POST',
               url: '{{ route("delete-pending-request") }}', 
               data: {
                     '_token': '{{ csrf_token() }}',
                     'request_id': requestId.toString() 
               },
               success: function(response) {
                     location.reload();
               },
               error: function(xhr, status, error) {
                     console.error(xhr.responseText);
               }
            });
         }
         
         
         function removeFriend(friendId) {
            $.ajax({
                  type: 'POST',
                  url: '{{ route("removeFriend") }}',
                  data: {
                     '_token': '{{ csrf_token() }}',
                     'friend_id': friendId
                  },
                  success: function(response) {
                     location.reload();
                  },
                  error: function(xhr, status, error) {
                     console.error(xhr.responseText);
                  }
            });
         }
         
         function deleteMessage(messageId) {
            $.ajax({
               type: 'POST',
               url: '{{ route("delete-message", ["id" => ":id"]) }}'.replace(':id', messageId),
               data: {
                     '_token': '{{ csrf_token() }}'
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