# phpub2twtxt
An PHP web interface to publish your microblogging in twtxt format [twtxt.txt](https://github.com/buckket/twtxt)

[Insert a screenshot here]

Requires PHP 8.1 or above.

## Setup and use

1. Upload the files to you Web Server, or clone this project from Git.
	- I'd recommend domain.com/twtxt/

2. Copy .config.sample to .config and edit the file
	- Add the absolute path of you twtxt.txt file in your file system, and in the public URL

3. Navigate to domain.com/twtxt/index.php (or equivalent) in you web browser and start microblogging

4. Tell the world to check out you awesome micro blog at www.yourdomain.net/twtxt.txt

## WARNING!

**Use of this software is totally at one's own risk!!!**

## Issues

* UX
	- [ ] Ass

* Security / login
	- [ ] Add Webauthn support

* Missing line break depending on how the file was left the last time
	- [x] add the line break as the first thing
	- [ ] check for line breaks at EOF before writing


## Ideas

# Meta
Code based on [register-with-txt by Gabriel de Jesus](https://github.com/gabrieldejesus/register-with-txt) and [pbpush by Luqaska](https://github.com/Luqaska/pbpush)

Done with bits of PHP to make it write just one line of twtxt compliant data at a time.

Distributed under the MIT License. See [LICENSE](LICENSE) for more information.

# Dev
curl
openssl
pdo_sqlite
sodium