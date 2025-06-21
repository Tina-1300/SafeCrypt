<?php
declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use SafeCrypt\Protocole\RSA;
use SafeCrypt\Protocole\RSAKey;
use SafeCrypt\Protocole\RSASave;
use SafeCrypt\Exceptions\CryptoException;

final class RSATest extends TestCase {
    private string $keyDir;
    private string $publicPath;
    private string $privatePath;
    private RSA $rsa;

    protected function setUp(): void {
        $this->keyDir = __DIR__ . '/keys';
        $this->publicPath = $this->keyDir . '/public.pem';
        $this->privatePath = $this->keyDir . '/private.pem';

        /*
        fwrite(STDOUT, PHP_EOL);
        fwrite(STDOUT, "==========================================" . PHP_EOL);
        fwrite(STDOUT, "     UNIT TEST : RSA ENCRYPTION/DECRYPTION" . PHP_EOL);
        fwrite(STDOUT, "==========================================" . PHP_EOL . PHP_EOL);
        */

        if (!file_exists($this->keyDir)) {
            mkdir($this->keyDir, 0777, true);
        }

        try {
            if (file_exists($this->publicPath) && file_exists($this->privatePath)) {
                //fwrite(STDOUT, "Chargement des clés existantes..." . PHP_EOL);
                $publicKey = file_get_contents($this->publicPath);
                $privateKey = file_get_contents($this->privatePath);
            } else {
                //fwrite(STDOUT, "Génération des clés RSA (1024 bits)..." . PHP_EOL);
                $keyGen = new RSAKey();
                $keys = $keyGen->generated(1024);

                $save = new RSASave();
                $save->save_key($keys['public'], $keys['private'], $this->keyDir);

                $publicKey = $keys['public'];
                $privateKey = $keys['private'];

                //fwrite(STDOUT, "Clés générées et sauvegardées." . PHP_EOL);
            }

            $this->rsa = new RSA($publicKey, $privateKey);
        } catch (CryptoException $e) {
            $this->fail("Erreur Crypto : " . $e->getMessage());
        } catch (\Exception $e) {
            $this->fail("Erreur Générale : " . $e->getMessage());
        }
    }

    public function testEncryptDecrypt(): void {
        $message = "Mon message secret";

        //fwrite(STDOUT, PHP_EOL);
        //fwrite(STDOUT, "Message original : $message" . PHP_EOL);

        $encrypted = $this->rsa->encrypt($message);
        //fwrite(STDOUT, "Chiffré          : $encrypted" . PHP_EOL);

        $decrypted = $this->rsa->decrypt($encrypted);
        //fwrite(STDOUT, "Déchiffré        : $decrypted" . PHP_EOL);

        $this->assertSame($message, $decrypted, "Le message déchiffré ne correspond pas au message original.");

        /*
        fwrite(STDOUT, PHP_EOL);
        fwrite(STDOUT, "Test RSA réussi : le message a été correctement déchiffré." . PHP_EOL);

        fwrite(STDOUT, PHP_EOL);
        fwrite(STDOUT, "FIN DU TEST RSA" . PHP_EOL);
        fwrite(STDOUT, "------------------------------------------" . PHP_EOL . PHP_EOL);
        */
    }

}