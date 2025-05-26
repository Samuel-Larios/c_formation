<!DOCTYPE html>
<html>
<head>
    <title>User Account Created</title>
</head>
<body>
    <h1>Hello {{ $utilisateur->name }},</h1>
    <p>Your account has been successfully created. Here are your login details:</p>
    <ul>
        <li><strong>Email:</strong> {{ $utilisateur->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    <p>Please remember to change your password after your first login.</p>
    <p>Best regards,</p>
    <p>The Management Team</p>
</body>
</html>
