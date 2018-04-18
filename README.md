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
Copy contents of this repository to document root of web server

*To increase security, disable access logging in your web server's configuration*

## Summary Of How It Works
### Submitting Secret
* Random 32-character key is created
* Submitted text is AES-256-CBC encrypted with key
* Kay is hashed with bcrypt (static salt, cost of 11) and then base64 encoded
* File created in `secrets` directory. Name of file is the base64'd bcrypt hash of the random key
* Encrypted version of submitted text is stored inside of created file
* Key is Base64 encoded
* Retrieval URL is created by appending Base64 version of the key to end
  * `https://flashpaper.io/?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d$`

### Retrieving Secret
* Base64 portion of URL is stripped from URL
* Decode Base64 string to get the decryption key
* Generate bcrypt of the key and base64 it
* Look for file in `secrets` that is named the base64'd hash that we just generated
* Get text from the file that we found and decrypt it with the key
* Return the decrypted secret text to user
* Delete the file

## Automating Requests With `curl`

To suppress the HTML and CSS output so that you just have plain-text results, you'll need to include the 'nostyle' argument in the POST data of each request.

### Get self-destructing link
`curl -s -X POST -d "nostyle=true&secret=**BASE64 SECRET HERE**" https://flashpaper.io`

### Retrieve secret text from link
`curl -s -X POST -d "nostyle=true" https://flashpaper.io/?k=1a2b3c4d5a6b7c8d9a0b1c2d3a4b5c6d$`

:exclamation: When generating a self-destructing link; the 'secret' variable must be in Base64 encoded format. There are some built-in checks to validate that you haven't forgotten this, but they will not work 100% of the time. If you fail to properly Base64 encode your secret before submission and manage to get a retrieval link returned to you, you **WILL** get invalid data when that secret is recovered from that link.
