# RSA Example

--- 


```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use SafeCrypt\Protocole\RSA;
use SafeCrypt\Protocole\RSAKey;
use SafeCrypt\Protocole\RSASave;
use SafeCrypt\Exceptions\CryptoException;

$keyDir = __DIR__ . '/keys';
$publicPath = $keyDir . '/public.pem';
$privatePath = $keyDir . '/private.pem';

try {
    // checking the existence of keys
    if (file_exists($publicPath) && file_exists($privatePath)){
        echo "loading...\n";

        // loading keys if they exist
        $publicKey = file_get_contents($publicPath);
        $privateKey = file_get_contents($privatePath);
    }else{
        echo "generation of keys...\n";

        // generation of keys if they are missing
        $keyGen = new RSAKey();
        $keys = $keyGen->generated(1024);

        // key backup
        $save = new RSASave();
        $save->save_key($keys['public'], $keys['private'], $keyDir);

        $publicKey = $keys['public'];
        $privateKey = $keys['private'];
    }

    // encryption decryption 
    $rsa = new RSA($publicKey, $privateKey);
    $encrypted = $rsa->encrypt("My secret message");
    echo "Encrypted : $encrypted\n";

    $decrypted = $rsa->decrypt($encrypted);
    echo "Decrypted : $decrypted\n";

}catch (CryptoException $e){
    echo "Error Crypto : " . $e->getMessage() . "\n";
}catch (\Exception $e){
    echo "General Error : " . $e->getMessage() . "\n";
}

// php test_rsa.php
```