# ChaCha20-Poly1305 Example 

---


```php
<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use SafeCrypt\Protocole\ChaCha20;
use SafeCrypt\Protocole\ChaCha20Key;
use SafeCrypt\Protocole\ChaCha20Load;
use SafeCrypt\Protocole\ChaCha20Save;
use SafeCrypt\Exceptions\CryptoException;

$keyDir = __DIR__ . '/keys';
$keyFileName = 'chacha20_key.pem';
$keyPath = $keyDir . DIRECTORY_SEPARATOR . $keyFileName;

if (!file_exists($keyDir)){
    mkdir($keyDir, 0700, true);
}

try{
    if (file_exists($keyPath)){
        $keyLoader = new ChaCha20Load();
        $key = $keyLoader->load_key($keyFileName, $keyDir);
    }else{
        $keyGen = new ChaCha20Key();
        $key = $keyGen->generated(32);

        $keySaver = new ChaCha20Save();
        $keySaver->save_key($key, $keyFileName, $keyDir);
    }

    $chacha = new ChaCha20($key);

}catch (CryptoException $e){
    exit("Crypto error during setup: " . $e->getMessage() . PHP_EOL);
}catch (\Exception $e){
    exit("General error during setup: " . $e->getMessage() . PHP_EOL);
}

echo "Setup ChaCha20 ready\n";

// ----------- Encrypt/Decrypt -----------
$message = "My secret message for ChaCha20-Poly1305.";
$aad = "userID:123,timestamp:" . time();

$encrypted = $chacha->encrypt($message, $aad);
$decrypted = $chacha->decrypt($encrypted, $aad);

echo "\n--- Test Encrypt/Decrypt ---\n";
echo "Encrypted (base64): " . base64_encode($encrypted) . PHP_EOL;
echo "Decrypted: " . $decrypted . PHP_EOL;
echo $decrypted === $message ? "Success\n" : "Failure\n";

```