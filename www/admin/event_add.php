<?php # J2Gallery - Admin Events

//
require_once('inc/initialize.php');


// Create $page_title and $section_title.
$section_title  = " | Admin ";
$page_name      = "Add Event";
$page_title     =  SITE_NAME . $section_title . $page_name;


//  HEADER INCLUDE BEGIN
include_once ('inc/templates/header.php');
//  HEADER INCLUDE END

$message = ""; // Error or Welcome Message.

if ( isset($_POST['submited']) ) { // Check if the form has been submitted.

	//
	require_once('inc/initialize.php');


	if (empty($_POST['event_start'])) { // Validate the start date.
		$s = FALSE;
		$message .= 'Please enter a Start Date.';
	} else {
		$s = $_POST['event_start'];
	}
	if (empty($_POST['event_end'])) { // Validate the end date.
		$e = FALSE;
		$message .= 'Please enter an Ending Date.';
	} else {
		$e = $_POST['event_end'];
	}
	if (empty($_POST['event_title'])) { // Validate the title.
		$t = FALSE;
		$message .= 'Please enter the Event\'s Name.';
	} else {
		$t = $_POST['event_title'];
	}
	if (empty($_POST['event_desc'])) { // Handle the location - not required.
		$d = " ";
	} else {
		$d = $_POST['event_desc'];
	}
	if (empty($_POST['event_address1'])) { // Handle the location - not required.
		$l = " ";
	} else {
		$l = $_POST['event_address1'];
	}
	if (empty($_POST['event_URL'])) { // Handle the URL - not required.
		$u = " ";
	} else {
		$u = $_POST['event_URL'];
	}

	if ($s && $e && $t && $d &&  $l && $u) { // If everything's OK.
		// Query the database.
			$query = "INSERT INTO events (event_start, event_end, event_title, event_desc, event_address1, event_URL) VALUES ('$s', '$e', '$t', '$d', '$l', '$u')";
			$result = $db->query($query); // Run the query.

			if ($result) { // If the update was successful.

				// Output the update's success, finish building the page, then exit the script.
				include_once ('inc/templates/sideNav.php');

				echo '<div id="content">
				<br />
				<p class="bigText">Add An Event</p>
				<br /><br />
				<p>The event was updated successfully. <a href="events.php" class="redLink">View events list</a>.
				<br />Or <a href="event_add.php" class="redLink">Add another event</a>.
				<br /><br /><br /></p>
				</div>';

				include("inc/templates/footer.php");
				exit(); // Quit the script.

			} else { // There were error with the post.
				$message .= 'Please make sure all required fields are complete.';
			}

		mysql_close(); // close the database connection.

	} else { // If everything wasn't OK.
		$message .= 'Please try again.';
	}
}

//  SIDE NAV BEGIN
include_once ('inc/templates/sideNav.php');
//  SIDE NAV END

?>


<!-- CONTENT BEGIN -->
<div id="content">
	<br />
	<p class="bigText">Add An Event</p>
	<p><br /><br />
	* Required fields.</p>
	<br /><br />
<?php
	if ($message != '') {
		echo '<p><span class="errortext">Error: ' . $message . '</span></p><br />';
	}
?>
	<!-- Begin form -->
	<form name="formName" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<table>
	<tr><td><p><span class="red">*</span> Start Date: </td>
	<td>
    <input type="text" id="event_start_field" name="event_start" size="10" maxlength="10" value="<?php if(isset($_POST['event_start'])) echo $_POST['event_start']; ?>" /></td></tr>
	<tr><td><span class="red">*</span> End Date: </td>
	<td><input type="text" id="event_end_field" name="event_end" size="10" maxlength="10" value="<?php if(isset($_POST['event_end'])) echo $_POST['event_end']; ?>" /></td></tr>
	<tr><td><span class="red">*</span> Event Name: </td>
	<td><input type="text" name="event_title" size="30" maxlength="60" value="<?php if(isset($_POST['event_title'])) echo $_POST['event_title']; ?>" />
	</td></tr>
    <tr><td>Event Description: </td>
	<td><input type="text" name="event_desc" size="30" maxlength="60" value="<?php if(isset($_POST['event_desc'])) echo $_POST['event_desc']; ?>" />
	</td></tr>
	<tr><td>Location: </td>
	<td><input type="text" name="event_address1" size="30" maxlength="30" value="<?php if(isset($_POST['event_address1'])) echo $_POST['event_address1']; ?>" />
	<span class="smalltext">(City & State)</span></td></tr>
	<tr><td>Event Website: </td>
	<td><input type="text" name="event_URL" size="30" maxlength="120" value="<?php if(isset($_POST['event_URL'])) echo $_POST['event_URL']; ?>" />
	</td></tr>
	<tr>
	<td>&nbsp;</td>
	<td><br /><input type="hidden" name="submited" value="">
	<a href="#" onClick="javascript:validateForm('addEvent');" class="submitBtn"><b>Submit</b></a></span></td>
	</tr></table>
	</form>

	<!-- End of form -->
<br /><br /><br /></p>
</div>
<!-- END CONTENT -->

<?php
// FOOTER INCLUDE BEGIN
include_once ('inc/templates/footer.php');
// FOOTER INCLUDE END
?>