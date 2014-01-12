<?php # J2Gallery - Admin Events


//
require_once('inc/initialize.php');



// Create $page_title and $section_title.
$section_title  = " | Admin ";
$page_name      = "Events";
$page_title     =  SITE_NAME . $section_title . $page_name;

//  HEADER INCLUDE BEGIN
include_once ('inc/templates/header.php');
//  HEADER INCLUDE END



// Check to see if the user is authenticated.
if ($userLoggedIn == "false") { // First name has not been set as a session variable.
	header ("Location: http://www.toulooz.com/admin/index.php"); // HARDCODED.
	// header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
	ob_end_clean(); // Delete the buffer.
	exit(); // Quit the script.
} // Othewise, show the user the page.


//  SIDE NAV BEGIN
include_once ('inc/templates/sideNav.php');
//  SIDE NAV END


// Date vars for display
$theCurMonth = date('F'); // The current month's name.
$theCurMonthNum = date('n'); // The current month's number.
$theCurYear = date('Y'); // The current year.
// $theCurDay = date('j'); // The current day of the month.

$theMonth = '';
$theYear = '';
if ( isset($_GET['m']) && isset($_GET['y']) ) { // Is the month and year set in the query string?
	$theMonth = $_GET['m'];
	$theYear = $_GET['y'];
} else { // Default the month and year to the current date.
	$theMonth = $theCurMonth;
	$theYear = $theCurYear;
}

?>

<!-- CONTENT BEGIN -->
<div id="content">
<br />
<p class="bigText">Events</p><br />


<table id="eventsTable" cellspacing="0">
  <tr>
    <th class="nobg"><b><?=$theMonth?> <?=$theYear?></b></th>
    <th>Date</th>
    <th>Event</th>
    <th>Description</th>
	<th>Location</th>
  </tr>
<?php
// Query the database.
$query = "SELECT event_id, event_start, DAYOFMONTH(event_start) as day1, DAYOFMONTH(event_end) as day2, event_title, event_desc, event_address1, event_URL
FROM events WHERE MONTHNAME(event_start)='$theMonth' AND YEAR(event_start)='$theYear' ORDER BY event_start";
$result = $db->query($query); // Run the query.
$num = $result->num_rows;

if ($num > 0) { // If it ran okay, display the records.
	$rowColor = 0;
	while ($row = $result->fetch_assoc()) {
		// if event is only one day, don't print day #2.
		$returnDateStr = '';
		if ($row['day1'] ==  $row['day2']) {
			$returnDateStr = $row['day1'];
		} else {
			$returnDateStr = $row['day1'] . "-" . $row['day2'];
		}
		// Display the record.
		if ($rowColor % 2) {
		echo '<tr>
    <td class="init"><a href="event_edit.php?eid=' . $row['event_id'] . '">Edit</a><br />
	<a href="event_remove.php?eid=' . $row['event_id'] . '">Remove</a></td>
    <td class="spec"><b>' . $returnDateStr . "</b></td><td>";
	if  ($row['event_URL'] != "") { echo '<a href="http://' . $row['event_URL'] . '" target="blank" class="">'; }
	echo $row['event_title'];
	if  ($row['event_URL'] != "") { echo '</a>'; }
	echo "</td><td>" . $row['event_desc'] . "</td>
    <td>" . $row['event_address1'] . '</td>
	</tr>';
		} else {
		echo '<tr>
    <td class="initalt"><a href="event_edit.php?eid=' . $row['event_id'] . '">Edit</a><br />
	<a href="event_remove.php?eid=' . $row['event_id'] . '">Remove</a></td>
    <td class="specalt"><b>' . $returnDateStr . '</b></td><td class="alt">';
	if  ($row['event_URL'] != "") { echo '<a href="http://' . $row['event_URL'] . '" target="blank" class="">'; }
	echo $row['event_title'];
    if  ($row['event_URL'] != "") { echo '</a>'; }
	echo '<td class="alt">' . $row['event_desc'] . '</td>
	<td class="alt">' . $row['event_address1'] . '</td>
 	</tr>';
		}
		$rowColor = $rowColor + 1;
	} // End of while loop.
} else {
	// No events for that month.
	echo '<tr><td class="initalt"></td>
		  <td colspan="4"><br /><br />There are no events scheduled for this month. <a href="event_add.php" class="redLink">Add an event</a>.<br /><br /></td></tr>';
}

?>
</table>

<br /><br /><br />

<?php
// Find out the years in the database to set-up the loop.
$yearQuery = "SELECT YEAR(event_start) AS year, event_start FROM events WHERE event_start >= " . $theCurYear . " GROUP BY year ORDER BY year DESC";
$yearResult = $db->query($yearQuery);   // Using mysqli

while ($yearRow = $yearResult->fetch_assoc()) {
	// Print out the available months. Check to make sure they start at current month/year.
	$query = ($theCurYear == $yearRow['year'] ) ?
		// Returns only current and remainder of months in current year.
		"SELECT DATE_FORMAT(event_start, '%b') AS month_abbrev, DATE_FORMAT(event_start, '%M') AS month_full,
		DATE_FORMAT(event_start, '%m') AS month_num, YEAR(event_start) AS year FROM events
		WHERE DATE_FORMAT(event_start, '%m') >= " . $theCurMonthNum . " AND YEAR(event_start) >= " . $yearRow['year'] . "
		GROUP BY month_num ORDER BY month_num ASC" :
		// Returns any month in following year(s).
		"SELECT DATE_FORMAT(event_start, '%b') AS month_abbrev, DATE_FORMAT(event_start, '%M') AS month_full,
		DATE_FORMAT(event_start, '%m') AS month_num, YEAR(event_start) AS year FROM events
		WHERE YEAR(event_start) = " . $yearRow['year'] . " GROUP BY month_num ORDER BY month_num ASC";
	$result = $db->query($query); // Run the query.
	$num = $result->num_rows; // How many records are there?
	$count = 1;
	while ($row = $result->fetch_assoc()) {
		// echo "<br />year=" . $row['year'] . " yearRow=" . $yearRow['year'] . "<br />";
		$printMonth = '';
		if ( ($row['month_full'] == $theMonth) && ($yearRow['year'] == $theYear) ) {
			$printMonth = "<b>" . $row['month_abbrev'] . "</b>";
		} else {
			$printMonth = '<a href="events.php?m=' . $row['month_full'] . '&y=' . $yearRow['year'] . '" class="text">' . $row['month_abbrev'] . '</a>';
		}
		if ($count != $num) $printMonth = $printMonth . "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
		if ($count == 1) echo "<p><b>" . $yearRow['year'] . "</b><br />";
		echo $printMonth;
		$count = $count + 1;
	}
	echo "<br /><br />";
}
?>
</p><br /><br />

</div>
<!-- END CONTENT -->

<!-- footer include -->
<?php
$db->close_connection(); // close the database connection.

// FOOTER INCLUDE BEGIN
include_once ('inc/templates/footer.php');
// FOOTER INCLUDE END
?>