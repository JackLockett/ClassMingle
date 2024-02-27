<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Discovery</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   </head>
   <body>
      @include('layouts.navbar')
      <div class="container mt-3">
         <div class="row justify-content-center">
            <div class="col-md-8">
               <h3 class="text-center">Class Mingle - Discovery Page</h3>
            </div>
         </div>
         <br>
         <div class="row">
            <div class="col-md-9">
               <div class="card mb-3">
                  <div class="card-header">Your Personal Feed</div>
                  <div class="card-body">
                     @if($personalFeedPosts->isEmpty())
                     <p class="card-text">It seems like there's nothing in your feed right now from the societies you're a member of.</p>
                     @else
                     @foreach($personalFeedPosts as $post)
                     <div class="card">
                        <div class="card-body">
                           <h5 class="card-title">{{ $post->postTitle }}</h5>
                           <h6 class="card-subtitle mb-2 text-muted">{{ $post->society->societyName }}</h6>
                           <p class="card-text">{{ $post->postComment }}</p>
                        </div>
                     </div>
                     <br>
                     @endforeach
                     @endif
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card mb-3">
                  <div class="card-header">Suggested Societies</div>
                  <div class="card-body text-primary">
                     @if($suggestedSocieties->isEmpty())
                     <p>No suggested societies are available at this time. Please check again later.</p>
                     @else
                     @foreach($suggestedSocieties as $society)
                     <p class="card-text">
                        <a href="{{ route('view-society', ['id' => $society->id]) }}">
                        {{ $society->societyName }}
                        </a>
                        - {{ count(json_decode($society->memberList, true)) }} Member{{ count(json_decode($society->memberList, true)) != 1 ? 's' : '' }}
                     </p>
                     @endforeach
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
      @include('layouts.footer')
   </body>
</html>