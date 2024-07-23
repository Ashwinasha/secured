<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Email Verification</h1>
    <p>Dear {{ $user->name }},</p>
    <p>Thank you for registering. Please use the following link to verify your email address:</p>
    <a href="{{ $verificationLink }}">Verify Email</a>
    <p>This link will expire in 60 minutes.</p>
    <p>Best Regards</p>
</body>
</html>
