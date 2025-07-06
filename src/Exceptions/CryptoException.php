<?php

namespace SafeCrypt\Exceptions;

use Exception;


class CryptoException extends Exception{

    public const ERR_INVALID_KEY_LENGTH = 1001;
    public const ERR_ENCRYPTION_FAILED = 1002;
    public const ERR_DECRYPTION_FAILED = 1003;
    public const ERR_INVALID_PARAMETER = 1004;


    public function __construct(string $message, int $code = 0, ?Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }

    public static function invalidKeyLength(int $length, int $expected): self{
        return new self(
            "Invalid key length: {$length} bytes provided, expected {$expected} bytes.",
            self::ERR_INVALID_KEY_LENGTH
        );
    }


    public static function encryptionFailed(): self{
        return new self(
            "Encryption failed.",
            self::ERR_ENCRYPTION_FAILED
        );
    }


    public static function decryptionFailed(): self{
        return new self(
            "Decryption failed.",
            self::ERR_DECRYPTION_FAILED
        );
    }

    public static function invalidParameter(string $parameterName): self{
        return new self(
            "Invalid parameter: {$parameterName}.",
            self::ERR_INVALID_PARAMETER
        );
    }

}