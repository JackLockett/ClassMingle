<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Password Reset</title>
   </head>
   <body>
      <p>Hello {{ $user->name }},</p>
      <p>You have requested to reset your password. Please click the following link to reset your password:</p>
      <p><a href="{{ url('reset-password?token=' . $token) }}">Reset Password</a></p>
      <p>If you did not request a password reset, you can ignore this email.</p>
      <p>Regards,<br>Class Mingle Team</p>
   </body>
</html>