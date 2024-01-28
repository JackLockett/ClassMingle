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
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3 class="text-center">{{ $society->societyName }} Society</h3>
            </div>
        </div>

        <br>

        <div id="alertContainer"></div>

        <div class="row">
            <div class="col-md-12">
               <div class="card mb-3">
                  <div class="card-header">Society Information</div>
                  <div class="card-body">
                     <h5 class="card-title">About {{ $society->societyName }}</h5>
                     <p class="card-text">
                     {{ $society->societyDescription }}
                     </p>
                     @if (is_array(json_decode($society->memberList, true)) && in_array(auth()->user()->id, json_decode($society->memberList, true)))
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createAcademicModal">Create A Post</a>
                        @if ($society->ownerId != auth()->user()->id)
                            <a href="#" class="btn btn-danger" id="leaveSocietyBtn" data-society-id="{{ $society->id }}">Leave Society</a>
                        @endif
                    @else
                        <a href="#" class="btn btn-success" id="joinSocietyBtn" data-society-id="{{ $society->id }}">Join Society</a>
                    @endif

                    @if ($society->ownerId == auth()->user()->id)
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#createAcademicModal">Edit Society Info</a>
                    @endif


                     <a href="{{ route('societies') }}" class="btn btn-secondary">Return</a>
                  </div>
               </div>
            </div>
         </div>


        <div class="row">
            <div class="col-md-9">
               <div class="card mb-3">
                  <div class="card-header">Society Feed</div>
                  <div class="card-body">
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="card mb-3">
                  <div class="card-header">Member Info</div>
                  <div class="card-body text-info">
                    <p class="card-text">
                        {{ count(json_decode($society->memberList, true)) }}
                        Member{{ count(json_decode($society->memberList, true)) != 1 ? 's' : '' }}
                    </p>
                  </div>
               </div>
            </div>
         </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const handleSocietyAction = (e, action) => {
                e.preventDefault();

                const buttonId = action === 'join' ? 'joinSocietyBtn' : 'leaveSocietyBtn';
                const actionMessage = action === 'join' ? 'Successfully joined the society!' : 'You have left this society!';
                const errorMessage = action === 'join' ? 'Failed to join the society.' : 'Failed to leave the society.';

                console.log(`${action.charAt(0).toUpperCase() + action.slice(1)} button clicked`);

                const societyId = document.getElementById(buttonId).getAttribute('data-society-id');

                fetch(`/${action}-society/${societyId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    showAlert(data.success, data.success ? actionMessage : errorMessage);

                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                });
            };

            const showAlert = (success, message) => {
                const alertClass = success ? 'alert-success' : 'alert-danger';
                const alertContainer = document.getElementById('alertContainer');
                const alertDiv = document.createElement('div');

                alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
                alertDiv.role = 'alert';
                alertDiv.innerHTML = `<strong>${message}</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>`;

                alertContainer.appendChild(alertDiv);
            };

            const addSocietyButtonListener = (buttonId, action) => {
                const societyBtn = document.getElementById(buttonId);
                if (societyBtn) {
                    societyBtn.addEventListener('click', e => handleSocietyAction(e, action));
                }
            };

            addSocietyButtonListener('joinSocietyBtn', 'join');
            addSocietyButtonListener('leaveSocietyBtn', 'leave');
        });

    </script>

    @include('layouts.footer')

</body>
</html>
