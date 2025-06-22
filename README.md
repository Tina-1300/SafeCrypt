# SafeCrypt

SafeCrypt is a PHP library that makes cryptography easier to manage by providing a wrapper for OpenSSL so that even novices can use cryptography.

> This library was designed to allow simple use of modern cryptography.

## 📦 Installation

You can install SafeCrypt via `composer` :

```bash
composer require tina-1300/safecrypt
```

## 🔍 Why SafeCrypt?

Using OpenSSL directly in PHP can require a lot of coding and a good understanding of best practices to avoid errors (key management, IV, authentication tags, encoding/decoding, etc.).

Here's a typical example of AES-256-GCM encryption in native PHP:

```php
<?php

$key = openssl_random_pseudo_bytes(32);
$key_length = strlen($key);
if ($key_length !== 32){
    die("The key is not 256 bits\n");
}

$iv = openssl_random_pseudo_bytes(12);
$iv_copy = $iv;
if (strlen($iv) !== 12){
    die("The IV is invalid\n");
}
if (strlen($iv_copy) !== 12){
    die("The copied IV is invalid\n");
}

$plaintext = "Here is a confidential message";
echo "Original post : $plaintext\n";

$cipher_algo = "aes-256-gcm";

$tag = '';
$ciphertext = openssl_encrypt($plaintext, $cipher_algo, $key, OPENSSL_RAW_DATA, $iv, $tag);

if ($ciphertext === false){
    die("Encryption error\n");
}

$ciphertext_b64 = base64_encode($ciphertext);
$iv_b64 = base64_encode($iv);
$tag_b64 = base64_encode($tag);

echo "Ciphertext (base64) : $ciphertext_b64\n";
echo "IV (base64) : $iv_b64\n";
echo "Tag (base64) : $tag_b64\n";


$decoded_ciphertext = base64_decode($ciphertext_b64);
$decoded_iv = base64_decode($iv_b64);
$decoded_tag = base64_decode($tag_b64);

if (strlen($decoded_iv) !== 12){
    die("Invalid decoded IV\n");
}

$decrypted = openssl_decrypt($decoded_ciphertext, $cipher_algo, $key, OPENSSL_RAW_DATA, $decoded_iv, $decoded_tag);

if ($decrypted === false){
    die("Decryption error\n");
}

echo "Deciphered message : $decrypted\n";

```

This approach, while powerful, requires a great deal of rigor.

With SafeCrypt, the same encryption is performed in just a few lines:

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use SafeCrypt\Protocole\AESGCM;


if(file_exists("./aes_key.txt") != true){
    $key = random_bytes(32);
    file_put_contents('aes_key.pem', base64_encode($key));
}

$keyFromFile = base64_decode(file_get_contents('aes_key.pem'));


$aes = new AESGCM($keyFromFile);

$data = "Secret message";

$encrypted = $aes->encrypt($data);

$data_crypted = "".$encrypted['ciphertext'].$encrypted['iv'].$encrypted['tag']."";

echo "encrypted data : " . $data_crypted . PHP_EOL;

$data_decrypted = $aes->decryptCombined($data_crypted);

echo "decrypted data : " . $data_decrypted . PHP_EOL;
```


## 📚 Documentation

Full documentation is available in the `docs\` folder or online [https://tina-1300.github.io/documentation/SafeCrypt/index.html](https://tina-1300.github.io/documentation/SafeCrypt/index.html).

## 🛠️ Contribution

Contributions are welcome! To facilitate the integration of your changes, please follow these best practices:

- Fork the repository and work on a branch dedicated to your feature or fix.

- Respect the branch naming convention (e.g., `feature/my-feature` or `fix/bug-description`...).

- Make sure to write clean and readable code, respecting PHP coding standards.

- Test your changes thoroughly before submitting a pull request.

- Add or update documentation and examples as needed.

- Write a clear and descriptive commit message.

- Submit a pull request explaining the purpose of your contribution and the changes made.

- Comply with the license policy and avoid including unauthorized third-party code.

Thank you for helping make SafeCrypt better!


## 📄 License

SafeCrypt is distributed under the MIT License. See the LICENSE file for more information.