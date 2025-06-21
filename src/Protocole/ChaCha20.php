<?php

// Do unit testing for ChaCha20

// add support for encryption and decryption via ChaCha20

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;

class ChaCha20{

    private string $key;
    private string $cipherMethod = 'chacha20-poly1305';


    public function __construct(string $key){
        if (!in_array($this->cipherMethod, openssl_get_cipher_methods())){
            throw new CryptoException(
                'The cipher method "' . $this->cipherMethod . '" is not supported by OpenSSL on this system. ' .
                'Ensure you have OpenSSL 1.1.0 or newer.'
            );
        }

        if (mb_strlen($key, '8bit') !== 32){
            throw new CryptoException('ChaCha20-Poly1305 key must be 32 bytes (256-bit).');
        }
        $this->key = $key;
    }

    public function getCipherMethod(): string{
        return $this->cipherMethod;
    }

    public function encrypt(string $plaintext, string $additionalData = ''): string{

        $ivLength = openssl_cipher_iv_length($this->cipherMethod);
        if ($ivLength === false || $ivLength !== 12){
            throw new CryptoException(
                'Unexpected IV length for ' . $this->cipherMethod . ': ' . ($ivLength === false ? 'false' : $ivLength) . ' (expected 12).'
            );
        }

        $iv = random_bytes($ivLength);
        if ($iv === false){
            throw new CryptoException('Failed to generate cryptographically secure IV.');
        }

        $tag = ''; 
        $tagLength = 16;

        $ciphertext = openssl_encrypt(
            $plaintext,
            $this->cipherMethod,
            $this->key,
            OPENSSL_RAW_DATA, 
            $iv,
            $tag,            
            $additionalData, 
            $tagLength       
        );

        if ($ciphertext === false){
            throw new CryptoException('OpenSSL encryption failed.');
        }

        return $iv . $ciphertext . $tag;
    }
   
    public function decrypt(string $encryptedMessage, string $additionalData = ''): string|false{

        $ivLength = openssl_cipher_iv_length($this->cipherMethod);
        if ($ivLength === false || $ivLength !== 12){
            throw new CryptoException('Unexpected IV length during decryption for ' . $this->cipherMethod);
        }

        $tagLength = 16;


        if (mb_strlen($encryptedMessage, '8bit') < ($ivLength + $tagLength)){
            return false; 
        }

        $iv = mb_substr($encryptedMessage, 0, $ivLength, '8bit');
        $tag = mb_substr($encryptedMessage, -($tagLength), null, '8bit');
        $ciphertext = mb_substr($encryptedMessage, $ivLength, -($tagLength), '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            $this->cipherMethod,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,           
            $additionalData 
        );

        return $plaintext;
    }
    
}