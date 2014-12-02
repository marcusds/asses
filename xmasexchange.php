<?php
echo 'Randomizing: ';
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

$selected = array();

shuffle_assoc( $santas );

foreach( $santas as $santa => $values ) {
	$name = select_rand( $santas, $santa, $values['no'] );
	$selected[$santa] = $name;
}

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

echo 'done!<br>';

echo 'Preparing to send:<br>';

set_time_limit( 0 );

foreach( $selected as $giver => $getter ) {
	$text = 'Salutations+'. $giver . ',+for+secret+Santa+you+got+' . $getter . '!+This+is+an+automated+message,+no+one+else+knows+what+name+you+have,+so+you+cannot+forget.';
	$url = 'http://192.168.0.3:9090/sendsms?phone=' . $santas[$giver]['cell'] . '&text=' . $text . '&password=';
	if( file_get_contents( $url ) ) {
		echo $giver . ' - good';
	} else {
		echo $giver . ' - FAILED!';
	}
	echo "<br>\n";
}
echo '</pre>';