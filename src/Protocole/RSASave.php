<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;


class RSASave{

    // method to save private keys and generated public key
    public function save_key(string $publicKey, string $privateKey, string $directory = './keys'){
        if (!is_dir($directory)){
            mkdir($directory, 0700, true);
        }

        if (!empty($publicKey)){
            file_put_contents($directory . '/public.pem', $publicKey);
        }

        if (!empty($privateKey)){
            file_put_contents($directory . '/private.pem', $privateKey);
        }
    }

}