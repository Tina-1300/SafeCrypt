<?php
declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use SafeCrypt\Protocole\AESGCM;

final class AESGCMTest extends TestCase{
    private AESGCM $aes;
    private string $key;
    private string $keyDir;
    private string $keyPath;

    protected function setUp(): void{
        $this->keyDir = __DIR__ . '/keys';
        $this->keyPath = $this->keyDir . '/aes_key.pem';

        if (!file_exists($this->keyDir)){
            mkdir($this->keyDir, 0777, true);
        }

        if(!file_exists($this->keyPath)){
            $this->key = bin2hex(random_bytes(32));
            file_put_contents($this->keyPath, $this->key);
        }

        $this->key = hex2bin(file_get_contents($this->keyPath));
        
        $this->aes = new AESGCM($this->key);
    }

    public function testEncryptDecrypt(): void{
        /*
        fwrite(STDOUT, PHP_EOL);
        fwrite(STDOUT, "==========================================" . PHP_EOL);
        fwrite(STDOUT, " UNIT TEST : AES-GCM ENCRYPTION/DECRYPTION" . PHP_EOL);
        fwrite(STDOUT, "==========================================" . PHP_EOL . PHP_EOL);
        */
        $message = "Message secret";
        $encrypted = $this->aes->encrypt($message);

        // Affichage des données
        /*
        fwrite(STDOUT, "Clé               : " . bin2hex($this->key) . PHP_EOL);
        fwrite(STDOUT, "Message original  : " . $message . PHP_EOL);
        fwrite(STDOUT, "Chiffrement       : " . bin2hex($encrypted['ciphertext']) . PHP_EOL);
        fwrite(STDOUT, "IV                : " . bin2hex($encrypted['iv']) . PHP_EOL);
        fwrite(STDOUT, "Tag               : " . bin2hex($encrypted['tag']) . PHP_EOL);
        */

        $decrypted = $this->aes->decrypt(
            $encrypted['ciphertext'],
            $encrypted['iv'],
            $encrypted['tag']
        );

        //fwrite(STDOUT, "Déchiffré         : " . $decrypted . PHP_EOL);

        $fused = $encrypted['ciphertext'] . $encrypted['iv'] . $encrypted['tag'];
        $decrypted2 = $this->aes->decryptCombined($fused);

        //fwrite(STDOUT, "Déchiffré         : " . $decrypted2 . PHP_EOL);

        // Vérifications
        $this->assertSame($message, $decrypted, "Le message déchiffré ne correspond pas au message original.");
        $this->assertSame($message, $decrypted2, "Le message déchiffré par decryptCombined ne correspond pas.");

        // Fin du test
        /*
        fwrite(STDOUT, PHP_EOL);
        fwrite(STDOUT, "FIN DU TEST AES-GCM" . PHP_EOL);
        fwrite(STDOUT, "------------------------------------------" . PHP_EOL . PHP_EOL);
        */
    }
}
 

// ../vendor\bin\phpunit AESGCMTest.php
// ../vendor\bin\phpunit .
// ../vendor\bin\phpunit tests
// vendor\bin\phpunit tests
// vendor\bin\phpunit .
// vendor\bin\phpunit AESGCMTest.php
// vendor\bin\phpunit tests/AESGCMTest.php
// vendor\bin\phpunit tests/.
// vendor\bin\phpunit --display-warnings tests/.
//composer require --dev phpunit/phpunit:^12
//extension=mbstring
// documentation : https://docs.phpunit.de/en/12.2/

