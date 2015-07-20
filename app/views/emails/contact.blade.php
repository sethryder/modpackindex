<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Contact Form Message</h2>

<div>
    <p>
        <b>From:</b> {{{ $input['name'] }}}<br/>
        <b>Email:</b> {{{ $input['email'] }}}<br/>
        <b>IP:</b> {{{ $input['sender_ip'] }}}<br/>
        <b>Subject:</b> {{{ $input['subject'] }}}<br/>
        <b>Message:</b></p>

    <p>{{{ $input['message'] }}}</p>
</div>
</body>
</html>