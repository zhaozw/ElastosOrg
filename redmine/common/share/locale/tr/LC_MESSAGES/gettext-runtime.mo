ÃÂ•À€À€À€À€À€À€À€À€À€À€À€À€)À€À€À€Ã¼À€À€À€À€À€À€Â À€À€BÀ€À€À€Â¡À€À€!À€À€Ã¤À€À€Ã°À€À€À€À€9À€À€À€Ã·À€À€MÀ€À€À€1À€À€À€À€À€À€À€eÀ€À€À€ÂŒÀ€À€:À€À€À€Ã²À€À€À€À€-À€À€ÃÀ€À€G	À€À€Â’À€À€&À€À€À€À€À€Â¹À€À€À€À€À€ÃÀ€À€*À€À€À€ÃŸÀ€À€1À€À€À€

À€À€&À€À€À€<
À€À€À€À€À€c
À€À€À€À€À€r
À€À€"À€À€À€Â‡
À€À€9À€À€À€Âª
À€À€IÀ€À€À€Ã¤
À€À€ÂÀ€À€À€.À€À€À€À€À€ÃŒÀ€À€À€À€À€ÃœÀ€À€À€À€À€ÃµÀ€À€À€À€À€À€À€À€À€À€À€À€À€À€À€'À€À€À€À€À€:À€À€Ã•À€À€FÀ€À€GÀ€À€À€À€À€+À€À€dÀ€À€Ã À€À€ÂÀ€À€HÀ€À€À€qÀ€À€TÀ€À€À€ÂºÀ€À€À€À€À€À€À€MÀ€À€À€À€À€,À€À€À€jÀ€À€ÃªÀ€À€À€Â—À€À€Â›À€À€Â‚À€À€ÂˆÀ€À€À€À€À€À€À€Â§À€À€À€À€À€ÃÀ€À€yÀ€À€À€ÃÀ€À€1À€À€À€JÀ€À€9À€À€À€|À€À€À€À€À€Â¶À€À€À€À€À€Ã…À€À€.À€À€À€ÃŸÀ€À€;À€À€À€À€À€RÀ€À€À€JÀ€À€Â±À€À€À€ÂÀ€À€À€À€À€OÀ€À€À€À€À€jÀ€À€À€À€À€~À€À€À€À€À€ÂÀ€À€À€À€À€Â À€À€À€À€À€Â¯À€À€
À€À€À€ÃƒÀ€À€À€À€À€	À€À€À€À€À€À€À€À€À€
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
PO-Revision-Date: 2006-07-01 08:58+0300
Last-Translator: NilgÃ¼n Belma BugÃ¼ner <nilgun@buguner.name.tr>
Language-Team: Turkish <gnu-tr-u12a@lists.sourceforge.net>
Language: tr
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n != 1);
X-Generator: KBabel 1.9.1
À€  -V, --version               SÃ¼rÃ¼m bilgilerini gÃ¶sterir ve Ã§Ä±kar
À€  -d, --domain=PAKET   ileti Ã§evirileri yazÄ±lÄ±m PAKETinden alÄ±nÄ±r.
  -e                   Ã¶ncelem dizilerinin kullanÄ±mÄ± etkinleÅŸtirilir
  -E                   (uyumluluk iÃ§in yoksayÄ±ldÄ±)
  -h, --help           bu yardÄ±m iletisini gÃ¶sterir ve Ã§Ä±kar
  -V, --version        sÃ¼rÃ¼m bilgilerini gÃ¶sterir ve Ã§Ä±kar
  [PAKET]              ileti Ã§evirileri yazÄ±lÄ±m PAKETinden alÄ±nÄ±r.
  MSGID MSGID-PLURAL   MSGID (tekil) / MSGID-PLURAL (Ã§oÄŸul) Ã§evrilir
  SAYI               SAYIya baÄŸlÄ± olarak tekil/Ã§oÄŸul iletilerden biri seÃ§ilir
À€  -d, --domain=PAKET    ileti Ã§evirisini PAKET.mo dosyasÄ±ndan alÄ±r.
  -e                    Ã¶nceleme dizilerinin kullanÄ±mÄ±nÄ± etkinleÅŸtirir
  -E                    (uyumluluk iÃ§in yoksayÄ±ldÄ±)
  -h, --help            bu yardÄ±m iletisini gÃ¶sterir ve Ã§Ä±kar
  -n                    izleyen satÄ±r sonu karakterini yoksayar
  -V, --version         sÃ¼rÃ¼m bilgilerini gÃ¶sterir ve Ã§Ä±kar
  [PAKET] MSGID         PAKET.mo dosyasÄ±ndan MSGID ile iliÅŸkili Ã§eviriyi alÄ±r
