---
title: On-demand Quake-like console with iTerm and Alfred
author: glennji
type: post
date: 2014-12-11T02:09:05+00:00
url: /?p=13290
featured_image: /wp-content/uploads/2014/12/xlwNj-1568x882.jpg
fg_temp_metadata:
  - 15106
slide_template:
  - default
sbg_selected_sidebar_replacement:
  - 'a:1:{i:0;s:1:"0";}'
sbg_selected_sidebar:
  - 'a:1:{i:0;s:1:"0";}'
price_table:
  - 'a:0:{}'
rd_slider_type:
  - no
rd_slider_position:
  - under
rd_top_bar:
  - no
rd_header_transparent:
  - no
rd_title:
  - yes
rd_bc:
  - yes
rd_show_navigation:
  - yes
rd_show_slider:
  - yes
rd_sidebar:
  - right
rd_share_buttons:
  - yes
rd_author_bio:
  - yes
rd_related_post:
  - yes
fg_perm_metadata:
  - 15106
categories:
  - Uncategorized

---
This is fun: using [Alfred][1] to show or hide a Quake-like [iTerm][2] console, even if iTerm isn&#8217;t running.

# <!--more-->iTerm

Ever since using [Tilda][3] on GNOME, I&#8217;ve loved having a terminal within easy reach, preferably a key-combo away ala the original Quake console. Once I started using a Mac, I missed it greatly &#8212; but after a little messing around it&#8217;s back!
  
First, you need [iTerm][4], a full-featured (and free!) Terminal replacement. Set this up the way you like and save it as the default profile AND default window arrangement:
  
[<img class="aligncenter wp-image-13293 size-medium" src="/wp-content/uploads/2014/12/hotkey-profile-300x183.png" alt="hotkey-profile" width="300" height="183" />][5]
  
I prefer &#8220;Style: Top of Screen&#8221; and some transparency, and I also set iTerm to quit on close on the General tab, because I&#8217;m going to use Alfred to do both the initial launch and the show/hide toggle:
  
[<img class="aligncenter size-medium wp-image-13294" src="/wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.30.35-pm-300x185.png" alt="Screen Shot 2014-12-11 at 12.30.35 pm" width="300" height="185" />][6]
  
Once it looks the way you want, save the window arrangement and set it as default:
  
[<img class="aligncenter size-medium wp-image-13295" src="/wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.36.48-pm-300x191.png" alt="Screen Shot 2014-12-11 at 12.36.48 pm" width="300" height="191" srcset="/wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.36.48-pm-300x191.png 300w, /wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.36.48-pm-768x490.png 768w, /wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.36.48-pm.png 975w" sizes="(max-width: 300px) 100vw, 300px" />][7]

# Alfred

Cool, so now you should have everything setup so that, when launched, iTerm looks the way you want it to. Launch and quit a few times to make sure, then add a simple Alfred workflow:
  
[<img class="aligncenter size-medium wp-image-13296" src="/wp-content/uploads/2014/12/Screen-Shot-2014-12-10-at-9.48.49-pm-300x109.png" alt="Screen Shot 2014-12-10 at 9.48.49 pm" width="300" height="109" />][8]
  
If you&#8217;re not familiar with Alfred, you can use the little &#8220;plus&#8221; icon at the bottom left-hand pane on the Workflows tab to see examples and templates. I created the above with &#8220;Templates > Files and Apps > Launch file group from hotkey&#8221;.
  
⌘⌥\` (cmd-alt-tilda, see?) seems a good one-hander, and close enough to force-quit (⌘⌥ESC) to seem native, but the real trick is in the iTerm action &#8212; &#8220;Toggle visibility for apps&#8221;:
  
[<img class="aligncenter size-medium wp-image-13297" src="/wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.56.38-pm-300x175.png" alt="Screen Shot 2014-12-11 at 12.56.38 pm" width="300" height="175" srcset="/wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.56.38-pm-300x175.png 300w, /wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.56.38-pm.png 597w" sizes="(max-width: 300px) 100vw, 300px" />][9]
  
At the end of all this, and with no Applescript in sight, it means **_a single key combination for bringing up a Quake-like iTerm_**_ **console **_that works no matter what_:_

  * &#8230; if iTerm isn&#8217;t running, Alfred launches it
  * &#8230; if iTerm is running but hidden, Alfred shows it
  * &#8230; if iTerm is running but behind another window, Alfred brings it to the foreground

 [1]: http://www.alfredapp.com/ "Alfred project page"
 [2]: http://iterm2.com/ "iTerm2 project page"
 [3]: http://tilda.sourceforge.net/tildadoc.php "sourceforge.net"
 [4]: http://iterm2.com/ "iterm2 project page"
 [5]: /wp-content/uploads/2014/12/hotkey-profile.png
 [6]: /wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.30.35-pm.png
 [7]: /wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.36.48-pm.png
 [8]: /wp-content/uploads/2014/12/Screen-Shot-2014-12-10-at-9.48.49-pm.png
 [9]: /wp-content/uploads/2014/12/Screen-Shot-2014-12-11-at-12.56.38-pm.png
