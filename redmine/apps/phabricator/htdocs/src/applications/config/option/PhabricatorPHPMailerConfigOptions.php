<?php

final class PhabricatorPHPMailerConfigOptions
  extends PhabricatorApplicationConfigOptions {

  public function getName() {
    return pht('PHPMailer');
  }

  public function getDescription() {
    return pht('Configure PHPMailer.');
  }

  public function getOptions() {
    return array(
      $this->newOption('phpmailer.mailer', 'string', 'smtp')
        ->setLocked(true)
        ->setSummary(pht('Configure mailer used by PHPMailer.'))
        ->setDescription(
          pht(
            "If you're using PHPMailer to send email, provide the mailer and ".
            "options here. PHPMailer is much more enormous than ".
            "PHPMailerLite, and provides more mailers and greater enormity. ".
            "You need it when you want to use SMTP instead of sendmail as the ".
            "mailer.")),
      $this->newOption('phpmailer.smtp-host', 'string', null)
        ->setLocked(true)
        ->setDescription(pht('Host for SMTP.')),
      $this->newOption('phpmailer.smtp-port', 'int', 25)
        ->setLocked(true)
        ->setDescription(pht('Port for SMTP.')),
      // TODO: Implement "enum"? Valid values are empty, 'tls', or 'ssl'.
      $this->newOption('phpmailer.smtp-protocol', 'string', null)
        ->setLocked(true)
        ->setSummary(pht('Configure TLS or SSL for SMTP.'))
        ->setDescription(
          pht(
            "Using PHPMailer with SMTP, you can set this to one of 'tls' or ".
            "'ssl' to use TLS or SSL, respectively. Leave it blank for ".
            "vanilla SMTP. If you're sending via Gmail, set it to 'ssl'.")),
      $this->newOption('phpmailer.smtp-user', 'string', null)
        ->setLocked(true)
        ->setDescription(pht('Username for SMTP.')),
      $this->newOption('phpmailer.smtp-password', 'string', null)
        ->setMasked(true)
        ->setDescription(pht('Password for SMTP.')),
      $this->newOption('phpmailer.smtp-encoding', 'string', '8bit')
        ->setSummary(pht('Configure how mail is encoded.'))
        ->setDescription(
          pht(
            "Mail is normally encoded in `8bit`, which works correctly with ".
            "most MTAs. However, some MTAs do not work well with this ".
            "encoding. If you're having trouble with mail being mangled or ".
            "arriving with too many or too few newlines, you may try ".
            "adjusting this setting.\n\n".
            "Supported values are `8bit` (default), `quoted-printable`, ".
            "`7bit`, `binary` and `base64`.\n\n".
            "The settings in the table below may work well.\n\n".
            "| MTA | Setting | Notes\n".
            "|-----|---------|------\n".
            "| SendGrid via SMTP | `quoted-printable` | Double newlines under ".
            "`8bit`.\n".
            "| All Other MTAs | `8bit` | Default setting.")),
    );
  }

}