À€  -h, --help                  bu yardÄ±m iletisini gÃ¶sterir ve Ã§Ä±kar
À€  -v, --variables             KABUK-BÄ°Ã‡Ä°MÄ° iÃ§indeki deÄŸiÅŸkenleri Ã§Ä±ktÄ±lar
À€Bruno HaibleÀ€Bir sayÄ±ya baÄŸlÄ± olarak ilgili tekil/Ã§oÄŸul ileti Ã§evirisini gÃ¶sterir.
À€Bir iletinin anadile Ã§evirisini gÃ¶sterir.
À€PAKET parametresi verilmemiÅŸse paket ismi TEXTDOMAIN Ã§evre deÄŸiÅŸkeninden
saptanÄ±r. Ä°leti kataloÄŸu olmasÄ± gereken dizinde deÄŸilse bulunduÄŸu dizin
TEXTDOMAINDIR Ã§evre deÄŸiÅŸkeni ile belirtilebilir.
Standart arama dizini: %s
À€PAKET parametresi verilmemiÅŸse .mo dosyasÄ±nÄ±n adÄ± TEXTDOMAIN Ã§evre
deÄŸiÅŸkeninden saptanÄ±r. Ä°leti kataloÄŸu olmasÄ± gereken dizinde deÄŸilse
bulunduÄŸu dizin TEXTDOMAINDIR Ã§evre deÄŸiÅŸkeni ile belirtilebilir.
Program -s seÃ§eneÄŸi ile kullanÄ±ldÄ±ÄŸÄ±nda `echo' komutu verilmiÅŸ gibi davranÄ±r.
Ancak iletiyi standart Ã§Ä±ktÄ±ya basitÃ§e kopyalamak yerine Ã§evirisini verir.
Standart arama dizini: %s
À€Normal iÅŸlem kipinde, $VARIABLE veya ${VARIABLE} biÃ§imindeki ortam
deÄŸiÅŸkenleri, karÅŸÄ± dÃ¼ÅŸen deÄŸerlerle deÄŸiÅŸtirilerek standart girdi
standart Ã§Ä±ktÄ±ya kopyalanÄ±r. EÄŸer bir KABUK-BÄ°Ã‡Ä°MÄ° verilmiÅŸse, yalnÄ±z
onun iÃ§indeki ortam deÄŸiÅŸkenleri iÃ§in bu ikame iÅŸlemi yapÄ±lÄ±r.
Aksi takdirde, standart girdideki ortam deÄŸiÅŸkenlerinin tÃ¼mÃ¼ne bu ikame
iÅŸlemi uygulanÄ±r.
À€Bilgilendirici Ã§Ä±ktÄ±:
À€Ä°ÅŸlem kipi:
À€YazÄ±lÄ±m hatalarÄ±nÄ± <bug-gnu-utils@gnu.org> adresine,
Ã‡eviri hatalarÄ±nÄ± <gnu-tr@belgeler.org> adresine bildiriniz.
À€Ortam deÄŸiÅŸkenlerinin deÄŸerleri ikame edilir.
À€Daha fazla bilgilendirilmek iÃ§in `%s --help' yazÄ±nÄ±z.
À€Ulrich DrepperÀ€Bilinmeyen sistem hatasÄ±À€KullanÄ±mÄ±: %s [SEÃ‡ENEK] [KABUK-BÄ°Ã‡Ä°MÄ°]
À€KullanÄ±mÄ±: %s [SEÃ‡ENEK] [PAKET] MSGID MSGID-PLURAL SAYI
À€KullanÄ±mÄ±: %s [SEÃ‡ENEK] [[PAKET] MSGID]
ya da:     %s [SEÃ‡ENEK] -s [MSGID]...
À€--variables seÃ§eneÄŸi kullanÄ±lmÄ±ÅŸsa, standart girdi gÃ¶rmezden gelinir ve
her satÄ±rda bir tane olmak Ã¼zere KABUK-BÄ°Ã‡Ä°MÄ° iÃ§indeki ortam deÄŸiÅŸkenleri
Ã§Ä±ktÄ±lanÄ±r.
À€%s tarafÄ±ndan yazÄ±ldÄ±.
À€"%s" okunurken hataÀ€bellek tÃ¼kendiÀ€argÃ¼manlar eksikÀ€standart girdiÀ€Ã§ok fazla argÃ¼manÀ€yazma hatasÄ±À€