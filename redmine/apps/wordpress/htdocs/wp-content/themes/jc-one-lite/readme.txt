# JC One Lite #

  - [support on made by JC](http://www.madeby-jc.com/)
  - [Demo](http://one.madeby-jc.com/)
  - [follow me @bibichette](https://twitter.com/bibichette)
  - [Donate with Paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2NN6LYY4S9PTW)
  - [Donate with Flattr](http://flattr.com/thing/226027/Bibich-on-Flattr)


## Credits ##

  - [HTML5 Boilerplate](http://html5boilerplate.com/)
  - Icon set from [iconic](http://somerandomdude.com/work/iconic/)
  - [Fancybox](http://fancybox.net/)
  - [Tabify](http://unwrongest.com/projects/tabify/)
  - [Muli Web Font](http://www.google.com/webfonts/specimen/Muli)
  
  
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

Code of [shortcode demo page](http://one.madeby-jc.com/shortcode/) :


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

    <h2>Box</h2>

    [box]
    Default Box : [lorem length="100" html="1"][/box]

    [box type="shadow"]
    Shadow : [lorem length="100" html="1"][/box]

    [box type="colored"]
    Colored : [lorem length="100" html="1"][/box]

    [box icon="compass"]
    Icon example 1 : [lorem length="50" html="1"][/box]

    [box icon="mail"]
    Icon example 2 : [lorem length="50" html="1"][/box]

    [box icon="map-pin"]
    Icon example 3 : [lorem length="50" html="1"][/box]

    [hr type="space"]


    <h2>Buttons</h2>

    [button link="/contact" icon="mail"]Contact[/button] [button link="/" icon="at"]at[/button] [button link="/" icon="comment"]comment[/button] [button link="/" icon="compass"]compass[/button] [button link="/" icon="denied"]denied[/button] [button link="/" icon="download"]download[/button] [button link="/" icon="heart"]heart[/button] [button link="/" icon="home"]home[/button] [button link="/" icon="image"]image[/button] [button link="/" icon="info"]info[/button] [button link="/" icon="key"]key[/button] [button link="/" icon="lightbulb"]lightbulb[/button] [button link="/" icon="link"]link[/button] [button link="/" icon="map-pin"]map-pin[/button] [button link="/" icon="mail"]mail[/button] [button link="/" icon="question-mark"]question-mark[/button] [button link="/" icon="rss"]rss[/button] [button link="/" icon="tag"]tag[/button] [button link="/" icon="trash"]trash[/button] [button link="/" icon="x"]x[/button]

    [hr type="space"]


    <h2>Blockquotes</h2>

    <h3>Simple</h3>


    [quote type="simple"]
    [lorem length="100" html="0"]

    [/quote]



    <h3>Double</h3>


    [quote type="double"]

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


