
class files {
      file {"/tmp/tmp1.txt":
         name =>"/tmp/tmp1.txt",
         source => "puppet://songzi/files/tmp1.txt"
         group => root,
         owner => root,
         mode => 600
          }
}
