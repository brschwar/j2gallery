<?php
//
// PHP for paint.php to update sort order.
// called from admin/paint.php
// returns positive number for count or negative number for error.
//


// Checks for dev environment.
$dev = strripos($_SERVER['HTTP_HOST'], '.dev');

// load db connect file
require_once('../../../../db_config/mysqli_connect.php');

// load configuration file for error management.
require_once ('error_config.php');

// load core objects
require_once('../classes/MysqliDatabase.class.php');

// Initialize Database Connection
$database = new MysqliDatabase();
$db =& $database;



if ( isset($_POST['adminTable']) ) {


	// find out how many records there are to update
	$size = count($_POST['adminTable']);
	$count = 1;

	// start a loop in order to update each record
	$i = 0;
	while ($i < $size) {

		$id = $_POST['adminTable'][$i];
		if ($id != '') {

			// UPDATE SORT ORDER
			$query = "UPDATE artwork SET sort_order = '$count' WHERE artwork_id = '$id' LIMIT 1";
			$db->query($query);
			++$count;
		}

		++$i;
	}

 	echo '<thead>
        <tr>
            <th nowrap="nowrap" class="nobg">&nbsp;</th>
            <th nowrap="nowrap" class="sort-numeric">Sort #</th>
            <th nowrap="nowrap" class="sort-alpha">Title</th>
            <th nowrap="nowrap" class="sort-alpha">Item #</th>
        </tr>
    </thead>
    <tbody>';


	// Query the database for the artwork information to display for just this page.
	$query = "SELECT artwork_id, sort_order, item_number, image_name, title, visible
	FROM artwork AS a LEFT JOIN mediums USING ( medium_id ), titles AS t
	WHERE a.title_id = t.title_id AND (TYPE = 'painting' OR TYPE = 'construction') AND visible = 1
	ORDER BY sort_order";
	$result = $db->query($query);
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


}  else {

	echo "rc=-3"; // No type passed.

}

$db->close_connection(); // close the database connection.

?>
