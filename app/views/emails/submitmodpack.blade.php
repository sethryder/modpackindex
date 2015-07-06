<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Modpack Submission:</h2>

<div>
    <p>
        <b>Modpack Name:</b> {{ $input['name'] }}<br/>
        <b>Creator(s):</b> {{ $input['creators_name'] }}<br/>
        <b>Minecraft Version:</b> {{ $input['minecraft_version'] }}<br/>
        <b>Launcher:</b> {{ $input['launcher'] }}<br/>
        <b>Website:</b> {{ $input['website'] }}<br/>
        <b>Mod List:</b> {{ $input['modlist'] }}<br/>
        <b>Pack Code:</b> {{ $input['packcode'] }}<br/>
        <b>Deck:</b> {{ $input['deck'] }}<br/>
        <b>Description:</b>

    <p>{{ $input['description'] }}</p><br/>
    <b>Comments:</b>

    <p>{{ $input['comments'] }}</p><br/>
    <b>Email:</b> {{ $input['email'] }}<br/>
    <b>IP:</b> {{ $input['sender_ip'] }}<br/>
    </p>
</div>
</body>
</html>