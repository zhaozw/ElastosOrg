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
����µ����W
����:������
����7������H����b����������:������ã����]����������:������|����O������·����4����������C������<����M����������������Î����8������Ñ����;������
����;������F����4����������G������·����=������ÿ����,������=����������j������������������������.������¢����������Ñ����4������à��������������������)����������8����������V����#������t��������������.����������B������M����I����������
������Ú����-������è����1����������1������H����:������z����7������µ����������í����������
����������'����������=����������L����2������]��������������������������$������������
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
PO-Revision-Date: 2008-11-12 09:51+0800
Last-Translator: Ji ZhengYu <zhengyuji@gmail.com>
Language-Team: Chinese (simplified) <translation-team-zh-cn@lists.sourceforge.net>
Language: zh_CN
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=1; plural=0;
��  --byte-subst=FORMATSTRING   替代不可转换的字节
��  --help                      显示此帮助并退出
��  --unicode-subst=FORMATSTRING
                              替代不可转换的 Unicode 字符
��  --version                   显示版本信息并退出
��  --widechar-subst=FORMATSTRING
                              替代不可转换的宽字符
��  -c                          忽略不可转换的字符
��  -f ENCODING, --from-code=ENCODING
                              输入编码
��  -l, --list                  列出支持的编码
��  -s, --silent                不显示有关转换出错的信息
��  -t ENCODING, --to-code=ENCODING
                              输出编码
��%s��%s 参数: 此处不允许用含大小的命令格式。��%s 参数: 此处不允许用可变精度的命令格式。��%s 参数: 此处不允许用可变长度的命令格式。��%s 参数: 字符‘%c’不是有效的转义符。��%s 参数: 用于终止命令格式的字符不是有效的转义符。��%s 参数: 格式字符串需要更多的参数: %u 参数。��%s 参数: 字符串在命令中部终止。��%s: 输入/输出错误��%s:%u:%u��%s:%u:%u: 无法转换��%s:%u:%u: 不完整的字符或者替换序列��(标准输入)��将文本从一种编码转换成另一种编码。
��输入/输出错误��输出信息:
��控制字符转换的选项:
��控制错误输出的选项:
��控制输入输出格式的选项:
��请发送错误报告至 <bug-gnu-libiconv@gnu.org>。
请将翻译错误发送至 <translation-team-zh-cn@lists.sourceforge.net>。
��尝试‘%s --help’以获得更多信息。
��用法: %s [OPTION...] [-f ENCODING] [-t ENCODING] [INPUTFILE...]
��用法：iconv [-c] [-s] [-f 输入编码] [-t 输出编码] [文件 ...]��作者 %s。
��无法将欲替换字节转换成 Unicode: %s��无法将欲替换字节转换成目标编码: %s��无法将欲替换字节转换成宽字符串: %s��无法将欲替换 unicode 字元转换成目标编码: %s��无法将欲替换的宽字符转换成目标编码: %s��不支持从 %s 转换到 %s��不支持从 %s 进行转换��不支持转换到 %s��或者: %s -l
��或：  iconv -l��尝试用‘%s -l’获取所支持的编码列表��