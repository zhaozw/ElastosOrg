ÃžÂ•À€À€À€À€À€À€À€À€À€À€À€À€)À€À€À€Ã¼À€À€À€À€À€À€Â À€À€BÀ€À€À€Â¡À€À€!À€À€Ã¤À€À€Ã°À€À€À€À€9À€À€À€Ã·À€À€MÀ€À€À€1À€À€À€À€À€À€À€eÀ€À€À€ÂŒÀ€À€:À€À€À€Ã²À€À€À€À€-À€À€ÃžÀ€À€G	À€À€Â’À€À€&À€À€À€À€À€Â¹À€À€À€À€À€ÃŽÀ€À€*À€À€À€ÃŸÀ€À€1À€À€À€

À€À€&À€À€À€<
À€À€À€À€À€c
À€À€À€À€À€r
À€À€"À€À€À€Â‡
À€À€9À€À€À€Âª
À€À€IÀ€À€À€Ã¤
À€À€ÂÀ€À€À€.À€À€À€À€À€ÃŒÀ€À€À€À€À€ÃœÀ€À€À€À€À€ÃµÀ€À€À€À€À€À€À€À€À€À€À€À€À€À€À€'À€À€À€À€À€:À€À€Â¬À€À€FÀ€À€:À€À€À€Ã³À€À€4À€À€.À€À€Ã©À€À€cÀ€À€@À€À€À€MÀ€À€NÀ€À€À€ÂŽÀ€À€À€À€À€ÃÀ€À€XÀ€À€À€ÃªÀ€À€1À€À€À€CÀ€À€À€À€uÀ€À€Â˜À€À€}À€À€(À€À€À€À€À€À€À€?À€À€À€À€À€NÀ€À€1À€À€À€]À€À€À€À€À€ÂÀ€À€@À€À€À€Â¬À€À€À€À€À€Ã­À€À€À€À€À€Ã¼À€À€$À€À€À€À€À€=À€À€À€7À€À€NÀ€À€À€uÀ€À€Â•À€À€À€Ã„À€À€À€À€À€ZÀ€À€À€À€À€lÀ€À€À€À€À€ÂŠÀ€À€À€À€À€ÂšÀ€À€À€À€À€ÂªÀ€À€À€À€À€Â·À€À€À€À€À€Ã„À€À€À€À€À€	À€À€À€À€À€À€À€À€À€
À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€
À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€À€  -V, --version               output version information and exit
À€  -d, --domain=TEXTDOMAIN   retrieve translated message from TEXTDOMAIN
  -e                        enable expansion of some escape sequences
  -E                        (ignored for compatibility)
  -h, --help                display this help and exit
  -V, --version             display version information and exit
  [TEXTDOMAIN]              retrieve translated message from TEXTDOMAIN
  MSGID MSGID-PLURAL        translate MSGID (singular) / MSGID-PLURAL (plural)
  COUNT                     choose singular/plural form based on this value
À€  -d, --domain=TEXTDOMAIN   retrieve translated messages from TEXTDOMAIN
  -e                        enable expansion of some escape sequences
  -E                        (ignored for compatibility)
  -h, --help                display this help and exit
  -n                        suppress trailing newline
  -V, --version             display version information and exit
  [TEXTDOMAIN] MSGID        retrieve translated message corresponding
                            to MSGID from TEXTDOMAIN
