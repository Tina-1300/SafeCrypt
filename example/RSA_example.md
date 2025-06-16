# RSA example


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
    // vérification de l'existance des clé 
    if (file_exists($publicPath) && file_exists($privatePath)){
        echo "chargement...\n";

        // chargement des clées si elle existe
        $publicKey = file_get_contents($publicPath);
        $privateKey = file_get_contents($privatePath);
    }else{
        echo "génération des clées...\n";

        // génération des clées si elle sont absantes
        $keyGen = new RSAKey();
        $keys = $keyGen->generated(1024);

        // sauvgarde des clées
        $save = new RSASave();
        $save->save_key($keys['public'], $keys['private'], $keyDir);

        $publicKey = $keys['public'];
        $privateKey = $keys['private'];
    }

    // chiffrement déchiffrement 
    $rsa = new RSA($publicKey, $privateKey);
    $encrypted = $rsa->encrypt("Mon message secret");
    echo "Encrypted : $encrypted\n";

    $decrypted = $rsa->decrypt($encrypted);
    echo "Decrypted : $decrypted\n";

}catch (CryptoException $e){
    echo "Erreur Crypto : " . $e->getMessage() . "\n";
}catch (\Exception $e){
    echo "Erreur Générale : " . $e->getMessage() . "\n";
}

// php test_rsa.php
```