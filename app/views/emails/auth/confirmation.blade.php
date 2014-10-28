<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Account Confirmation</h2>

<div>
    <p>Someone registered an account under this email at Modpack Index. If this was you follow this link to confirm you account: {{ URL::to('user/verify', array($confirmation)) }}.</p>
    <p>If this was not you, you can safety ignore this email. No further action is required on your part.</p>
</div>
</body>
</html>