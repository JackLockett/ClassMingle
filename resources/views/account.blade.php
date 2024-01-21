<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Account</title>
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
            <h3 class="text-center">My Account</h3>
         </div>
      </div>
      <br>
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-6">
               <div class="card">
                  <div class="card-header">
                     <b>Class Mingle Account</b>
                  </div>
                  <div class="card-body">
                     <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label"><strong>Email Address:</strong></label>
                        <div class="col-md-8">
                           <input type="email" id="email" class="form-control" value="{{ $email }}" readonly>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="creationDate" class="col-md-4 col-form-label"><strong>Creation Date:</strong></label>
                        <div class="col-md-8">
                           <input type="text" id="creationDate" class="form-control" value="{{ $created_at }}" readonly>
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-12 mb-2">
                           <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#changeEmailModal">Change Email</button>
                        </div>
                        <div class="col-12 mb-2">
                           <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#changePasswordModal">Change Password</button>
                        </div>
                        <div class="col-12">
                           <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteAccountModal">Delete Account</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Modal for Change Email -->
   <div class="modal fade" id="changeEmailModal" tabindex="-1" role="dialog" aria-labelledby="changeEmailModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="changeEmailModalLabel">Change Email</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <form id="changeEmailForm" action="{{ route('change-email') }}" method="POST">
                       @csrf
                       @if ($errors->has('change_email'))
                              <div class="alert alert-danger">
                                 {{ $errors->first('change_email') }}
                              </div>
                        @endif
                       <div class="form-group">
                           <label for="newEmail">New Email Address:</label>
                           <input type="email" class="form-control" id="newEmail" name="newEmail" required>
                       </div>
                       <div class="form-group">
                           <label for="currentPassword">Current Password:</label>
                           <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                       </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                           <button type="submit" class="btn btn-primary">Submit</button>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>

<!-- Modal for Change Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" action="{{ route('change-password') }}" method="POST">
                    @csrf
                    @if ($errors->has('change_password'))
                           <div class="alert alert-danger">
                              {{ $errors->first('change_password') }}
                           </div>
                     @endif
                    <div class="form-group">
                        <label for="currentPassword">Current Password:</label>
                        <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password:</label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmNewPassword">Confirm New Password:</label>
                        <input type="password" class="form-control" id="confirmNewPassword" name="new_password_confirmation" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Change Email Modal -->
<script>
    $(document).ready(function() {
        @if ($errors->has('change_email'))
            $('#changeEmailModal').modal('show');
        @endif
    });
</script>

<!-- JavaScript for Change Password Modal -->
<script>
    $(document).ready(function() {
        @if ($errors->has('change_password'))
            $('#changePasswordModal').modal('show');
        @endif
    });
</script>


   <!-- Modal for Delete Account -->
   <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
       <!-- Similar structure as the Change Email modal -->
   </div>

   @include('layouts.footer')
</body>
</html>
