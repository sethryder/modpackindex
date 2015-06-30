<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Account Confirmation</h2>

<div>
    <p>Someone registered an account under this email at Modpack Index. If this was you follow this link to confirm you account: <a href="{{ $site_url }}user/verify/{{ $confirmation }}" rel="nofollow">{{ $site_url }}user/verify/{{ $confirmation }}</a></p>
    <p>If this was not you, you can safety ignore this email. No further action is required on your part.</p>
</div>
</body>
</html>