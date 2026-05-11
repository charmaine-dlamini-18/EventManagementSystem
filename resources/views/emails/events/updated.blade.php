<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #d97706; color: #fff; padding: 30px 40px; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 30px 40px; color: #333; line-height: 1.6; }
        .detail-box { background: #fffbeb; border-left: 4px solid #d97706; padding: 16px 20px; border-radius: 4px; margin: 20px 0; }
        .detail-box p { margin: 6px 0; font-size: 14px; }
        .detail-box strong { color: #b45309; }
        .cancelled-notice { background: #fef2f2; border: 1px solid #fca5a5; padding: 14px 18px; border-radius: 6px; color: #b91c1c; margin: 16px 0; font-weight: bold; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background: #d97706; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { padding: 20px 40px; background: #f9f9f9; font-size: 12px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>📅 Event Updated</h1>
        <p style="margin:8px 0 0; opacity:0.9;">Details have changed for an event you're attending</p>
    </div>

    <div class="body">
        <p>Hi <strong>{{ $attendee->name }}</strong>,</p>

        @if($event->status === 'cancelled')
            <div class="cancelled-notice">
                ⚠️ This event has been <strong>cancelled</strong> by the organizer.
            </div>
            <p>We're sorry for the inconvenience. Please check our events page for other upcoming events.</p>
        @else
            <p>The organizer has made updates to an event you're registered for. Please review the latest details below:</p>
        @endif

        <div class="detail-box">
            <p><strong>Event:</strong> {{ $event->title }}</p>
            <p><strong>Date:</strong> {{ $event->start_date->format('l, F j, Y \a\t g:i A') }}</p>
            <p><strong>End:</strong> {{ $event->end_date->format('l, F j, Y \a\t g:i A') }}</p>
            <p><strong>Location:</strong> {{ $event->location }}</p>
            <p><strong>Status:</strong>
                @if($event->status === 'published') <span style="color:#16a34a">Published ✅</span>
                @elseif($event->status === 'cancelled') <span style="color:#dc2626">Cancelled ❌</span>
                @else <span>{{ ucfirst($event->status) }}</span>
                @endif
            </p>
        </div>

        @if($event->status !== 'cancelled')
            <p>If you can no longer attend, you may cancel your registration from your dashboard.</p>
            <a href="{{ route('events.show', $event) }}" class="btn">View Event</a>
        @else
            <a href="{{ route('events.index') }}" class="btn">Browse Other Events</a>
        @endif
    </div>

    <div class="footer">
        <p>This email was sent by {{ config('app.name') }} because you are registered for this event.</p>
    </div>
</div>
</body>
</html>