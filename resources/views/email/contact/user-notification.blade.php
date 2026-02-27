<x-mail::message>
  # Thank you for contacting us, {{ $submission->name }}!

  We have received your message regarding **{{ $submission->subject }}**. Our team will get back to you as soon as
  possible.

  **Your Message:**
  {{ $submission->message }}

  Thanks,<br>
  {{ config('app.name') }}
</x-mail::message>
