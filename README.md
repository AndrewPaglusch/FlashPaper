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

Change the static AES key and salt in `includes/functions.php` if you're going to use this in production. Do **not** skip this step if your care about security!

### To generate a unique key and salt:
```
#key
openssl rand -base64 256 | tr -d '\n'

#salt
openssl rand -base64 64 | tr -d '\n'
```

To further increase security, disable access logging in your web server's configuration so nothing sensetive (IP addresses, useragents, timestamps, etc) are logged to disk.

## Summary Of How It Works
### Submitting Secret
* Random 256-bit cryptographically strong key is created
* Random IV is created
* Submitted text is AES-256-CBC encrypted with key. Random IV used during encryption
* Ciphertext is now encrypted with static AES key. **This should be unique for your install!**
* IV + key is hashed with bcrypt (static salt, cost of 11) and then base64 encoded
* File is created in `secrets` directory. The name of file is a random 20-character string + the base64'd bcrypt hash of the IV + key
* Ciphertext is stored inside of created file
* 'k' value of retrieval URL is base64 endcoded IV + key
  * `https://flashpaper.io/?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d$`

### Retrieving Secret
* 'k' value of URL is base64 decoded and split into IV and key portions
* IV + key from URL are hashed with bcrypt (static salt, cost of 11) and then base64 encoded
* Look for file in `secrets` directory that's named the base64'd hash of IV + key that we just generated (plus random 20-character prefix in filename). If the URL has been tampered with, the hash will not match any filename on disk and no secret will not be returned.
* Get text from the file that we found and decrypt it with the key and IV from URL
* Decrypt text using static AES key
* Return the decrypted text to user
* Delete the file

## Automating Requests With `curl`

### Create self-destructing link
`curl -X POST -d "k=**BASE64 SECRET HERE**" "https://flashpaper.io/api.php"`

### Retrieve secret text from link
`curl "https://flashpaper.io/api.php?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d$"`

### Don't want to deal with JSON?
`curl -X POST -d "k=**BASE64 SECRET HERE**" "https://flashpaper.io/api.php?json=false"`

`curl "https://flashpaper.io/api.php?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d$&json=false"`

:exclamation: When generating a self-destructing link; the 'secret' variable must be in Base64 encoded format. If you fail to properly Base64 encode your secret before submission and manage to get a retrieval link returned to you, you **WILL** get invalid data when that secret is recovered from that link.
