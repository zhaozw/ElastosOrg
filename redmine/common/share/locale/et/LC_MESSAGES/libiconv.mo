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
����¦����W
����B������þ����A������A����e����������D������é����j������.����B����������W������Ü����G������4����<������|����V������¹��������������<����������;������P����;����������4������È����K������ý����������I����*������Ø����������������������������������.������5����������d����,������u����������¢����
������«����6������¹����&������ð����3����������=������K����)����������G������³����>������û����������:����,������J����4������w����F������¬����5������ó����6������)����.������`����!����������"������±����������Ô����������ã����4������ô��������������������������$������������
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
PO-Revision-Date: 2010-08-04 15:37+0300
Last-Translator: Toomas Soome <Toomas.Soome@microlink.ee>
Language-Team: Estonian <linux-ee@lists.eenet.ee>
Language: et
MIME-Version: 1.0
Content-Type: text/plain; charset=ISO-8859-15
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n != 1);
��  --byte-subst=VORMING        mitteteisendatavate baitide asendus
��  --help                      väljasta see abiinfo ja lõpeta töö
��  --unicode-subst=VORMING
                              mitteteisendavate Unikoodi sümbolite asendus
��  --version                   väljasta versiooni info ja lõpeta töö
��  --widechar-subst=VORMING
                              mitteteisendatavate mitmebaidi sümbolite asendus
��  -c                          eemalda mitteteisendatavad sümbolid
��  -f KODEERING, --from-code=KODEERING
                              sisendi kooditabel
��  -l, --list                  väljasta toetatud kooditabelite nimekiri
��  -s, --silent                keela probleemidest teatamine
��  -t KODEERING, --to-code=KODEERING
                              väljundi kooditabel
��%s��%s argument: Suuruse määranguga vorming ei ole siin lubatud.��%s argument: Muutuva täpsusega vorming ei ole siin lubatud.��%s argument: Muutuva pikkusega vorming ei ole siin lubatud.��%s argument: Sümbol '%c' ei ole lubatud teisenduses.��%s argument: Vormingi direktiivi lõpetav sümbol ei ole teisenduses lubatud.��%s argument: Vormingu sõne nõuab enam kui ühte argumenti: %u argument.��%s argument: Vormingu sõne nõuab enam kui ühte argumenti: %u argumenti.��%s argument: Sõne lõppeb keset direktiivi.��%s: S/V viga��%s:%u:%u��%s:%u:%u: ei saa teisendada��%s:%u:%u: mittetäielik sümbol või nihkejärjend��(standardsisend)��Teisendab teksti ühest kooditabelist teise.
��S/V viga��Infoväljund:
��Teisendamisel tekkivate probleemide kontrolli võtmed:
��Vigade väljundi kontrollimise võtmed:
��Sisendi ja väljundi vormingut kontrollivad võtmed:
��Vigadest teatage palun aadressil <bug-gnu-libiconv@gnu.org>.
��Lisainfo saamiseks kasutage `%s --help'.
��Kasutamine: %s [VÕTI...] [-f KODEERING] [-t KODEERING] [SISENDFAIL...]
��Kasutamine: iconv [-c] [-s] [-f koodist] [-t koodi] [fail ...]��Kirjutanud %s.
��baidiasendust ei saa Unikoodi teisendada: %s��baitide asendust ei saa sihttabelisse teisendada: %s��baidiasendust ei saa mitmebaidiliste sümbolitega sõneks teisendada: %s��unikoodi asendust ei saa sihttabelisse teisendada: %s��widechar sümboleid ei saa sihttabelisse teisendada: %s��teisendust tabelist %s tabelisse %s ei toetata��teisendust tabelist %s ei toetata��teisendust tabelisse %s ei toetata��või:    %s -l
��või:    iconv -l��Toetatud kooditabelite niekirja saate käsuga '%s -l'��