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
����­����W
����F����������I������L����k����������O����������l������R����F������¿����i����������U������p����[������Æ����g������"��������������W����������N������å����K������4����9����������`������º����W����������E������s����������¹����������Ë����%������Ú����I������������������J����U������Y����
������¯����������½����/������Ï����/������ÿ����/������/����y������_����P������Ù����e������*����`����������������ñ����L������������U������M����O������£����V������ó����^������J����=������©����6������ç����3����������������R����������g����Q������~��������������������������$������������
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
��or:    iconv -l��try '%s -l' to get the list of supported encodings��Project-Id-Version: GNU libiconv 1.12
Report-Msgid-Bugs-To: bug-gnu-libiconv@gnu.org
POT-Creation-Date: 2011-08-07 15:24+0200
PO-Revision-Date: 2010-08-24 18:49+0900
Last-Translator: Yasuaki Taniguchi <yasuakit@gmail.com>
Language-Team: Japanese <translation-team-ja@lists.sourceforge.net>
Language: ja
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=1; plural=0;
��  --byte-subst=書式文字列   変換できないバイトの置換
��  --help                      このヘルプを表示して終了する
��  --unicode-subst=書式文字列
                              変換できない Unicode 文字の置換
��  --version                   バージョン情報を表示して終了する
��  --widechar-subst=書式文字列
                              変換できないワイド文字の置換
��  -c                          変換できない文字を破棄する
��  -f 文字コード, --from-code=文字コード
                              入力の文字コード
��  -l, --list                  サポートする文字コード一覧を表示する
��  -s, --silent                変換問題のエラーメッセージ出力を抑止する
��  -t 文字コード, --to-code=文字コード
                              出力の文字コード
��%s��引数 %s : ここではサイズ指定を行う書式指定を使用できません。��引数 %s : ここでは可変精度の書式指定を使用できません。��引数 %s : ここでは可変幅の書式指定を使用できません。��引数 %s : 文字 '%c' は無効な変換指定です。��引数 %s : 書式指定を終端させる文字は有効な変換指定ではありません。��引数 %s : 書式文字列が2つ以上の引数を消費します: %u 個の引数。��引数 %s : 書式指定の中間で文字列が終了しました。��%s: I/O エラー��%s:%u行:%u列��%s:%u行:%u列: 変換できません��%s:%u行:%u列: 不完全な文字またはシフトシーケンスです��(標準入力)��ある文字コードから別の文字コードへテキストを変換します。
��I/O エラー��情報の出力:
��変換の問題を制御するオプション:
��エラー出力を制御するオプション:
��入出力形式を制御するオプション:
��バグレポート先 <bug-gnu-libiconv@gnu.org>
翻訳問題レポート先 <translation-team-ja@lists.sourceforge.net>
��さらに情報が必要な場合は `%s --help' と入力してください。
��使用法: %s [オプション...] [-f 文字コード] [-t 文字コード] [入力ファイル...]
��使用法: iconv [-c] [-s] [-f 変換元コード] [-t 変換先コード] [ファイル名 ...]��作成者 %s.
��バイトから Unicode へ置換をおこなう変換ができません: %s��バイトから変換先文字コードへ置換を行う変換ができません: %s��バイトからワイド文字への置換を行う変換ができません: %s��Unicodeから変換先文字コードへの置換を行う変換ができません: %s��ワイド文字から変換先文字コードへの置換を行う変換ができません: %s��%s から %s への変換はサポートされていません��%s からの変換はサポートされていません��%s への変換はサポートされていません��または:    %s -l
��または:    iconv -l��サポートする文字コード一覧は '%s -l' と入力してください。��