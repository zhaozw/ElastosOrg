Þ��������������������ü������%������Ü������������p����B������q����!����´����ð����Ö����9������Ç����M����������e������O����:������µ��������ð����Þ����
	��������é
����������|��������������*������¢����1������Í����&������ÿ����������&
����"������;
����9������^
����I������
����������â
������������������������������©����������º����������Ì����������Û����������î��������ú����<��������������Ð����A����a����E������£����������é����j������~����=������é��������'��������B����é����Y����������C����������X����2������l����3����������)������Ó����$������ý����A������"����E������d����]������ª����´����������������½����������Î����������é����������ù������������������������������2������������������������������������������������������������������������������������������������������������������������������������	������������
��������������
����������������������������������������������������������������������������������������  -V, --version               output version information and exit
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
��Unknown system error��Usage: %s [OPTION] [SHELL-FORMAT]
��Usage: %s [OPTION] [TEXTDOMAIN] MSGID MSGID-PLURAL COUNT
��Usage: %s [OPTION] [[TEXTDOMAIN] MSGID]
or:    %s [OPTION] -s [MSGID]...
��When --variables is used, standard input is ignored, and the output consists
of the environment variables that are referenced in SHELL-FORMAT, one per line.
��Written by %s.
��error while reading "%s"��memory exhausted��missing arguments��standard input��too many arguments��write error��Project-Id-Version: GNU gettext-runtime 0.14.5
Report-Msgid-Bugs-To: bug-gnu-gettext@gnu.org
POT-Creation-Date: 2010-06-04 01:57+0200
PO-Revision-Date: 2005-06-22 19:36-0600
Last-Translator: Max de Mendizábal <max@upn.mx>
Language-Team: Spanish <es@li.org>
Language: es
MIME-Version: 1.0
Content-Type: text/plain; charset=ISO-8859-1
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n != 1);
��  -V, --version               muestra la versión y finaliza
��  -d, --domain=DOMINIOTEXTO obtiene los mensajes traducidos de DOMINIOTEXTO
  -e                        habilita la expansión de algunas secuencias
                            de escape
  -E                        (existe por compatibilidad, no tiene efecto)
  -h, --help                muestra este texto de ayuda y finaliza
  -V, --version             informa de la versión y finaliza
  [DOMINIOTEXTO]            obtiene el mensaje traducido del DOMINIOTEXTO
  MSGID MSGID-PLURAL        traduce el MSGID (singular) / MSGID-PLURAL (plural)
  CUENTA                    selecciona la forma singular/plural con base
                            en este valor
��  -d, --domain=DOMINIOTEXTO obtiene los mensajes traducidos de DOMINIOTEXTO
  -e                        habilita la expansión de algunas secuencias
                            de escape
  -E                        (existe por compatibilidad, no tiene efecto)
  -h, --help                muestra este texto de ayuda y finaliza
  -n                        suprime el carácter de nueva línea
  -V, --version             informa de la versión y finaliza
  [DOMINIOTEXTO] MSGID      obtiene el mensaje traducido correspondiente
                            al MSGID del DOMINIOTEXTO
��  -h, --help                  muestra este texto de ayuda y finaliza
�� -v, --variables                    muestra las variables que aparecen en el 
                                    FORMATO-DE-INTÉRPRETE-DE-COMANDOS
��Muestra la traducción del idioma nativo de un mensaje textual cuya forma
gramatical depende de un número.
��Mostrar la traducción a idioma nativo de un mensaje textual.
��Si no se escribe el parámetro DOMINIOTEXTO, el dominio se determina por
la variable de entorno TEXTDOMAIN. Si el catálogo de mensajes no está en
el directorio por defecto, con la variable de entorno TEXTDOMAINDIR se
puede especificar otro lugar.
Directorio de búsqueda estándar: %s
��Si no se escribe el parámetro DOMINIOTEXTO, el dominio se determina por
la variable de entorno TEXTDOMAIN. Si el catálogo de mensajes no está en
el directorio por defecto, con la variable de entorno TEXTDOMAINDIR se
puede especificar otro lugar.
Cuando se utiliza con la opción -s, el programa se comporta como la
instrucción `echo', pero no se reduce a hacer simplemente una copia en
la salida estándar (stdout) sino que además traduce los mensajes que
encuentre dentro del catálogo seleccionado.
Directorio de búsqueda estándar: %s
��En modo de operación normal, se copia la entrada estándar a la salida
estándar con las referencias a las variables de entorno de la forma
$VARIABLE o ${VARIABLE} sustituidas por sus valores
correspondientes. Si se da un FORMATO-DEL-INTÉRPRETE-DE-COMANDOS, sólo
aquellas variables de entorno que estén referenciadas en el
FORMATO-DEL-INTÉRPRETE-DE-COMANDOS son sustituidas; de otra forma,
todas las referencias a las variables de entorno existentes en la
entrada estándar, son sustituidas.
��Salida informativa:
��Modo de operación:
��Comunicar los `bugs' a <bug-gnu-gettext@gnu.org>.
��Sustituye los valores de las variables de entorno.
��Pruebe `%s --help' para más información.
��Hay un error del sistema desconocido��Modo de empleo: %s [OPCIÓN] [FORMATO-DEL-INTÉRPRETE-DE-COMANDOS]
��Modo de empleo: %s [OPCIÓN] [DOMINIODETEXTO] MSGID MSG-PLURAL CUENTA
��Modo de empleo: %s [OPCIÓN] [[DOMINIOTEXTO] MSGID]
             o: %s [OPCIÓN] -s [MSGID]...
��Cuando se utiliza --variables, se ignora la entrada estándar, y la salida
contiene las variables de entorno que referidas en el FORMATO-DEL-
INTÉRPRETE-DE-COMANDOS, una por línea.
��Escrito por %s.
��error mientras se lee "%s"��Memoria agotada��faltan argumentos��entrada estándar��demasiados argumentos��error de escritura��