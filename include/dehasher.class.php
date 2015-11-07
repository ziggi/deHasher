<?php
/**
 * Hash/dehash class
 *
 * @author Sergey Marochkin <me@ziggi.org>
 * @version 3.0
 * @copyright 2012-2015 Sergey Marochkin
 * @license The MIT License
 */

class deHasher
{
	/** @var array Extarnal databases */
	private $_external_db = array(
		'md5' => array(
			'http://md5.gromweb.com/query/:hash_value',
			'http://md5db.net/api/:hash_value',
		),
		'sha1' => array(
			'http://sha1.gromweb.com/query/:hash_value',
		),
	);

	private $_supported_algos = array();

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct($host, $base, $user, $password)
	{
		$this->db = new PDO("mysql:host=$host;dbname=$base", $user, $password);
		$this->_supported_algos = array_flip(hash_algos());
	}

	/**
	 * Gets hash
	 *
	 * @param string $text Text to hash
	 * @param string $type Text to hash
	 *
	 * @return string|array Return string if $type is not null, array if not
	 */
	public function getHash($text, $type = null)
	{
		// basic params validation
		if (!isset($text)) {
			return false;
		}

		if (!is_null($type) && !$this->isSupportedType($type)) {
			return false;
		}

		// check on exists
		$type_query = !is_null($type) ? ' AND `type`.`type_name` = :type' : '';

		$query = 'SELECT
		            `type`.`type_name`,
		            `hash`.`hash_value`
		          FROM
		            `hash`, `text`, `type`
		          WHERE
		            `hash`.`text_id` = `text`.`text_id` AND
		            `hash`.`type_id` = `type`.`type_id` AND
		            `text`.`text_value` = :text' . $type_query;

		$sth = $this->db->prepare($query);

		$sth->bindValue(':text', $text);

		if (!is_null($type)) {
			$sth->bindValue(':type', $type);
		}

		if (!$sth->execute() || $sth->rowCount() == 0) {
			return $this->addHash($text, $type);
		}

		// get result
		$result = array();
		$hashs = $sth->fetchAll();

		foreach ($hashs as $row) {
			$type_name = $row['type_name'];
			$hash_value = $row['hash_value'];

			$result[$type_name] = $hash_value;
		}

		if (isset($result[$type])) {
			return $result[$type];
		}

		return $result;
	}

	/**
	 * Get text from hash
	 *
	 * @param string $hash Hash value
	 * @param string $type (optional) Type of hash
	 * @param bool $include_external_db (optional) Use external databases
	 *
	 * @return bool|string Return false if text not found, string if not
	 */
	public function getText($hash, $type = null, $include_external_db = false)
	{
		// basic params validation
		if (!isset($hash)) {
			return false;
		}

		if (!is_null($type) && !$this->isSupportedType($type)) {
			return false;
		}

		// check on exists
		$type_query = !is_null($type) ? ' AND `type`.`type_name` = :type' : '';

		$query = 'SELECT
		            `text`.`text_value`
		          FROM
		            `hash`, `text`, `type`
		          WHERE
		            `hash`.`text_id` = `text`.`text_id` AND
		            `hash`.`type_id` = `type`.`type_id` AND
		            `hash`.`hash_value` = :hash' . $type_query;

		$sth = $this->db->prepare($query);

		$sth->bindValue(':hash', $hash);

		if (!is_null($type)) {
			$sth->bindValue(':type', $type);
		}

		if (!$sth->execute() || $sth->rowCount() == 0) {
			// include external db
			if ($include_external_db && isset($this->_external_db[$type])) {
				// make fake context
				$opts = array(
					'http' => array(
						'method' => "GET",
						'header' => "Accept-language: en",
						'user_agent' => "Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405"
					)
				);

				$context = stream_context_create($opts);

				// send query
				foreach ($this->_external_db[$type] as $uri) {
					$query_uri = preg_replace('/\:hash\_value/', $hash, $uri);

					$content = @file_get_contents($query_uri, false, $context);
					if ($content === false) {
						continue;
					}

					// validation
					if ($type($content) === strtolower($hash)) {
						$this->addHash($content);
						return $content;
					}
				}
			}

			return false;
		}

		$result = $sth->fetch();
		return $result[0];
	}

	/**
	 * Gets the number of hashes
	 *
	 * @param string $type (optional) Type of hash
	 *
	 * @return integer Return the number of hashes
	 */
	public function getCount($type = null)
	{
		// basic params validation
		if (!is_null($type) && !$this->isSupportedType($type)) {
			return false;
		}

		// get result
		$type_query = !is_null($type) ? ' AND `type`.`type_name` = :type' : '';

		$query = 'SELECT
		            count(`hash`.`hash_id`)
		          FROM
		            `hash`, `type`
		          WHERE
		            `hash`.`type_id` = `type`.`type_id`' . $type_query;

		$sth = $this->db->prepare($query);

		if (!is_null($type)) {
			$sth->bindValue(':type', $type);
		}

		if (!$sth->execute() || $sth->rowCount() == 0) {
			return false;
		}

		$result = $sth->fetch();
		return $result[0];
	}

	/**
	 * Gets the available types of hashes
	 *
	 * @return array Return the list of hashes
	 */
	public function getTypeList()
	{
		return array_keys($this->_supported_algos);
	}

	/**
	 * Check type on supporting
	 *
	 * @param string $type Type of hash
	 *
	 * @return bool
	 */
	public function isSupportedType($type)
	{
		return isset($this->_supported_algos[$type]);
	}

	/**
	 * Adds hash
	 *
	 * @param string $text Text to hash
	 * @param string $type (optional) Type of hash
	 *
	 * @return string|array Return string if $type is not null, array if not
	 */
	private function addHash($text, $type = null)
	{
		// add text
		$sth = $this->db->prepare('INSERT INTO `text` (`text_value`) VALUES (?)');
		$sth->execute(array($text));

		$text_id = $this->db->lastInsertId('text_id');

		// get types
		$sth = $this->db->prepare('SELECT `type_id`, `type_name` FROM `type`');
		$sth->execute();

		$types = $sth->fetchAll();

		// insert hashes
		$result = array();

		$sth = $this->db->prepare('INSERT INTO `hash` (`hash_value`, `text_id`, `type_id`) VALUES (?, ?, ?)');

		foreach ($types as $row) {
			$type_id = $row['type_id'];
			$type_name = $row['type_name'];

			if (!$this->isSupportedType($type_name)) {
				continue;
			}

			$hash = hash($type_name, $text);
			$result[$type_name] = $hash;

			$sth->execute(array($hash, $text_id, $type_id));
		}

		// result
		if (isset($result[$type])) {
			return $result[$type];
		}

		return $result;
	}
}
