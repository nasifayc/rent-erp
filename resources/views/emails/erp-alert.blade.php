<h2>{{ $notification->title }}</h2>
<p>{{ $notification->message }}</p>
<p><strong>Priority:</strong> {{ strtoupper($notification->priority) }}</p>
<p><strong>Type:</strong> {{ $notification->type }}</p>
<p><strong>Created:</strong> {{ optional($notification->created_at)->format('Y-m-d H:i') }}</p>
