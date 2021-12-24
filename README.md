# FlashPaper
A one-time encrypted zero-knowledge password/secret sharing application focused on simplicity and security. No database or complicated set-up required.

## Demo

https://flashpaper.io

![Picture of Main Page](https://i.imgur.com/KIs9fjE.png)

## Installation

### Docker *(Recommended)*
  1. Download and extract the [latest release](https://github.com/AndrewPaglusch/FlashPaper/releases/latest) of FlashPaper
  2. Edit the `docker-compose.yml` file with your customizations
  3. Run `docker-compose up -d` to start FlashPaper
  4. Set up a reverse-proxy in front of FlashPaper that terminates SSL/TLS

### Traditional
  **Requirements:** PHP 7.0+ and a web server
  1. Download and extract the [latest release](https://github.com/AndrewPaglusch/FlashPaper/releases/latest) of FlashPaper to the document root of your web server
  2. Copy `settings.example.php` to `settings.php` and make customizations to that file
  3. Disable access logging in your web server's configuration so nothing sensitive (IP addresses, user agent strings, timestamps, etc) are logged to disk

## How It Works
### Submitting Secret
  1. `<random>--secrets.sqlite` sqlite database created (if it doesn't already exist)
  2. `<random>--aes-static.key` randomized 256-bit AES static key created (if one doesn't exist already)
  3. Random 256-bit AES key created
  4. Random 128-bit IV created
  5. Random 64-bit ID created
  6. ID + AES key hashed with bcrypt 
  7. Submitted text encrypted with AES-256-CBC using AES key and random IV
  8. Ciphertext now encrypted with AES-256-CBC using static AES key and random IV
  9. ID and AES key joined (known as `k`)
  10. Random prune date/time generated using `prune`->`min_days`/`max_days`
  11. ID, IV, bcrypt hash, ciphertext, and prune epoch stored in DB
  12. `k` value returned to user in one-time URL

### Retrieving Secret
  1. `k` value removed from URL
  2. `k` value split into two parts: ID and AES key
  3. IV, bcrypt hash, ciphertext looked up in DB with ID from `k`
  4. `k` bcrypt hash compared against bcrypt hash from DB (prevents tampering of URL)
  5. Ciphertext decrypted with static AES key and IV
  6. Ciphertext decrypted with AES key from `k` and IV
  7. Entry deleted from DB
  8. Decrypted text sent to user

## Settings

### `prune`:
 - `enabled`: Turn on/off auto-pruning of old secrets from the database upon page load
 - `min_days`/`max_days`: When a secret is submitted, a random date/time is generated between `min_days` and `max_days` in the future. After that date/time has elapsed, the secret will be pruned from the database if `enabled` is set to `true`. This is to prevent your database from being filled with secrets that are never retrieved. NOTE: Even if `enabled` is set to `false`, the prune value will still be generated and stored in the database, but secrets will not be pruned unless `enabled` is switched to `true`.

## Donations

PayPal: https://paypal.me/AndrewPaglusch

BitCoin: 1EYDa33S14ejuQGMhSjtBUmBHTBB8mbTRs

Donations are not expected, but they are very appreciated!
