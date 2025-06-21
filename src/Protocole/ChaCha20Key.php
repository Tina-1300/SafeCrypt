<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;


class ChaCha20Key{

    public function generated(int $size){
        if($size !== 32){
            throw new CryptoException("ChaCha20-Poly1305 key must be 32 bytes (256-bit). Provided size: {$size} bytes.");
        }
        $key = random_bytes($size);
        if ($key === false){
            throw new CryptoException("Failed to generate cryptographically secure random bytes for the key.");
        }
        return $key;
    }
        
}