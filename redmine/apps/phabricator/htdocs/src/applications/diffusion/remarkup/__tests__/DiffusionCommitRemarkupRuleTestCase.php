<?php

final class DiffusionCommitRemarkupRuleTestCase extends PhabricatorTestCase {

  public function testProjectObjectRemarkup() {
    $cases = array(
      '{rP12f3f6d3a9ef9c7731051815846810cb3c4cd248}' => array(
        'embed' => array(
          array(
            'offset' => 1,
            'id' => 'rP12f3f6d3a9ef9c7731051815846810cb3c4cd248',
          ),
        ),
        'ref' => array(
          array(
            'offset' => 1,
            'id' => 'rP12f3f6d3a9ef9c7731051815846810cb3c4cd248',
          ),
        ),
      ),
      '{rP1234, key=value}' => array(
        'embed' => array(
          array(
            'offset' => 1,
            'id' => 'rP1234',
            'tail' => ', key=value',
          ),
        ),
        'ref' => array(
          array(
            'offset' => 1,
            'id' => 'rP1234',
          ),
        ),
      ),
      '{rP1234 key=value}' => array(
        'embed' => array(
          array(
            'offset' => 1,
            'id' => 'rP1234',
            'tail' => ' key=value',
          ),
        ),
        'ref' => array(
          array(
            'offset' => 1,
            'id' => 'rP1234',
          ),
        ),
      ),
    );

    foreach ($cases as $input => $expect) {
      $rule = new DiffusionCommitRemarkupRule();
      $matches = $rule->extractReferences($input);
      $this->assertEqual($expect, $matches, $input);
    }
  }

}