À€  -h, --help                  display this help and exit
À€  -v, --variables             output the variables occurring in SHELL-FORMAT
À€Bruno HaibleÀ€Display native language translation of a textual message whose grammatical
form depends on a number.
À€Display native language translation of a textual message.
À€If the TEXTDOMAIN parameter is not given, the domain is determined from the
environment variable TEXTDOMAIN.  If the message catalog is not found in the
regular directory, another location can be specified with the environment
variable TEXTDOMAINDIR.
Standard search directory: %s
À€If the TEXTDOMAIN parameter is not given, the domain is determined from the
environment variable TEXTDOMAIN.  If the message catalog is not found in the
regular directory, another location can be specified with the environment
variable TEXTDOMAINDIR.
When used with the -s option the program behaves like the `echo' command.
But it does not simply copy its arguments to stdout.  Instead those messages
found in the selected catalog are translated.
Standard search directory: %s
À€In normal operation mode, standard input is copied to standard output,
with references to environment variables of the form $VARIABLE or ${VARIABLE}
being replaced with the corresponding values.  If a SHELL-FORMAT is given,
only those environment variables that are referenced in SHELL-FORMAT are
substituted; otherwise all environment variables references occurring in
standard input are substituted.
À€Informative output:
À€Operation mode:
À€Report bugs to <bug-gnu-gettext@gnu.org>.
À€Substitutes the values of environment variables.
À€Try `%s --help' for more information.
À€Ulrich DrepperÀ€Unknown system errorÀ€Usage: %s [OPTION] [SHELL-FORMAT]
À€Usage: %s [OPTION] [TEXTDOMAIN] MSGID MSGID-PLURAL COUNT
À€Usage: %s [OPTION] [[TEXTDOMAIN] MSGID]
or:    %s [OPTION] -s [MSGID]...
À€When --variables is used, standard input is ignored, and the output consists
of the environment variables that are referenced in SHELL-FORMAT, one per line.
À€Written by %s.
À€error while reading "%s"À€memory exhaustedÀ€missing argumentsÀ€standard inputÀ€too many argumentsÀ€write errorÀ€Project-Id-Version: gettext-runtime 0.15-pre5
Report-Msgid-Bugs-To: bug-gnu-gettext@gnu.org
POT-Creation-Date: 2010-06-04 01:57+0200
PO-Revision-Date: 2006-07-05 17:16+0800
Last-Translator: Abel Cheung <abelcheung@gmail.com>
Language-Team: Chinese (Hong Kong) <community@linuxhall.org>
Language: zh_HK
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=1; plural=0;
À€  -V, --version               é¡¯ç¤ºç‰ˆæœ¬è³‡è¨Šä¸¦çµæŸ
À€  -d, --domain=æ–‡å­—é ˜åŸŸ     ç”±<æ–‡å­—é ˜åŸŸ>è®€å–ç¿»è­¯å¾Œçš„è¨Šæ¯ã€‚
  -e                        å…è¨±å±•é–‹æŸé¡ž escape sequence
  -E                        (ç‚ºäº†ç›¸å®¹æ€§å­˜åœ¨çš„é¸é …ï¼Œä¸æœƒé€ æˆä»»ä½•å½±éŸ¿)
  -h, --help                é¡¯ç¤ºé€™æ®µèªªæ˜Žæ–‡å­—ä¸¦çµæŸ
  -V, --version             é¡¯ç¤ºç‰ˆæœ¬è³‡è¨Šä¸¦çµæŸ
  [æ–‡å­—é ˜åŸŸ]                ç”±<æ–‡å­—é ˜åŸŸ>è®€å–ç¿»è­¯è¨Šæ¯
  MSGID è¤‡æ•¸MSGID           ç¿»è­¯ MSGID (å–®æ•¸) / è¤‡æ•¸MSGID (è¤‡æ•¸)
  æ•¸å­—                      æ ¹æ“š<æ•¸å­—>ä¾†é¸æ“‡å–®æ•¸æˆ–è¤‡æ•¸åž‹æ…‹
À€  -d, --domain=æ–‡å­—é ˜åŸŸ     ç”±<æ–‡å­—é ˜åŸŸ>è®€å–ç¿»è­¯å¾Œçš„è¨Šæ¯
  -e                        å…è¨±å±•é–‹æŸé¡ž escape sequence
  -E                        (ç‚ºäº†ç›¸å®¹æ€§å­˜åœ¨çš„é¸é …ï¼Œä¸æœƒé€ æˆä»»ä½•å½±éŸ¿)
  -h, --help                é¡¯ç¤ºé€™æ®µèªªæ˜Žæ–‡å­—ä¸¦çµæŸ
  -n                        åŽ»é™¤æœ«ç«¯çš„ newline å­—å…ƒ
  -V, --version             é¡¯ç¤ºç‰ˆæœ¬è³‡è¨Šä¸¦çµæŸ
  [æ–‡å­—é ˜åŸŸ] MSGID          ç”±<æ–‡å­—é ˜åŸŸ>è®€å–ç›¸æ‡‰æ–¼ MSGID çš„ç¿»è­¯è¨Šæ¯
