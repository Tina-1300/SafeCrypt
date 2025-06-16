<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;


class RSALoad{

    public function load_key(array $options): array{
        $public = $options['public'] ?? null;
        $private = $options['private'] ?? null;

        if (!$public && !$private){
            throw new CryptoException("At least one key must be provided.");
        }

        return [
            'public' => $public,
            'private' => $private,
        ];
    } 

}