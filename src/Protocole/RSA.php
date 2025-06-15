<?php


namespace SafeCrypt\Protocole;

use SafeCrypt\Exceptions\CryptoException;

class RSA{

    private string $public_key;
    private string $private_key;

    public function __construct(string $public_key = '', string $private_key = ''){
        if (empty($public_key) && empty($private_key)){
            throw new CryptoException("At least one key (public or private) is required.");
        }

        if (!empty($public_key)){
            if (!openssl_pkey_get_public($public_key)){
                throw new CryptoException("Public key format is invalid.");
            }
            $this->public_key = $public_key;
        }

        if (!empty($private_key)){
            if (!openssl_pkey_get_private($private_key)){
                throw new CryptoException("Private key format is invalid.");
            }
            $this->private_key = $private_key;
        }
    }

    public function encrypt(string $data):string{
        if(empty($this->public_key)){
            throw new CryptoException("Public key is required for encryption.");
        }
        if(!openssl_public_encrypt($data, $encrypted, $this->public_key)){
            throw new CryptoException("Encryption failed.");
        }

        return base64_encode($encrypted);
    } // chiffrement des données 


    public function decrypt(string $encryptedBase64): string{
        if (empty($this->private_key)){
            throw new CryptoException("Private key is required for decryption.");
        }

        $encrypted = base64_decode($encryptedBase64);

        if (!openssl_private_decrypt($encrypted, $decrypted, $this->private_key)){
            throw new CryptoException("Decryption failed.");
        }
        return $decrypted;
    } // dechiffrement des données

}
