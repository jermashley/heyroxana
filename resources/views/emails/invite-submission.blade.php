<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invite Submission</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #111827;">
    <h2>Hey Roxana Invite Submission</h2>
    <p>A new date invite was submitted. Here are the details:</p>

    <ul>
        <li><strong>Date type:</strong> {{ $submission->date_type_label }} ({{ $submission->date_type }})</li>
        <li><strong>Scheduled at:</strong> {{ $submission->scheduled_at?->format('Y-m-d H:i') ?? 'No exact time yet' }}
        </li>
        <li><strong>Message:</strong> {{ $submission->message ?: '—' }}</li>
        <li><strong>Token:</strong> {{ $submission->token }}</li>
        <li><strong>IP address:</strong> {{ $submission->ip_address ?? '—' }}</li>
        <li><strong>User agent:</strong> {{ $submission->user_agent ?? '—' }}</li>
        <li><strong>Submitted at:</strong> {{ $submission->created_at?->format('Y-m-d H:i') }}</li>
    </ul>
</body>

</html>
