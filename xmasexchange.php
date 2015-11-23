<?php

// Customization.

$twilio = true;
$twilioAccountSid = '123';
$twilioAuthToken = '123';
$twilioFrom = '123456789';
$cellIP = false;
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
}

foreach($selected as $giver => $getter) {
	$text = 'Salutations ' . $giver . ', for secret Santa you got ' . $getter . '! This is an automated message, no one else knows what name you have, so you cannot forget.';
	
	if($twilio !== false) {
		$sms = $twilio->account->messages->sendMessage($twilioFrom, $$santas[$giver]['cell'], $text);
	} else if($cell !== false) {
		$url = 'http://' . $cellIP . ':9090/sendsms?phone=' . $santas[$giver]['cell'] . '&text=' . urlencode($text) . '&password=';
		if(file_get_contents($url)) {
			echo $giver . ' - good';
		} else {
			echo $giver . ' - FAILED!';
		}
	}
	echo "\n";
}

echo 'All done!';