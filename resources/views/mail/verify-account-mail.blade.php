<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Verify Account</title>
   </head>
   <body>
      <p>Hello {{ $user->username }},</p>
      <p>Welcome to Class Mingle! To verify your account, please click the following link:</p>
      <p><a href="{{ route('verify-account', ['token' => $token]) }}">Verify Account</a></p>
      <p>If you did not create an account with us, you can ignore this email.</p>
      <p>Regards,<br>Class Mingle Team</p>
   </body>
</html>
