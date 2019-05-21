---
title: "About Me"
author: glennji
date: 2019-03-05T15:54:31+11:00
draft: false
crosslink: "true"
---

{{< figure src="/images/little-me.png" class="floatright" >}}_This is the personal website of **Glenn J. Mason**, a technologist and unrepentant geek living and working in Sydney, Australia with his wife[^1] and son[^2]. He also rarely speaks about himself in the third-person..._

[^1]: "common law" wife; for some reason, we've never gotten around to actually "tieing the knot", although it's arguably the best excuse for bringing a bunch of old friends from around the world together for a party.

[^2]: Age seven and with greater mastery of a PS4 controller than I've ever seen, let alone the Wii U, iPad, phone ... how many platforms can you get Minecraft on these days? God, I love that kid.

Somehow you've stumbled onto my (oft-ignored) website, and then have clicked on the "about" link to find out more about me – so thanks!! You probably won't find much of interest here I'm afraid, but you're welcome to have a poke around anyway. Let's see, what can I tell you about myself?

> _A highly-motivated and intelligent computer scientist with a keen interest in all aspects of information technology and more than a decade of commercial experience in developing software, providing 24×7 support, establishing standards and building infrastructure. I like “big data” systems, resilient cloud infrastructure (AWS, GCP), machine learning and AI, and “speak” a couple of programming languages. I’m a Linux/UNIX_ otaku, _an open-source advocate (and occasional contributor) and a generally geeky kind of guy._

I've a long list of hobbies, including science-fiction novels, motorcycles, camping, software engineering, devops, craft beer & brewing, robotics/electronics and boardgames. I've travelled a few places, although there are plenty more things I'd like to try.

***

## Spoilers

{{< figure src="/images/timeline.png" caption="Figure 1: One possible timeline">}}

***

## About this website

I've had a 'blog for a while (check the archives), and have over the years migrated it from one platform to another: Blogspot to Drupal to Wordpress and beyond. These days, it's a static site built by Hugo, using a hacked up version of the hello-friend-ng theme that I've never gotten around to forking. If you haven't checked out Hugo, or static site generators in general (I hear Jekyll is good too) then you definitely should: setting this one up was pretty easy and is currently hosted for free on Firebase. Thanks, Googs!

In fact, there's really only one neat feature that I'm exploring, which is automagic crosslinks between posts and "terms", another content type I created to try to get some notes in order. In short, any mention of the title of a piece of content will become a link to that content. To do this, add the content to the "crosslink" index via frontmatter:
```
---
title: "About"
author: glennji
date: 2019-03-05T15:54:31+11:00
draft: false
crosslink: "true"
categories:
  - whoami
---
```

... and use some derivation of the following in the layout for the content:

```
<div class="post-content">
  {{ if .Params.crosslink }}
    {{ $related := .Site.RegularPages.RelatedIndices . "crosslink" }}
    {{ .Scratch.Set "content" .Content }}
    {{ with $related }}
      {{ range . }}
        {{ $title := .Params.title }}
        {{ $permalink := .Permalink }}
        {{ $content := replaceRE (printf "(.*)\\b(%s|%s|%s)\\b" (lower $title) $title (title $title)) (printf "$1 [$2](%s)" $permalink | markdownify ) ($.Page.Scratch.Get "content") }}
        {{ $.Page.Scratch.Set "content" $content }}
      {{ end }}
    {{ end }}
    {{ .Scratch.Get "content" | safeHTML }}
  {{ else }}
    {{ .Content }}
  {{ end }}
</div>
```

## Geek Selfie
{{% figure src="/images/bg3.jpg" caption="Figure 2: Yep, that's what we call 'Movember'" %}}
