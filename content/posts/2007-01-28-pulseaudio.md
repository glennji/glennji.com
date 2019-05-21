---
title: PulseAudio
author: glennji
type: post
date: 2007-01-28T11:41:13+00:00
url: /?p=13062
featured_image: /wp-content/uploads/2007/01/pavucontrol.png
categories:
  - Technology
  - Uncategorized

---
On Friday night I watched a presentation about [PulseAudio][1], an open source and cross-platform sound server which could well finally bring decent desktop audio to GNU/Linux and other open systems.
  
With PulseAudio you get &#8220;Compiz for sound&#8221; &#8211; mixing and redirecting of multiple channels (finally!) with individual (and remembered) volumes, hot-plugging of sound devices and the ability to direct audio streams to different devices, including networked devices. Even better, it provides a number of plugins and compatibility libraries so existing software doesn&#8217;t need to be modified and recompiled.
  
This means you can do neat tricks like: giving the focused window (e.g. IM, Ekiga) more volume and lowering the background sounds (e.g. music and video players like Rhythmbox and Totem) &#8211; it&#8217;s nice to hear people when they call you, after all! You can also use the built-in USB microphone on your webcam only with the instant messenger application, or playback IM through your headphones but keep playing music through the main speakers. It also means you can combine one or more sound cards, perhaps using two stereo cards to create a 4.1 virtual output device.
  
It also does network audio, so I think I can hook it up in some tricky ways within my fledgling &#8220;smart home&#8221;: redirect music to various network devices, use MPD to control playback from the web-tablet, even stream audio from my workstation to the media centre (and have it keep in-sync with a corresponding video stream). Then, once I get a VOIP solution (hostname &#8220;affinity&#8221;) I can redirect calls to wherever we are in the house. And so on and so forth.

 [1]: http://pulseaudio.org/ "link"
