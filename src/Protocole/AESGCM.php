<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;
use Exception;

/*

Todo : 
Risque de sécurité (methode: (encrypt)) :

possibilité de collision sur les vecteurs d'initialisation (IV / nonce),
ce qui pourrait permettre à un attaquant de compromettre l'authenticité 
et la confidentialité des données chiffrées. Il est recommandé d'implémenter 
une version avec des IV incrémentaux ou uniques pour chaque chiffrement.

*/



class AESGCM{
    private string $key;

    private const IV_B64_LEN = 16;  // 12 bytes IV -> 16 Base64 chars
    private const TAG_B64_LEN = 24; // 16 bytes Tag -> 24 Base64 chars
    private const TOTAL_SUFFIX_LEN = self::IV_B64_LEN + self::TAG_B64_LEN;

    public function __construct(string $key){
        if (strlen($key) !== 32){
            throw new CryptoException("Key must be 256-bit (32 bytes).");
        }
        $this->key = $key;
    }

    private function generate_nonce(): string {
        $xor_key = random_bytes(12);
        usleep(20);

        if (function_exists('hrtime')){
            $timestamp_ns = hrtime(true);
        }else{
            $timestamp_ns = (int)(microtime(true) * 1_000_000_000);
        }

        if (PHP_VERSION_ID >= 70000){
            $timestamp_bytes = pack('J', $timestamp_ns);
        }

        $random_bytes = random_bytes(4);
        $base_nonce = $timestamp_bytes . $random_bytes;

        $final_nonce = '';
        for ($i = 0; $i < 12; $i++){
            $final_nonce .= chr(ord($base_nonce[$i]) ^ ord($xor_key[$i]));
        }

        if (strlen($final_nonce) !== 12){
            throw new CryptoException("Critical error: The final nonce does not have the expected size of 12 bytes.");
        }

        return $final_nonce;
    }


    public function encrypt(string $plaintext): array{
                
        $iv = $this->generate_nonce(); // 96 bit 

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

        // Tag Extraction
        $tagBase64 = substr($combinedData, -self::TAG_B64_LEN);

        // IV Extraction
        $ivBase64 = substr($combinedData, -(self::TOTAL_SUFFIX_LEN), self::IV_B64_LEN);
        
        // The rest is the ciphertext
        $ciphertextBase64 = substr($combinedData, 0, -(self::TOTAL_SUFFIX_LEN));

        // Calls the existing decrypt method with the extracted parts
        return $this->decrypt($ciphertextBase64, $ivBase64, $tagBase64);
    }

}