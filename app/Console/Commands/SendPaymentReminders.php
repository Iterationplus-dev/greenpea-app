<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\InvoiceSetting;
use Illuminate\Console\Command;
use App\Mail\PaymentReminderMail;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminder emails for unpaid invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = InvoiceSetting::first();

        if (! $settings || ! $settings->reminders_enabled) {
            $this->info('Invoice reminders are disabled.');
            return Command::SUCCESS;
        }

        $invoices = Invoice::where('status', '!=', PaymentStatus::PAID->value)
            ->where('balance_due', '>', 0)
            ->whereDate('issued_at', '<=', now()->subDays($settings->days_before_reminder))
            ->get();

        foreach ($invoices as $invoice) {

            Mail::to($invoice->booking->guest_email)
                ->send(new PaymentReminderMail($invoice));

            if ($settings->admin_email) {
                Mail::to($settings->admin_email)
                    ->send(new PaymentReminderMail($invoice));
            }

            $this->info("Reminder sent for invoice: {$invoice->number}");
        }

        return Command::SUCCESS;
    }
}
