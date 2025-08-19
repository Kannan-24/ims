<?php

namespace App\Services;

class TotpService
{
    /**
     * Generate a Base32 (RFC 3548) encoded secret (without padding) suitable for TOTP apps.
     */
    public function generateSecret(int $bytes = 20): string
    {
        $random = random_bytes($bytes);
        return rtrim($this->base32Encode($random), '=');
    }

    public function getOtpAuthUrl(string $appName, string $email, string $secret): string
    {
        $label = rawurlencode($appName.':'.$email);
        $issuer = rawurlencode($appName);
        return "otpauth://totp/{$label}?secret={$secret}&issuer={$issuer}&period=30&digits=6";
    }

    public function verifyCode(string $secret, string $code, int $window = 1): bool
    {
        $timeSlice = floor(time() / 30);
        $code = trim($code);
        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals($this->calculateCode($secret, $timeSlice + $i), $code)) {
                return true;
            }
        }
        return false;
    }

    protected function calculateCode(string $secret, int $timeSlice): string
    {
        $key = $this->base32Decode($secret);
        if ($key === null) {
            // Fallback: legacy secrets previously used modified base64 (with +/ replaced) and may include lowercase.
            $legacy = $this->legacyDecode($secret);
            if ($legacy !== null) {
                $key = $legacy;
            } else {
                return '000000';
            }
        }
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $time, $key, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncatedHash = unpack('N', substr($hash, $offset, 4))[1] & 0x7FFFFFFF;
        return str_pad((string)($truncatedHash % 1000000), 6, '0', STR_PAD_LEFT);
    }

    private function base32Encode(string $data): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $binaryString = '';
        foreach (str_split($data) as $char) {
            $binaryString .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }
        $chunks = str_split($binaryString, 5);
        $base32 = '';
        foreach ($chunks as $chunk) {
            if (strlen($chunk) < 5) {
                $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            }
            $base32 .= $alphabet[bindec($chunk)];
        }
        $padLength = (8 - (strlen($base32) % 8)) % 8; // RFC 3548 padding length
        return $base32 . str_repeat('=', $padLength);
    }

    private function base32Decode(string $base32): ?string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $clean = strtoupper($base32);
        $clean = preg_replace('/[^A-Z2-7=]/', '', $clean);
        if ($clean === '') return null;
        $binaryString = '';
        foreach (str_split(rtrim($clean, '=')) as $char) {
            $pos = strpos($alphabet, $char);
            if ($pos === false) return null;
            $binaryString .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }
        $bytes = '';
        $eightBitChunks = str_split($binaryString, 8);
        foreach ($eightBitChunks as $chunk) {
            if (strlen($chunk) === 8) {
                $bytes .= chr(bindec($chunk));
            }
        }
        return $bytes;
    }

    /**
     * Decode legacy secrets that were generated using a modified base64 ( +/ replaced with AB ).
     */
    private function legacyDecode(string $secret): ?string
    {
        // Restore +/ then base64 decode; accept paddingless string.
        $candidate = strtr($secret, 'AB', '+/');
        $pad = strlen($candidate) % 4;
        if ($pad) {
            $candidate .= str_repeat('=', 4 - $pad);
        }
        $decoded = base64_decode($candidate, true);
        return $decoded === false ? null : $decoded;
    }
}
