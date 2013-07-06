<?php

class Hasher {
	public static $config = array(
		'version' => '1.5',
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
			'function' => 'md5',
			'pattern' => '/^[a-f0-9]{32}$/i'
		),
		'md5_md5' => array(
			'function' => 'md5_md5',
			'pattern' => '/^[a-f0-9]{32}$/i'
		),
		'sha1' => array(
			'function' => 'sha1',
			'pattern' => '/^[a-f0-9]{40}$/i'
		),
		'base64' => array(
			'decoding' => true,
			'encode_function' => 'base64_encode',
			'decode_function' => 'base64_decode',
		),
	);

	private $db;

	function __construct()
	{
		$this->db = new mysqli(self::$config['db']['host'], self::$config['db']['user'], self::$config['db']['pass'], self::$config['db']['base']);
		if ($this->db->connect_errno) {
			echo "Failed to connect to data base: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
			exit;
		}
		$this->db->query("SET NAMES utf8");
	}

	public function get_hash_count($type)
	{
		if ($type == "all") {
			$sum = 0;
			foreach ($this->hashes as $key => $value) {
				if (isset($value['decoding'])) {
					continue;
				}
				$sum += $this->get_hash_count($key);
			}
			return $sum;
		}

		$result = $this->db->query("SELECT COUNT(*) FROM `deHasher_$type`");
		$array = $result->fetch_row();
		return $array[0];
	}

	public function add_hash($type, $hash, $text)
	{
		return $this->db->query("INSERT INTO `deHasher_$type` (`Hash`,`Text`) VALUES ('$hash', '$text')");
	}

	public function get_text($type, $hash)
	{
		$result = $this->db->query("SELECT `Text` FROM `deHasher_$type` WHERE `Hash`='$hash'");
		if ($result->num_rows == 0) {
			return false;
		}
		$array = $result->fetch_row();
		return $array[0];
	}

	public function hash_exists($type, $text)
	{
		$result = $this->db->query("SELECT `Hash` FROM `deHasher_$type` WHERE `Text`='$text'");
		if ($result->num_rows == 0) {
			return false;
		}
		return true;
	}

	public function filter_param($param)
	{
		return $this->db->real_escape_string($param);
	}
}

function md5_md5($text) {
	return md5(md5($text));
}