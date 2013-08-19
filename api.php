<?php

include "app.php";

$app = new Hasher;
$type = $app->get('type');


if ($app->get('count') !== null) {
	$count = $app->get_hash_count($type);
	echo $count;
	exit;
}

$text = $app->get('text');
if ($text !== null) {
	$app->add_hash_to_all($text);
	
	$hash = $app->hash($type, $text);
	echo $hash;
	exit;
}

$hash = $app->get('hash');
if ($hash !== null) {
	$uot = $app->get('uot');
	
	$text = $app->get_text($type, $hash, $uot);
	echo $text;
	exit;
}
