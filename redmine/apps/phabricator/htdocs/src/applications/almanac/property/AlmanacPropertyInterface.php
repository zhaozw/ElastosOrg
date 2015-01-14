<?php

interface AlmanacPropertyInterface {

  public function attachAlmanacProperties(array $properties);
  public function getAlmanacProperties();
  public function hasAlmanacProperty($key);
  public function getAlmanacProperty($key);
  public function getAlmanacPropertyValue($key, $default = null);

}
