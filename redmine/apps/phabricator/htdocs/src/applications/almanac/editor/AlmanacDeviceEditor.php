<?php

final class AlmanacDeviceEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhabricatorAlmanacApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Almanac Device');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = AlmanacDeviceTransaction::TYPE_NAME;
    $types[] = AlmanacDeviceTransaction::TYPE_INTERFACE;
    $types[] = PhabricatorTransactions::TYPE_VIEW_POLICY;
    $types[] = PhabricatorTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {
    switch ($xaction->getTransactionType()) {
      case AlmanacDeviceTransaction::TYPE_NAME:
        return $object->getName();
    }

    return parent::getCustomTransactionOldValue($object, $xaction);
  }

  protected function getCustomTransactionNewValue(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case AlmanacDeviceTransaction::TYPE_NAME:
      case AlmanacDeviceTransaction::TYPE_INTERFACE:
        return $xaction->getNewValue();
    }

    return parent::getCustomTransactionNewValue($object, $xaction);
  }

  protected function applyCustomInternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case AlmanacDeviceTransaction::TYPE_NAME:
        $object->setName($xaction->getNewValue());
        return;
      case AlmanacDeviceTransaction::TYPE_INTERFACE:
      case PhabricatorTransactions::TYPE_VIEW_POLICY:
      case PhabricatorTransactions::TYPE_EDIT_POLICY:
      case PhabricatorTransactions::TYPE_EDGE:
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhabricatorLiskDAO $object,
    PhabricatorApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case AlmanacDeviceTransaction::TYPE_NAME:
      case PhabricatorTransactions::TYPE_VIEW_POLICY:
      case PhabricatorTransactions::TYPE_EDIT_POLICY:
      case PhabricatorTransactions::TYPE_EDGE:
        return;
      case AlmanacDeviceTransaction::TYPE_INTERFACE:
        $old = $xaction->getOldValue();
        if ($old) {
          $interface = id(new AlmanacInterfaceQuery())
            ->setViewer($this->requireActor())
            ->withIDs(array($old['id']))
            ->executeOne();
          if (!$interface) {
            throw new Exception(pht('Unable to load interface!'));
          }
        } else {
          $interface = AlmanacInterface::initializeNewInterface()
            ->setDevicePHID($object->getPHID());
        }

        $new = $xaction->getNewValue();
        if ($new) {
          $interface
            ->setNetworkPHID($new['networkPHID'])
            ->setAddress($new['address'])
            ->setPort((int)$new['port'])
            ->save();
        } else {
          $interface->delete();
        }
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }

  protected function validateTransaction(
    PhabricatorLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);

    switch ($type) {
      case AlmanacDeviceTransaction::TYPE_NAME:
        $missing = $this->validateIsEmptyTextField(
          $object->getName(),
          $xactions);

        if ($missing) {
          $error = new PhabricatorApplicationTransactionValidationError(
            $type,
            pht('Required'),
            pht('Device name is required.'),
            nonempty(last($xactions), null));

          $error->setIsMissingFieldError(true);
          $errors[] = $error;
        } else {
          foreach ($xactions as $xaction) {
            $message = null;
            $name = $xaction->getNewValue();

            try {
              AlmanacNames::validateServiceOrDeviceName($name);
            } catch (Exception $ex) {
              $message = $ex->getMessage();
            }

            if ($message !== null) {
              $error = new PhabricatorApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                $message,
                $xaction);
              $errors[] = $error;
            }
          }
        }

        if ($xactions) {
          $duplicate = id(new AlmanacDeviceQuery())
            ->setViewer(PhabricatorUser::getOmnipotentUser())
            ->withNames(array(last($xactions)->getNewValue()))
            ->executeOne();
          if ($duplicate && ($duplicate->getID() != $object->getID())) {
            $error = new PhabricatorApplicationTransactionValidationError(
              $type,
              pht('Not Unique'),
              pht('Almanac devices must have unique names.'),
              last($xactions));
            $errors[] = $error;
          }
        }

        break;
      case AlmanacDeviceTransaction::TYPE_INTERFACE:
        // We want to make sure that all the affected networks are visible to
        // the actor, any edited interfaces exist, and that the actual address
        // components are valid.

        $network_phids = array();
        foreach ($xactions as $xaction) {
          $old = $xaction->getOldValue();
          $new = $xaction->getNewValue();
          if ($old) {
            $network_phids[] = $old['networkPHID'];
          }
          if ($new) {
            $network_phids[] = $new['networkPHID'];

            $address = $new['address'];
            if (!strlen($address)) {
              $error = new PhabricatorApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                pht('Interfaces must have an address.'),
                $xaction);
              $errors[] = $error;
            } else {
              // TODO: Validate addresses, but IPv6 addresses are not trival
              // to validate.
            }

            $port = $new['port'];
            if (!strlen($port)) {
              $error = new PhabricatorApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                pht('Interfaces must have a port.'),
                $xaction);
              $errors[] = $error;
            } else if ((int)$port < 1 || (int)$port > 65535) {
              $error = new PhabricatorApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                pht(
                  'Port numbers must be between 1 and 65535, inclusive.'),
                $xaction);
              $errors[] = $error;
            }
          }
        }

        if ($network_phids) {
          $networks = id(new AlmanacNetworkQuery())
            ->setViewer($this->requireActor())
            ->withPHIDs($network_phids)
            ->execute();
          $networks = mpull($networks, null, 'getPHID');
        } else {
          $networks = array();
        }

        $addresses = array();
        foreach ($xactions as $xaction) {
          $old = $xaction->getOldValue();
          if ($old) {
            $network = idx($networks, $old['networkPHID']);
            if (!$network) {
              $error = new PhabricatorApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                pht(
                  'You can not edit an interface which belongs to a '.
                  'nonexistent or restricted network.'),
                $xaction);
              $errors[] = $error;
            }

            $addresses[] = $old['id'];
          }

          $new = $xaction->getNewValue();
          if ($new) {
            $network = idx($networks, $new['networkPHID']);
            if (!$network) {
              $error = new PhabricatorApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                pht(
                  'You can not add an interface on a nonexistent or '.
                  'restricted network.'),
                $xaction);
              $errors[] = $error;
            }
          }
        }

        if ($addresses) {
          $interfaces = id(new AlmanacInterfaceQuery())
            ->setViewer($this->requireActor())
            ->withDevicePHIDs(array($object->getPHID()))
            ->withIDs($addresses)
            ->execute();
          $interfaces = mpull($interfaces, null, 'getID');
        } else {
          $interfaces = array();
        }

        foreach ($xactions as $xaction) {
          $old = $xaction->getOldValue();
          if ($old) {
            $interface = idx($interfaces, $old['id']);
            if (!$interface) {
              $error = new PhabricatorApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                pht('You can not edit an invalid or restricted interface.'),
                $xaction);
              $errors[] = $error;
            }
          }
        }
      break;
    }

    return $errors;
  }



}
