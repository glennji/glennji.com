---
title: Something useful for work downtime – Bazaar, Python
author: glennji
type: post
date: 2007-02-08T06:51:56+00:00
url: /?p=13082
featured_image: /wp-content/uploads/2007/02/bazaar-vcs.org_.png
categories:
  - Technology

---
So I&#8217;ve applied for a job with Canonical, that wonderful company that (in cooperation with the community of course) brings us Ubuntu.
  
In an effort to brush up my development skills I&#8217;ve now installed Bazaar and Python on my (Windows) workstation. In theory, this means I&#8217;ll be able to learn something useful during lunchtimes and the inevitable weekend conference calls. I&#8217;ve done a little Python before, but it&#8217;s been a while and I wasn&#8217;t exactly a guru even then. This must change!
  
Of course, nothing is easy when you&#8217;re stuck behind a couple of firewalls &#8212; Bazaar uses CURL (or libCurl) to do it&#8217;s remote connection/retrieval stuff, and I can&#8217;t seem to get it to go through the work proxy. Some Microsoft pish, no doubt. CURL should be able to do proxy authentication, but I can&#8217;t quite figure out how you do the SSPI stuff, i.e. pick up my authentication details from my Windows profile.
  
Oh well, there&#8217;s always the [NTLM Authentication Proxy Server][1]. I&#8217;ve used that to good effect before.
  
When I get some time I&#8217;m going to try using Bazaar for my home backup scheme too (at the moment it&#8217;s Subversion running on the Linkstation). I wonder if it would work as a generic synchronisation tool for the Nokia as well? Wow, the possibilities really are endless when you start thinking about them!

 [1]: http://ntlmaps.sourceforge.net/ "link"
