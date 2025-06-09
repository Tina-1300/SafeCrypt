```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use SafeCrypt\Protocole\AESGCM;


if(file_exists("./aes_key.txt") != true){
    $key = random_bytes(32);
    file_put_contents('aes_key.txt', base64_encode($key));
}


$keyFromFile = base64_decode(file_get_contents('aes_key.txt'));


$aes = new AESGCM($keyFromFile);

$data = "Message secret";

try {
    $encrypted = $aes->encrypt($data);

    echo "Ciphertext (base64) : " . $encrypted['ciphertext'] . PHP_EOL;
    echo "IV (base64) : " . $encrypted['iv'] . PHP_EOL;
    echo "Tag (base64) : " . $encrypted['tag'] . "\n". PHP_EOL;
} catch (Exception $e) {
    echo "Erreur d'encrypt : " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$decrypted = $aes->decrypt($encrypted['ciphertext'], $encrypted['iv'], $encrypted['tag']);

echo "Décrypté : " . $decrypted  . PHP_EOL;

// php test.php
```