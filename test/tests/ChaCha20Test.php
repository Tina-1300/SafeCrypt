<?php
declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use SafeCrypt\Protocole\ChaCha20;
use SafeCrypt\Protocole\ChaCha20Key;
use SafeCrypt\Protocole\ChaCha20Load;
use SafeCrypt\Protocole\ChaCha20Save;
use SafeCrypt\Exceptions\CryptoException;

final class ChaCha20Test extends TestCase{
    private string $keyDir;
    private string $keyFileName = 'chacha20_key.pem'; 
    private string $keyPath;
    private ChaCha20 $cha_cha_20;
    private string $testKey;

    protected function setUp(): void{
        $this->keyDir = __DIR__ . '/keys';
        $this->keyPath = $this->keyDir . DIRECTORY_SEPARATOR . $this->keyFileName;

        if (!file_exists($this->keyDir)){
            mkdir($this->keyDir, 0700, true);
        }

        try{
            if (file_exists($this->keyPath)){
                $keyLoader = new ChaCha20Load();
                $this->testKey = $keyLoader->load_key($this->keyFileName, $this->keyDir);
            }else{
                $keyGen = new ChaCha20Key();
                $this->testKey = $keyGen->generated(32);

                $keySaver = new ChaCha20Save();
                $keySaver->save_key($this->testKey, $this->keyFileName, $this->keyDir);
            }

            $this->cha_cha_20 = new ChaCha20($this->testKey);

        }catch (CryptoException $e){
            $this->fail("Crypto error during setup : " . $e->getMessage());
        }catch (\Exception $e){
            $this->fail("General error during setup : " . $e->getMessage());
        }
    }

    public function testEncryptDecrypt(): void{
        $message = "My secret message for ChaCha20-Poly1305. This is an important test.";
        $additionalData = "userID:123, timestamp:" . time();

        $encrypted = $this->cha_cha_20->encrypt($message, $additionalData);
        $decrypted = $this->cha_cha_20->decrypt($encrypted, $additionalData);

        $this->assertIsString($decrypted, "Decryption should not fail for a valid message.");
        $this->assertSame($message, $decrypted, "The decrypted message does not match the original message.");
    }

    public function testAuthenticationFailureOnCiphertextTampering(): void{
        $message = "This message will be spoofed.";
        $additionalData = "authentification_test";

        $encrypted = $this->cha_cha_20->encrypt($message, $additionalData);

        $ivLength = openssl_cipher_iv_length($this->cha_cha_20->getCipherMethod());
        $tagLength = 16;
        $ciphertextLength = mb_strlen($encrypted, '8bit') - $ivLength - $tagLength;

        $this->assertGreaterThan(0, $ciphertextLength, "The ciphertext is too short to be forged.");

        $tamperedEncrypted = $encrypted;
        $tamperPos = $ivLength + (int) floor($ciphertextLength / 2); 

        $tamperedPosFinal = ($tamperPos < mb_strlen($tamperedEncrypted, '8bit') - $tagLength)
            ? $tamperPos
            : mb_strlen($tamperedEncrypted, '8bit') - 5;

        $tamperedEncrypted = substr_replace(
            $tamperedEncrypted,
            chr(ord($tamperedEncrypted[$tamperedPosFinal]) ^ 0x01),
            $tamperedPosFinal,
            1
        );

        $decrypted = $this->cha_cha_20->decrypt($tamperedEncrypted, $additionalData);

        $this->assertFalse($decrypted, "Decryption of a forged message should fail.");
    }

    public function testAuthenticationFailureOnAADTtampering(): void{
        $message = "This message has additional data.";
        $originalAdditionalData = "client_id:XYZ, transaction:ABC";

        $encrypted = $this->cha_cha_20->encrypt($message, $originalAdditionalData);
        $tamperedAdditionalData = $originalAdditionalData . " - MODIFIED";

        $decrypted = $this->cha_cha_20->decrypt($encrypted, $tamperedAdditionalData);

        $this->assertFalse($decrypted, "Decryption with additional forged data should fail.");
    }

    public function testAuthenticationFailureOnTagTampering(): void{
        $message = "Test for tag tampering directly.";
        $additionalData = "test_tag_tamper";

        $encrypted = $this->cha_cha_20->encrypt($message, $additionalData);

        $tagLength = 16;
        $tagStartPos = mb_strlen($encrypted, '8bit') - $tagLength;

        $tamperedEncrypted = substr_replace(
            $encrypted,
            chr(ord($encrypted[$tagStartPos]) ^ 0x01),
            $tagStartPos,
            1
        );

        $decrypted = $this->cha_cha_20->decrypt($tamperedEncrypted, $additionalData);

        $this->assertFalse($decrypted, "Decryption with a forged tag should fail.");
    }

    public function testKeyLengthValidation(): void{
        $this->expectException(CryptoException::class);
        $this->expectExceptionMessage('ChaCha20-Poly1305 key must be 32 bytes (256-bit).');

        new ChaCha20(random_bytes(16));
    }

    public function testEmptyKeyValidation(): void{
        $this->expectException(CryptoException::class);
        $this->expectExceptionMessage('ChaCha20-Poly1305 key must be 32 bytes (256-bit).');

        new ChaCha20('');
    }
}
