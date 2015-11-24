Automated Secret Santa Enabling System
=====

A.S.S.E.S. is a PHP based Secret Santa Organizing System that sends the messages out via a text message using an Android phone. More complex than some email based alternatives, but much easier to use for the particpants of the Secret Santa.

Using the format in xmasexchange.php, setup the $santas array to include the name and cellphone number of those participating and optionally any people that should not be selected as a target for a Santa.

Requirements: PHP5 (test on PHP5.5)

## Twilio

To use Twilio to send SMSs, modify the file to set $twilio to true and then set your Twilio creditentals.

## Plivo

To use Plivo to send SMSs, modify the file to set $plivo to true and then set your Plivo creditentals.

## Local

You will then need the app SMS Gateway for Android (https://play.google.com/store/apps/details?id=eu.apksoft.android.smsgateway&hl=en) or something similar that you can modify the code to work with it's API. This sends out everyone's recipient to then via a SMS message. Be warned your phone may leave a copy of texts sent on your phone, my stock Android did, so I used another app to remove before I could "accidentally" see who had my name.


There may be easier ways to do this, but who cares.