<?php # This script set the error reporting and logging for the site.

if ($dev)
{
    error_reporting (E_ALL); // Development level
    set_error_handler('my_error_handler');
} else {
    error_reporting (0); // Production level
}

// Use my own error-handling function.
function my_error_handler($e_number, $e_message) {

	$message = 'An error occurred in script ' . __FILE__ . ' on line ' . __LINE__ . ":<br /> $e_message";

	// error log ($message, 1, 'brschwar@yahoo.com'); // Production, send email.
	echo '<br /><span class="errortext">', $message, '</span><br />'; // Developoment, print error in red.

}

?>