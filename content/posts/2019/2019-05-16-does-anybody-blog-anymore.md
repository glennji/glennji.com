---
title: "Does Anybody Blog Anymore?"
author: glennji
date: 2019-05-16T23:09:29+10:00
type: post
draft: false
toc: false
crosslink: "true"
images:
cover: "/wp-content/uploads/2019/01/bede-person-page.jpg"
tags:
  - blogging
  - glennji.com
---
A little after 11pm on a Thursday night and I've just finished[^1] the migration of my 'blog from Wordpress to Hugo, the static-site generator written in Go. It was actually reasonably easy considering I had quite a few posts and pages, not to mention the content types provided by various plugins.

[^1]: Well, finished enough that I don't mind hitting the "publish" button. Which of course isn't a button, but a commandline utility which pushes the generated pages up Google's Firebase for free hosting. Thanks, Googs!

(But it's 2019: does anybody blog anymore?)

Hugo has taken a little bit of getting used to, but I'm definitely liking it more as I use it. Content is authored in Markdown, for one thing, and the render templates use the same Go-Template language that I'm using for templating files in Helmfiles and charts, which makes it somewhat transferable. There are plenty of themes to start with and customize, and even a "deployment service" at [forestry.io](https://forestry.io/) if I decide I need a visual editor. Migration was via a Wordpress plugin, with the slight complication that I needed to spin up a local Wordpress to actually get the conversion plugin to run — but even that was pretty straightforward with `docker-compose`.

(Seriously, folks write Medium posts and share photos on Instagram, but 'blogging? I'm sure a hardcore base will tell you that blogging is still cool/profitable/engaging/worthwhile, but I'm not convinced. On the other hand, I've never _really_ had any readers, and this is mostly just a journal that I can go back to myself when I fancy it, so perhaps I shouldn't care either way?)

So if you're in fact not just me, and this website hasn't gone through another theme and/or platform transition ... have a look around! So of the neat features I've got at the moment are: "dark" and "light" mode; estimated reading time; some subtle animation; mostly responsive design; and then big one: automagic linking between named pages. (Read the About Me page to see how that works.)

There's plenty left to do, of course:

  * Implement pagination for the posts. (They go back to 2004!)
  * Add a search box. That could be fun.
  * Group the projects by category
  * Review and edit most of the notes — too many of them are copy-paste HTML rather than proper Markdown, and that makes me sad.
  * ...
  * Profit[^2]!

[^2]: It's an old joke but (I think) a good one, and especially relevant as we head towards whatever comes after an attention-economy; something to do with kudos/whuffie, perhaps? Or perhaps just using bees as currency? _Dance fo' yo' beez!_

I should definitely go to bed soon, as we're doing a production release tomorrow and I want to be alert in case something goes wrong; unlikely but not impossible. I'll take my shiny little laptop, blue-blocker glasses and finish this post from the comfort of our mattress on the floor, then switch on my lucid-dream-encouraging eyemask get some shut-eye — which of course are all things I should probably explain! Another post!
