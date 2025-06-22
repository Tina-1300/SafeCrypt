# AES-GCM Example

---

### Encrypting & decrypting a message :


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


//-------
//$decrypted = $aes->decrypt($encrypted['ciphertext'], $encrypted['iv'], $encrypted['tag']);
//or 
$str = $encrypted['ciphertext'].$encrypted['iv'].$encrypted['tag'];
$decrypted = $aes->decryptCombined($str);
//-----


echo "Decipher : " . $decrypted  . PHP_EOL;

// php test.php
```

### File encryption & decryption :


```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use SafeCrypt\Protocole\AESGCM;

$file_key = "aes_key.txt";
$original_file = "PMS.pdf"; // PMS.pdf
$encrypted_file =  $original_file.".lock"; // PMS.pdf.lock

if(!file_exists("./".$file_key)){
    $key = random_bytes(32);
    file_put_contents($file_key, base64_encode($key));
}

$keyFromFile = base64_decode(file_get_contents($file_key));
$aes = new AESGCM($keyFromFile);


if(!file_exists($original_file)){
    $data = file_get_contents($encrypted_file);
    $decrypted = $aes->decryptCombined($data);
    file_put_contents($original_file, $decrypted, FILE_APPEND | LOCK_EX);
    unlink($encrypted_file);
    exit(0);
}


$data = file_get_contents($original_file);
$encrypted = $aes->encrypt($data);
file_put_contents($encrypted_file, $encrypted['ciphertext'].$encrypted['iv'].$encrypted['tag'], FILE_APPEND | LOCK_EX);
unlink($original_file);

// php test.php
```
