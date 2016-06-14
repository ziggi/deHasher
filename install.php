<?php

$conf = include 'config.php';

include_once 'include/dehasher.class.php';

$dehasher = new deHasher($conf['db']['host'], $conf['db']['base'], $conf['db']['user'], $conf['db']['pass']);

// tables
echo "Adding tables...<br>";
echo "Adding table hash...<br>";
$query = "CREATE TABLE `hash` (
            `hash_id` int(10) UNSIGNED NOT NULL,
            `type_id` int(10) UNSIGNED NOT NULL,
            `text_id` int(10) UNSIGNED NOT NULL,
            `hash_value` varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$dehasher->db->query($query);

echo "Adding table text...<br>";
$query = "CREATE TABLE `text` (
            `text_id` int(10) UNSIGNED NOT NULL,
            `text_value` text NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$dehasher->db->query($query);

echo "Adding table type...<br>";
$query = "CREATE TABLE `type` (
            `type_id` int(10) UNSIGNED NOT NULL,
            `type_name` varchar(16) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$dehasher->db->query($query);

echo "Adding tables completed.<br>";

// indexes
echo "Adding indexes...<br>";
echo "Adding indexes for hash...<br>";
$query = "ALTER TABLE `hash`
            ADD PRIMARY KEY (`hash_id`),
            ADD KEY `idx_hash` (`type_id`),
            ADD KEY `idx_hash_0` (`text_id`);";
$dehasher->db->query($query);

echo "Adding indexes for text...<br>";
$query = "ALTER TABLE `text`
            ADD PRIMARY KEY (`text_id`);";
$dehasher->db->query($query);

echo "Adding indexes for type...<br>";
$query = "ALTER TABLE `type`
            ADD PRIMARY KEY (`type_id`);";
$dehasher->db->query($query);

echo "Adding indexes completed.<br>";

// auto increments
echo "Adding auto increments...<br>";
echo "Adding auto increments for hash...<br>";
$query = "ALTER TABLE `hash`
            MODIFY `hash_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;";
$dehasher->db->query($query);

echo "Adding auto increments for text...<br>";
$query = "ALTER TABLE `text`
            MODIFY `text_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;";
$dehasher->db->query($query);

echo "Adding auto increments for type...<br>";
$query = "ALTER TABLE `type`
            MODIFY `type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;";
$dehasher->db->query($query);

echo "Adding auto increments completed.<br>";

// constraints
echo "Adding constraints...<br>";
echo "Adding constraints for hash...<br>";
$query = "ALTER TABLE `hash`
            ADD CONSTRAINT `fk_hash` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
            ADD CONSTRAINT `fk_hash_0` FOREIGN KEY (`text_id`) REFERENCES `text` (`text_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;";
$dehasher->db->query($query);
echo "Adding constraints completed.<br>";

// supported types
$types = $dehasher->getTypeList();

foreach ($types as $type) {
	$dehasher->db->query("INSERT INTO `type` (`type_name`) VALUES ('" . $type . "')");
}

// exit
if (!unlink('install.php')) {
	exit("Remove 'install.php' file immediately!");
} else {
	exit("'install.php' file hash been removed.");
}
