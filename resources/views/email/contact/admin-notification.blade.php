<x-mail::message>
  # New Contact Form Submitted

  You have received a new enquiry from the contact form.

  **Details:**
  - **Name:** {{ $submission->name }}
  - **Email:** {{ $submission->email }}
  - **Phone:** {{ $submission->phone }}
  - **Subject:** {{ $submission->subject }}

  **Message:**
  {{ $submission->message }}

  <x-mail::button :url="config('app.url') . '/admin/cms/contact-submissions/' . $submission->id">
    View in Admin Panel
  </x-mail::button>

  Thanks,<br>
  {{ config('app.name') }}
</x-mail::message>
