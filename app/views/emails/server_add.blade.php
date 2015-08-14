<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Server Info / Confirmation</h2>

<div>
    <p>Your server has been added to Mopdack Index!</p>

    <p>Please click the following link to confirm/activate your server. Your server will not be listed on the site until
    you do so. If you do not activate your server within 48 hours it will be automatically removed and you will need to
    re-add it.</p>

    <p><a href="{{ $site_url }}server/confirm/{{{ $server_id }}}/{{{ $password }}}">{{ $site_url }}server/confirm/{{{ $server_id }}}/{{{ $password }}}</a></p>

    <p>Your can use the following link to edit your server.</p>

    <p><a href="{{ $site_url }}server/edit/{{{ $server_id }}}/{{{ $password }}}"></a>{{ $site_url }}server/edit/{{{ $server_id }}}/{{{ $password }}}</p>

    <p><b>Do not lose this email!</b></p>
</div>
</body>
</html>
