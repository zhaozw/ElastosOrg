Þ������������������������)������ü������������ ����B������¡����!����ä����ð��������9������÷����M������1��������������e����������:������ò��������-����Þ����G	��������&����������¹����������Î����*������ß����1������

����&������<
����������c
����������r
����"������
����9������ª
����I������ä
����������.����������Ì����������Ü����������õ������������������������������'����������:����Ø����F����E����������B����e����ÿ����¨����=������¨����O������æ����������6����d������B����'������§��������Ï����Ñ����Þ��������°����������4����������K����:������Z����'����������@������½����������þ����������
����+������*����:������V����P���������� ������â������������������������������²����������Ï����������ë����������ü����
����������������	������������������
����������������������������������������
��������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������  -V, --version               output version information and exit
��  -d, --domain=TEXTDOMAIN   retrieve translated message from TEXTDOMAIN
  -e                        enable expansion of some escape sequences
  -E                        (ignored for compatibility)
  -h, --help                display this help and exit
  -V, --version             display version information and exit
  [TEXTDOMAIN]              retrieve translated message from TEXTDOMAIN
  MSGID MSGID-PLURAL        translate MSGID (singular) / MSGID-PLURAL (plural)
  COUNT                     choose singular/plural form based on this value
��  -d, --domain=TEXTDOMAIN   retrieve translated messages from TEXTDOMAIN
  -e                        enable expansion of some escape sequences
  -E                        (ignored for compatibility)
  -h, --help                display this help and exit
  -n                        suppress trailing newline
  -V, --version             display version information and exit
  [TEXTDOMAIN] MSGID        retrieve translated message corresponding
                            to MSGID from TEXTDOMAIN
��  -h, --help                  display this help and exit
��  -v, --variables             output the variables occurring in SHELL-FORMAT
��Bruno Haible��Display native language translation of a textual message whose grammatical
form depends on a number.
��Display native language translation of a textual message.
��If the TEXTDOMAIN parameter is not given, the domain is determined from the
environment variable TEXTDOMAIN.  If the message catalog is not found in the
regular directory, another location can be specified with the environment
variable TEXTDOMAINDIR.
Standard search directory: %s
��If the TEXTDOMAIN parameter is not given, the domain is determined from the
environment variable TEXTDOMAIN.  If the message catalog is not found in the
regular directory, another location can be specified with the environment
variable TEXTDOMAINDIR.
When used with the -s option the program behaves like the `echo' command.
But it does not simply copy its arguments to stdout.  Instead those messages
found in the selected catalog are translated.
Standard search directory: %s
��In normal operation mode, standard input is copied to standard output,
with references to environment variables of the form $VARIABLE or ${VARIABLE}
being replaced with the corresponding values.  If a SHELL-FORMAT is given,
only those environment variables that are referenced in SHELL-FORMAT are
substituted; otherwise all environment variables references occurring in
standard input are substituted.
��Informative output:
��Operation mode:
��Report bugs to <bug-gnu-gettext@gnu.org>.
��Substitutes the values of environment variables.
��Try `%s --help' for more information.
��Ulrich Drepper��Unknown system error��Usage: %s [OPTION] [SHELL-FORMAT]
��Usage: %s [OPTION] [TEXTDOMAIN] MSGID MSGID-PLURAL COUNT
��Usage: %s [OPTION] [[TEXTDOMAIN] MSGID]
or:    %s [OPTION] -s [MSGID]...
��When --variables is used, standard input is ignored, and the output consists
of the environment variables that are referenced in SHELL-FORMAT, one per line.
��Written by %s.
��error while reading "%s"��memory exhausted��missing arguments��standard input��too many arguments��write error��Project-Id-Version: gettext-runtime 0.16
Report-Msgid-Bugs-To: bug-gnu-gettext@gnu.org
POT-Creation-Date: 2010-06-04 01:57+0200
PO-Revision-Date: 2007-06-14 09:43+0400
Last-Translator: Oleg S. Tihonov <ost@tatnipi.ru>
Language-Team: Russian <ru@li.org>
Language: ru
MIME-Version: 1.0
Content-Type: text/plain; charset=koi8-r
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2;
��  -V, --version               ÎÁÐÅÞÁÔÁÔØ ÉÎÆÏÒÍÁÃÉÀ Ï ×ÅÒÓÉÉ É ×ÙÊÔÉ
��  -d, --domain=äïíåî     ÉÓÐÏÌØÚÏ×ÁÔØ ÐÅÒÅ×ÅÄÅÎÎÙÅ ÓÏÏÂÝÅÎÉÑ ÉÚ äïíåîá
  -e                     ÒÁÚÒÅÛÉÔØ ÉÓÐÏÌØÚÏ×ÁÎÉÅ ÎÅËÏÔÏÒÙÈ escape-
                         -ÐÏÓÌÅÄÏ×ÁÔÅÌØÎÏÓÔÅÊ
  -E                     (ÉÇÎÏÒÉÒÕÅÔÓÑ ÄÌÑ ÓÏ×ÍÅÓÔÉÍÏÓÔÉ)
  -h, --help             ÐÏËÁÚÁÔØ ÜÔÕ ÓÐÒÁ×ËÕ É ×ÙÊÔÉ
  -V, --version          ÐÏËÁÚÁÔØ ÉÎÆÏÒÍÁÃÉÀ Ï ×ÅÒÓÉÉ É ×ÙÊÔÉ
  [äïíåî]                ÎÁÊÔÉ ÐÅÒÅ×ÏÄ ÓÏÏÂÝÅÎÉÑ × ÕËÁÚÁÎÎÏÍ äïíåîå
  MSGID MSGID-PLURAL     ÐÅÒÅ×ÅÓÔÉ MSGID (ÅÄ. ÞÉÓÌÏ) / MSGID-PLURAL (ÍÎ. ÞÉÓÌÏ)
  þéóìï                  ×ÙÂÒÁÔØ ÅÄ./ÍÎ. ÞÉÓÌÏ ÎÁ ÏÓÎÏ×Å ÜÔÏÇÏ ÚÎÁÞÅÎÉÑ
