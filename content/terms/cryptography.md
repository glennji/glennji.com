---
title: "Cryptography"
author: glennji
date: 2019-05-13T14:19:28+10:00
draft: false
type: term
crosslink: "true"
categories:
  - Technology
---
From Greek κρυπτός, (kryptos) "hidden, secret"; and γράφειν (graphein) "writing" (or cryptology, -λογία, (-logia), "the study", cryptography is a branch of information processing which studies techniques for secure communications over an untrusted channel, and of "cracking" such communications.

Modern cryptography includes: encryption and decryption; authentication &amp; authorisation; data integrity and confidentiality.

"Classic" cryptography almost entirely revolved around encryption and decryption: creating cyphers (paired algorithms, usually with a key) to turn plaintext into cyphertext; some early work also involved hiding the message (steganography).
Modern cryptology encompasses cryptography (creating encryption techniques) and cryptanalysis (breaking encrypted messages without the key).

<h3>Attacks On The 1-time Pad</h3>
c<sub>1</sub> = m<sub>1</sub> ⊕ PRG(k)
c<sub>2</sub> = m<sub>2</sub> ⊕ PRG(k)
then
c<sub>1</sub> ⊕ c<sub>2</sub> = m<sub>1</sub> ⊕ m<sub>2</sub>
and there is enough redundancy in English (and ASCII) to recover m1, m2 (and therefore PRG(k)).
Venona Project
MS-PPTP
WEP
&nbsp;
<div></div>
<div></div>
