<?php

final class PhabricatorSMSConfigOptions
  extends PhabricatorApplicationConfigOptions {

  public function getName() {
    return pht('SMS');
  }

  public function getDescription() {
    return pht('Configure SMS.');
  }

  public function getOptions() {
    $adapter_description = $this->deformat(pht(<<<EODOC
Adapter class to use to transmit SMS to an external provider. A given external
provider will most likely need more configuration which will most likely
require registration and payment for the service.
EODOC
  ));

    return array(
      $this->newOption(
        'sms.default-sender',
        'string',
        null)
        ->setDescription(pht('Default "from" number.'))
        ->addExample('8675309', 'Jenny still has this number')
        ->addExample('18005555555', 'Maybe not a real number'),
      $this->newOption(
        'sms.default-adapter',
        'class',
        null)
        ->setBaseClass('PhabricatorSMSImplementationAdapter')
        ->setSummary(pht('Control how sms is sent.'))
        ->setDescription($adapter_description),
      $this->newOption(
        'twilio.account-sid',
        'string',
        null)
        ->setDescription(pht('Account ID on Twilio service.'))
        ->setLocked(true)
        ->addExample('gf5kzccfn2sfknpnadvz7kokv6nz5v', pht('30 characters')),
      $this->newOption(
        'twilio.auth-token',
        'string',
        null)
        ->setDescription(pht('Authorization token from Twilio service.'))
        ->setLocked(true)
        ->setHidden(true)
        ->addExample('f3jsi4i67wiwt6w54hf2zwvy3fjf5h', pht('30 characters')),
    );
  }

}