À€  -h, --help                  é¡¯ç¤ºé€™æ®µèªªæ˜Žæ–‡å­—ä¸¦çµæŸ
À€  -v, --variables             é¡¯ç¤º SHELL-FORMAT ä¸­å‡ºç¾éŽçš„ç’°å¢ƒè®Šæ•¸
À€Bruno HaibleÀ€é¡¯ç¤ºæŸæ–‡å­—è¨Šæ¯çš„æœ¬åœ°èªžè¨€ç¿»è­¯ï¼Œè€Œç¿»è­¯çš„æ–‡æ³•å—å–®æ•¸è¤‡æ•¸å½±éŸ¿ã€‚
À€é¡¯ç¤ºæŸå€‹æ–‡å­—è¨Šæ¯çš„æœ¬åœ°èªžè¨€ç¿»è­¯ã€‚
À€å‡å¦‚æ²’æœ‰æŒ‡å®š<æ–‡å­—é ˜åŸŸ>åƒæ•¸ï¼Œç¨‹å¼æœƒæ ¹æ“š TEXTDOMAIN ç’°å¢ƒè®Šæ•¸ä¾†è¨­å®šæ–‡å­—é ˜åŸŸã€‚
å‡å¦‚è¨Šæ¯è³‡æ–™æª”ä¸åœ¨æ­£å¸¸çš„ç›®éŒ„ä¸‹ï¼Œå¯ä»¥ç”¨ç’°å¢ƒè®Šæ•¸ TEXTDOMAINDIR æŒ‡å®šè¨Šæ¯è³‡æ–™æª”
çš„æ‰€åœ¨çš„ä½ç½®ã€‚
æ¨™æº–çš„æœå°‹ç›®éŒ„ç‚ºï¼š%s
À€å‡å¦‚æ²’æœ‰æŒ‡å®š<æ–‡å­—é ˜åŸŸ>åƒæ•¸ï¼Œç¨‹å¼æœƒæ ¹æ“š TEXTDOMAIN ç’°å¢ƒè®Šæ•¸ä¾†è¨­å®š<æ–‡å­—é ˜åŸŸ>ã€‚
å‡å¦‚è¨Šæ¯è³‡æ–™æª”ä¸åœ¨æ­£å¸¸çš„ç›®éŒ„ä¸‹ï¼Œå¯ä»¥ç”¨ç’°å¢ƒè®Šæ•¸ TEXTDOMAINDIR æŒ‡å®šè¨Šæ¯è³‡æ–™æª”
çš„æ‰€åœ¨çš„ä½ç½®ã€‚
ä½¿ç”¨é¸é …ã€Œ-sã€æ™‚æœ¬ç¨‹å¼å’Œã€Œechoã€æŒ‡ä»¤é¡žä¼¼ã€‚ä½†ä¸æ˜¯ç›´æŽ¥é¡¯ç¤ºåƒæ•¸ï¼Œ
è€Œæ˜¯å°‹æ‰¾è¨Šæ¯è³‡æ–™æª”è£ç¿»è­¯éŽçš„è¨Šæ¯ã€‚
æ¨™æº–çš„æœå°‹ç›®éŒ„ç‚ºï¼š%s
À€åœ¨æ­£å¸¸æ¨¡å¼ä¸‹ï¼Œæœƒå¾žæ¨™æº–è¼¸å…¥è®€å–è³‡æ–™ï¼Œä¸¦å°‡å…§å®¹ä¸­çš„ç’°å¢ƒè®Šæ•¸ (åƒ $VARIABLE æˆ–
${VARIABLE}) è½‰æ›ç‚ºè©²è®Šæ•¸çš„å€¼ï¼Œä¸¦åœ¨æ¨™æº–è¼¸å‡ºé¡¯ç¤ºçµæžœã€‚å¦‚æžœæŒ‡å®šäº† SHELL-FORMATï¼Œ
å‰‡åªæ›¿æ› SHELL-FORMAT ä¸­æŒ‡å®šçš„è®Šæ•¸ï¼Œå¦å‰‡æ‰€æœ‰ç’°å¢ƒè®Šæ•¸éƒ½æœƒæ›¿æ›ã€‚
À€é¡¯ç¤ºè³‡è¨Š:
À€æ“ä½œæ¨¡å¼:
À€è«‹å‘ <bug-gnu-gettext@gnu.org> åŒ¯å ±éŒ¯èª¤ã€‚
À€æ›¿æ›ç’°å¢ƒè®Šæ•¸çš„å€¼ã€‚
À€å¦‚æžœæƒ³ç²å–é¡å¤–è³‡è¨Šï¼Œè«‹å˜—è©¦åŸ·è¡Œã€Œ%s --helpã€ã€‚
À€Ulrich DrepperÀ€ä¸æ˜Žçš„ç³»çµ±éŒ¯èª¤À€ç”¨æ³•ï¼š%s [é¸é …] [SHELL-FORMAT]
À€ç”¨æ³•ï¼š%s [é¸é …] [æ–‡å­—é ˜åŸŸ] MSGID è¤‡æ•¸MSGID æ•¸å­—
À€ç”¨æ³•ï¼š%s [é¸é …] [[æ–‡å­—é ˜åŸŸ] MSGID]
æˆ–ï¼š  %s [é¸é …] -s [MSGID]...
À€å¦‚æžœä½¿ç”¨ --variables é¸é …ï¼Œå°‡ä¸æœƒç†æœƒè¼¸å…¥è³‡æ–™ï¼Œåªæœƒè®€å– SHELL-FORMAT ä¸­çš„
ç’°å¢ƒè®Šæ•¸ä¸¦é¡¯ç¤ºè®Šæ•¸åç¨±ï¼Œæ¯å€‹ä¸€è¡Œã€‚
À€ç”± %s ç·¨å¯«ã€‚
À€è®€å–ã€Œ%sã€æ™‚å‡ºç¾éŒ¯èª¤À€è¨˜æ†¶é«”è€—ç›¡À€ç¼ºå°‘äº†åƒæ•¸À€æ¨™æº–è¼¸å…¥À€åƒæ•¸éŽå¤šÀ€å¯«å…¥æ™‚ç™¼ç”ŸéŒ¯èª¤À€