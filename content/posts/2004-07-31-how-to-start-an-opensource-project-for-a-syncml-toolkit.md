---
title: How to start an opensource project for a SyncML toolkit?
author: glennji
type: post
date: 2004-07-31T15:02:38+00:00
url: /?p=16711
featured_image: /wp-content/uploads/2018/01/57985675_6745142d90_b.jpg
categories:
  - Australia
  - Melbourne
  - Technology
tags:
  - chronofiles

---
<div class="post">
  <div class="post-body">
    <a href="https://web.archive.org/web/20041113231551/http://sync4j.sourceforge.net/">Sync4j </a>is an opensource Java implementation of the <a href="https://web.archive.org/web/20041113231551/http://www.openmobilealliance.org/tech/affiliates/syncml/syncmlindex.html">SyncML </a>protocol, which is used to synchronise mobile-user-interesting information such as contact details (addressbooks), bookmarks and calendars. SyncML is used by and supported in a number of different products, mostly synchronising clients for PDAs and mobile phones, and is notably lacking in some other high-profile products (Microosft Outlook and Exchange). This usually means that each particular PDA or phone has it&#8217;s own Outlook-like information store which may or may not synchronise with Outlook.<br /> I find this particularly annoying.<br /> As an actively mobile user (and aren&#8217;t we all?) I want to be able to take my information store with me wherever I go. I want to be able to have one addressbook, one calendar, and one set of bookmarks. If I upgrade my phone (or buy a new toy) I don&#8217;t want to have to screw around with syncs and merges and all that guff. I want access to my phonebook if I&#8217;m in an Internet café. I don&#8217;t want to re-enter information ever again.<br /> Most importantly, I don&#8217;t want to have to install fifteen clients and manually kick of the synchronisation process. Automated until conflicts require attention, I want to be able to set my sync and never look at it again.<br /> Nor am I alone in feeling this way. My friend Warrick and I, for example, have discussed this at length over a refreshing <a href="http://glennji.com/lexicon/beer/">ale</a> or three. And since nobody else is going to write this for us, we will have to do it &#8212; so let&#8217;s start a FOSS project!<br /> I&#8217;m predominantly a Java developer, hence the reference to Sync4j at the start of this entry, so at least the first iteration of the server and some client tools will be in Java. The way I see it, we will need to have a central server, a web-interface to that server, and some client-side synchronise plugins and/or services (actually a single service with its own plugins for each type of client-side datastore: Outlook, Evolution, <a href="https://web.archive.org/web/20041113231551/http://www.nat.org/dashboard/">Dashboard </a>and different phones and PDAs).<br /> The question, then, is where do we start? Looking at some other opensource projects, I&#8217;d probably say that the first thing to do is cut some code (after the initial design stages, which will happen at the aforementioned distribution center for refreshing ales), then register a Sourceforge project.<br /> Hey, if anyone out there stumbles across this and has some advice, leave a comment or just <a href="mailto:glenn@glennji.com">email</a> me? That&#8217;d be grand.
  </div>
</div>
