<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Link</title>
</head>
<body>
    <h1>Password Reset Link</h1>
    <p>Hello {{ $user->name }},</p>
    <p>Click the link below to reset your password:</p>
    <a href="{{ $resetLink }}">{{ $resetLink }}</a>
    <p>This link will expire in 60 minutes.</p>
</body>
</html>
