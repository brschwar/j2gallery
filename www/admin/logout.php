<?php # Admin logout.php

// Include the configuration file for error management.
require_once ('inc/functions/error_config.php');

ob_start();
session_start();

// If no first_name variable exists, redirect the user.
if (!isset($_SESSION['first_name'])) {
	header ("Location: http://www.j2gallery.com/admin/index.php"); // HARDCODED.
		// Dynamic (Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
	ob_end_clean(); // Delete the buffer.
	exit(); // Quit the script.

} else { // Log out the user.

	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie (session_name(), '', time()-300, '/', '', 0);

	header ("Location: http://www.j2gallery.com/admin/index.php"); // HARDCODED.
	ob_end_clean(); // Delete the buffer.

}

?>