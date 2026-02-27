<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\ContactAdminNotification;
use App\Mail\ContactUserNotification;
use App\Models\ContactSubmission;
use App\Repositories\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        $page = (new PageRepository)->findBySlug('contact-us');

        $data['page'] = $page;
        $data['meta'] = $page ? $page->metaForLocale() : null;

        // Fetch subjects from settings
        $subjectsRaw = setting('contact_subjects', '');
        $data['subjects'] = array_filter(array_map('trim', explode("\n", $subjectsRaw)));

        return view('theme.xtremez.contact', $data);
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Save to database
        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);

        // Send Email to User
        Mail::to($submission->email)->send(new ContactUserNotification($submission));

        // Send Email to Admin
        Mail::to('sales@xtremez.store')->send(new ContactAdminNotification($submission));

        // Update notified_at
        $submission->update(['notified_at' => now()]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('Thank you! Your message has been sent successfully.'),
            ]);
        }

        return back()->with('success', __('Thank you! Your message has been sent successfully.'));
    }
}
