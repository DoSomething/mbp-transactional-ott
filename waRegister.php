<?php
/**
 *
 */

  // Load configuration settings
  require_once('mb-wu-config.inc');

  // Load Composer autoload magic
  require_once __DIR__ . '/vendor/autoload.php';

$bla = FALSE;
if ($bla) {
  $bla = TRUE;
}

  // Username: Phone number with country code but without '+' or '00', ie: 34123456789
  $username = getenv("MB-WA-USERNAME");
  
  // Identity: The name of the file where is going to be saved the identity.
  // This string is used for retrieving a new password.
  $identity = 'identity';
  
  // Nickname: The name is going to appear in notifications.
  $nickname = getenv("MB-WA-NICKNAME");
  
  // Debug: You can see nodes and more information if you set it to true,
  $debug = true;
  
  // Create a instance of WhastPort.
  $w = new WhatsProt($username, $identity, $nickname, $debug);

  // "SMS" or "voice" call
  $option = getenv("MB-WA-OPTION");
  
  // Optional based on values found in:
  // https://github.com/mgp25/WhatsAPI-Official/blob/master/src/networkinfo.csv
  $carrier = getenv("MB-WA-CARRIER");
  
  // Two step registration process, submit code from step one to get password
  $code = getenv("MB-WA-CODE");
  if ($code == '') {
    try {
      $w->codeRequest($option, $carrier);
    }
    catch(Exception $e) {
      echo $e->getMessage();
      exit(0);
    }
  }
  else {
    try {
      $result = $w->codeRegister(trim($code));
      echo "\nYour password is: ".$result->pw."\n";
    }
    catch(Exception $e) {
      echo $e->getMessage();
      exit(0);
    }
  }
