# 📜 Changelog

All notable changes to this project will be documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/)
and versioning follows [SemVer](https://semver.org/lang/fr/).

---

## [1.2.0] - 2025-06-15
### Added
- Implementation of the RSA asymmetric encryption protocol
- Key pair generation (`RSAKey`)
- Encryption, Decryption (`RSA`)
- Saving and loading keys (`RSASave`, `RSALoad`)

---

## [1.1.0] - 2025-06-10
### Added
- Improvement on `AES-256-GCM`:
- Added a method to facilitate decryption

---

## [1.0.0] - 2025-06-10
### Added
- First stable release
- Better error handling via `CryptoException`
- Implementation of the `AES-256-GCM` symmetric encryption:
- Random AES key generation
- Secure encryption and decryption with IV + tag
- Base64 support for encrypted messages

---