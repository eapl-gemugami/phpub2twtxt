# TO DO
## Must Have
- [ ] Get all the twits belonging to a thread, and make the tree
- [ ] Generate threads with replies
- [ ] Sort your follower list by last twt (and show that in the list)
- [ ] Add a button to reply to a twt
- [ ] Decide if we allow Selfsigned certificates
## Should have
- [ ] Fix Login redirection with Post-Redirect-Get
- [ ] Allow only a login every 3 seconds
- [ ] Check that the Twtxt URLs are valid (by regex or ping)
- [ ] Read all the twts from your followers
- [ ] Implement discover_url = to know where to inform for followers
- [ ] Implement discover PHP to receive suscriptions
- [ ] Cache reads to avoid retreiving same file many times
- [ ] Show a default avatar for .txts w/o avatar URL
- [ ] Get Thread hashes (#m443x2q)
- [ ] Get Menctions @<~duriny https://envs.net/~duriny/twtxt.txt>
## Nice to have
- [ ] Support markdown or gemtext
- [ ] Support images
- [ ] Implement RSS for followers and your timeline
- [ ] Check replies from your followers to know if someone replied to you
- [ ] Auto-anounce that you've replied
- [ ] Add a URL router for fancy URLs

# Done
- [X] Fixed responsivity
- [X] Login with TOTP
- [X] Get the timeline of all your suscriptions
- [X] Cache the last twts from your followers
- [X] Sort all the 'timeline' by date
- [X] Write to your .txt file
- [X] Read a twtxt file from an URL
- [X] Read meta from .txt file (Test if it works for edge cases)
- [X] Get twt Hash
