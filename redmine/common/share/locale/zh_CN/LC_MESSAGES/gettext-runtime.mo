Þ������������������������)������������������°����B������±����!����ô����ð��������9����������M������A��������������÷����������e����������:������ú��������5	����Þ����O
��������.����������Á
����������Ö
����*������ç
����1����������&������D����������k����������z����"����������9������²����I������ì����������6����������Ô����������ä����������ý�������������������� ����������/����������B����Â����N����:����������#����L����×����p����7������H����D����������������Å��������Ò����R������Õ����.������(����
����W����¶����b����O��������������i����������}����0����������������¿����.������Ü������������������������#������0����;������T����K����������������Ü����������m������������������������������¤����������´����������Á����	������Î����������
������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������������	��������������������������������
��������������  -V, --version               output version information and exit
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
��Bruno Haible��Copyright (C) %s Free Software Foundation, Inc.
License GPLv3+: GNU GPL version 3 or later <http://gnu.org/licenses/gpl.html>
This is free software: you are free to change and redistribute it.
There is NO WARRANTY, to the extent permitted by law.
��Display native language translation of a textual message whose grammatical
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
��error while reading "%s"��memory exhausted��missing arguments��standard input��too many arguments��write error��Project-Id-Version: gettext-runtime 0.16.2-pre5
Report-Msgid-Bugs-To: bug-gnu-gettext@gnu.org
POT-Creation-Date: 2010-06-04 01:57+0200
PO-Revision-Date: 2008-08-09 20:25+0800
Last-Translator: LI Daobing <lidaobing@gmail.com>
Language-Team: Chinese (simplified) <translation-team-zh-cn@lists.sourceforge.net>
Language: zh_CN
MIME-Version: 1.0
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=1; plural=0;
��  -V, --version               输出版本信息并退出
��  -d, --domain=文本域       由<文本域>读取翻译后的消息。
  -e                        允许展开某些转义字符
  -E                        (为了兼容性存在的选项，不会造成任何影响)
  -h, --help                显示此段说明并退出
  -V, --version             显示版本信息并退出
  [文本域]                  由<文本域>读取翻译消息
  MSGID 复数-MSGID          翻译 MSGID (单数) / 复数-MSGID (复数)
  数字                      根据<数字>来选择单数或复数型态
��  -d, --domain=文本域       由<文本域>读取翻译后的消息
  -e                        允许展开某些转义字符
  -E                        (为了兼容性存在的选项，不会造成任何影响)
  -h, --help                显示此段说明消息并退出
  -n                        禁用尾随的换行符
  -V, --version             显示版本信息并退出
  [文本域] MSGID            由<文本域>读取相应于 MSGID 的翻译消息
��  -h, --help                  显示此帮助并退出
��  -v, --variables             输出 SHELL格式 中出现的变量
��Bruno Haible��版权所有 (C) %s Free Software Foundation, Inc.
授权协议 GPLv3+: GNU GPL 版本 3 或之后版本 <http://gnu.org/licenses/gpl.html>
这是自由软件: 你有修改和再发布的自由。
本软件在法律允许的范围内不提供任何担保。
��显示某原文消息的本地语言翻译，而翻译的语法和数字有关。
��显示某原文消息的本地语言翻译。
��假如没有指定<文本域>参数，程序会根据 TEXTDOMAIN 环境变量来设定文本域。
假如消息一览表文件不在正常的目录下，可以用环境变量 TEXTDOMAINDIR 指定
消息一览表文件所在的位置。
标准的搜索目录为：%s
��假如没有指定<文本域>参数，程序会根据 TEXTDOMAIN 环境变量来设定<文本域>。
假如消息一览表文件不在正常的目录下，可以用环境变量 TEXTDOMAINDIR 指定
消息一览表文件所在的位置。
使用选项“-s”时此程序和“echo”命令类似。但它不是直接把参数复制至标准
输出，而是寻找消息一览表文件里翻译过的消息。
标准的搜索目录为：%s
��在普通操作模式下，会将标准输入复制到标准输出中，而以 $VARIABLE 或 ${VARIABLE}
格式给出的环境变量引用会被替换成相应的值。如果给出了 SHELL格式，则只有在该格式
中引用的环境变量会被替换；否则，标准输入中出现的全部环境变量引用都将被替换。
��信息性输出：
��操作模式：
��将错误报告至 <bug-gnu-gettext@gnu.org>。
��替换环境变量的值。
��试试“%s --help”来获取更多信息。
��Ulrich Drepper��未知的系统错误��用法：%s [选项] [SHELL格式]
��用法：%s [选项] [文本域] MSGID 复数-MSGID 数字
��用法：%s [选项] [[文本域] MSGID]
或：  %s [选项] -s [MSGID]...
��若使用了 --variables，则会忽略标准输入，而输出由 SHELL格式 引用的环境变量组成，
每行出现一个环境变量。
��由 %s 编写。
��读取“%s”时出错��内存耗尽��缺少了参数��标准输入��参数过多��写错误��