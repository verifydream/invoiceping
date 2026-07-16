<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Resend\Laravel\Facades\Resend;

class ReminderService
{
    /**
     * Reminder intervals in days after invoice is sent.
     */
    const REMINDER_INTERVALS = [
        7  => 'internal',    // Internal reminder (dashboard notification)
        14 => 'first',       // First email follow-up
        21 => 'second',      // Second email follow-up (firmer)
        30 => 'third',       // Third email follow-up (final)
    ];

    /**
     * Process all invoices that need reminders.
     */
    public function processReminders(): array
    {
        $results = ['processed' => 0, 'sent' => 0, 'skipped' => 0];

        // Get all sent invoices with reminders enabled
        $invoices = Invoice::where('status', 'sent')
            ->where('reminder_enabled', true)
            ->whereNotNull('sent_at')
            ->get();

        foreach ($invoices as $invoice) {
            $results['processed']++;

            $daysSinceSent = $invoice->daysSinceSent();
            if ($daysSinceSent === null) {
                $results['skipped']++;
                continue;
            }

            // Check which reminder interval matches
            foreach (self::REMINDER_INTERVALS as $days => $type) {
                if ($daysSinceSent >= $days && $this->shouldSendReminder($invoice, $days)) {
                    $this->sendReminder($invoice, $type);
                    $results['sent']++;
                    break;
                }
            }
        }

        Log::info('Reminder processing complete', $results);
        return $results;
    }

    /**
     * Check if we should send this reminder (not already sent for this interval).
     */
    private function shouldSendReminder(Invoice $invoice, int $days): bool
    {
        // If reminder_count >= days/7, we've already sent enough reminders
        // Simple heuristic: each 7-day interval = 1 reminder
        return $invoice->reminder_count < ($days / 7);
    }

    /**
     * Send the appropriate reminder.
     */
    private function sendReminder(Invoice $invoice, string $type): void
    {
        if ($type === 'internal') {
            // Just update the last_reminder_at timestamp
            $invoice->update([
                'last_reminder_at' => now(),
                'reminder_count' => $invoice->reminder_count + 1,
            ]);
            return;
        }

        // Send email reminder via Resend
        try {
            $subject = match ($type) {
                'first' => "Friendly reminder: Invoice for {$invoice->client_name}",
                'second' => "Follow-up: Invoice for {$invoice->client_name} still pending",
                'third' => "Final notice: Invoice for {$invoice->client_name} overdue",
            };

            $view = match ($type) {
                'first' => 'emails.reminders.first',
                'second' => 'emails.reminders.second',
                'third' => 'emails.reminders.third',
            };

            Resend::emails()->send([
                'from' => config('services.resend.from', env('RESEND_FROM')),
                'to' => $invoice->client_email,
                'subject' => $subject,
                'html' => view($view, ['invoice' => $invoice])->render(),
            ]);

            $invoice->update([
                'last_reminder_at' => now(),
                'reminder_count' => $invoice->reminder_count + 1,
            ]);

            Log::info("Reminder sent", [
                'invoice_id' => $invoice->id,
                'type' => $type,
                'to' => $invoice->client_email,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send reminder", [
                'invoice_id' => $invoice->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
