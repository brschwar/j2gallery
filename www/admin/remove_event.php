<?php # J2Gallery - Admin Remove Event

// 
require_once('inc/initialize.php');


// Create $page_title and $section_title.
$section_title = "Events";
$page_title = 'Jackson-Junge Gallery | Admin ' . $section_title;

//  HEADER INCLUDE BEGIN
include_once ('includes/header.php');
//  HEADER INCLUDE END



$message = ""; // Error or Welcome Message.
$event_id = "";
if (isset($_GET['eid'])) { // Check if an event_id was sent to the page.
	$event_id = $_GET['eid'];
	
	// Is the event_id of the event in the database?
	$query = "SELECT event_id FROM events WHERE event_id='$event_id'";
	$result = @mysql_query ($query); // Run the query.
	$num = mysql_num_rows ($result); // How many records are there?
	
	if ($num > 0) { // The event_id was found.
		
		// Begin form validation.
		if (isset($_POST['submited'])) { // Check if the form has been submitted.
			// Query the database.
			$query2 = "DELETE FROM events WHERE event_id='$event_id'";
			$result2 = mysql_query ($query2);
		
			if ($result2) { // If the update was successful.
				// Output the success, finish building the page, then exit the script.
				include_once ('includes/sideNav.php');
				echo '<div id="content">
					<br />
					<p class="bigText">Event Removed</p>
					<br /><br />
					<p>The Event was removed successfully. <a href="events.php" class="redLink">Back to Events</a>.
					<br /><br /><br /></p>
					</div>';
				include_once("includes/footer.php");
				exit(); // Quit the script.
				
			} else { // There was an error with the DELETE.
				$message .= 'There was a problem deleting the Event. 
				Please go <a href="events.php" class="redLink">back to Events</a>.';
			}
		}// End of SUBMIT conditional.
	
	} else { // The event_id was not found in the database.
		$message .= 'The Event was not found, please go <a href="events.php" class="redLink">back to Events</a>.';
	}
} else { // The event_id was not set in the URL.
	$message .= 'The Event was not found, please go <a href="events.php" class="redLink">back to Events</a>.';
}

//  SIDE NAV BEGIN 
include_once ('includes/sideNav.php');
//  SIDE NAV END
?>


<!-- CONTENT BEGIN -->
<div id="content">
	<br />
	<p class="bigText">Remove An Event</p>
	<p><br /><br />
	Are you sure you would like to remove this Event? Deleting will remove it permanently.</p>
	<br /><br />
<?
if ($message != '') {
	echo '<p><span class="errortext">Error: ' . $message . '</span></p><br />';
}

if ($event_id != "") {
	// Query the database.
	$query = "SELECT event_id, MONTHNAME(event_start) as month, YEAR(event_start) as year, DAYOFMONTH(event_start) as date1, 
	DAYOFMONTH(event_end) as date2, event_title, event_desc, event_address1, event_URL FROM events WHERE event_id='$event_id'";
	$result = @mysql_query ($query); // Run the query.
	$num = mysql_num_rows ($result); // How many records are there?
	
	if ($num > 0) { // If it ran okay, display the records.
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			// Display the record.
			// if event is only one day, don't print day #2.
			$returnDateStr = '';
			if ($row['date1'] ==  $row['date2']) {
				$returnDateStr = $row['date1'];
			} else {
				$returnDateStr = $row['date1'] . "-" . $row['date2'];
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
			<td class="init"><b>' . $row['month'] . " " .  $row['year'] . '</b></td>
			<td class="spec"><b>' . $returnDateStr . "</b></td><td>";
			if  ($row['event_URL'] != "") { echo '<a href="http://' . $row['event_URL'] . '" target="blank" class="">'; }
			echo $row['event_title'];
			if  ($row['event_URL'] != "") { echo '</a>'; }
			echo "</td><td>" . $row['event_desc'] . '</td>
			<td>' . $row['event_address1'] . '</td>
			</tr></table>
			<br /><br />
			<!-- Begin form -->
			<form name="formName" action="' . $_SERVER['PHP_SELF'] . '?eid=' . $_GET['eid'] . '" method="post">
			<input type="hidden" name="submited" value="">
			<p><a href="#" onClick="document.formName.submit();" class="submitBtn"><b>REMOVE</b></a></span></p>
			</form>
			<!-- End of form -->';
		} // End of while loop.
	}
	mysql_close(); // Close the database connection.
}
?>
<br /><br /><br /></p>
</div>
<!-- END CONTENT -->

<?
// FOOTER INCLUDE BEGIN
include_once ('includes/footer.php');
// FOOTER INCLUDE END
?>