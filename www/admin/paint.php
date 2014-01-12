<?php # J2Gallery - Admin View Painting List

//
require_once('inc/initialize.php');

// Create $page_title and $section_title.
$section_title  = " | Admin ";
$page_name       = "Originals";
$page_title     =  SITE_NAME . $section_title . $page_name;


//  HEADER INCLUDE BEGIN
include_once ('inc/templates/header.php');
//  HEADER INCLUDE END




//  SIDE NAV BEGIN
include_once ('inc/templates/sideNav.php');
//  SIDE NAV END


?>


<!-- CONTENT BEGIN -->
<div id="content">
	<br />
	<p class="bigText">List of Originals</p><br /><br />
	<p><a href="paint_edit.php" class="updateBtn blackLink">ADD A PAINTING</a></p>
    <br /><br />
	<div id="errorMsg" class="errortext"></div>

<table id="adminTable" cellspacing="0" cellpadding="5" width="100%" class="sortable">
	<thead>
        <tr>
            <th nowrap="nowrap" class="nobg">&nbsp;</th>
            <th nowrap="nowrap" class="sort-numeric">Sort #</th>
            <th nowrap="nowrap" class="sort-alpha">Title</th>
            <th nowrap="nowrap" class="sort-alpha">Item #</th>
        </tr>
    </thead>
    <tbody>


<?php


	// Query the database for the artwork information to display for just this page.
	$query = "SELECT artwork_id, sort_order, item_number, image_name, title, visible
	FROM artwork AS a LEFT JOIN mediums USING ( medium_id ), titles AS t
	WHERE a.title_id = t.title_id AND (TYPE = 'painting' OR TYPE = 'construction') AND visible = 1
	ORDER BY sort_order";
    $result = $db->query($query);   // Using mysqli
    $num = $result->num_rows;

	if ($num > 0) { // There were paintings found.

		// Display the result.
		while ($row = $result->fetch_assoc()) {

			// Display the record(s).
			echo "<tr id='" . $row['artwork_id'] . "'>
			<td class='init'> <a href='paint_edit.php?inum=" . $row['item_number'] . "'>[edit] </\td>
			<td class='dragHandle'>" . $row['sort_order'] . "</\td>
			<td>" . stripslashes($row['title']) . "</\td>
			<td>" . $row['item_number'] . "</\td>
			</\tr>";

		} // End of while loop.

	} else { // Error message.
		echo '<td><br /><p class="error" align="center">Sorry! We are unable to display any paintings at this time. Please try again later.</p></td>';
	}

?>


    </tbody>
</table>


<?php // Visible ==0;

	// Query the database for the artwork information to display for just this page.
	$query = "SELECT artwork_id, sort_order, item_number, image_name, title, visible
	FROM artwork AS a LEFT JOIN mediums USING ( medium_id ), titles AS t
	WHERE a.title_id = t.title_id AND (TYPE = 'painting' OR TYPE = 'construction') AND visible = 0
	ORDER BY sort_order";
    $result = $db->query($query);   // Using mysqli
    $num = $result->num_rows;

	if ($num > 0) { // There were paintings found.

	echo '<br /><br /><p class="bigText">List of Originals Not Shown</p><br /><br />
	<table id="adminTable" cellspacing="0" cellpadding="5" width="100%" class="sortable">
	<thead>
        <tr>
            <th nowrap="nowrap" class="nobg">&nbsp;</th>
            <th nowrap="nowrap">Sort #</th>
            <th nowrap="nowrap">Title</th>
            <th nowrap="nowrap">Item #</th>
        </tr>
    </thead>
    <tbody>';


		// Display the result.
		while ($row = $result->fetch_assoc()) {

				if ($row['visible']=='0') {

					// Display the record(s).
					echo "<tr id='" . $row['artwork_id'] . "' class='notVisible'>
					<td class='init'> <a href='paint_edit.php?inum=" . $row['item_number'] . "'>[edit] </\td>
					<td>" . $row['sort_order'] . "</\td>
					<td>" . stripslashes($row['title']) . "</\td>
					<td>" . $row['item_number'] . "</\td>
					</\tr>";
				}

		} // End of while loop.

	} else { // Error message.
		echo '<td><br /><p class="error" align="center">Sorry! We are unable to display any paintings at this time. Please try again later.</p></td>';
	}

	echo '    </tbody></table>';


    $db->close_connection(); // close the database connection.

?>




<br /><br /><br /></p>
</div>
<!-- END CONTENT -->

<?php
// FOOTER INCLUDE BEGIN
include_once ('inc/templates/footer.php');
// FOOTER INCLUDE END
?>