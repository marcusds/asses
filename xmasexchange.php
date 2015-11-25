<?php

// Customization.

// Twilio
$twilio = false;
$twilioAccountSid = '';
$twilioAuthToken = '';
$twilioFrom = ''; // Phone number

// Plivo
$plivo = false;
$plivoID = '';
$plivioToken = '';
$plivoFrom = ''; // Phone number

// Cell API
$cellIP = false; // IP address of phone, or false

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

function shuffle_assoc($list) {
	if(!is_array($list))
		return $list;

	$keys = array_keys($list);
	shuffle($keys);
	$random = array();
	foreach($keys as $key) {
		$random[$key] = $list[$key];
	}
	return $random;
}

$attempts = 0;
$success = false;

while($success === false && $attempts < 1000) {
	$tos = $santas;
	$selected = array();
	$attempts++;
	$success = true;
	foreach(array_keys($santas) as $from) {
		$tos = shuffle_assoc($tos);
		array_reverse($tos);
		foreach(array_keys($tos) as $to) {
			if($to !== $from && (!isset($select[$to]) || $select[$to] !== $from) && !in_array($to, $santas[$from]['no'])) {
				$selected[$from] = $to;
				unset($tos[$to]);
				break;
			}
		}
	}

	if(count($tos) > 0 || count($selected) !== count($santas)) {
		$success = false;
		continue;
	}
}

if($success === false) {
	die('Fail!');
}

echo 'Preparing to send:' . "\n";

set_time_limit(0);

if($twilio === true) {
	require_once __DIR__ . '/twilio-php-master/Services/Twilio.php';
	$twilio = new Services_Twilio($twilioAccountSid, $twilioAuthToken);
} else if ($plivo === true) {
	    require_once __DIR__ . '/plivo-php-master/plivo.php';

		$plivo = new RestAPI($plivoID, $plivoToken);
}

$salutations = [
	'Salutations',
	'Hola',
	'Greetings',
];

foreach($selected as $giver => $getter) {
	$text = $salutations[array_rand($salutations)] . ' ' . $giver . ', for gift exchange you got ' . $getter . '! No one else knows what name you have so you cannot forget. - Automated Secret Santa Enabling System';
	if($santas[$giver]['cell'] === false) {
		continue;
	}
	if($twilio !== false) {
		$sms = $twilio->account->messages->sendMessage($twilioFrom, $santas[$giver]['cell'], $text);
		if($sms->error_code) {
			echo 'ERROR';
			print_r($sms);
			die;
		}
	} else if($plivo !== false) {
		$response = $plivo->send_message(array(
			'src' => $plivoFrom,
			'dst' => $santas[$giver]['cell'],
			'text' => $text,
			'method' => 'POST'
		));
	} else if($cellIP !== false) {
		$url = 'http://' . $cellIP . ':9090/sendsms?phone=' . $santas[$giver]['cell'] . '&text=' . urlencode($text) . '&password=';
		if(file_get_contents($url)) {
			echo $giver . ' - good';
		} else {
			echo $giver . ' - FAILED!';
		}
		echo "\n";
	}
}

if($twilio === false && $plivo === false && $cellIP === false) {
	print_r($selected);
}

echo 'All done!';