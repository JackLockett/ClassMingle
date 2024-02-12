<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap Example</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .multi-collapse {
      transition: height 0.3s ease-in-out;
    }
  </style>
</head>
<body>
@include('layouts.navbar')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center">My Profile</h3>
        </div>
    </div>
    <br>
    <p class="d-inline-flex gap-1">
    <a class="btn btn-primary mr-2" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" onclick="toggleCollapse('multiCollapseExample1')">Toggle My Profile Settings</a>
<button class="btn btn-primary mr-2" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2" onclick="toggleCollapse('multiCollapseExample2')">Toggle My Societies</button>
<button class="btn btn-primary mr-2" type="button" data-toggle="collapse" data-target="#multiCollapseExample3" aria-expanded="false" aria-controls="multiCollapseExample3" onclick="toggleCollapse('multiCollapseExample3')">Toggle My Bookmarks</button>

    </p>
    <div class="row">
        <div class="col col-12">
            <div class="collapse multi-collapse" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-header">
                        My Profile Settings
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

                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12">
            <div class="multi-collapse collapse" id="multiCollapseExample2">
                <div class="card">
                    <div class="card-header">
                        My Societies
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12">
            <div class="multi-collapse collapse" id="multiCollapseExample3">
                <div class="card">
                    <div class="card-header">
                        My Bookmarks
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')

</body>
</html>
