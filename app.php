<?php

class deHasher {
	private $db;
	
	public static $config = array(
		'version' => '2.0',
		'db' => array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => 'root',
			'base' => 'deHasher',
		),
		'description' => 'Хэширование текста и дехэширование хэша',
		'keywords' => 'hash,md5,sha1,base64,хэш,md5_decode,sha1_decode,base64_encode,base64_decode',
	);

	public $hashes = array(
		'md5' => array(
			'encode_function' => 'md5',
			'pattern' => '/^[a-f0-9]{32}$/i'
		),
		'md5_md5' => array(
			'encode_function' => 'md5_md5',
			'pattern' => '/^[a-f0-9]{32}$/i'
		),
		'sha1' => array(
			'encode_function' => 'sha1',
			'pattern' => '/^[a-f0-9]{40}$/i'
		),
		'base64' => array(
			'decoding' => true,
			'encode_function' => 'base64_encode',
			'decode_function' => 'base64_decode',
		),
	);
	
	public $other_db = array(
		'http://md5.darkbyte.ru/api.php?q=',
	);

	function __construct()
	{
		$this->db = new mysqli(self::$config['db']['host'], self::$config['db']['user'], self::$config['db']['pass'], self::$config['db']['base']);
		if ($this->db->connect_errno) {
			exit("Failed to connect to data base: (" . $this->db->connect_errno . ") " . $this->db->connect_error);
		}
		$this->db->query("SET NAMES utf8");
	}

	public function get_hash_count($type)
	{
		if ($type === "all") {
			$sum = 0;
			// recursive sum
			foreach ($this->hashes as $type => $value) {
				if ($this->is_type_decoding($type)) {
					continue;
				}
				$sum += $this->get_hash_count($type);
			}
			return $sum;
		}
		if ($this->is_type_exists($type)) {
			$result = $this->db->query("SELECT COUNT(`id`) FROM `deHasher_$type`");
			$array = $result->fetch_row();
			return $array[0];
		}
		return null;
	}

	public function add_hash($type, $hash, $text)
	{
		return $this->db->query("INSERT INTO `deHasher_$type` (`Hash`, `Text`) VALUES ('$hash', '$text')");
	}

	public function add_hash_to_all($text)
	{
		foreach ($this->hashes as $type => $value) {
			if ($this->is_type_decoding($type)) {
				continue;
			}
			if (!$this->is_hash_exists($type, $text)) {
				$hash = $this->hash($type, $text);
				
				$this->add_hash($type, $hash, $text);
			}
		}
	}
	
	public function get_text($type, $hash, $uot = 0)
	{
		// if type is base64 and etc.
		if ($this->is_type_decoding($type)) {
			$func = $this->hashes[$type]['decode_function'];
			return urldecode( $func($hash) );
		}
		
		// is hash?
		if (!preg_match($this->hashes[$type]['pattern'], $hash)) {
			return false;
		}
		
		// get text
		if ($result = $this->db->query("SELECT `Text` FROM `deHasher_$type` WHERE `Hash`='$hash'")) {
			if ($result->num_rows > 0) {
				$array = $result->fetch_row();
				return $array[0];
			}
		}
		
		// use other db's
		if ($uot === 1) {
			foreach ($this->other_db as $uri) {
				$content = file_get_contents($uri . $hash);
				if (!$content) {
					continue;
				}
				
				// check on valid
				if (md5($content) === strtolower($hash)) {
					$this->add_hash_to_all($content);
					return $content;
				}
			}
		}
		return false;
	}
	
	public function hash($type, $text)
	{
		if (!$this->is_type_exists($type)) {
			return null;
		}
		$func = $this->hashes[$type]['encode_function'];
		return $func($text);
	}
	
	public function is_hash_exists($type, $text)
	{
		if ($result = $this->db->query("SELECT `Hash` FROM `deHasher_$type` WHERE `Text`='$text'")) {
			if ($result->num_rows > 0) {
				return true;
			}
		}
		return false;
	}

	public function is_type_exists($type)
	{
		if (array_key_exists($type, $this->hashes)) {
			return true;
		}
		return false;
	}
	
	public function is_type_decoding($type)
	{
		if (isset($this->hashes[$type]['decoding'])) {
			return true;
		}
		return false;
	}

	public function filter_param($param)
	{
		return $this->db->real_escape_string($param);
	}
	
	public function get($field) {
		if (isset($_GET[$field])) {
			if (empty($_GET[$field])) {
				return 1;
			} else {
				return urldecode( $this->filter_param($_GET[$field]) );
			}
		}
		return null;
	}
}

function md5_md5($text) {
	return md5(md5($text));
}
