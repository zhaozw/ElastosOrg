Þ������������������������)������ü������������ ����B������¡����!����ä����ð��������9������÷����M������1��������������e����������:������ò��������-����Þ����G	��������&����������¹����������Î����*������ß����1������

����&������<
����������c
����������r
����"������
����9������ª
����I������ä
����������.����������Ì����������Ü����������õ������������������������������'����������:����Õ����F����G����������+����d����à��������H������q����T������º��������������M����������,������j����ê��������������������������������§����������Á����y������Ð����1������J����9������|����������¶����������Å����.������ß����;����������R������J����±����������������O����������j����������~�������������������� ����������¯����
������Ã����������	������������������
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
��error while reading "%s"��memory exhausted��missing arguments��standard input��too many arguments��write error��Project-Id-Version: gettext-runtime 0.15-pre5
Report-Msgid-Bugs-To: bug-gnu-gettext@gnu.org
POT-Creation-Date: 2010-06-04 01:57+0200
PO-Revision-Date: 2006-07-01 08:58+0300
Last-Translator: Nilgün Belma Bugüner <nilgun@buguner.name.tr>
Language-Team: Turkish <gnu-tr-u12a@lists.sourceforge.net>
Language: tr
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n != 1);
X-Generator: KBabel 1.9.1
��  -V, --version               Sürüm bilgilerini gösterir ve çıkar
��  -d, --domain=PAKET   ileti çevirileri yazılım PAKETinden alınır.
  -e                   öncelem dizilerinin kullanımı etkinleştirilir
  -E                   (uyumluluk için yoksayıldı)
  -h, --help           bu yardım iletisini gösterir ve çıkar
  -V, --version        sürüm bilgilerini gösterir ve çıkar
  [PAKET]              ileti çevirileri yazılım PAKETinden alınır.
  MSGID MSGID-PLURAL   MSGID (tekil) / MSGID-PLURAL (çoğul) çevrilir
  SAYI               SAYIya bağlı olarak tekil/çoğul iletilerden biri seçilir
��  -d, --domain=PAKET    ileti çevirisini PAKET.mo dosyasından alır.
  -e                    önceleme dizilerinin kullanımını etkinleştirir
  -E                    (uyumluluk için yoksayıldı)
  -h, --help            bu yardım iletisini gösterir ve çıkar
  -n                    izleyen satır sonu karakterini yoksayar
  -V, --version         sürüm bilgilerini gösterir ve çıkar
  [PAKET] MSGID         PAKET.mo dosyasından MSGID ile ilişkili çeviriyi alır
��  -h, --help                  bu yardım iletisini gösterir ve çıkar
��  -v, --variables             KABUK-BİÇİMİ içindeki değişkenleri çıktılar
��Bruno Haible��Bir sayıya bağlı olarak ilgili tekil/çoğul ileti çevirisini gösterir.
��Bir iletinin anadile çevirisini gösterir.
��PAKET parametresi verilmemişse paket ismi TEXTDOMAIN çevre değişkeninden
saptanır. İleti kataloğu olması gereken dizinde değilse bulunduğu dizin
TEXTDOMAINDIR çevre değişkeni ile belirtilebilir.
Standart arama dizini: %s
��PAKET parametresi verilmemişse .mo dosyasının adı TEXTDOMAIN çevre
değişkeninden saptanır. İleti kataloğu olması gereken dizinde değilse
bulunduğu dizin TEXTDOMAINDIR çevre değişkeni ile belirtilebilir.
Program -s seçeneği ile kullanıldığında `echo' komutu verilmiş gibi davranır.
Ancak iletiyi standart çıktıya basitçe kopyalamak yerine çevirisini verir.
Standart arama dizini: %s
��Normal işlem kipinde, $VARIABLE veya ${VARIABLE} biçimindeki ortam
değişkenleri, karşı düşen değerlerle değiştirilerek standart girdi
standart çıktıya kopyalanır. Eğer bir KABUK-BİÇİMİ verilmişse, yalnız
onun içindeki ortam değişkenleri için bu ikame işlemi yapılır.
Aksi takdirde, standart girdideki ortam değişkenlerinin tümüne bu ikame
işlemi uygulanır.
��Bilgilendirici çıktı:
��İşlem kipi:
��Yazılım hatalarını <bug-gnu-utils@gnu.org> adresine,
Çeviri hatalarını <gnu-tr@belgeler.org> adresine bildiriniz.
��Ortam değişkenlerinin değerleri ikame edilir.
��Daha fazla bilgilendirilmek için `%s --help' yazınız.
��Ulrich Drepper��Bilinmeyen sistem hatası��Kullanımı: %s [SEÇENEK] [KABUK-BİÇİMİ]
��Kullanımı: %s [SEÇENEK] [PAKET] MSGID MSGID-PLURAL SAYI
��Kullanımı: %s [SEÇENEK] [[PAKET] MSGID]
ya da:     %s [SEÇENEK] -s [MSGID]...
��--variables seçeneği kullanılmışsa, standart girdi görmezden gelinir ve
her satırda bir tane olmak üzere KABUK-BİÇİMİ içindeki ortam değişkenleri
çıktılanır.
��%s tarafından yazıldı.
��"%s" okunurken hata��bellek tükendi��argümanlar eksik��standart girdi��çok fazla argüman��yazma hatası��