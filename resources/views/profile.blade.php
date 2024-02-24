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
         .btn-toggle {
         border-radius: 25px;
         font-size: 0.9rem;
         padding: 10px 20px;
         }
         .collapse {
         transition: height 0.5s ease;
         }
         .card {
         border-radius: 15px;
         box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
         }
         .form-control {
         border-radius: 15px;
         }
      </style>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-4">
         <div class="row justify-content-center">
            <div class="col-md-8">
               <h3 class="text-center">My Profile</h3>
            </div>
         </div>
         <div class="row mt-4">
            <div class="col-md-12">
               <div class="btn-group-toggle d-flex" data-toggle="buttons">
                  <label class="btn btn-secondary mr-2 btn-toggle active">
                  <input type="checkbox" checked autocomplete="off" onclick="toggleCollapse('multiCollapseExample1', this)"> My Profile Settings
                  </label>
                  <label class="btn btn-secondary mr-2 btn-toggle">
                  <input type="checkbox" autocomplete="off" onclick="toggleCollapse('multiCollapseExample2', this)"> My Societies
                  </label>
                  <label class="btn btn-secondary mr-2 btn-toggle">
                  <input type="checkbox" autocomplete="off" onclick="toggleCollapse('multiCollapseExample3', this)"> Bookmarked Posts
                  </label>
                  <label class="btn btn-secondary mr-2 btn-toggle">
                  <input type="checkbox" autocomplete="off" onclick="toggleCollapse('multiCollapseExample4', this)"> Bookmarked Comments
                  </label>
               </div>
            </div>
         </div>
         <div class="row mt-4">
            <div class="col col-12">
               <div class="collapse show" id="multiCollapseExample1">
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
            </div>
            <div class="col col-12">
               <div class="collapse" id="multiCollapseExample2">
                  <div class="card">
                     <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">My Societies</h5>
                     </div>
                     <div class="card-body">
                        <p class="card-text">You haven't joined any societies yet.</p>
                        <a href="#" class="btn btn-primary">
                        <i class="fas fa-search"></i> Discover Societies
                        </a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col col-12">
               <div class="collapse" id="multiCollapseExample3">
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
                           </div>
                        </div>
                        @endforeach
                        @else
                        <p class="card-text">You haven't bookmarked any posts yet.</p>
                        <a href="#" class="btn btn-primary">
                        <i class="fas fa-compass"></i> Explore Content
                        </a>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col col-12">
            <div class="collapse" id="multiCollapseExample4">
               <div class="card">
                  <div class="card-header bg-secondary text-white">
                     <h5 class="mb-0">Bookmarked Comments</h5>
                  </div>
                  <div class="card-body">
                     <p class="card-text">You haven't bookmarked any comments yet.</p>
                     <a href="#" class="btn btn-primary">
                     <i class="fas fa-compass"></i> Explore Content
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
      @include('layouts.footer')
      <script>
         function toggleCollapse(id, button) {
           $('[id^="multiCollapseExample"]').not('#' + id).collapse('hide');
           setTimeout(function() {
             $('#' + id).collapse('toggle');
           }, 300);
         
           $('.btn-toggle').removeClass('active');
           $(button).addClass('active');
         }
      </script>
   </body>
</html>