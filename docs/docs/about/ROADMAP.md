# 🛡️ SafeCrypt - Roadmap

This document describes the features planned for future versions of the SafeCrypt library.

---

## ✅ Release version: `v1.0.0`

- [x] AESGCM message encryption and decryption
- [x] Error handling via the `CryptoException` class

---

## ✅ Release version: `v1.1.0` (Goal: Improved symmetric encryption)

- [x] Added the `decryptCombined` method to the AESGCM class

---

## ✅ Current version: `v1.2.0` – (Purpose: Adding RSA Asymmetric Encryption)

- [x] RSA Encryption (Public/Private Key)
- [x] RSA Key Generation (RSAKey)
- [x] RSA Key Saving/Loading (RSASave/RSALoad)
- [x] RSA Message Encryption & Decryption

---

## 🚧 Version `v1.3.0` – Addition of new symmetric protocols

- [ ] ChaCha20-Poly1305 encryption
- [ ] ChaCha20 + Poly1305 MAC implementation
> Fast and secure alternative to AES-GCM, ideal for mobile devices

---

## 🚧 Version `v1.4.0` – Added new symmetric protocols

- [ ] AES encryption
- [ ] ECB implementation
- [ ] CBC implementation
- [ ] CTR implementation

---

## 🚧 Version `v1.5.0` – Advanced Asymmetric Protocol and Signatures

- [ ] Elliptic Curve Cryptography (ECC)
- [ ] Support for common curves (secp256r1, secp256k1, ed25519)
- [ ] Key generation, lightweight encryption, digital signatures

- [ ] ECDSA (Elliptic Curve Digital Signature Algorithm)
- [ ] Signature and verification with elliptic curves

- [ ] EdDSA (Ed25519)
- [ ] Modern, fast, secure, and compact signature
---

## 📌 Contribute

Want to help?
1. Choose a `🚧` task from the roadmap.
2. Create a dedicated branch on GitHub with a clear name that matches your task (e.g., `feature/chacha20-poly1305`, `bugfix/aes-gcm-decrypt`).
3. Implement your feature or fix.
4. Open a Pull Request (PR) from your branch to `dev` for review.

Thanks for your contribution! 🚀

---

## 🧪 Automated Tests (TODO)

- [X] Add PHPUnit tests for all protocols
- [X] Add RSA tests
- [X] Add AES-GCM tests
