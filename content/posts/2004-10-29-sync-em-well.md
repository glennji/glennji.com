---
title: Sync em well
author: glennji
type: post
date: 2004-10-29T14:21:11+00:00
url: /?p=16674
featured_image: /wp-content/uploads/2018/01/57985675_6745142d90_b.jpg
categories:
  - Australia
  - Melbourne
  - Technology
tags:
  - chronofiles

---
<p class="post-title">
  I just had a thought about the syncml server.
</p>

<div class="post-body">
  First, we set up the (open source) syncml servlet somewhere. We might need to do some work on the user accounts and authentication, and we&#8217;ll also want a nice web interface for registering for the service. Once done, any software that can syncronise with a syncml server will be usable (although I don&#8217;t know many that are), if only in a limited fashion.<br /> Then we write a little Java app that can do some common import/export tasks for the apps that aren&#8217;t syncml ready (like reading bookmarks, or Outlook addressbooks) and put it on the site with Java Webstart. You click a link, webstart downloads the app and runs it. The app asks for filesystem permissions (it&#8217;s running on the client) then when you grant them it does the import and export.<br /> Rather a broad overview of the work involved, but nevertheless fairly straightforward.<br /> We could even allow it to do &#8220;temporary home&#8221; importing. Say you&#8217;re on a machine temporarily, and you want your calendar back. You could fire up the app (via Webstart) then click &#8220;Import my information temporarily&#8221; (or some such), and it would backup the current files and replace them with yours from the server. You fire up your calendar app, and voila, it&#8217;s yours.<br /> When you&#8217;re finished, it can synchronise any changes you did during the session, them restore the backup (so your calendar is back on the server, not on the PC).<br /> Just a thought. Comment below, or via email.
</div>
