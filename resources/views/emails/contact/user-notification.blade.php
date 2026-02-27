@extends('emails.layout')

@section('title', 'Thank you for contacting us')

@section('content')
  <h1>Thank you for reaching out!</h1>
  <p>Hello {{ $submission->name }},</p>
  <p>We have received your message regarding <strong>{{ $submission->subject }}</strong>. Our team is reviewing your
    enquiry and will get back to you shortly.</p>

  <div class="message-box">
    {{ $submission->message }}
  </div>

  <p style="margin-top: 25px;">Best regards,<br>{{ config('app.name') }} Team</p>
@endsection
