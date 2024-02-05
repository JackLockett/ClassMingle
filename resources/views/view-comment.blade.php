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
                <h3 class="text-center">View Comment</h3>
            </div>
        </div>

        <a href="{{ url('/societies/' . $society->id . '/posts/' . $post->id) }}" class="btn btn-secondary btn-sm mb-3">Return to Post</a>


        <div class="card">
            <div class="card-body">
                <div class="comment">
                    <strong>{{ $comment->user->username }}</strong> said:
                    <p>{{ $comment->comment }}</p>
                </div>

                @if ($comment->responses->count() > 0)
                    <h5 class="mt-3">Responses ({{ $comment->responses->count() }})</h5>
                    @foreach($comment->responses as $response)
                        <div class="response mt-3">
                            <strong>{{ $response->user->username }}</strong> responded:
                            <p>{{ $response->comment }}</p>
                        </div>
                    @endforeach
                @else
                    <p class="mt-3">No responses yet. Be the first to respond!</p>
                @endif

                <form action="{{ route('respond-to-comment', ['societyId' => $society->id, 'postId' => $post->id, 'commentId' => $comment->id]) }}" method="POST">
                    @csrf
                    <div class="form-group mt-3">
                        <label for="response">Your Response:</label>
                        <textarea class="form-control" id="response" name="response" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Response</button>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.footer')
</body>

</html>
