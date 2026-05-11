<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #16a34a; color: #fff; padding: 30px 40px; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 30px 40px; color: #333; line-height: 1.6; }
        .detail-box { background: #f0fdf4; border-left: 4px solid #16a34a; padding: 16px 20px; border-radius: 4px; margin: 20px 0; }
        .detail-box p { margin: 6px 0; font-size: 14px; }
        .detail-box strong { color: #15803d; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background: #16a34a; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { padding: 20px 40px; background: #f9f9f9; font-size: 12px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>✅ You're In!</h1>
        <p style="margin:8px 0 0; opacity:0.9;">Your registration has been approved</p>
    </div>

    <div class="body">
        <p>Hi <strong>{{ $registration->user->name }}</strong>,</p>

        <p>Great news! The organizer has approved your registration for the following event:</p>

        <div class="detail-box">
            <p><strong>Event:</strong> {{ $registration->event->title }}</p>
            <p><strong>Date:</strong> {{ $registration->event->start_date->format('l, F j, Y \a\t g:i A') }}</p>
            <p><strong>Location:</strong> {{ $registration->event->location }}</p>
            <p><strong>Status:</strong> Confirmed ✅</p>
        </div>

        <p>We look forward to seeing you there. If your plans change, you can cancel your registration from your dashboard.</p>

        <a href="{{ route('registrations.index') }}" class="btn">View My Registrations</a>
    </div>

    <div class="footer">
        <p>This email was sent by {{ config('app.name') }}. Please do not reply to this email.</p>
    </div>
</div>
</body>
</html>