# File encryption example AESGCM

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use SafeCrypt\Protocole\AESGCM;

/*
Faire  une library php pour lire les très très gros fichier pour pouvoir les chiffre 
car la video de 620 MO n'a pas pu ce chargé trop lourd

*/

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