<?php

namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;


class RSAKey{ 

    public function generated(int $keySize = 1024): array{
        $config = [
            "private_key_bits" => $keySize,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);

        if (!$res){
            throw new CryptoException("Key generation failed.");
        }

        openssl_pkey_export($res, $privateKey);
        $details = openssl_pkey_get_details($res);
        $publicKey = $details['key'];

        return [
            'public' => $publicKey,
            'private' => $privateKey,
        ];
    } // génération des clées RSA 

}