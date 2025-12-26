<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message
     */
    public static function send(string $phone, string $message): bool
    {
        if (! config('services.whatsapp.enabled')) {
            return false;
        }

        try {
            $response = Http::withToken(config('services.whatsapp.token'))
                ->post(config('services.whatsapp.endpoint'), [
                    'to' => self::formatPhone($phone),
                    'message' => $message,
                ]);

            return $response->successful();

        } catch (\Throwable $e) {
            Log::error('WhatsApp send failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Normalize Nigerian phone numbers
     */
    protected static function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '234' . substr($phone, 1);
        }

        return $phone;
    }
}
