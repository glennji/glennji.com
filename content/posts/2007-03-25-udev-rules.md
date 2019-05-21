---
title: udev rules
author: glennji
type: post
date: 2007-03-25T06:56:57+00:00
url: /?p=13088
categories:
  - Uncategorized

---
Not much here: Today I wrote a udev rule which creates a /dev/nokia770 device when the web tablet is plugged in.
  
On Ubuntu Edgy, create a file named 50-nokia770.rule in /etc/udev/rules.d with the following content:

> <pre>BUS=="usb",SYSFS{product}=="Nokia 770",KERNEL=="sd?1",NAME="nokia770"</pre>
