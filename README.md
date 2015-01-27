# mbp-transactional-ott
A producer for transactions with Over The Top (ott) services:

###WhatsApp

####Setup
A configuration file (mb-wa-config.inc) is need to store connection settings:

**MB-WA-USERNAME**
  WhatsApp account to send messages from
**MB-WA-NICKNAME**
  The nickname of the sender account

**MB-WA-TARGET-USERNAMES**
  A comma separated list of target users to send a message to.
**MB-WA-NICKNAMES**
  A comma separated list of nicknames to associate with the target users.

**MB-WA-OPTION**
  (sms or voice) The method to receive the WhatsApp "code".
**MB-WA-CARRIER**
  Optional carrier to use when requesting the WhatsApp code.

**MB-WA-CODE**
  The "code" sent to the mobile phone (MB-WA-USERNAME) at the end of first step in registration process (waRegistration.php). Remove "-" from sent code.
**MB-WA-PASSWORD**
  Return value after doing waRegistration.php process

####Use
Start the script to send an invitation to signup to a mock campaign based on the `MB-WA-TARGET-USERNAMES` value.
```
$ php mbp-transactional-ott.php [-d | --debug]
```
The script will "block" and process any messages sent to the `MB-WA-USERNAME` account.