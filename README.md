Automated Secret Santa Enabling System
=====

Using the format in xmasexchange.php, setup the $santas array to include the name and cellphone number of those particapating and optionally any people that should not be selected as a target for a Santa.

You will then need the app SMS Gateway for Android (https://play.google.com/store/apps/details?id=eu.apksoft.android.smsgateway&hl=en) or something similar that you can modifiy the code to work with it's API. This sends out everyone's recipent to then via a SMS message. Bewarned your phone may leave a copy of texts sent on your phone, my stock Android did, so I used another app to remove before I could "accidently" see who had my name.

Their may be easier ways to do this, but who cares.

Requirements: PHP5 (test on PHP5.4)