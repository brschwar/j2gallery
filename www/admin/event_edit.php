<?php # J2Gallery - Admin Events

//
require_once('inc/initialize.php');


// Create $page_title and $section_title.
$section_title  = " | Admin ";
$page_name      = "Edit Event";
$page_title     =  SITE_NAME . $section_title . $page_name;


//  HEADER INCLUDE BEGIN
include_once ('inc/templates/header.php');
//  HEADER INCLUDE END

$message = ""; // Error or Welcome Message.
$event_id = isset($_GET['eid']) ? $_GET['eid'] : "";
if ($event_id != "") { // Check if an event_id was sent to the page.

	// Is the event_id of the event in the database?
	$query = "SELECT event_id, event_start, event_end, event_title, event_desc, event_address1, event_URL FROM events WHERE event_id='$event_id'";
	$result = $db->query($query); // Run the query.
	$num = $result->num_rows; // How many records are there?

	if ($num > 0) { // The event_id was found.

		if ( isset($_POST['submited']) ) { // Check if the form has been submitted.

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
			if (empty($_POST['event_desc'])) { // Validate the location.
				$d = " ";
			} else {
				$d = $_POST['event_desc'];
			}
			if (empty($_POST['event_address1'])) { // Validate the location.
				$l = " ";
			} else {
				$l = $_POST['event_address1'];
			}
			if (empty($_POST['event_URL'])) { // Handle the URL - not required.
				$u = " ";
			} else {
				$u = $_POST['event_URL'];
			}

			if ($s && $e && $t && $d && $l && $u) { // If everything's OK.

				// Query the database and Update the Event.
				$query2 = "UPDATE events SET event_start='$s', event_end='$e', event_title='$t', event_desc='$d',
				event_address1='$l', event_URL='$u' WHERE event_id='$event_id'";
				$result2 = $db->query($query2); // Run the query.

				if ($result2) { // If the Event edit was successful.

					// Output the update's success, finish building the page, then exit the script.
					include_once ('inc/templates/sideNav.php');

					echo '<div id="content">
					<br />
					<p class="bigText">Add An Event</p>
					<br /><br />
					<p>The Event was updated successfully. <a href="events.php" class="redLink">Go back to Events</a>.<br /><br />';

					// Display the updated event then quit the script.
					// Query the database.
					$query3 = "SELECT event_id, MONTHNAME(event_start) as month, YEAR(event_start) as year,
					DAYOFMONTH(event_start) as date1, DAYOFMONTH(event_end) as date2, event_title, event_desc,
					event_address1, event_URL FROM events WHERE event_id='$event_id'";
					$result3 = $db->query($query3); // Run the query.
					$num3 = $result3->num_rows; // How many records are there?

					if ($num3 > 0) { // If it ran okay, display the records.
						while ($row3 = $result3->fetch_assoc()) {
							// Display the record.
							// if event is only one day, don't print day #2.
							$returnDateStr = '';
							if ($row3['date1'] ==  $row3['date2']) {
								$returnDateStr = $row3['date1'];
							} else {
								$returnDateStr = $row3['date1'] . "-" . $row3['date2'];
							}
							echo '<table id="eventsTable" cellspacing="0">
							<tr>
							<th class="nobg">&nbsp;</th>
							<th>Date</th>
							<th>Event</th>
							<th>Description</th>
							<th>Location</th>
							</tr>
							<tr>
							<td class="init"><b>' . $row3['month'] . " " .  $row3['year'] . '</b></td>
							<td class="spec"><b>' . $returnDateStr . "</b></td><td>";
							if  ($row3['event_URL'] != "") { echo '<a href="http://' . $row3['event_URL'] . '" target="blank" class="">'; }
							echo $row3['event_title'];
							if  ($row3['event_URL'] != "") { echo '</a>'; }
							echo "</td><td>" . $row3['event_desc'] . '</td>
							<td>' . $row3['event_address1'] . '</td>
							</tr></table>';
						} // End of while loop.
					}
					echo '<br /><br /><br /></p>
					</div>';

					include("inc/templates/footer.php");
					exit(); // Quit the script.

				} else { // The Event was not edited successfully.
					$message .= "There was a problem updating the Event. Please try agian.";
				}
			} else { // The Form was submitted, but not all required fields were sent.
				$message .= "Please fill out all of the required fields.";
			}
		// End FORM submittal.
		}

		// The eid is sent and the event_id was found in the DB.
		// Set-up the data to be displayed in the form elements below.
		while ($row = $result->fetch_assoc()) {
			$_POST['event_start'] = $row['event_start'];
			$_POST['event_end'] = $row['event_end'];
			$_POST['event_title'] = $row['event_title'];
			$_POST['event_desc'] = $row['event_desc'];
			$_POST['event_address1'] = $row['event_address1'];
			$_POST['event_URL'] = $row['event_URL'];
		}
    	$db->close_connection(); // close the database connection.


	} else {
		$message .= "That event is not found.";
	}// End database search for event ID
} else {
	$message .= "There was no event selected. Please <a href='events.php' class='red'>select an event</a>.";
}// End check for event ID.

//  SIDE NAV BEGIN
include_once ('inc/templates/sideNav.php');
//  SIDE NAV END

?>


<!-- CONTENT BEGIN -->
<div id="content">
	<br />
	<p class="bigText">Edit Event</p>
	<p><br />Make any changes, then click on "Update" below to view them.
	<br /><br />
	<span class="red">*</span> Required fields.</p><br />
<?php
	if ($message != '') {
		echo '<p><span class="errortext">Error: ' . $message . '</span></p><br />';
	}
?>
	<!-- Begin form -->
	<form name="formName" action="<?php echo $_SERVER['PHP_SELF'] . "?eid=" . $event_id; ?>" method="post">
	<table>
	<tr><td><p><span class="red">*</span> Start Date: </td>
	<td><input type="text" id="event_start_field" name="event_start" size="10" maxlength="10" value="<?php if(isset($_POST['event_start'])) echo $_POST['event_start']; ?>" /></td></tr>
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