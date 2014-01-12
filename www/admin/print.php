<?php # J2Gallery - Admin View Print List


//
require_once('inc/initialize.php');

$section_title  = " | Admin ";
$page_name       = "Reproductions";
$page_title     =  SITE_NAME . $section_title . $page_name;


// Checks to see if the theme has been selected.
if (isset($_GET['theme'])) {
	$strTheme = $_GET['theme'];
	if ($strTheme == "") {
		$strTheme = "All";
	}
} else { // Not selected, redirect to homepage.
	$strTheme = "All";
}


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
	<p class="bigText">List of Reproductions</p><br /><br />
	<a href="print_edit.php" class="updateBtn blackLink">ADD A REPRODUCTION</a>


<?php // Code for the themes pulldown menu

// Query to pull the themes from the database.
$query_themes = "SELECT theme FROM themes ORDER BY theme ASC";
$result_themes = $db->query($query_themes);   // Using mysqli

// Begin the form.
echo '<div class="themeMenu" align="right">
	<form name="theme_form" action="print.php" method="GET">
	<select name="theme" onChange="document.theme_form.submit();" class="themeInput">
	<option value="All">View All Themes</option>\n';

// Fetch and print all the records.
while ($row = $result_themes->fetch_assoc()) {
	$selected = ($strTheme==$row['theme']) ? "selected='true'" : "";
	echo "<option value=\"" . $row['theme'] . "\" " . $selected . ">" . $row['theme'] . "</option>";
}

// End the form
echo '</select></form></div><br /><br />
<div id="errorMsg" class="errortext"></div>';
mysqli_free_result ($result_themes);





// Build the Tables:
// Query to pull the themes from the database.
if ($strTheme == "All") { // If the theme selection is set to "Viiew All Themes".
	$query_themes2 = "SELECT theme FROM themes ORDER BY theme ASC";
} else {
	$query_themes2 = "SELECT theme FROM themes WHERE theme = '$strTheme'";
}
$result_themes2 = $db->query($query_themes2);   // Using mysqli


while ($row2 = $result_themes2->fetch_assoc()) {



	echo '
	<p class="bigText">'.$row2['theme'].'</p><br />
	<table id="adminTable" cellspacing="0" cellpadding="5" width="100%" class="'.str_replace(" ","_",$row2['theme']).' sortable">
	<thead>
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
	WHERE a.theme_id = t.theme_id AND m.type =  'print' AND theme = '".$row2['theme']."' AND visible = '1'
	ORDER BY sort_order ASC";

    $result = $db->query($query);   // Using mysqli
    $num = $result->num_rows;


	if ($num > 0) { // There were prints found.

		// Display the result.
		while ($row = $result->fetch_assoc()) {

			// Display the record(s).
			echo "<tr id='" . $row['image_name'] . "'>
			<td class='init'> <a href='print_edit.php?iname=" . $row['image_name'] . "'>[edit] </\td>
			<td class='dragHandle'>" . $row['sort_order'] . "</\td>
			<td>" . stripslashes($row['title']) . "</\td>
			<td>" . $row['image_name'] . "</\td>
			</\tr>";

		} // End of while loop.

	} else { // Error message.
		echo '<td class="init">&nbsp;</td><td colspan="3"><br /><p class="error" align="center">Sorry! There are no Reproductions for this theme.</p></td>';
	}

	echo '</tbody></table>';



// Visible ==0;

/*
	// Query the database for the artwork information to display for just this page.
	$query = "SELECT  DISTINCT title, image_name, theme, sort_order
	FROM mediums AS m
	LEFT  JOIN artwork AS a USING ( medium_id )
	LEFT  JOIN titles USING ( title_id ), themes AS t
	WHERE a.theme_id = t.theme_id AND m.type =  'print' AND theme = '".$row2['theme']."' AND visible = '0'
	ORDER BY sort_order ASC";
	$result = mysql_query($query);
	$num = mysql_num_rows($result);

	if ($num > 0) { // There were prints found.


		echo '<br /><br /><p class="bigText">List of '.$row2['theme'].' Not Shown</p><br /><br />
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
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {

				if ($row['visible']=='0') {

					// Display the record(s).
					echo "<tr id='" . $row['artwork_id'] . "' class='notVisible'>
					<td class='init'> <a href='print_edit.php?iname=" . $row['item_number'] . "'>[edit] </\td>
					<td>" . $row['sort_order'] . "</\td>
					<td>" . stripslashes($row['title']) . "</\td>
					<td>" . $row['item_number'] . "</\td>
					</\tr>";
				}

		} // End of while loop.

	}

	echo '</tbody></table>';

*/
	echo '<br /><br /><br /><br />';




}

$db->close_connection(); // close the database connection.
// mysql_free_result ($result_themes2);


?>




<br /><br /><br /></p>
</div>
<!-- END CONTENT -->

<?php
// FOOTER INCLUDE BEGIN
include_once ('inc/templates/footer.php');
// FOOTER INCLUDE END
?>