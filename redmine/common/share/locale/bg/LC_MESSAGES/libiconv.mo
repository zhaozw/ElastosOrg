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
��������W
����������ñ����r����������¤������ø����§���������� ������E����d������æ����������K����d������Ð����¡������5����������×����������\����o������_����������Ï����������V����u������Û����¸������Q��������
����a������ ����,����������������¯����8������¸����^������ñ����������P����T������p����(������Å����.������î����`����������N������~����W������Í����Ë������%����E������ñ����t������7����J������¬����������÷����m������
����������x����������ÿ������������������������G������¥����=������í����?������+ ����������k ���������� ����s������ ��������������������������$������������
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
PO-Revision-Date: 2008-01-03 14:20+0200
Last-Translator: Roumen Petrov <transl@roumenpetrov.info>
Language-Team: Bulgarian <dict@fsa-bg.org>
Language: bg
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n != 1);
��  --byte-subst=ФОРМАТИРАЩ_НИЗ
                              заместител на непреобразуваеми байтове
��  --help                      показване на тази помощ и изход от програмата
��  --unicode-subst=ФОРМАТИРАЩ_НИЗ
                              заместител на непреобразуваеми знаци от Уникод
��  --version                   извеждане на информация за версията и изход от
                              програмата
��  --widechar-subst=ФОРМАТИРАЩ_НИЗ
                              заместител на непреобразуваеми широки знаци
��  -c                          игнориране на непреобразуваеми знаци
��  -f КОДИРАНЕ, --from-code=КОДИРАНЕ
                              кодиране на входните данни
��  -l, --list                  изброяване на поддържаните кодирания
��  -s, --silent                подтискане на съобщенията за грешки при
                              преобразуване
��  -t КОДИРАНЕ, --to-code=КОДИРАНЕ
                              кодиране на изходните данни
��%s��%s аргумент: не е позволена директива за форматиране с размер.��%s аргумент: не е позволена директива за форматиране с променлива точност.��%s аргумент: не е позволена директива за форматиране с променлива ширина.��%s аргумент: знакът '%c' не е валиден определител за преобразуване.��%s аргумент: знакът, завършващ директивата за форматиране, не е валиден определител за преобразуване.��%s аргумент: форматиращият низ използва повече от един аргумент: %u аргумент.��%s аргумент: форматиращият низ използва повече от един аргумент: %u аргумента.��%s аргумент: низът завършва по средата на директивата.��%s: входно/изходна грешка��%s:%u:%u��%s:%u:%u: не може да се преобразува��%s:%u:%u: непълен знак или изместваща последователност��(стандартен вход)��Преобразува текст от едно кодиране към друго.
��входно/изходна грешка��Извеждане на информация:
��Аргументи контролиращи проблеми при преобразуване:
��Аргументи задаващи извеждането на грешки:
��Аргументи задаващи входния и изходния формати:
��Подавайте доклади за грешки в програмата на <bug-gnu-libiconv@gnu.org>.
Подавайте доклади за грешки в превода на <dict@fsa-bg.org>.
��За повече информация опитайте "%s --help".
��Употреба: %s [АРГУМЕНТИ...] [-f КОДИРАНЕ] [-t КОДИРАНЕ] [ВХОДЕН_ФАЙЛ...]
��Употреба: iconv [-c] [-s] [-f fromcode] [-t tocode] [файл ...]��Автори: %s.
��заместителят за байт към Уникод не може да се преобразува: %s��заместителят за байт не може да се преобразува в резултатното кодиране: %s��заместителят за байт в Уникод не може да се преобразува в широк знак: %s��заместителят за Уникод не може да се преобразува към резултатното кодиране: %s��заместителят за широк знак не може да се преобразува към резултатното кодиране: %s��не се поддържа преобразуване от %s към %s��не се поддържа преобразуване от %s��не се поддържа преобразуване към %s��или:      %s -l
��или:      iconv -l��опитайте с '%s -l', за да получите списък с поддържаните кодирания��