��  -d, --domain=äïíåî        ÉÓÐÏÌØÚÏ×ÁÔØ ÐÅÒÅ×ÅÄÅÎÎÙÅ ÓÏÏÂÝÅÎÉÑ ÉÚ äïíåîá
  -e                        ÒÁÚÒÅÛÉÔØ ÉÓÐÏÌØÚÏ×ÁÎÉÅ ÎÅËÏÔÏÒÙÈ escape-
                            -ÐÏÓÌÅÄÏ×ÁÔÅÌØÎÏÓÔÅÊ
  -E                        (ÉÇÎÏÒÉÒÕÅÔÓÑ ÄÌÑ ÓÏ×ÍÅÓÔÉÍÏÓÔÉ)
  -h, --help                ÐÏËÁÚÁÔØ ÜÔÕ ÓÐÒÁ×ËÕ É ×ÙÊÔÉ
  -n                        ÎÅ ×Ù×ÏÄÉÔØ ÚÁ×ÅÒÛÁÀÝÉÊ ÐÅÒÅ×ÏÄ ÓÔÒÏËÉ 
  -V, --version             ÐÏËÁÚÁÔØ ÉÎÆÏÒÍÁÃÉÀ Ï ×ÅÒÓÉÉ É ×ÙÊÔÉ
  [äïíåî] MSGID             ÎÁÊÔÉ ÐÅÒÅ×ÏÄ ÓÏÏÂÝÅÎÉÑ MSGID × äïíåîå
