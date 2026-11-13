<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class TicketQrService
{
    /**
     * Payload QR = order_code + tanda tangan HMAC pendek.
     * Saat scan di pintu masuk, verifikasi dengan verifyPayload().
     */
    public static function payload(string $orderCode): string
    {
        $sig = substr(hash_hmac('sha256', $orderCode, config('app.key')), 0, 16);

        return $orderCode . '.' . $sig;
    }

    /** Kembalikan order_code jika valid, null jika tanda tangan salah. */
    public static function verifyPayload(string $payload): ?string
    {
        $pos = strrpos($payload, '.');
        if ($pos === false) {
            return null;
        }

        $orderCode = substr($payload, 0, $pos);

        return hash_equals(self::payload($orderCode), $payload) ? $orderCode : null;
    }

    /** Hasilkan PNG (binary string). */
    public static function png(string $orderCode, int $size = 300): string
    {
        $result = (new Builder(
            writer: new PngWriter(),
            data: self::payload($orderCode),
            size: $size,
            margin: 10,
        ))->build();

        return $result->getString();
    }
}
