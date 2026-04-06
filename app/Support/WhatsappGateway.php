<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class WhatsappGateway
{
    public static function send(string $phone, string $message): bool
    {
        $config = (array) config('services.whatsapp', []);
        $driver = (string) ($config['driver'] ?? 'custom');

        if ($driver === 'meta') {
            return self::sendMeta($phone, $message, $config);
        }

        return self::sendCustom($phone, $message, $config);
    }

    private static function sendMeta(string $phone, string $message, array $config): bool
    {
        $meta = (array) ($config['meta'] ?? []);

        $token = trim((string) ($meta['token'] ?? $config['token'] ?? ''));
        $phoneNumberId = trim((string) ($meta['phone_number_id'] ?? ''));
        $apiVersion = trim((string) ($meta['api_version'] ?? 'v22.0'));

        $fixedTo = self::normalizePhone((string) ($meta['test_to'] ?? ''));
        $dynamicTo = self::normalizePhone($phone);
        $to = $fixedTo !== '' ? $fixedTo : $dynamicTo;

        if ($token === '' || $phoneNumberId === '' || $to === '') {
            return false;
        }

        $endpoint = "https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages";

        $response = Http::withToken($token)
            ->acceptJson()
            ->post($endpoint, [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message,
                ],
            ]);

        return $response->successful();
    }

    private static function sendCustom(string $phone, string $message, array $config): bool
    {
        $endpoint = trim((string) ($config['endpoint'] ?? ''));
        $token = trim((string) ($config['token'] ?? ''));
        $to = self::normalizePhone($phone);

        if ($endpoint === '' || $token === '' || $to === '') {
            return false;
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->post($endpoint, [
                'to' => $to,
                'message' => $message,
            ]);

        return $response->successful();
    }

    private static function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', trim($phone)) ?? '';
    }
}