��  -h, --help                  ÎÁÐÅÞÁÔÁÔØ ÜÔÕ ÓÐÒÁ×ËÕ É ×ÙÊÔÉ
��  -v, --variables             ×Ù×ÅÓÔÉ ÐÅÒÅÍÅÎÎÙÅ, ÎÁÊÄÅÎÎÙÅ × æïòíáôå-ïâïìïþëé
��âÒÕÎÏ èÁÊÂÌ��ïÔÏÂÒÁÖÁÅÔ ÐÅÒÅ×ÏÄ ÔÅËÓÔÏ×ÏÇÏ ÓÏÏÂÝÅÎÉÑ, ÇÒÁÍÍÁÔÉÞÅÓËÁÑ ÆÏÒÍÁ ËÏÔÏÒÏÇÏ
ÚÁ×ÉÓÉÔ ÏÔ ÎÅËÏÔÏÒÏÇÏ ÞÉÓÌÁ.
��ïÔÏÂÒÁÖÁÅÔ ÐÅÒÅ×ÏÄ ÔÅËÓÔÏÇÏ ÓÏÏÂÝÅÎÉÑ.
��åÓÌÉ ÎÅ ÚÁÄÁÎ ÐÁÒÁÍÅÔÒ äïíåî, ÉÓÐÏÌØÚÕÅÔÓÑ ÄÏÍÅÎ, ÕÓÔÁÎÏ×ÌÅÎÎÙÊ ×
ÐÅÒÅÍÅÎÎÏÊ ÓÒÅÄÙ TEXTDOMAIN.  åÓÌÉ ÆÁÊÌ Ó ÐÅÒÅ×ÅÄÅÎÎÙÍÉ ÓÏÏÂÝÅÎÉÑÍÉ ÎÅ
ÎÁÊÄÅÎ × ÓÔÁÎÄÁÒÔÎÏÍ ËÁÔÁÌÏÇÅ, ÍÏÖÎÏ ÕËÁÚÁÔØ ÄÒÕÇÏÊ ËÁÔÁÌÏÇ Ó ÐÏÍÏÝØÀ
ÐÅÒÅÍÅÎÎÏÊ ÓÒÅÄÙ TEXTDOMAINDIR.
óÔÁÎÄÁÒÔÎÙÊ ËÁÔÁÌÏÇ ÐÏÉÓËÁ: %s
��åÓÌÉ ÎÅ ÚÁÄÁÎ ÐÁÒÁÍÅÔÒ äïíåî, ÉÓÐÏÌØÚÕÅÔÓÑ ÄÏÍÅÎ, ÕÓÔÁÎÏ×ÌÅÎÎÙÊ ×
ÐÅÒÅÍÅÎÎÏÊ ÓÒÅÄÙ TEXTDOMAIN.  åÓÌÉ ÆÁÊÌ Ó ÐÅÒÅ×ÅÄÅÎÎÙÍÉ ÓÏÏÂÝÅÎÉÑÍÉ ÎÅ
ÎÁÊÄÅÎ × ÓÔÁÎÄÁÒÔÎÏÍ ËÁÔÁÌÏÇÅ, ÍÏÖÎÏ ÕËÁÚÁÔØ ÄÒÕÇÏÊ ËÁÔÁÌÏÇ Ó ÐÏÍÏÝØÀ
ÐÅÒÅÍÅÎÎÏÊ ÓÒÅÄÙ TEXTDOMAINDIR.
ðÒÉ ÉÓÐÏÌØÚÏ×ÁÎÉÉ Ó ËÌÀÞÏÍ -s, ÐÏ×ÅÄÅÎÉÅ ÐÒÏÇÒÁÍÍÙ ÐÏÈÏÖÅ ÎÁ ÐÏ×ÅÄÅÎÉÅ
ËÏÍÁÎÄÙ `echo'.  îÏ ×ÍÅÓÔÏ ÐÒÏÓÔÏÇÏ ËÏÐÉÒÏ×ÁÎÉÑ ÁÒÇÕÍÅÎÔÏ× ×
ÓÔÁÎÄÁÒÔÎÙÊ ×Ù×ÏÄ, ×Ù×ÏÄÑÔÓÑ ÉÈ ÐÅÒÅ×ÏÄÙ ÉÚ ÕËÁÚÁÎÎÏÇÏ ÄÏÍÅÎÁ.
óÔÁÎÄÁÒÔÎÙÊ ËÁÔÁÌÏÇ ÐÏÉÓËÁ: %s
��÷ ÏÂÙÞÎÏÍ ÒÅÖÉÍÅ ÒÁÂÏÔÙ ÓÔÁÎÄÁÒÔÎÙÊ ××ÏÄ ËÏÐÉÒÕÅÔÓÑ ÎÁ ÓÔÁÎÄÁÒÔÎÙÊ
×Ù×ÏÄ, Á ÓÓÙÌËÉ ÎÁ ÐÅÒÅÍÅÎÎÙÅ ÓÒÅÄÙ ×ÉÄÁ $ðåòåíåîîáñ ÉÌÉ ${ðåòåíåîîáñ}
ÚÁÍÅÎÑÀÔÓÑ ÎÁ ÓÏÏÔ×ÅÔÓÔ×ÕÀÝÉÅ ÚÎÁÞÅÎÉÑ.  åÓÌÉ ÚÁÄÁÎ æïòíáô-ïâïìïþëé,
ÐÏÄÓÔÁ×ÌÑÀÔÓÑ ÔÏÌØËÏ ÔÅ ÐÅÒÅÍÅÎÎÙÅ, ÎÁ ËÏÔÏÒÙÅ ÅÓÔØ ÓÓÙÌËÉ ×
æïòíáôå-ïâïìïþëé; × ÐÒÏÔÉ×ÎÏÍ ÓÌÕÞÁÅ ÐÏÄÓÔÁ×ÌÑÀÔÓÑ ×ÓÅ ÓÓÙÌËÉ ÎÁ
ÐÅÒÅÍÅÎÎÙÅ ÓÒÅÄÙ, ×ÓÔÒÅÞÁÀÝÉÅÓÑ ÎÁ ÓÔÁÎÄÁÒÔÎÏÍ ××ÏÄÅ.
��éÎÆÏÒÍÁÃÉÏÎÎÙÊ ×Ù×ÏÄ:
��òÅÖÉÍ ÒÁÂÏÔÙ:
��ïÂ ÏÛÉÂËÁÈ ÓÏÏÂÝÁÊÔÅ ÐÏ ÁÄÒÅÓÕ <bug-gnu-gettext@gnu.org>.
��ðÏÄÓÔÁ×ÌÑÅÔ ÚÎÁÞÅÎÉÑ ÐÅÒÅÍÅÎÎÙÈ ÓÒÅÄÙ.
��ðÏÐÒÏÂÕÊÔÅ `%s --help' ÄÌÑ ÐÏÌÕÞÅÎÉÑ ÂÏÌÅÅ ÐÏÄÒÏÂÎÏÇÏ ÏÐÉÓÁÎÉÑ.
��õÌØÒÉÈ äÒÅÐÐÅÒ��îÅÉÚ×ÅÓÔÎÁÑ ÓÉÓÔÅÍÎÁÑ ÏÛÉÂËÁ��éÓÐÏÌØÚÏ×ÁÎÉÅ: %s [ëìàþ] [æïòíáô-ïâïìïþëé]
��éÓÐÏÌØÚÏ×ÁÎÉÅ: %s [ëìàþ] [äïíåî] MSGID MSGID-PLURAL þéóìï
��éÓÐÏÌØÚÏ×ÁÎÉÅ: %s [ëìàþ] [[äïíåî] MSGID]
      ÉÌÉ:     %s [ëìàþ] -s [MSGID]...
