<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h1>Password Reset Code</h1>
    <p>Dear {{ $user->name }},</p>
    <p>We received a request to reset your password. Please use the following code to reset your password:</p>
    <h2>{{ $code }}</h2>
    <p>This code will expire in 60 minutes.</p>
    <p>If you did not request a password reset, please ignore this email.</p>
    <p>Best Regards,<br/>Your Company Name</p>
</body>
</html>
