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
// TO DO: MAKE THIS MORE SECURE
if (isset($_SESSION['first_name'])) { // First name has been set as a session variable.
	$userLoggedIn = "true";
} else if ( !(strpos($_SERVER["PHP_SELF"], "index.php")) ) { // Check to see if the user is authenticated.
	header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
	ob_end_clean(); // Delete the buffer.
	exit(); // Quit the script.
} // Othewise, show the user the page.


date_default_timezone_set("America/Chicago");
$the_date = getdate();

?>



<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $page_title; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/thickbox.css" type="text/css">

        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->




<body>

<!-- BORDER DIV TAGS -->
<div id="mainBorder2"><div id="mainBorder1"><div id="main">

<img src="img/admin_head2.gif" alt="Admin" width="750" height="125" border="0" />