��åÓÌÉ ÚÁÄÁÎ ËÌÀÞ --variables, ÓÔÁÎÄÁÒÔÎÙÊ ××ÏÄ ÉÇÎÏÒÉÒÕÅÔÓÑ, É ×Ù×ÏÄ
ÓÏÓÔÏÉÔ ÉÚ ÐÅÒÅÍÅÎÎÙÈ ÓÒÅÄÙ, ÎÁ ËÏÔÏÒÙÅ ÅÓÔØ ÓÓÙÌËÉ ×
æïòíáôå-ïâïìïþëé, ÐÏ ÏÄÎÏÊ ÎÁ ÓÔÒÏËÅ.
��á×ÔÏÒ ÐÒÏÇÒÁÍÍÙ -- %s.
��ÏÛÉÂËÁ ÐÒÉ ÞÔÅÎÉÉ "%s"��ÏÐÅÒÁÔÉ×ÎÁÑ ÐÁÍÑÔØ ÉÓÞÅÒÐÁÎÁ��ÎÅÏÂÈÏÄÉÍÏ ÚÁÄÁÔØ ÁÒÇÕÍÅÎÔÙ��ÓÔÁÎÄÁÒÔÎÙÊ ××ÏÄ��ÓÌÉÛËÏÍ ÍÎÏÇÏ ÁÒÇÕÍÅÎÔÏ×��ÏÛÉÂËÁ ÚÁÐÉÓÉ��