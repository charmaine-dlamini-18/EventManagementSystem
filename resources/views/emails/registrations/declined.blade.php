<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #dc2626; color: #fff; padding: 30px 40px; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 30px 40px; color: #333; line-height: 1.6; }
        .detail-box { background: #fef2f2; border-left: 4px solid #dc2626; padding: 16px 20px; border-radius: 4px; margin: 20px 0; }
        .detail-box p { margin: 6px 0; font-size: 14px; }
        .detail-box strong { color: #b91c1c; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { padding: 20px 40px; background: #f9f9f9; font-size: 12px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Registration Update</h1>
        <p style="margin:8px 0 0; opacity:0.9;">Regarding your event registration</p>
    </div>

    <div class="body">
        <p>Hi <strong>{{ $registration->user->name }}</strong>,</p>

        <p>Unfortunately, the organizer was unable to confirm your registration for the following event:</p>

        <div class="detail-box">
            <p><strong>Event:</strong> {{ $registration->event->title }}</p>
            <p><strong>Date:</strong> {{ $registration->event->start_date->format('l, F j, Y \a\t g:i A') }}</p>
            <p><strong>Location:</strong> {{ $registration->event->location }}</p>
            <p><strong>Status:</strong> Declined</p>
        </div>

        <p>This may be due to capacity limits or other event requirements. We encourage you to browse other upcoming events.</p>

        <a href="{{ route('events.index') }}" class="btn">Browse Events</a>
    </div>

    <div class="footer">
        <p>This email was sent by {{ config('app.name') }}. Please do not reply to this email.</p>
    </div>
</div>
</body>
</html>