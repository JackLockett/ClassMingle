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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3 class="text-center">{{ $society->societyName }}</h3>
            </div>
        </div>
        <a href="{{ route('view-society', ['id' => $society->id]) }}" class="btn btn-secondary mb-3">Return to Society</a>
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{ $post->postTitle }}</h2>
                <p class="card-text">{{ $post->postComment }}</p>
                <p class="card-text">
                    <small class="text-muted">
                        Posted by {{ $post->author->username }} • {{ $post->created_at->diffForHumans() }}
                    </small>
                </p>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Comments ({{ $post->comments->count() }})</h5>
            </div>
            <div class="card-body">
                @foreach ($post->comments as $comment)
                <div class="comment mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $comment->user->username }}</strong> said:
                        </div>
                        <div class="text-muted">
                            <small>{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <p>{{ $comment->comment }}</p>
                </div>
                @endforeach
            </div>
            <div class="card-footer">
                <form action="{{ route('add-comment', ['postId' => $post->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="comment">Add a Comment:</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</body>
</html>
