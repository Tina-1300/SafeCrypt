<?php


namespace SafeCrypt\Interfaces;

interface CipherInterface
{
    public function encrypt(string $plaintext): array;
    public function decrypt(string $ciphertext, string $iv, string $tag): string;
}
