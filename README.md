# phpub2twtxt
An PHP web interface to publish your microblogging in twtxt format [twtxt.txt](https://github.com/buckket/twtxt)

Forked from phpup2twt for a more complete interface to write, read and reply to twts.

[Insert a screenshot here]

~~Requires PHP 8.1 or above.~~
Tested with PHP 7.2.32 (For compatibility)

## Setup and use

1. Upload the files to youe Web Server, or clone this project from Git.
	- I'd recommend domain.com/twtxt/

2. Copy .config.sample to .config and edit the file
	- Add the absolute path of youe twtxt.txt file in your file system, and in the public URL

3. Navigate to domain.com/twtxt/index.php (or equivalent) in youe web browser and start microblogging

4. Tell the world to check out your awesome micro-blog at www.yourdomain.net/twtxt.txt

## WARNING!

**Use of this software is totally at one's own risk!!!**

## TODO

Check TODO.md for a detailed

* Security / login
	- [ ] Add TOTP
	- [ ] Add Webauthn support

* Missing line break depending on how the file was left the last time
	- [X] add the line break as the first thing
	- [ ] check for line breaks at EOF before writing


## Ideas

# Meta
Code based on [register-with-txt by Gabriel de Jesus](https://github.com/gabrieldejesus/register-with-txt) and [pbpush by Luqaska](https://github.com/Luqaska/pbpush)

Done with bits of PHP to make it write just one line of twtxt-compliant data at a time.

Distributed under the MIT License. See [LICENSE](LICENSE) for more information.

# Required extensions in PHP.ini
curl
openssl
pdo_sqlite
sodium