<?php

namespace App\Services;

use App\Models\CMS\Email;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Request to send an email based on stored template + recipients.
     * Filters out inactive admins from the final "to" list.
     */
    public static function request_mail(string $reference, array $data): ?bool
    {
        $email = Email::where('reference', $reference)->active()->first();
        if (!$email) {
            Log::emergency(["message" => "Email template not found", "reference" => $reference, "payload" => $data]);
            return null;
        }


        if (!View::exists($email->template)) {
            Log::emergency(["message" => "Email view not found", "reference" => $reference, "payload" => $data, "template" => $email->template]);
            return null;
        }

        // 1) Base recipients from DB
        $subject = $data['subject'] ?? ($email->subject ?? null);
        $to      = $email->to()->pluck('email')->toArray();
        $cc      = $email->cc()->pluck('email')->toArray();
        $bcc     = $email->bcc()->pluck('email')->toArray();
        $exclude = $email->exclude()->pluck('email')->toArray();

        // 2) Merge payload recipients
        if (!empty($data['to']))      $to      = array_merge($to,   (array) $data['to']);
        if (!empty($data['cc']))      $cc      = array_merge($cc,   (array) $data['cc']);
        if (!empty($data['bcc']))     $bcc     = array_merge($bcc,  (array) $data['bcc']);
        if (!empty($data['exclude'])) $exclude = array_merge($exclude, (array) $data['exclude']);

        // 3) Normalise + validate
        $to      = self::sanitizeEmails($to);
        $cc      = self::sanitizeEmails($cc);
        $bcc     = self::sanitizeEmails($bcc);
        $exclude = self::sanitizeEmails($exclude);

        // 4) Apply excludes
        if ($exclude) {
            $to  = array_values(array_diff($to,  $exclude));
            $cc  = array_values(array_diff($cc,  $exclude));
            $bcc = array_values(array_diff($bcc, $exclude));
        }

        // 5) Remove INACTIVE Admins (only those that are admins; keep non-admin emails)
        $to  = self::removeInactiveAdmins($to);
        $cc  = self::removeInactiveAdmins($cc);
        $bcc = self::removeInactiveAdmins($bcc);

        // 6) From
        $from = null;
        if (!empty($email['from_email'])) {
            $from = [
                'email' => mb_strtolower(trim($email['from_email'])),
                'name'  => $email['from_name'] ?? null,
            ];
        }

        // 7) Guard
        if (empty($to) && empty($cc) && empty($bcc)) {
            Log::warning([
                "message"   => "Email skipped: no active recipients after filtering",
                "reference" => $reference,
            ]);
            return false;
        }

        // 8) Send
        return self::send_mail(
            template: $email->template,
            subject: $subject,
            to: $to,
            cc: $cc,
            bcc: $bcc,
            from: $from,
            viewData: $data
        );
    }

    /**
     * Actually sends the email using a Blade view as the body.
     */
    private static function send_mail(
        string $template,
        string $subject,
        array $to,
        array $cc = [],
        array $bcc = [],
        ?array $from = null,
        array $viewData = []
    ): bool {
        try {
            Mail::send($template, $viewData, function ($message) use ($subject, $to, $cc, $bcc, $from) {
                if (!empty($from['email'])) {
                    $message->from($from['email'], $from['name'] ?? null);
                }
                foreach ($to as $addr) {
                    $message->to($addr);
                }
                foreach ($cc as $addr) {
                    $message->cc($addr);
                }
                foreach ($bcc as $addr) {
                    $message->bcc($addr);
                }

                if ($subject) {
                    $message->subject($subject);
                }
            });

            if (count(Mail::failures()) > 0) {
                Log::error(['message' => 'Mail::send failures', 'failures' => Mail::failures()]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error(['message' => 'Email send exception', 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Validate, trim, lowercase, and de-duplicate email lists.
     */
    private static function sanitizeEmails(array $emails): array
    {
        $emails = array_map(fn($e) => mb_strtolower(trim((string) $e)), $emails);
        $emails = array_filter($emails, function ($e) {
            return filter_var($e, FILTER_VALIDATE_EMAIL);
        });
        return array_values(array_unique($emails));
    }


    /**
     * Remove only those emails that belong to Admins AND are inactive.
     * (Non-admin emails pass through untouched.)
     */
    private static function removeInactiveAdmins(array $emails): array
    {
        if (!$emails) return $emails;

        // fetch admin emails present in the list
        $adminEmails = Admin::query()
            ->whereIn('email', $emails)
            ->pluck('email')
            ->map(fn($e) => mb_strtolower(trim($e)))
            ->toArray();

        if (!$adminEmails) return $emails;

        // from those admins, find the inactive ones
        $inactiveAdminEmails = Admin::query()
            ->whereIn('email', $adminEmails)
            ->where('is_active', 0)
            ->pluck('email')
            ->map(fn($e) => mb_strtolower(trim($e)))
            ->toArray();

        if (!$inactiveAdminEmails) return $emails;

        // drop inactive admin emails only
        return array_values(array_diff($emails, $inactiveAdminEmails));
    }

    /**
     * True if the email belongs to an Admin and that admin is inactive.
     */
    private static function isInactiveAdminEmail(string $email): bool
    {
        $email = mb_strtolower(trim($email));
        return Admin::query()
            ->where('email', $email)
            ->where('is_active', 0)
            ->exists();
    }
}
