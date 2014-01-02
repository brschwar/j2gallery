<?php # config.inc

// This script set the error reporting and logging for the site.
// Also, any user-defined functions or constants used in the site.

// error_reporting (0); // Production level
error_reporting (E_ALL); // Development level

// Use my own error-handling function.
function my_error_handler($e_number, $e_message) {

	$message = 'An error occurred in script ' . __FILE__ . ' on line ' . __LINE__ . ": $e_message";	
	
	// error log ($message, 1, 'brschwar@yahoo.com'); // Production, send email.
	echo '<span class="errortext">', $message, '</span>'; // Developoment, print error in red.

}

set_error_handler('my_error_handler');

?>