# JC One Lite #

  - [support on Netcodes](http://www.netcod.es/)
  - [Demo](http://demo-jconelite.netcod.es/)
  - [follow me @netcod_es](https://twitter.com/netcod_es)
  - [Donate with Paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8GGWRXZAAKPHW)


## Credits ##

### HTML5 Boilerplate

- Source : http://html5boilerplate.com/
- License URI: https://github.com/h5bp/html5-boilerplate/blob/master/LICENSE.md

### Fancybox

- Source: http://fancybox.net/
- License: Dual licensed under the MIT and GPL licenses:
    - http://www.opensource.org/licenses/mit-license.php
    - http://www.gnu.org/licenses/gpl.html

### Tabify

Tabify, Jan Jarfalk

- Source: http://unwrongest.com/projects/tabify/
- LIcense: MIT License
- License URI: http://www.opensource.org/licenses/mit-license.php

## Fonts

Lato, Copyright 2010 Łukasz Dziedzic

- Source: http://www.google.com/webfonts/specimen/Lato
- License: SIL Open Font License, 1.1
- License URI: http://scripts.sil.org/OFL

## Icons

Font Awesome by Dave Gandy

- Source: http://fortawesome.github.com/Font-Awesome/
- License: SIL Open Font License
- License URI: http://scripts.sil.org/OFL

## Social Logos Icons

Social Logos are custom-created, using Social Logos font.

- Source: http://www.dafont.com/social-logos.font
- License: Free


## Softwares ##

  - [Elementary OS](http://elementaryos.org/)
  - [Sublime Text 2](http://www.sublimetext.com/2)
  - [gedit](http://gedit.org/)
  - [Inkscape](http://inkscape.org/)
  - [The Gimp](http://www.gimp.org/)
  - [Poedit](http://www.poedit.net/)
  - ...

## Shortcode example ##

See [shortcode API](http://codex.wordpress.org/Shortcode_API) on wordpress.org for more explanations.

Code of [shortcode demo page](http://demo-jconelite.netcod.es/shortcode/) :


<h2>Layout</h2>

<h3>50%, 50%</h3>

[one_half]
[lorem]
[/one_half]
[one_half_last]
[lorem]
[/one_half_last]

<h3>33%, 66%</h3>

[one_third]
[lorem length="50"]
[/one_third]
[two_third_last]
[lorem length="100"]
[/two_third_last]

<h3>66%, 33%</h3>

[two_third]
[lorem length="100"]
[/two_third]
[one_third_last]
[lorem length="50"]
[/one_third_last]

<h3>33%, 33%, 33%</h3>

[one_third]
[lorem length="50"]
[/one_third]
[one_third]
[lorem length="50"]
[/one_third]
[one_third_last]
[lorem length="50"]
[/one_third_last]




<h2>Box</h2>

[box]
Default Box : [lorem length="100" html="1"][/box]

[box type="shadow"]
Shadow : [lorem length="100" html="1"][/box]

[box type="colored"]
Colored : [lorem length="100" html="1"][/box]

[box icon="download"]
Icon example 1 : [lorem length="50" html="1"][/box]

[box icon="envelope"]
Icon example 2 : [lorem length="50" html="1"][/box]

[box icon="search"]
Icon example 3 : [lorem length="50" html="1"][/box]

[hr type="space"]


<h2>Buttons</h2>

[button link="#" icon="download"]download[/button] [button link="#" icon="refresh"]refresh[/button] [button link="#" icon="search"]search[/button] [button link="#" icon="envelope"]envelope[/button] [button link="#" icon="tags"]tags[/button] [button link="#" icon="star"]star[/button] [button link="#" icon="warning-sign"]warning-sign[/button] [button link="#" icon="user"]user[/button] [button link="#" icon="twitter"]twitter[/button] [button link="#" icon="bookmark"]bookmark[/button] [button link="#" icon="rss"]rss[/button] [button link="#" icon="cloud"]cloud[/button] [button link="#" icon="ok"]ok[/button] [button link="#" icon="remove"]remove[/button] [button link="#" icon="bolt"]bolt[/button] [button link="#" icon="asterisk"]...[/button]

[hr type="space"]


<h2>Blockquotes</h2>

<h3>Simple</h3>


[quote type="simple"]
[lorem length="100" html="0"]

[/quote]


<h3>Pull</h3>



[quote type="pull"]

[lorem length="20" html="0"]

[/quote]

[lorem length="250" html="1"]


[hr type="space"]


<h2>Dropcap</h2>

[dropcap color="#2b96f1"]L[/dropcap]orem [lorem length="250"]
[hr type="space"]

[dropcap color="#ff0066"]L[/dropcap]orem [lorem length="250"]
[hr type="space"]

[dropcap color="#86c441"]L[/dropcap]orem [lorem length="250"]
[hr type="space"]


<!--
<h2>List</h2>

[one_third]
[list type="del"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third]
[one_third]
[list type="dot"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third]
[one_third_last]
[list type="check"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third_last]

[one_third]
[list type="star"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third]
[one_third]
[list type="hash"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third]
[one_third_last]
[list type="minus"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third_last]

[one_third]
[list type="plus"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third]
[one_third]
[list type="play"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third]
[one_third_last]
[list type="heart"]
<ul>
<li>item 1</li>
<li>item 2</li>
<li>...</li>
</ul>
[/list]
[/one_third_last]
[hr type="space"]
-->
