# TO DO
## Must Have
- [ ] Sort your follower list by last twt (and show that in the list)
## Should have
- [ ] Fix Login redirection with Post-Redirect-Get
- [ ] Allow only a login every 3 seconds
- [ ] Check that the Twtxt URLs are valid (by regex or ping)
- [ ] Fix error for following list with Mastodon accounts
- [ ] Implement discover_url = to know where to inform for followers
- [ ] Implement discover PHP to receive suscriptions
- [/] Show a default avatar for .txts w/o avatar URL
- [/] Get Menctions @<~duriny https://envs.net/~duriny/twtxt.txt>
## Nice to have
- [ ] Support markdown or gemtext
- [ ] Support images
- [ ] Implement RSS for followers and your timeline
- [ ] Check replies from your followers to know if someone replied to you
- [ ] Auto-anounce that you've replied
- [ ] Add a URL router for fancy URLs
- [ ] Decide if we allow Selfsigned certificates

# Done
- [X] Get Thread hashes (#m443x2q)
- [X] Cache reads to avoid retreiving same file many times
- [X] Read all the twts from your followers
- [X] Add a button to reply to a twt
- [X] Generate threads with replies
- [X] Get all the twits belonging to a thread, and make the tree
- [X] Fixed Session cookie time (to 7 days)
- [X] Fixed responsivity
- [X] Login with TOTP
- [X] Get the timeline of all your suscriptions
- [X] Cache the last twts from your followers
- [X] Sort all the 'timeline' by date
- [X] Write to your .txt file
- [X] Read a twtxt file from an URL
- [X] Read meta from .txt file (Test if it works for edge cases)
- [X] Get twt Hash
