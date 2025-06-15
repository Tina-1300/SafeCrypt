<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;


class RSASave{

    // fonction permettant de sauvgarder les clées priver et clé public generer
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