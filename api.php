<?php

include "app.php";

$app = new Hasher;
$type = isset($_GET['type']) ? $app->filter_param($_GET['type']) : $type;

if (!array_key_exists($type, $app->hashes) && $type != "all") {
	echo 'Unidentified hash type "' . $type . '"';
	return;
}

if (isset($_GET['count'])) {
	echo $app->get_hash_count($type);
	return;
}

if (isset($_GET['text'])) {
	$text = urldecode($app->filter_param($_GET['text']));

	if (isset($app->hashes[$type]['decoding'])) {
		$func = $app->hashes[$type]['encode_function'];
		echo $func($text);
		return;
	}

	$func = $app->hashes[$type]['function'];
	$text_hash = $func($text);

	if (!$app->hash_exists($type, $text)) {
		$app->add_hash($type, $text_hash, $text);
	}

	echo $text_hash;
} else if (isset($_GET['hash'])) {
	$hash = $app->filter_param($_GET['hash']);
	$result_print = null;

	if (isset($app->hashes[$type]['decoding'])) {
		$func = $app->hashes[$type]['decode_function'];
		echo urldecode($func($hash));
		return;
	}

	if (!preg_match($app->hashes[$type]['pattern'], $hash)) {
		return;
	}
	
	$text = $app->get_text($type, $hash);

	if ($text !== false) {
		// check other databases
		$uot = isset($_GET['uot']) ? $_GET['uot'] : 0;
		if ($uot == 1) {
			$content = @file_get_contents('http://md5.darkbyte.ru/api.php?q='.$hash);
			if (!$content) {
				$result_print = '';
			} else {
				// check on valid
				if (md5($content) == strtolower($hash)) {
					$result_print = $content;
					$app->add_hash($type, $hash, $content);
				} else {
					$result_print = '';
				}
			}
		} else {
			$result_print = '';
		}
	} else {
		$result_print = urldecode($text);
	}
	echo $result_print;
}
