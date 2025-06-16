<?php

namespace SafeCrypt\Exceptions;

use Exception;

/**
 * Class CryptoException
 *
 * Exception personnalisée pour les erreurs liées aux opérations cryptographiques
 * dans la bibliothèque OpenCrypto.
 *
 * Permet d'identifier précisément les erreurs liées à la cryptographie et d'y
 * associer des codes spécifiques pour faciliter la gestion des erreurs.
 */
class CryptoException extends Exception
{
    // Codes d'erreur personnalisés
    public const ERR_INVALID_KEY_LENGTH = 1001;
    public const ERR_ENCRYPTION_FAILED = 1002;
    public const ERR_DECRYPTION_FAILED = 1003;
    public const ERR_INVALID_PARAMETER = 1004;

    /**
     * CryptoException constructor.
     *
     * @param string         $message  Le message de l'exception
     * @param int            $code     Code d'erreur spécifique à la cryptographie (optionnel)
     * @param Exception|null $previous Exception précédente (optionnel)
     */
    public function __construct(string $message, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Crée une exception pour une clé invalide.
     *
     * @param int $length Taille de la clé fournie
     * @param int $expected Taille attendue de la clé
     * @return self
     */
    public static function invalidKeyLength(int $length, int $expected): self
    {
        return new self(
            "Invalid key length: {$length} bytes provided, expected {$expected} bytes.",
            self::ERR_INVALID_KEY_LENGTH
        );
    }

    /**
     * Crée une exception pour une erreur de chiffrement.
     *
     * @return self
     */
    public static function encryptionFailed(): self
    {
        return new self(
            "Encryption failed.",
            self::ERR_ENCRYPTION_FAILED
        );
    }

    /**
     * Crée une exception pour une erreur de déchiffrement.
     *
     * @return self
     */
    public static function decryptionFailed(): self
    {
        return new self(
            "Decryption failed.",
            self::ERR_DECRYPTION_FAILED
        );
    }

    /**
     * Crée une exception pour un paramètre invalide.
     *
     * @param string $parameterName Nom du paramètre invalide
     * @return self
     */
    public static function invalidParameter(string $parameterName): self
    {
        return new self(
            "Invalid parameter: {$parameterName}.",
            self::ERR_INVALID_PARAMETER
        );
    }
}
