<?php

$root = "/home/sun/phabricator/apps/phabricator/htdocs";
require_once $root.'/scripts/__init_script__.php';

function addUserForAdmin($arg1,$arg2,$arg3,$arg4) {
$username = $arg1;
$email = $arg2;
$realname = $arg3;
$admin = $arg4;
$admin = id(new PhabricatorUser())->loadOneWhere(
  'username = %s',
  $arg4);
if (!$admin) {
  throw new Exception(
    'Admin user must be the username of a valid Phabricator account, used '.
    'to send the new user a welcome email.');
}

$existing_user = id(new PhabricatorUser())->loadOneWhere(
  'username = %s',
  $username);
if ($existing_user) {
  throw new Exception(
    "There is already a user with the username '{$username}'!");
}

$existing_email = id(new PhabricatorUserEmail())->loadOneWhere(
  'address = %s',
  $email);
if ($existing_email) {
  throw new Exception(
    "There is already a user with the email '{$email}'!");
}

$user = new PhabricatorUser();
$user->setUsername($username);
$user->setRealname($realname);
$user->setIsApproved(1);

$email_object = id(new PhabricatorUserEmail())
  ->setAddress($email)
  ->setIsVerified(1);

id(new PhabricatorUserEditor())
  ->setActor($admin)
  ->createNewUser($user, $email_object);

$user->sendWelcomeEmail($admin);
}
