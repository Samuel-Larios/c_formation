<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Student Account</title>
</head>
<body>
    <h2>Hello {{ $student->name }},</h2>

    <p>Your account has been successfully created.</p>

    <p>Here are your login credentials:</p>
    <ul>
        <li><strong>Email:</strong> {{ $student->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>We recommend that you change your password after your first login.</p>

    <p>Thank you.</p>
</body>
</html>
