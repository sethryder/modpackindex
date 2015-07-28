<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Server Deactivated</h2>

<div>
    <p>Your server '{{{ $server->name }}}' has been disabled due to us not being able to reach it after 9 failed
    attempts in a 45 minute period.</p>

    <p>If your server is reachable again, you can set it active <a href="{{ $site_url }}server/edit/{{{ $server->id }}}">here</a>.
    If the server is not re-activated in 15 days, it will be removed completely from the site.</p>
</div>
</body>
</html>
