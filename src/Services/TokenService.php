<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Services;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class TokenService
{
    /**
     * Creating an encrypted project api token.
     * This allows us to validate the token (decrypt it) without database access
     * Before validating the token.
     */
    public static function generate(string $encryptionKey = null)
    {
        $encrypter = self::getEncrypter($encryptionKey);
        $token = Str::random(40);

        return $encrypter->encryptString($token);
    }

    public static function validate($payload, string $encryptionKey = null): bool
    {
        $encrypter = self::getEncrypter($encryptionKey);
        try {
            $encrypter->decryptString($payload);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private static function getEncrypter(string $encryptionKey = null)
    {
        if (! $encryptionKey) {
            $key = config('tribe.api_encryption_key', config('app.key'));
            $encryptionKey = base64_decode(str_replace('base64:', '', $key));
        }

        return new Encrypter($encryptionKey, 'AES-256-CBC');
    }
}
