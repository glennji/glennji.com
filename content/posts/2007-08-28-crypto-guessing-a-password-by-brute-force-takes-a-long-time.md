---
title: "Crypto: Guessing a password by brute-force takes a long time!"
author: glennji
type: post
date: 2007-08-28T01:28:41+00:00
url: /?p=13094
categories:
  - Uncategorized

---
This weekend was a long weekend, and the (annual?) London FrightFest &#8211; lots of new and indy horror movies showing in Leicester Square over four days.  It would&#8217;ve been fun but as we&#8217;re being cheap whilst on a single income we opted to stay home and watch horror movies instead.
  
I thought we could probably download something, so we gave it a go.  Unfortunately one of the movies I downloaded &#8211; not even a horror, not even likely to be very good &#8211; was archived in an encrypted RAR file.  Worse, whoever packaged it up wanted me to call a phone in the US to hear the password (at $100/min, no doubt).
  
So I&#8217;m not going to do that, of course.  Instead I wrote a little Java application to brute-force the RAR password.  That&#8217;ll show &#8217;em, provided it ever finishes.
  
Of course, it takes a long time.  Say I have 26 letters, in upper and lower case, 10 digits and 10 symbols &#8211; that&#8217;s 26 + 26 + 10 + 10 = 72 characters in the character pool, right?
  
If it&#8217;s a 6 character password, allowing repetition, that&#8217;s
  
72<sup>6</sup> = 139,314,069,504 possibilities.
  
I&#8217;ve scripted it to try all 6, 7 and 8 character passwords at the moment.  Be cool if it works, however.
