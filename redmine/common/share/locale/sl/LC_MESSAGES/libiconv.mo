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
����õ����W
����@������M����=����������i������Ì����J������6����i����������I������ë����V������5����<����������D������É����U����������������d����>������g����J������¦����D������ñ����5������6����W������l��������Ä����+������Ü������������������������������ ����.������>����������m����5������u����
������«����������¶����5������Ë����"����������.������$����-������S����,����������D������®����E������ó����
������9����5������D����:������z����:������µ����=������ð����D������.����,������s����!������ ����������Â����������â����������ò����5��������������������������������$������������
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
PO-Revision-Date: 2008-04-26 09:48+0200
Last-Translator: Primož Peterlin <primoz.peterlin@biofiz.mf.uni-lj.si>
Language-Team: Slovenian <translation-team-sl@lists.sourceforge.net>
Language: sl
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=4; plural=(n%100==1 ? 1 : n%100==2 ? 2 : n%100==3 || n%100==4 ? 3 : 0);
��  --byte-subst=OBLIKOVNI_NIZ  zamenjava za nepretvorljive bajte
��  --help                      izpiši ta navodila in končaj
��  --unicode-subst=OBLIKOVNI_NIZ
                              zamenjava za nepretvorljive unikodne znake
��  --version                   izpiši informacijo o različici in končaj
��  --widechar-subst=OBLIKOVNI_NIZ
                              zamenjava za nepretvorljive široke znake
��  -c                          zavrzi znake, ki jih ni mogoče pretvoriti
��  -f KODIRANJE, --from-code=KODIRANJE
                              kodni nabor vhoda
��  -l, --list                  izpiši podprte nabore znakov
��  -s, --silent                brez poročil o napakah pri pretvorbi
��  -t KODIRANJE, --to-code=KODIRANJE
                              kodni nabor izhoda
��%s��argument %s: oblikovna direktiva z velikostjo tu ni dovoljena.��argument %s: oblikovna direktiva z nestalno natančnostjo tu ni dovoljena.��argument %s: oblikovna direktiva z nestalno širino tu ni dovoljena.��argument %s: znak %c ni veljavno določilo pretvorbe.��argument %s: znak, ki zaključuje oblikovno direktivo, ni veljavno določilo pretvorbe.��argument %s: oblikovni niz porabi več kot en argument: %u argumentov.��argument %s: oblikovni niz porabi več kot en argument: %u argument.��argument %s: oblikovni niz porabi več kot en argument: %u argumenta.��argument %s: oblikovni niz porabi več kot en argument: %u argumente.��argument %s: niz se konča sredi direktive.��%s: V/I napaka��%s:%u:%u��%s:%u:%u pretvorba ni mogoča��%s:%u:%u nepopolni znak ali pomično zaporedje��(stdin)��Pretvori besedilo iz enega kodnega nabora v drugega.
��V/I napaka��Informativni izpis:
��Izbire, ki določajo odziv na težave pri pretvorbi:
��Izbire, ki določajo izpis napak:
��Izbire, ki določajo vhodni in izhodni zapis:
��Napake javite na <bug-gnu-libiconv@gnu.org>.
��Poskusite »%s --help« za več informacij.
��Uporaba: %s [IZBIRA...] [-f KODIRANJE] [-t KODIRANJE] [DATOTEKA...]
��Uporaba: iconv [-c] [-s] [-f vh_nabor] [-t izh_nabor] [datoteka ...]<��Avtor %s.
��nadomestnega bajta ni mogoče pretvoriti v unikod: %s��pretvorba nadomestnega bajta v ciljni nabor ni mogoča: %s��nadomestnega bajta ni mogoče pretvoriti v široki niz: %s��nadomestnega unikoda ni mogoče pretvoriti v ciljni nabor: %s��pretvorba nadomestnega širokega znaka v ciljni nabor ni mogoča: %s��pretvorba iz nabora %s v nabor %s ni podprta��pretvorba iz nabora %s ni podprta��pretvorba v nabor %s ni podprta��ali:     %s -l
��ali:     iconv -l��poskusite »%s -l« za seznam podprtih naborov znakov��