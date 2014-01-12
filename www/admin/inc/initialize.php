<?php
//
defined('SITE_NAME') ? null : define("SITE_NAME", "Jackson-Junge Gallery");

// Checks for dev environment.
$dev = strripos($_SERVER['HTTP_HOST'], '.dev');


// load db connect file
require_once('../../db_config/mysqli_connect.php');

// load configuration file for error management.
require_once ('functions/error_config.php');

// load core objects
require_once('classes/MysqliDatabase.class.php');


// Initialize Database Connection
$database = new MysqliDatabase();
$db =& $database;

?>