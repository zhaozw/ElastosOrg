Þ��������.����������������=������ü������������ð����C������ñ����9������5����o������o����B������ß����m������"����?����������\������Ð����;������-����P������i����[������º��������������@����������N������Z����J������©����D������ô����d������9��������������:������9	����
������t	����������	����������	����0������¤	����������Õ	����5������Ý	����	������
����������
����)������2
����"������\
����1������
����+������±
����&������Ý
����A����������;������F��������������/����������7������Â����3������ú����:������.����;������i����$������¥����������Ê����������é����
������
����������
����2������$
����²����W
����E������
����?������P����j����������E������û����j������A����?������¬����N������ì����9������;����L������u����L������Â��������������:����������D������M����C����������<������Ö����V����������������j����7����������������;����������O����������X����2������t����������§����,������·����������ä����������ô����'����������%������,����1������R����.����������'������³����:������Û����?����������������V����0������f����0����������:������È����4����������8������8����#������q��������������������³����������Ñ����������à����0������ñ��������������������������$������������
������������������������������������������������������������%������������������)����������������������������������,������������ ������������������������������������+������������!������	��������������������������������'������������"������(������.������-������������������������������������
������������������������������������#��������������������&������������*��������������������  --byte-subst=FORMATSTRING   substitution for unconvertible bytes
��  --help                      display this help and exit
��  --unicode-subst=FORMATSTRING
                              substitution for unconvertible Unicode characters
��  --version                   output version information and exit
��  --widechar-subst=FORMATSTRING
                              substitution for unconvertible wide characters
��  -c                          discard unconvertible characters
��  -f ENCODING, --from-code=ENCODING
                              the encoding of the input
��  -l, --list                  list the supported encodings
��  -s, --silent                suppress error messages about conversion problems
��  -t ENCODING, --to-code=ENCODING
                              the encoding of the output
��%s��%s argument: A format directive with a size is not allowed here.��%s argument: A format directive with a variable precision is not allowed here.��%s argument: A format directive with a variable width is not allowed here.��%s argument: The character '%c' is not a valid conversion specifier.��%s argument: The character that terminates the format directive is not a valid conversion specifier.��%s argument: The format string consumes more than one argument: %u argument.��%s argument: The format string consumes more than one argument: %u arguments.��%s argument: The string ends in the middle of a directive.��%s: I/O error��%s:%u:%u��%s:%u:%u: cannot convert��%s:%u:%u: incomplete character or shift sequence��(stdin)��Converts text from one encoding to another encoding.
��I/O error��Informative output:
��Options controlling conversion problems:
��Options controlling error output:
��Options controlling the input and output format:
��Report bugs to <bug-gnu-libiconv@gnu.org>.
��Try `%s --help' for more information.
��Usage: %s [OPTION...] [-f ENCODING] [-t ENCODING] [INPUTFILE...]
��Usage: iconv [-c] [-s] [-f fromcode] [-t tocode] [file ...]��Written by %s.
��cannot convert byte substitution to Unicode: %s��cannot convert byte substitution to target encoding: %s��cannot convert byte substitution to wide string: %s��cannot convert unicode substitution to target encoding: %s��cannot convert widechar substitution to target encoding: %s��conversion from %s to %s unsupported��conversion from %s unsupported��conversion to %s unsupported��or:    %s -l
��or:    iconv -l��try '%s -l' to get the list of supported encodings��Project-Id-Version: libiconv 1.12
Report-Msgid-Bugs-To: bug-gnu-libiconv@gnu.org
POT-Creation-Date: 2011-08-07 15:24+0200
PO-Revision-Date: 2007-12-27 21:22+0000
Last-Translator: Edmund GRIMLEY EVANS <edmundo@rano.org>
Language-Team: Esperanto <translation-team-eo@lists.sourceforge.net>
Language: eo
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n != 1);
��  --byte-subst=FORMATĈENO     substituado de nekonverteblaj bitokoj
��  --help                      montri ĉi tiun helpon kaj eliri
��  --unicode-subst=FORMATĈENO
                              substituado de nekonverteblaj unikodaj signoj
��  -version                    eligi informon pri la versio kaj eliri
��  --widechar-subst=FORMATĈENO
                              substituado de nekonverteblaj larĝaj signoj
��  -c                          forĵeti nekonverteblajn signojn
��  -f KODO, --from-code=KODO
                              la kodo de la enigo
��  -l, --list                  listigi la konatajn kodojn
��  -s, --silent                subpremi eraromesaĝojn pri konvertoproblemoj
��  -t KODO, --to-code=KODO
                              la kodo de la eligo
��%s��%s argumento: Formatdirektivo kun grando ne eblas ĉi tie.��%s argumento: Formatdirektivo kun variabla precizo ne eblas ĉi tie.��%s argumento: Formatdirektivo kun variabla larĝo ne eblas ĉi tie.��%s argumento: La signo '%c' ne estas valida konvertospecifo.��%s argumento: La signo, kiu finas la formatdirektivon ne estas valida konvertospecifo.��%s argumento: La formatoĉeno konsumas pli ol unu argumenton: %u argumento.��%s argumento: La formatoĉeno konsumas pli ol unu argumenton: %u argumentoj.��%s argumento: La signoĉeno finiĝas meze de direktivo.��%s: leg/skrib-eraro��%s:%u:%u��%s:%u:%u: ne povas konverti��%s:%u:%u: malkompleta signo aŭ reĝimŝanĝa kodo��(normala enigo)��Konvertas tekston de unu kodo al alia kodo.
��leg/skrib-eraro��Informa eligo:
��Opcioj, kiuj regas konvertoproblemojn:
��Opcioj, kiuj regas eligon de eraroj:
��Opcioj, kiuj regas la formon de enigo kaj eligo:
��Raportu cimojn al <bug-gnu-libiconv@gnu.org>.
��Provu '%s --help' por pli da informoj.
��Uzado: %s [OPCIO...] [-f KODO] [-t KODO] [ENIGDOSIERO...]
��Uzado: iconv [-c] [-s] [-f fontkodo] [-t celkodo] [dosiero ...]��Verkita de %s.
��ne povas konverti bitoksubstituon al Unikodo: %s��ne povas konverti bitoksubstituon al celkodo: %s��ne povas konverti bitoksubstituon al larĝa signoĉeno: %s��ne povas konverti unikodan substituon al celkodo: %s��ne povas konverti larĝasignan substituon al celkodo: %s��konvertado de %s al %s ne disponata��konvertado de %s ne disponata��konvertado al %s ne disponata��aŭ:    %s -l
��aŭ:    iconv -l��provu '%s -l' por ricevi liston de konataj kodoj��