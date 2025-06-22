# SafeCrypt - AES-GCM

SafeCrypt implements the AES-GCM algorithm, a symmetric encryption protocol that allows for the secure encryption of very large data.

---


## 🔐 Main features

- key generation
- encryption
- decryption
- Key size support: 256 bits

## 🧱 API - Classes and Methods

| Array | Class |
|---------|-------|
|   1     | AESGCM(string $key) |


| Array | method | description |
|-------|---------|------------|
| 1 | encrypt(string $plaintext) | allows you to encrypt data|
| 1 | decrypt(string $ciphertext, string $iv, string $tag) | allows you to decrypt data |
| 1 | decryptCombined(string $combinedData) | makes decrypting data much easier |


## 🚀 Example

Below I put a link allowing you to look at the code examples : 

[example-aes-gcm](../example/aes-gcm.md)


## ⚠️ Safety Notes

- Never expose the key.