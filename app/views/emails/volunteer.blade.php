<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Volunteer Application</h2>

<div>
    <p>
        <b>Nickname:</b> {{{ $input['nickname'] }}}<br/>
        <b>Email:</b> {{{ $input['email'] }}}<br/>
        <b>Age:</b> {{{ $input['age'] }}}<br/>
        <b>Position:</b> {{{ $input['position'] }}}<br/>
        <b>IP:</b> {{{ $input['sender_ip'] }}}<br/>
        <b>Why:</b></p>

    <p>{{{ $input['why'] }}}</p>
</div>
</body>
</html>