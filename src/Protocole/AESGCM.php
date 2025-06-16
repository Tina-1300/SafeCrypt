<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;

class AESGCM{
    private string $key;

    private const IV_B64_LEN = 16;  // 12 bytes IV -> 16 Base64 chars
    private const TAG_B64_LEN = 24; // 16 bytes Tag -> 24 Base64 chars
    private const TOTAL_SUFFIX_LEN = self::IV_B64_LEN + self::TAG_B64_LEN;

    public function __construct(string $key){
        if (strlen($key) !== 32) {
            throw new CryptoException("Key must be 256-bit (32 bytes).");
        }
        $this->key = $key;
    }

    public function encrypt(string $plaintext): array{
        $iv = random_bytes(12); // 96-bit IV recommended
        $tag = '';
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $iv, $tag);

        if ($ciphertext === false){
            throw new CryptoException("Encryption failed.");
        }

        return [
            'ciphertext' => base64_encode($ciphertext),
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag),
        ];
    }

    public function decrypt(string $ciphertext, string $iv, string $tag): string{
        $plaintext = openssl_decrypt(
            base64_decode($ciphertext),
            'aes-256-gcm',
            $this->key,
            OPENSSL_RAW_DATA,
            base64_decode($iv),
            base64_decode($tag)
        );

        if ($plaintext === false){
            throw new CryptoException("Decryption failed.");
        }

        return $plaintext;
    }

    public function decryptCombined(string $combinedData): string{

        if (strlen($combinedData) < self::TOTAL_SUFFIX_LEN){
            throw new CryptoException("Combined data is too short to contain IV and Tag.");
        }

        // Extraction du Tag
        $tagBase64 = substr($combinedData, -self::TAG_B64_LEN);

        // Extraction de l'IV
        $ivBase64 = substr($combinedData, -(self::TOTAL_SUFFIX_LEN), self::IV_B64_LEN);
        
        // Le reste est le texte chiffré
        $ciphertextBase64 = substr($combinedData, 0, -(self::TOTAL_SUFFIX_LEN));

        // Appelle la méthode decrypt existante avec les parties extraites
        return $this->decrypt($ciphertextBase64, $ivBase64, $tagBase64);
    }



}
