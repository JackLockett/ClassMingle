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
                    <strong>
                        <a href="{{ route('user.profile', ['id' => $comment->user->id]) }}">
                            {{ $comment->user->username }}
                        </a>
                    </strong> said:
                    <p>{{ $comment->comment }}</p>
                </div>

                <p class="card-text">
                    <small class="text-muted">
                        Commented by 
                        <a href="{{ route('user.profile', ['id' => $comment->user->id]) }}">
                            {{ $comment->user->username }}
                        </a>
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
                                    <a href="{{ route('user.profile', ['id' => $response->user->id]) }}">
                                        {{ $response->user->username }}
                                    </a>
                                </strong> responded:
                                <p>{{ $response->comment }}</p>
                            </div>

                            <div class="text-muted">
                                <small>{{ $response->created_at->diffForHumans() }}</small>
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
                        <button type="submit" id="submitResponse" class="btn btn-primary">Submit Response</button>
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
                        <button type="submit" id="submitResponse" class="btn btn-primary">Submit Response</button>
                    </form>
                </div>
            </div>
        @endif

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const commentTextarea = document.getElementById("response");
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

                const submitButton = document.getElementById("submitResponse");
                submitButton.disabled = remainingChars < 0 || remainingChars === maxCharCount;
            }
        });
    </script>

    @include('layouts.footer')
</body>

</html>
