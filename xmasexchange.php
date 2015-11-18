<?php

// Customization.

$twilio = true;
$twilioAccountSid = '123';
$twilioAuthToken = '123';
$twilioFrom = '123456789';
$cellIP = '192.168.0.3';
$santas = array(
	'name1' => array(
		'cell' => 123456789,
		'no' => array( 'name2' )
	),
	'name2' => array(
		'cell' => 123456789,
		'no' => array()
	),
	'name3' => array(
		'cell' => 123456789,
		'no' => array( 'name2', 'name1')
	),
	'name4' => array(
		'cell' => 123456789,
		'no' => array()
	),
	'name5' => array(
		'cell' => 123456789,
		'no' => array( 'name3' )
	),
);


// End of customization.

header('Content-type: text/plain');

function select_rand( $array, $santa, $nos ) {
	global $selected;
	$name = array_rand( $array );
	if( ( isset( $selected[$name] ) && $selected[$name] == $santa ) || $name == $santa || in_array( $name, $nos ) || in_array( $name, $selected ) ) {
		$name = select_rand( $array, $santa, $nos );
	}
	return $name;
}

function shuffle_assoc(&$array) {
	$keys = array_keys($array);

	shuffle($keys);

	foreach($keys as $key) {
		$new[$key] = $array[$key];
	}

	$array = $new;

	return true;
}

echo 'Randomizing: ';

$selected = array();

shuffle_assoc( $santas );

foreach( $santas as $santa => $values ) {
	$name = select_rand( $santas, $santa, $values['no'] );
	$selected[$santa] = $name;
}

echo 'done!'."\n";

echo 'Preparing to send:'."\n";

set_time_limit( 0 );

if( $twilio === true) {
	require_once __DIR__ . '/twilio-php/Services/Twilio.php';
	$twilio = new Services_Twilio( $twilioAccountSid, $twilioAuthToken );
}

foreach( $selected as $giver => $getter ) {
	$text = 'Salutations '. $giver . ', for secret Santa you got ' . $getter . '! This is an automated message, no one else knows what name you have, so you cannot forget.';
	
	if( $twilio === true) {
		$sms = $twilio->account->messages->sendMessage( $twilioFrom, $number, $message );
	} else {
		$url = 'http://' . $cellIP . ':9090/sendsms?phone=' . $santas[$giver]['cell'] . '&text=' . urlencode($text) . '&password=';
		if( file_get_contents( $url ) ) {
			echo $giver . ' - good';
		} else {
			echo $giver . ' - FAILED!';
		}
	}
	echo "\n";
}

echo 'All done!';