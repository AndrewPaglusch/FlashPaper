# FlashPaper
A one-time encrypted zero-knowledge password/secret sharing application focused on simplicity and security. No database or complicated set-up required.

## Demo

https://flashpaper.io

![Picture of Main Page](https://i.imgur.com/3gDOy5l.png)

## Requirements
* PHP 5.5.0+
* Web server
* Linux

## Installation
Copy the contents of this repository to document root of your web server. 

Change the static AES key in `includes/functions.php` if you're going to use this in production. Do **not** skip this step if your care about security!

### To generate a unique key:
```
openssl rand -base64 256 | tr -d '\n'
```

To further increase security, disable access logging in your web server's configuration so nothing sensetive (IP addresses, useragents, timestamps, etc) are logged to disk.

## Summary Of How It Works
### Submitting Secret
* `secrets.sqlite` sqlite database created (if it doesn't already exist) with two tables: `salts` and `secrets`.
* Random 256-bit AES key is created
* Random 16-bit IV is created
* Random 64-bit salt is created
* Random 16-bit salt ID is created
* AES key is hashed with bcrypt (cost of 11). Random salt is used.
* Submitted text is encrypted with AES-256-CBC using AES key and random IV
* Ciphertext is now encrypted with AES-256-CBC using static AES key and random IV
* Salt ID and AES key joined (known as `k`)
* Salt ID and salt stored in `salts` table in DB
* bcrypt hash, IV, and ciphertext stored in `secrets` table in DB
* `k` value returned to user in one-time URL
  * Example URL: `https://flashpaper.io/?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d7e8f9g`

 
### Retrieving Secret
* `k` value removed from URL and base64 decoded
* Decoded `k` value split into two parts: salt ID and AES key
* Salt looked up from `salts` table in DB using salt ID from `k`
* AES key from `k` hashed with bcrypt (cost of 11). Salt from DB is used.
* bcrypt output used to look up ciphertext and IV from `secrets` table in DB
* Ciphertext decrypted with static AES key and IV
* Ciphertext decrypted with AES key from `k` and IV
* Salt, salt ID, bcrypt hash, ciphertext, and IV all deleted from DB
* Decrypted text sent to user

## Automating Requests With `curl`

### Create self-destructing link
`curl -X POST -d "k=**BASE64 SECRET HERE**" "https://flashpaper.io/api.php"`

### Retrieve secret text from link
`curl "https://flashpaper.io/api.php?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d$"`

### Don't want to deal with JSON?
`curl -X POST -d "k=**BASE64 SECRET HERE**" "https://flashpaper.io/api.php?json=false"`

`curl "https://flashpaper.io/api.php?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d$&json=false"`

:exclamation: When generating a self-destructing link; the 'secret' variable must be in Base64 encoded format. If you fail to properly Base64 encode your secret before submission and manage to get a retrieval link returned to you, you **WILL** get invalid data when that secret is recovered from that link.
