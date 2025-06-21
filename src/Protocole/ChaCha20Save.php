<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;


class ChaCha20Save{

    public function save_key(string $key, string $fileName, string $directory = './keys'): void{
        
        $fullPath = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

        if (!is_dir($directory)){
            if (!mkdir($directory, 0700, true)){
                throw new CryptoException("Failed to create directory: {$directory}");
            }
        }

        if (empty($key)){
            throw new CryptoException("Cannot save an empty key.");
        }

        if (file_put_contents($fullPath, $key, LOCK_EX) === false){
            throw new CryptoException("Failed to save key to file: {$fullPath}");
        }

    }


        
}