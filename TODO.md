# TO DO
## Must Have
- [/] Get the timeline of all your suscriptions
- [/] Cache the last twts from your followers
- [ ] Sort all the 'timeline' by date
- [ ] Write to your .txt file
- [ ] Get all the twits belonging to a thread, and make the tree
- [ ] Generate threads with replies
- [ ] Sort your follower list by last twt (and show that in the list)
- [ ] Add a button to reply to a twt
- [ ] Decide if we allow Selfsigned certificates
## Should have
- [X] Login with TOTP
- [ ] Fix Login redirection with Post-Redirect-Get
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
- [X] Read a twtxt file from an URL
- [X] Read meta from .txt file (Test if it works for edge cases)
- [X] Get twt Hash

Errors to fix
Loading URL: https://eapl.mx/twtxt.txt
Updating: https://birdsite.slashdev.space/users/mirkosertic
Updating: https://birdsite.slashdev.space/users/ClownWorld_
Updating: https://gameliberty.club/users/babylonbee
Updating: https://hachyderm.io/users/robpike
Updating: https://mastodon.cloud/users/mkheck
Updating: https://mastodon.online/users/starbuxman
Updating: https://mastodon.social/users/VaughnVernon
Updating: https://mastodon.social/users/rands
Updating: https://noagendasocial.com/users/Syberia
Updating: https://nondeterministic.computer/users/martin
Updating: https://pone.social/users/soapone
Updating: https://toot.thoughtworks.com/users/mfowler
Updating: https://feeds.twtxt.net/Aaron_Parecki/twtxt.txt
Updating: https://social.kyoko-project.wer.ee/user/akoizumi/twtxt.txt
Warning: file_get_contents(): Peer certificate CN=`*.j6.ee' did not match expected CN=`social.kyoko-project.wer.ee' in C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\phpub2twtxt\functions.php on line 145

Warning: file_get_contents(): Failed to enable crypto in C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\phpub2twtxt\functions.php on line 145

Warning: file_get_contents(https://social.kyoko-project.wer.ee/user/akoizumi/twtxt.txt): failed to open stream: operation failed in C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\phpub2twtxt\functions.php on line 145
Updating: http://a.9srv.net/tw.txt
Warning: file_get_contents(http://a.9srv.net/tw.txt): failed to open stream: HTTP request failed! in C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\phpub2twtxt\functions.php on line 145
Updating: https://twtxt.net/user/biblia/twtxt.txtUpdating: https://brainshit.fr/twtxt.txt
Warning: file_get_contents(https://brainshit.fr/twtxt.txt): failed to open stream: HTTP request failed! HTTP/1.1 404 Not Found in C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\phpub2twtxt\functions.php on line 145
Updating: https://twtxt.net/user/brasshopper/twtxt.txt
Updating: https://yarn.zn80.net/user/carsten/twtxt.txt
Fatal error: Maximum execution time of 30 seconds exceeded in C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www\phpub2twtxt\functions.php on line 145