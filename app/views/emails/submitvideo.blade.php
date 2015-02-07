<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Video Submission:</h2>
<div>
    <p>
        <b>URL:</b> {{ $input['url'] }}<br/>
        <b>Type:</b> {{ $input['type'] }}<br/>
        <b>Modpack:</b> {{ $input['modpack'] }}<br/>
        <b>Mod:</b> {{ $input['mod'] }}<br/>
        <b>Comments:</b>
        <p>{{ $input['comments'] }}</p><br/>
        <b>Email:</b> {{ $input['email'] }}<br/>
        <b>IP:</b> {{ $input['sender_ip'] }}<br/>
    </p>
</div>
</body>
</html>