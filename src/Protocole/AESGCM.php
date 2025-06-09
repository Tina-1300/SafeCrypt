<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;

class AESGCM
{
    private string $key;

    public function __construct(string $key)
    {
        if (strlen($key) !== 32) {
            throw new CryptoException("Key must be 256-bit (32 bytes).");
        }
        $this->key = $key;
    }

    public function encrypt(string $plaintext): array
    {
        $iv = random_bytes(12); // 96-bit IV recommended
        $tag = '';
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $iv, $tag);

        if ($ciphertext === false) {
            throw new CryptoException("Encryption failed.");
        }

        return [
            'ciphertext' => base64_encode($ciphertext),
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag),
        ];
    }

    public function decrypt(string $ciphertext, string $iv, string $tag): string
    {
        $plaintext = openssl_decrypt(
            base64_decode($ciphertext),
            'aes-256-gcm',
            $this->key,
            OPENSSL_RAW_DATA,
            base64_decode($iv),
            base64_decode($tag)
        );

        if ($plaintext === false) {
            throw new CryptoException("Decryption failed.");
        }

        return $plaintext;
    }
}
