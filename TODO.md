# TO DO
## Must Have
- [ ] Follow and Stop following URLs
- [ ] Fix Login redirection with Post-Redirect-Get

## Should have
- [/] Parse mentions like @<~duriny https://envs.net/~duriny/twtxt.txt>
- [ ] In the reply, select the language for the twt
- [ ] In the reply, show the root message to make it easier to know what are replying to
- [ ] Switch the project from Github to some service more friendly to FOSS ?
- [ ] Allow a login only every 3 seconds
- [ ] Check that the Twtxt URLs are valid (by regex or ping)
- [ ] Fix error for following list with Mastodon accounts (on Yarn.social twtxt)
- [ ] Implement discover_url = to know where to inform for followers
- [ ] Implement discover PHP to receive suscriptions

## Nice to have
- [/] Support markdown or gemtext -> Currently supports images and links
- [ ] Chat view - Sort your follower list by last twt (and show that in the list)
      like [Mastodon Treed](https://dzwdz.github.io/treed/client.html) does
- [ ] Implement RSS for main timeline
- [ ] Check replies from your followers to know if someone replied to you (Like notifications)
- [ ] Auto-anounce that you've replied
- [ ] Add a URL router for fancy URLs
- [ ] Decide if we allow Selfsigned certificates and implement
- [ ] Support for Gemini URLs

## Done (Sorted by implementation date)
- [X] Fix Creation of dynamic property DateInterval::$w is deprecated on PHP 8.2 (functions.php)
- [X] Fork the project to `twtxt-php` repository
- [X] Show a default avatar for .txts w/o avatar URL
- [X] Updated documentation more friendly to final users
- [X] Added emojis
- [X] Support Markdown images
- [X] Add a view for User twts (when you click on their avatar or nick)
- [X] Implement long Session cookie time (to 7 days) refreshing when it's used
- [X] Get Thread hashes (#m443x2q)
- [X] Cache reads to avoid retreiving same file many times
- [X] Read all the twts from your followers
- [X] Add a button to reply to a twt
- [X] Generate threads with replies
- [X] Get all the twits belonging to a thread, and make the tree
- [X] Fixed responsivity
- [X] Login with TOTP
- [X] Get the timeline of all your suscriptions
- [X] Cache the last twts from your followers
- [X] Sort all the 'timeline' by date
- [X] Write to your .txt file
- [X] Read a twtxt file from an URL (HTTP)
- [X] Read meta from .txt file (Test if it works for edge cases)
- [X] Get twt Hash (based on yarn.social spec)
