# SafeCrypt - RSA

SafeCrypt implements the RSA algorithm, an asymmetric encryption protocol based on a public/private key pair.

This mechanism allows the secure exchange of sensitive data (such as an AES key), even over unencrypted channels such as HTTP. Using RSA, two clients (PCs) can securely exchange an AES session key without the key being compromised by network interception.

---


## 🔐 Main features

- RSA key pair generation (public key/private key)
- Public key encryption
- Private key decryption
- Key export/import in text format (Base64 or PEM)
- Key size support: 1024 <!--2048, 3072, 4096--> bits

## 🧱 API - Classes and Methods

| Array | Class |
|---------|-------|
|   1     | RSA(string $public_key, string $private_key)|
|   2     | RSAKey() |
|   3     | RSALoad() |
|   4     | RSASave() |

| Array | method | description |
|-------|---------|------------|
| 1 | encrypt(string $data) | allows you to encrypt data|
| 1 | decrypt(string $encryptedBase64) | allows you to decrypt data |
|---|---|---|
|2  | generated(int $keySize = 1024) | allows you to generate a private/public key |
|---|---|---|
|3  | load_key(array $options) | allows you to load a private/public key|
|---|---|---|
|4 | save_key(string $publicKey, string $privateKey, string $directory = './keys') | method to save private keys and generated public key |



## 🚀 Example

Below I put a link allowing you to look at the code examples : 

[example-rsa](../example/rsa.md)


## ⚠️ Safety Notes

- Never expose the private key.
- RSA is only used for symmetric key exchange (e.g., AES), not for encrypting large files.
- Preferably, use AES-GCM or ChaCha20 for data encryption after the key exchange.