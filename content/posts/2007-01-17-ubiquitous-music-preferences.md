---
title: Ubiquitous music preferences
author: glennji
type: post
date: 2007-01-17T08:26:36+00:00
url: /?p=13044
featured_image: /wp-content/uploads/2018/01/57981160_6458b4b9df_b.jpg
categories:
  - Technology

---
I&#8217;ve been using the very good AMSN instant messenger client to chat to my family and friends overseas, mostly because it&#8217;s the only open source client I could find that lets me use the webcam sharing features. This seems to work quite well, even on my aging Toshiba laptop and over an 802.11b wifi network &#8212; I can both send and receive webcam streams from people using \*huck\* Microsoft MSN Messenger, although there were some problems with being able to send a video stream from one of the newer messengers (my darling sister couldn&#8217;t find the option to switch it on, which makes me wonder why there are so many different &#8220;official&#8221; versions of MSN Messenger, when none seem to offer anything more than the cross-platform AMSN can. Go figure, MS is FUDding themselves).
  
We&#8217;ve also been using Skype for audio transmission, since that&#8217;s cross platform (although not open source, which irks me because I&#8217;d like to have one application for each problem-domain and run the same solution on my GNU/Linux laptop, Nokia 770 web-tablet, my sister&#8217;s Apple Mac Mini and of course the ubiquitous (and annoying) Windows platform). Skype works okay, and having two icons in my system notification area is better than five or more. I was starting up Ekiga too, but I only know 1 person with an Ekiga account and they never seem to be online!
  
<img style="float: left; margin-right: 20px;" src="http://glennji.com/files/images/LastFmPlayer.jpg" alt="Last.fm player" />
  
Anyway, I&#8217;ve also been thinking about information ubiquity (there&#8217;s that word again) and it&#8217;s possible benefits and potential drawbacks. For example, I&#8217;ve been submitting my music choices to last.fm from various places &#8212; there are Audioscrobbler/Last.fm plugins for Songbird, Rhythmbox, XMMS/Streamtuner and XBOX media center &#8212; and today I tuned into my &#8220;neighbour radio&#8221; station with the Last.fm client for Linux for the first time &#8230; and it knows what I like! I keep getting tune after tune of great stuff, most of which I&#8217;ve not heard before.
  
Even better, the Last.fm player submits those back into my &#8220;listened list&#8221;, so as long as I remember to skip the tracks I don&#8217;t like it will have a bigger and bigger pool of &#8220;audio data points&#8221; from which to suggest music to listen to. That means I get exposed to more and more artists, which can only be a good thing. (Combined with Songbird&#8217;s music blog subscriptions I&#8217;m finding all sorts of new music that I like.)
  
There are a few things missing, however, to make it a perfect system.
  
One has already been solved, as I found out today: what about the music I listen to &#8220;unwired&#8221; on my MP3 player? Luckily someone has thought of this and written podscrobbler, a Python-based app that submits tracks listened to on an iPod whilst away from a PC or web-tablet. This is great, since Dee is letting me use her iPod Nano! I&#8217;ll be trying out podscrobbler very soon.
  
Another is knowing what tracks I&#8217;m listening to. When I&#8217;m at home I&#8217;ve got my wireless headphones, and wander away from the PC all the time (to read, surf the web on the Nokia, whatever). This means that if a track comes on that I really like I have to walk back into the computer room to see who it is; it&#8217;s the same if I hate a track and want to change it.
  
So what I really need here is some kind of web-interface that can tell me what track is currently playing, and give some kind of control over it. It would also be nice if it let me control different audio sources: the Last.fm player, Streamtuner, Rhythmbox and the XBOX media center, if possible. This way I would hear a song, then whip out the web-tablet and have full details and control. The lazy music-lovers dream?
  
I think before I get that I&#8217;m going to try to hack Last.fm &#8220;listening to&#8221; information into an AMSN plugin. There is one that picks up this info from Rhythmbox and XMMS already, so it should just be a case of adding another handler that can connect to Last.fm and parse an XML file. TclXML will come in handy for this, I imagine, but I&#8217;m never written TCL so we&#8217;ll see.
