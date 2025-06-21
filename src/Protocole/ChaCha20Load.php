<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;


class ChaCha20Load{

    public function load_key(string $fileName, string $directory = './keys'): string{

        $fullPath = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($fullPath)){
            throw new CryptoException("Key file not found: {$fullPath}");
        }

        $key = file_get_contents($fullPath);
        if ($key === false){
            throw new CryptoException("Failed to read key from file: {$fullPath}");
        }

        if (empty($key)){
            throw new CryptoException("Key file is empty: {$fullPath}");
        }

        if (mb_strlen($key, '8bit') !== 32){
            throw new CryptoException("Loaded key has incorrect length. Expected 32 bytes, got " . mb_strlen($key, '8bit') . " bytes.");
        }

        return $key;
    }
            
}