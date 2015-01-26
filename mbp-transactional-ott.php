<?php
/**
 * mbc-transactional-ott
 *
 * A consummer application for the Message Broker system that processes message
 * requests to OTT (Over The Top) message platforms.
 *
 * Supports:
 *   - WhatsApp transactionals: https://www.whatsapp.com
 */

 
$bla = FALSE;
if ($bla) {
  $bla = TRUE;
}

  use DoSomething\MBStatTracker\StatHat;
  date_default_timezone_set('America/New_York');
  
  // Load configuration settings
  require_once('mb-wu-config.inc');

  // Load Composer autoload magic
  require_once __DIR__ . '/vendor/autoload.php';

  // Example connection based on
  // https://github.com/mgp25/WhatsAPI-Official/blob/master/examples/exampleFunctional.php

  $options = getopt("d::", array("debug::"));
  $debug = (array_key_exists("debug", $options) || array_key_exists("d", $options)) ? true : false;

  // Telephone number including the country code without '+' or '00'.
  $username = getenv("MB-WA-USERNAME");

  // A server generated Password you received from WhatsApp. This can NOT be manually created
  $password = getenv("MB-WA-PASSWORD");

  // Obtained during registration with this API or using MissVenom
  // (https://github.com/shirioko/MissVenom) to sniff from your phone.
  $identity = getenv("MB-WA-CODE");;

  // This is the username (or nickname) displayed by WhatsApp clients.
  $nickname = "DoSomething.org";

  // // Destination telephone number including the country code without '+' or '00'.
  $targets = explode(',', getenv("MB-WA-TARGET-USERNAMES"));

  /**
   * Connection process
   */
   echo "[] Logging in as '$nickname' ($username)". PHP_EOL;

  // Create the whatsapp object and setup a connection.
  $w = new WhatsProt($username, $identity, $nickname, $debug);
  $w->connect();

  // Now loginWithPassword function sends Nickname and (Available) Presence
  $w->loginWithPassword($password);
  echo '[*] Connected to WhatsApp', PHP_EOL . PHP_EOL;
  
  // Send message
  $line = 'Howdy from DoSomething.org test WhatsApp Message Broker producer. Consider signing up for a campaign by replying with "Signup WA".';
  foreach ($targets as $target) {
    $w->sendMessage($target , $line);
    echo "[*] Welcome message sent to WhatsApp account: $target", PHP_EOL;
  }

  // infinate loop - wait for responce from WhatsApp accounts
  while (1) {

    $w->pollMessage();
    $msgs = $w->getMessages();
    foreach ($msgs as $m) {

      // process inbound messages
      $message_id = $m->getAttribute("id");
      $from = $m->getAttribute("from");
      $from_presentation = str_replace("@s.whatsapp.net", '', $from);
      $type = $m->getAttribute("type");
      $time = date("l - d M Y G:i e", $m->getAttribute("t"));
      $notify = $m->getAttribute("notify");
      $body = $m->getChild("body")->getData();
      
      echo "$from_presentation ($notify) sent a $type message at $time", PHP_EOL;
      echo $body, PHP_EOL . PHP_EOL;

      if (strtolower($body) == 'signup wa' || strtolower($body) == 'wa') {
        $w->sendMessage($from , "**Bam** Thanks $notify you're signed up for the WhatsApp Campaign!");
        $w->sendMessageImage($from, "http://fedup.dosomething.org/assets/logo-ds-08d05f4bfc918613c69af7f9f537c620.png");
        $w->sendMessageAudio($from, 'http://www.myinstants.com/media/sounds/bazinga.swf.mp3');
      }
      else {
        $w->sendMessage($from , "**Huh?!?** I'm not sure what you're talking about?!?");
        $w->sendMessageImage($from, "https://s-media-cache-ak0.pinimg.com/236x/97/10/a1/9710a16587d86f0f9648b9279e3c9e68.jpg");
        $w->sendMessageAudio($from, 'http://www.richmolnar.com/Sounds/Apu%20-%20This%20is%20the%20peak%20hour.wav');
      }

    }

  }
  