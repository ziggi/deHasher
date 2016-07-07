<?php

return array(
	// version
	'version' => ($version = '3.0.2'),
	// description
	'description' => ($description = 'Hashing text and dehashing hash'),
	// keywords
	'keywords' => 'hash,dehash,md5,sha1,hashing,dehashing,hash reverse',
	// title
	'title' => 'deHasher v' . $version . ' - ' . $description,
	// database
	'db' => array(
		'host' => 'localhost',
		'user' => 'root',
		'pass' => 'root',
		'base' => 'deHasher',
	),
	// template folder name under /tpl
	'tpl' => 'default',
	// uri addres to site
	'uri' => '//' . preg_replace('#/$#', '', $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF'])),
	// params
	'uri_param' => preg_replace('#^' . dirname($_SERVER['PHP_SELF']) . '/?#', '', $_SERVER['REQUEST_URI'], 1),
);
