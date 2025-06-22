# ChaCha20-Poly1305 

SafeCrypt implements the ChaCha20-Poly1305 algorithm, a modern symmetric encryption protocol that provides security, speed, and resistance to side-channel attacks.

---


## 🔐 Main features

- key generation
- encryption
- decryption
- Key size support: 256 bits

## 🧱 API - Classes and Methods

| Array | Class |
|---------|-------|
|   1     | ChaCha20(string $key) |
|   2     | generated(int $size)  |
|   3     |   ChaCha20Load() |
|   4     |   ChaCha20Save() |
 

| Array | method | description |
|-------|---------|------------|
| 1 | getCipherMethod() | returns the encryption method |
| 1 | encrypt(string $plaintext, string $additionalData = '') | allows data to be encrypted |
| 1 | decrypt(string $encryptedMessage, string $additionalData = '') | allows data to be decrypted |
|---|---|---|
| 2 | generated(int $size) | allows you to generate a key|
|---|---|---|
| 3 | load_key(string $fileName, string $directory = './keys') | allows you to load a key |
|---|---|---|
|4| save_key(string $key, string $fileName, string $directory = './keys') | allows you to save a key that has been generated |


## 🚀 Example

Below I put a link allowing you to look at the code examples : 

[example-ChaCha20-Poly1305](../example/ChaCha20-Poly1305.md)


## ⚠️ Safety Notes

- Never expose the key.
- Always use a unique nonce per encryption.
- AAD ensures metadata integrity.