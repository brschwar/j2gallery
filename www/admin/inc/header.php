<?php # header.php


// Start the output buffering and initialize a session.
ob_start();
session_start();


// Check for a $page_title value.
if (!isset($page_title)) {
	$page_title = 'Jackson-Junge Gallery | Admin';
}

// Variable for Admin section - user login.
$userLoggedIn = "false"; // Check to see if the user is logged in. Available in INCLUDES.
// Check to see if the user is authenticated.
if (isset($_SESSION['first_name'])) { // First name has been set as a session variable.
	$userLoggedIn = "true";
} else if ( !(strpos($_SERVER["PHP_SELF"], "index.php")) ) { // Check to see if the user is authenticated.
	// header ("Location: http://www.toulooz.com/admin/index.php"); // HARDCODED.
	header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
	ob_end_clean(); // Delete the buffer.
	exit(); // Quit the script.
} // Othewise, show the user the page.


date_default_timezone_set("America/Chicago");
$the_date = getdate();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $page_title; ?></title>

<link rel="stylesheet" href="css/styles.css" type="text/css">
<link rel="stylesheet" href="css/thickbox.css" type="text/css">

<script language="JavaScript" src="js/jquery-1.2.6.js"></script>
<script language="JavaScript" src="js/ui/jquery-ui-personalized-1.5.3.packed.js"></script>
<script language="JavaScript" src="js/plugins/jquery-thickbox-v3-compressed.js"></script>

<!--// 
<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>
<script language="JavaScript" src="js/plugins/jquery-form-2.17.packed.js"></script>
//-->


<!--//
	// Page Specific JavaScript
    //-->
<?php 

if ($section_title == "Home") echo '<script language="JavaScript" src="js/login.js"></script>'."\n".'<script language="JavaScript" src="js/home.js"></script>';

if ($section_title == "Events") echo '<script language="JavaScript" src="js/events.js"></script>'."\n".'<script language="JavaScript" src="js/java.js"></script';

if ($section_title == "Originals") echo '<script language="JavaScript" src="js/plugins/jquery.tablednd_0_5.js"></script>'."\n".'<script language="JavaScript" src="js/paint.js"></script>';
if ($section_title == "Add/Edit Original") echo '<script language="JavaScript" src="js/plugins/jquery-easyToolTip.js"></script>'."\n".'<script language="JavaScript" src="js/paint-edit.js"></script>';

if ($section_title == "Reproductions") echo '<script language="JavaScript" src="js/plugins/jquery.tablednd_0_5.js"></script>'."\n".'<script language="JavaScript" src="js/print.js"></script>';
if ($section_title == "Add/Edit Reproduction") echo '<script language="JavaScript" src="js/plugins/jquery-easyToolTip.js"></script>'."\n".'<script language="JavaScript" src="js/print-edit.js"></script>';

?>

</head>


<body>

<!-- BORDER DIV TAGS -->
<div id="mainBorder2"><div id="mainBorder1"><div id="main">

<img src="img/admin_head2.gif" alt="Admin" width="750" height="125" border="0" />