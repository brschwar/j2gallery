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



if ( isset($_POST['adminTable']) && isset($_POST['theme']) ) {


	// find out how many records there are to update
	$size = count($_POST['adminTable']);
	$count = 1;


	// start a loop in order to update each record
	$i = 0;
	while ($i < $size) {


		$imageName = $_POST['adminTable'][$i];
		if ($imageName != '') {

			// Return all Prints with the same image_name
			$query = "SELECT artwork_id FROM artwork WHERE image_name = '$imageName'";
			$result = $db->query($query);
			while ($row = $result->fetch_assoc()) {
				// UPDATE SORT ORDER
				$query = "UPDATE artwork SET sort_order = '$count' WHERE artwork_id = '".$row['artwork_id']."' LIMIT 1";
				$db->query($query);
			}
			++$count;
		}
		++$i;


	}

 	//
	// Send the new table back to the page.
	//
 	echo '<thead>
        <tr>
            <th nowrap="nowrap" class="nobg">&nbsp;</th>
            <th nowrap="nowrap" class="sort-numeric">Sort #</th>
            <th nowrap="nowrap" class="sort-alpha">Title</th>
            <th nowrap="nowrap" class="sort-alpha">Item #</th>
        </tr>
    </thead>
    <tbody>';


	$query = "SELECT  DISTINCT title, image_name, theme, sort_order
	FROM mediums AS m
	LEFT  JOIN artwork AS a USING ( medium_id )
	LEFT  JOIN titles USING ( title_id ), themes AS t
	WHERE a.theme_id = t.theme_id AND m.type =  'print' AND theme = '".str_replace("_"," ",$_POST['theme'])."' AND visible = '1'
	ORDER BY sort_order ASC";

    $result = $db->query($query);
    $num = $result->num_rows;


	if ($num > 0) { // There were prints found.

		// Display the result.
		while ($row = $result->fetch_assoc()) {

			// Display the record(s).
			echo "<tr id='" . $row['image_name'] . "'>
			<td class='init'> <a href='paint_edit.php?inum=" . $row['image_name'] . "'>[edit] </\td>
			<td class='dragHandle'>" . $row['sort_order'] . "</\td>
			<td>" . stripslashes($row['title']) . "</\td>
			<td>" . $row['image_name'] . "</\td>
			</\tr>";

		} // End of while loop.

	} else { // Error message.
		echo '<td class="init"></td><td colspan="4"><br /><p class="error" align="center">Sorry! We are unable to display any paintings at this time. Please try again later.</p></td>';
	}

	echo '</tbody>';


}  else {

	echo "rc=-3"; // No type passed.

}

mysql_close();

?>
