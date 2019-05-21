---
title: Clock Radio
tags:
  - arduino
  - raspberrypi
created: '2019-03-19T01:26:00.543Z'
modified: '2019-04-12T05:09:32.805Z'
attachments: [ge-clockradio.jpg]
projectType: software
toc: true
cover: "/wp-content/uploads/ge-clockradio.jpg"
---

Idea: Replace the guts of the (US-voltage) vintage clock radio I have with an Arduino (driving the display) and a radio circuit.

# Hacks/Ideas
```
Self-dimming clock display e.g. get brighter gradually towards the alarm time
Input: existing controls
Gut a portable DAB radio (~$50, ebay) and integrate
Add a smart bulb (lumen, phillips hue) to my bedside light and make an easy “dawn light”
Voice activation/control(!!) http://blog.oscarliang.net/raspberry-pi-voice-recognition-works-like-siri/
Use a, old mouse for “tuner” dial (scroll wheel of ball mouse)
e-ink display
temperature on 7-segment display
notification RGB LED?
http://makezine.com/projects/raspberry-pi-radio-time-machine/
```

# Devices
## Raspberry Pi (A)

One powerful feature of the Raspberry Pi is the row of GPIO (general purpose input/output) pins along the edge of the board, next to the yellow video out socket.

These pins are a physical interface between the Pi and the outside world. At the simplest level, you can think of them as switches that you can turn on or off (input) or that the Pi can turn on or off (output). Seventeen of the 26 pins are GPIO pins; the others are power or ground pins.

## Arduino Uno

The Uno is a microcontroller board based on the ATmega328P. It has 14 digital input/output pins (of which 6 can be used as PWM outputs), 6 analog inputs, a 16 MHz quartz crystal, a USB connection, a power jack, an ICSP header and a reset button.

# Inputs
```
4x small buttons
1x snooze button
1x 2 position slider (“AM/FM”)
1x 4 position slider (“On/off/alarm/set”)
1x volume control
1x tuning dial
< 11x “switch” inputs ( and 2x analogue) — RPi
+ the 7-segment display clock — Arduino
Outputs:
1x speaker
1x LED segment display (how to drive?)
Idea: Use a Raspberry Pi + Arduino UNO.
Connecting RPI and Arduino
I2C: http://blog.oscarliang.net/raspberry-pi-arduino-connected-i2c/
USB: http://blog.oscarliang.net/connect-raspberry-pi-and-arduino-usb-cable/
Serial GPIO: http://blog.oscarliang.net/raspberry-pi-and-arduino-connected-serial-gpio/
```
