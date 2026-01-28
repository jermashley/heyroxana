<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invite Opened</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #111827;">
    <h2>Invite Page Opened</h2>
    <p>The invite page was opened.</p>

    <ul>
        <li><strong>Token:</strong> {{ $token }}</li>
        <li><strong>Opened at:</strong> {{ $openedAt }}</li>
        <li><strong>IP address:</strong> {{ $ipAddress ?? '—' }}</li>
        <li><strong>User agent:</strong> {{ $userAgent ?? '—' }}</li>
    </ul>
</body>
</html>
