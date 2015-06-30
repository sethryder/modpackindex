<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>

		<div>
			<p>To reset your password, complete this form: <a href="{{ $site_url }}reset/{{ $token }}" rel="nofollow">{{ $site_url }}reset/{{ $token }}</a>.</p>
			<p>This link will expire in 60 minutes.</p>
		</div>
	</body>
</html>
