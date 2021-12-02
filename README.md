# FlashPaper
A one-time encrypted zero-knowledge password/secret sharing application focused on simplicity and security. No database or complicated set-up required.

## Demo

https://flashpaper.io

![Picture of Main Page](https://i.imgur.com/3gDOy5l.png)

## Requirements
* PHP 5.6+
* Web server

## Installation
Copy the contents of this repository to document root of your web server.
Copy settings.example.php to settings.php and make customizations to that file

To further increase security, disable access logging in your web server's configuration so nothing sensetive (IP addresses, useragents, timestamps, etc) are logged to disk.

## Summary Of How It Works
### Submitting Secret
* `<random>--secrets.sqlite` sqlite database created (if it doesn't already exist).
* Random 256-bit AES key is created
* Random 256-bit AES static key is created (if one doesn't exist already)
* Random 128-bit IV is created
* Random 64-bit ID is created
* ID + AES key is hashed with bcrypt 
* Submitted text is encrypted with AES-256-CBC using AES key and random IV
* Ciphertext is now encrypted with AES-256-CBC using static AES key and random IV
* ID and AES key joined (known as `k`)
* ID, IV, bcrypt hash, and ciphertext stored in DB
* `k` value returned to user in one-time URL
  * Example URL: `https://flashpaper.io/?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d7e8f9g`

 
### Retrieving Secret
* `k` value removed from URL
* `k` value split into two parts: ID and AES key
* IV, bcrypt hash, and ciphertext looked up from DB with ID from `k`
* `k` bcrypt hash compared against bcrypt hash from DB (prevents tampering of URL)
* Ciphertext decrypted with static AES key and IV
* Ciphertext decrypted with AES key from `k` and IV
* Entry deleted from DB
* Decrypted text sent to user

## Donations

PayPal: https://paypal.me/AndrewPaglusch

BitCoin: 1EYDa33S14ejuQGMhSjtBUmBHTBB8mbTRs

Donations are not expected, but they are very appreciated!
