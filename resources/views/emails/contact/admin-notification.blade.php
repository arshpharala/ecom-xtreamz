@extends('emails.layout')

@section('title', 'New Contact Form Submission')

@section('content')
  <h1>New Enquiry Received</h1>
  <p>A new contact form has been submitted with the following details:</p>

  <table class="details-table">
    <tr>
      <td>Name</td>
      <td>{{ $submission->name }}</td>
    </tr>
    <tr>
      <td>Email</td>
      <td>{{ $submission->email }}</td>
    </tr>
    <tr>
      <td>Phone</td>
      <td>{{ $submission->phone ?? 'N/A' }}</td>
    </tr>
    <tr>
      <td>Subject</td>
      <td>{{ $submission->subject }}</td>
    </tr>
    <tr>
      <td>IP Address</td>
      <td>{{ $submission->ip_address }}</td>
    </tr>
    <tr>
      <td>Date</td>
      <td>{{ $submission->created_at->format('M d, Y H:i') }}</td>
    </tr>
  </table>

  <p><strong>Message:</strong></p>
  <div class="message-box">
    {{ $submission->message }}
  </div>

  <div style="text-align: center;">
    <a href="{{ config('app.url') . '/admin/cms/contact-submissions/' . $submission->id }}" class="btn">View in Admin
      Panel</a>
  </div>
@endsection
