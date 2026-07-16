<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

class InvoicePingReminder extends Command
{
    protected $signature = 'invoiceping:remind';
    protected $description = 'Process invoice reminders (run daily via scheduler)';

    public function handle(ReminderService $reminderService): int
    {
        $results = $reminderService->processReminders();

        $this->info("Reminder processing complete:");
        $this->info("  Processed: {$results['processed']}");
        $this->info("  Sent: {$results['sent']}");
        $this->info("  Skipped: {$results['skipped']}");

        return Command::SUCCESS;
    }
}
