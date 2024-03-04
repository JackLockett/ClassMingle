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
               <ul class="nav nav-pills justify-content-center mb-4" id="pills-tab" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" id="profile-settings-tab" data-toggle="pill" href="#profile-settings" role="tab" aria-controls="profile-settings" aria-selected="true">My Profile Settings</a>
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
               </ul>
            </div>
         </div>
         <div class="row mt-4">
            <div class="col-md-12">
               <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade show active" id="profile-settings" role="tabpanel" aria-labelledby="profile-settings-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">My Profile Settings</h5>
                        </div>
                        <div class="card-body">
                           <form method="POST" action="{{ route('profile-update') }}">
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
                              <button type="submit" class="btn btn-success">
                              <i class="fas fa-save"></i> Update Profile
                              </button>
                           </form>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="my-societies" role="tabpanel" aria-labelledby="my-societies-tab">
                  <div class="card">
                     <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">My Societies</h5>
                     </div>
                     <div class="card-body">
                        @if($joinedSocieties->count() > 0)
                              <div class="table-responsive">
                                 <table class="table">
                                    <thead>
                                          <tr>
                                             <th>Name</th>
                                             <th>Description</th>
                                             <th>Actions</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          @foreach($joinedSocieties as $society)
                                             <tr>
                                                <td>{{ $society->societyName }}</td>
                                                <td>{{ $society->societyDescription }}</td>
                                                <td>
                                                   <a href="{{ route('view-society', ['id' => $society->id]) }}" class="btn btn-primary">View Society</a>
                                                </td>
                                             </tr>
                                          @endforeach
                                    </tbody>
                                 </table>
                              </div>
                        @else
                              <p class="card-text">You haven't joined any societies yet.</p>
                              <a href="#" class="btn btn-primary">
                                 <i class="fas fa-search"></i> Discover Societies
                              </a>
                        @endif
                     </div>
                  </div>
                  </div>
                  <div class="tab-pane fade" id="bookmarked-posts" role="tabpanel" aria-labelledby="bookmarked-posts-tab">
                     <div class="card">
                        <div class="card-header bg-secondary text-white">
                           <h5 class="mb-0">Bookmarked Posts</h5>
                        </div>
                        <div class="card-body">
                           @if($bookmarks->count() > 0)
                           @foreach($bookmarks as $bookmark)
                           <div class="card mb-3">
                              <div class="card-body">
                                 <h5 class="card-title">{{ $bookmark->post->postTitle }}</h5>
                                 <p class="card-text">Post Author: {{ $bookmark->post->author->username }}</p>
                                 <a href="{{ route('view-post', ['societyId' => $bookmark->post->societyId, 'postId' => $bookmark->post->id]) }}" class="btn btn-primary">View Post</a>
                                 <a href="#" class="btn btn-danger"
                                    onclick="event.preventDefault();
                                             document.getElementById('unbookmark-post-form-{{$bookmark->post->id}}').submit();">
                                    Unbookmark
                                 </a>

                                 <form id="unbookmark-post-form-{{$bookmark->post->id}}" action="{{ route('unbookmark.post', ['postId' => $bookmark->post->id]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                 </form>


                              </div>
                           </div>
                           @endforeach
                           @else
                           <p class="card-text">You haven't bookmarked any posts yet.</p>
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
                           <h5 class="mb-0">Saved Comments</h5>
                        </div>
                        <div class="card-body">
                           @if($comments->count() > 0)
                           @foreach($comments as $savedComment)
                              <div class="card mb-3">
                                 <div class="card-body">
                                       <h5 class="card-title">{{ $savedComment->comment->comment }}</h5>
                                       <p class="card-text">Comment Author: {{ $savedComment->user->username }}</p>
                                       <a href="{{ route('view-comment', ['societyId' => $savedComment->comment->post->societyId, 'postId' => $savedComment->comment->post->id, 'commentId' => $savedComment->comment->id]) }}" class="btn btn-primary">View Comment</a>
                                       <!-- Form for unsave comment -->
                                       <form id="unsave-comment-form-{{$savedComment->comment->id}}" action="{{ route('unsave-comment', ['commentId' => $savedComment->comment->id]) }}" method="POST" style="display: inline;">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-danger">Unsave Comment</button>
                                       </form>
                                 </div>
                              </div>
                           @endforeach
                           @else
                           <p class="card-text">You haven't saved any comments yet.</p>
                           <a href="/societies" class="btn btn-primary">
                           <i class="fas fa-compass"></i> Explore Content
                           </a>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
   </body>
</html>