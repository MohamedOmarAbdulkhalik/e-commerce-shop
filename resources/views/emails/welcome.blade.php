<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
    <h1>Welcome to {{ config('app.name') }}, {{ $user->name }}!</h1>
    <p>Thank you for registering with us. We're excited to have you on board.</p>
    <p>Start exploring our products and enjoy your shopping experience!</p>
</body>
</html>