# phpub2twtxt - twtxt-php
A PHP web interface to publish your microblogging in twtxt format [twtxt.txt](https://github.com/buckket/twtxt)

Forked from phpup2twt for a more complete interface to write, read and reply to twts.

[Insert a screenshot here]

Tested with PHP 7.3.33

## Setup and use

1. Upload the files to your Web Server, or clone this project from Git.
	- I'd recommend domain.com/twtxt/

2. Copy .config.sample to .config and edit the file
	- Add the absolute path of your twtxt.txt file in your file system, and the public URL

3. Navigate to domain.com/twtxt/index.php (or equivalent) in your web browser and start microblogging

4. Tell the world to check out your awesome micro-blog at www.yourdomain.net/twtxt.txt

## WARNING!

**Use of this software is totally at one's own risk!!!**

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