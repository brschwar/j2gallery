<?php # J2Gallery - Admin Add or Edit Print

//
require_once('inc/initialize.php');

$section_title  = " | Admin ";
$page_name       = "Add/Edit Reproduction";
$page_title     =  SITE_NAME . $section_title . $page_name;

//  HEADER INCLUDE BEGIN
include_once ('inc/templates/header.php');
//  HEADER INCLUDE END



// Check to see if the user is authenticated.
if ($userLoggedIn == "false") { // First name has not been set as a session variable.
	// header ("Location: http://www.toulooz.com/admin/index.php"); // HARDCODED.
	header ("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php");
	ob_end_clean(); // Delete the buffer.
	exit(); // Quit the script.
} // Othewise, show the user the page.


//  SIDE NAV BEGIN
include_once ('inc/templates/sideNav.php');
//  SIDE NAV END


$message = ""; // Instruction Message or Error Message.
$buttonText = "UPDATE";


//
// Form Fields
//
// NUMBER
$NUMBER='';
// ARTWORK_ID
$ARTWORK_ID_ARRAY = array();
$ARTWORK_ID = '';
$ARTWORK_ID_ERROR = 'true';
// ITEM_NUMBER
$ITEM_NUMBER_ARRAY = array();
$ITEM_NUMBER = '';
$ERROR_ITEM_NUMBER_ARRAY = array('true');
$ERROR_ITEM_NUMBER = 'true';
$ERROR_ITEM_NUMBER_MSG = '';
// ARTIST_ID
$ARTIST_ID = '';
$ERROR_ARTIST_ID = 'true';
$ERROR_ARTIST_ID_MSG = '';
// ARTIST FIRST_NAME
$FIRST_NAME = '';
$ERROR_FIRST_NAME = 'true';
$ERROR_FIRST_NAME_MSG = '';
// ARTIST MIDDLE_NAME
$MIDDLE_NAME = '';
$ERROR_MIDDLE_NAME = 'true';
$ERROR_MIDDLE_NAME_MSG = '';
// ARTIST LAST_NAME
$LAST_NAME = '';
$ERROR_LAST_NAME = 'true';
$ERROR_LAST_NAME_MSG = '';
// ARTIST_CODE
$ARTIST_CODE = '';
$ERROR_ARTIST_CODE = 'true';
$ERROR_ARTIST_CODE_MSG = '';
// TITLE_ID
$TITLE_ID = '';
$ERROR_TITLE_ID = 'true';
$ERROR_TITLE_ID_MSG = '';
// TITLE_NEW
$TITLE_NEW = '';
$ERROR_TITLE_NEW = 'true';
$ERROR_TITLE_NEW_MSG = '';
// YEAR
$YEAR = '';
$ERROR_YEAR = 'true';
$ERROR_YEAR_MSG = '';
// YEAR_NEW
$YEAR_NEW = '';
$ERROR_YEAR_NEW = 'true';
$ERROR_YEAR_NEW_MSG = '';

// THEME_ID
$THEME_ID = '';
$ERROR_THEME_ID = 'true';
$ERROR_THEME_ID_MSG = '';
// THEME_NEW
$THEME_NEW = '';
$ERROR_THEME_NEW = 'true';
$ERROR_THEME_NEW_MSG = '';

// DELETE
$DELETE_ARRAY = array();
$DELETE = '';


// MEDIUM
$MEDIUM_ARRAY = array();
$ERROR_MEDIUM_ARRAY = array('true');
$ERROR_MEDIUM = 'true';
$ERROR_MEDIUM_MSG = '';
// MEDIUM_NEW
$MEDIUM_NEW_ARRAY = array();
$ERROR_MEDIUM_NEW = 'true';
$ERROR_MEDIUM_NEW_MSG = '';

// SIZE
$SIZE_ARRAY = array();
$SIZE = '';
$ERROR_SIZE_ARRAY = array('true');
$ERROR_SIZE = 'true';
$ERROR_SIZE_MSG = '';
// PRICE
$PRICE_ARRAY = array();
$PRICE = '';
$ERROR_PRICE_ARRAY = array('true');
$ERROR_PRICE = 'true';
$ERROR_PRICE_MSG = '';
// FRAMED
$FRAMED = '';
$ERROR_FRAMED = 'true';
$ERROR_FRAMED_MSG = '';
// IMAGE_NAME
$IMAGE_NAME = '';
$ERROR_IMAGE_NAME = 'true';
$ERROR_IMAGE_NAME_MSG = '';
// SORT_ORDER
$SORT_ORDER = '';
$ERROR_SORT_ORDER = 'true';
$ERROR_SORT_ORDER_MSG = '';
// VISIBLE
$VISIBLE = '';
$ERROR_VISIBLE = 'true';
$ERROR_VISIBLE_MSG = '';
// IMAGES
/*
$ERROR_THUMB_IMAGE = 'true';
$ERROR_THUMB_IMAGE_MSG = '';
$ERROR_MED_IMAGE = 'true';
$ERROR_MED_IMAGE_MSG = '';
$ERROR_LARGE_IMAGE = "true';
$ERROR_LARGE_IMAGE_MSG = '';
*/


//
// Determine How to Handle page:
// 1. iname passed via GET, show info.
// 2. page submitted, handle form.
// 3. no iname, means add an Reproduction.
//

























//
//
// 1. iname passed via GET, show info.
//
//
if ( isset($_GET['iname']) ) {
	//
	// Item number has been passed. Show info.
	//

	$IMAGE_NAME = rtrim($_GET['iname'], '#');
	// Query the database for the artwork information to display.
	$query = "SELECT *
			FROM artwork AS a LEFT JOIN titles AS t USING ( title_id )
			WHERE a.title_id = t.title_id AND image_name = '$IMAGE_NAME'";
    $result = $db->query($query);   // Using mysqli
    $num = $result->num_rows;


	if ($num > 0) { // There was a print found.
		$y=0;
		while ($row = $result->fetch_assoc()) {

			$ARTWORK_ID_ARRAY[] = $row['artwork_id'];
			$ITEM_NUMBER_ARRAY[] = $row['item_number'];
			$ARTIST_ID = $row['artist_id'];
			$TITLE_ID = $row['title_id'];
			$YEAR = $row['year'];
			$THEME_ID = $row['theme_id'];
			$MEDIUM_ARRAY[] = $row['medium_id'];
			$SIZE_ARRAY[] = htmlspecialchars($row['size']);
			$PRICE_ARRAY[] = $row['price'];
			$FRAMED = $row['framed'];
			$IMAGE_NAME = $row['image_name'];
			$SORT_ORDER = $row['sort_order'];
			$VISIBLE = $row['visible'];
			$DELETE_ARRAY[] = 'FALSE';
			$message = '<p class="bigText">Edit Reproduction</p>
						<p><br />Make any changes, then click on "Update" below to view them.
						<br /><br /><br />
						<span class="red">All fields required.</span><br /><br /></p>';
			$ERROR_ITEM_NUMBER_ARRAY[$y]='true';
			$ERROR_MEDIUM_ARRAY[$y]='true';
			$ERROR_SIZE_ARRAY[$y]='true';
			$ERROR_PRICE_ARRAY[$y]='true';
			$y++;
		}

	} else {
		$message = '<p class="bigText">Edit Reproduction</p>
				    <p><br />Unable to find that Reproduction. Please try again.
				    <br /><br /><br />
				    <span class="red">All fields required.</span><br /><br /></p>';
	}
	mysqli_free_result ($result);




























//
//
// 2. page submitted, handle form.
//
//
 }  else if ( isset($_POST['IMAGE_NAME']) ) {

	//
	// Image Name posted back on submit. Handle Form.
	//


	//
	// Validate Fields
	//
	if ( isset($_POST['submit']) ) { // Handle the form on Submit.



		// ARTIST_ID
		if ( isset($_POST['ARTIST_ID']) ) {
			$ARTIST_ID = $_POST['ARTIST_ID'];
			if ($ARTIST_ID == '') {
				$ERROR_ARTIST_ID = 'false';
			} else if ($ARTIST_ID == 'NEW') {

				// ARTIST FIRST NAME
				if ( isset($_POST['FIRST_NAME']) ) {
					$FIRST_NAME = $_POST['FIRST_NAME'];
					if ($FIRST_NAME == '') $ERROR_FIRST_NAME = 'false';
				} else {
					$ERROR_FIRST_NAME = 'false';
				}

				// ARTIST MIDDLE NAME - NOT REQUIRED
				if ( isset($_POST['MIDDLE_NAME']) ) {
					$MIDDLE_NAME = $_POST['MIDDLE_NAME'];
				}

				// ARTIST LAST NAME
				if ( isset($_POST['LAST_NAME']) ) {
					$LAST_NAME = $_POST['LAST_NAME'];
					if ($LAST_NAME == '') $ERROR_LAST_NAME = 'false';
				} else {
					$ERROR_LAST_NAME = 'false';
				}

				// ARTIST CODE - Check for duplicates.
				if ( isset($_POST['ARTIST_CODE']) ) {
					$ARTIST_CODE = $_POST['ARTIST_CODE'];
					if ($ARTIST_CODE == '') {
						$ERROR_ARTIST_CODE = 'false';
					} else {
						$query_artists = "SELECT * FROM artists ORDER BY last_name ASC";
						$result_artists = $db->query($query_artists);
						while ($row_artists = $result_artists->fetch_assoc()) {
							if ($row_artists['artist_code'] == $ARTIST_CODE) $ERROR_ARTIST_CODE = 'false';
						}
						mysqli_free_result ($result_artists); // End query
					}
				} else {
						$ERROR_ARTIST_CODE = 'false';
				}

			}
		} else {
			$ERROR_ARTIST_ID = 'false';
		}

		// TITLE ID
		if ( isset($_POST['TITLE_ID']) ) {
			$TITLE_ID = $_POST['TITLE_ID'];
			if ($TITLE_ID == '') {
				$ERROR_TITLE_ID = 'false';
			} else if ($TITLE_ID == 'NEW') {
				// TITLE_NEW
				if ( isset($_POST['TITLE_NEW']) ) {
					$TITLE_NEW = $_POST['TITLE_NEW'];
					if ($TITLE_NEW == '') {
						$ERROR_TITLE_NEW = 'false';
					} else {
						$query_titles = "SELECT * FROM titles";
						$result_titles = $db->query($query_titles);
						while ($row_titles = $result_titles->fetch_assoc()) {
							if ($row_titles['title'] == $TITLE_NEW) $ERROR_TITLE_NEW = 'false';
						}
						mysqli_free_result ($result_titles); // End query
					}
				} else {
					 $ERROR_TITLE_NEW = 'false';
				}
				// YEAR_NEW - BASED ON NEW TITLE
				if ( isset($_POST['YEAR_NEW']) ) {
					$YEAR_NEW = $_POST['YEAR'];
					if ($YEAR_NEW == '') $ERROR_YEAR_NEW = 'false';
				} else {
					 $ERROR_YEAR_NEW = 'false';
				}
			}
		} else {
			$ERROR_TITLE_ID = 'false';
		}


		// YEAR
		if ( isset($_POST['YEAR']) ) {
			$YEAR = $_POST['YEAR'];
			if ($YEAR == '') $ERROR_YEAR = 'false';
		} else {
			$ERROR_YEAR = 'false';
		}


		// THEME_ID
		if ( isset($_POST['THEME_ID']) ) {
			$THEME_ID = $_POST['THEME_ID'];
			if ($THEME_ID == '') {
				$ERROR_THEME_ID = 'false';
			} else if ($THEME_ID == 'NEW') {

				// THEME_NEW - Check for duplicates.
				if ( isset($_POST['THEME_NEW']) ) {
					$THEME_NEW = $_POST['THEME_NEW'];
					if ($THEME_NEW == '') {
						$ERROR_THEME_NEW = 'false';
					} else {
						$query_themes = "SELECT * FROM themes";
						$result_themes = $db->query($query_themes);
						while ($row_themes = $result_themes->fetch_assoc()) {
							if ($row_themes['theme'] == $THEME_NEW) $ERROR_THEME_NEW = 'false';
						}
						mysqli_free_result ($result_themes); // End query
					}
				} else {
					$ERROR_THEME_NEW = 'false';
				}
			}
		} else {
			$ERROR_THEME_NEW = 'false';
		}




		//
		// ARRAY FIELDS
		// Number of loops:
		if (isset($_POST['NUMBER']) ) $num = $_POST['NUMBER'];


		//
		// DELETE - ARRAY (From Hidden Field)
		//
		if (isset($_POST['DELETE']) ) {
			$x=0;
			foreach ($_POST['DELETE'] as $value) {
				$DELETE_ARRAY[$x] = $value;
				$x++;
			}
		}

		//
		// ARTWORK_ID - ARRAY
		//
		if (isset($_POST['ARTWORK_ID']) ) {
			$x=0;
			foreach ($_POST['ARTWORK_ID'] as $value) {
				$ARTWORK_ID_ARRAY[$x] = $value;
				if ($ARTWORK_ID_ARRAY[$x]=='') $ARTWORK_ID_ERROR = 'false';
				$x++;
			}
		} else {
			$ARTWORK_ID_ERROR = 'false';
			$ARTWORK_ID_ARRAY[] = '';
		}

		//
		// ITEM_NUMBER - ARRAY
		//
		if (isset($_POST['ITEM_NUMBER']) ) {
			$x=0;
			foreach ($_POST['ITEM_NUMBER'] as $value) {
				$ITEM_NUMBER_ARRAY[$x] = $value;
				if ($ITEM_NUMBER_ARRAY[$x]=='' && $DELETE_ARRAY[$x]=='FALSE') {
					$ERROR_ITEM_NUMBER_ARRAY[$x] = 'false';
					$ERROR_ITEM_NUMBER = 'false';
				} else {
					$ERROR_ITEM_NUMBER_ARRAY[$x] = 'true';
				}
				$x++;
			}
		} else {
			$ERROR_ITEM_NUMBER = 'false';
			$ITEM_NUMBER_ARRAY[] = '';
		}



		//
		// MEDIUM - ARRAY NEW
		//
		if ( isset($_POST['MEDIUM']) ) {
			$x=0;
			foreach ($_POST['MEDIUM'] as $value) {
				$MEDIUM_ARRAY[$x] = $value;
				if ($MEDIUM_ARRAY[$x]== 'NEW') {

					echo "<Br />medium is new<br />";

					// MEDIUM_NEW - Check for duplicates.
					if ( isset($_POST['$MEDIUM_NEW']) ) {

						$ERROR_MEDIUM_ARRAY[$x]='true';

						// $MEDIUM_NEW_ARRAY = $MEDIUM_NEW[$x];
						// LOOP THROUGH NEW MEDIUM VALUES
						$THEME_NEW = $_POST['THEME_NEW'];
						if ($THEME_NEW == '') {
							$ERROR_THEME_NEW = 'false';
						} else {
							$query_themes = "SELECT * FROM themes";
							$result_themes = $db->query($query_themes);
							while ($row_themes = $result_themes->fetch_assoc()) {
								if ($row_themes['theme'] == $THEME_NEW) $ERROR_THEME_NEW = 'false';
							}
							mysqli_free_result ($result_themes); // End query
						}
					}

				} else {
					$ERROR_MEDIUM_ARRAY[$x] = 'true';
				}
				$x++;
			}
		} else {
			$ERROR_MEDIUM_NEW = 'false';
			$MEDIUM_ARRAY[] = '';
		}


		//
		// SIZE - ARRAY
		//
		if (isset($_POST['SIZE']) ) {
			$x=0;
			foreach ($_POST['SIZE'] as $value) {
				$SIZE_ARRAY[$x] = stripslashes(htmlspecialchars($value));
				if ($SIZE_ARRAY[$x]=='' && $DELETE_ARRAY[$x] == 'FALSE') {
					$ERROR_SIZE_ARRAY[$x] = 'false';
					$ERROR_SIZE = 'false';
				} else {
					$ERROR_SIZE_ARRAY[$x] = 'true';
				}
				$x++;
			}
		} else {
			$ERROR_SIZE = 'false';
			$SIZE_ARRAY[] = '';
		}


		//
		// PRICE - ARRAY
		//
		if (isset($_POST['PRICE']) ) {
			$x=0;
			foreach ($_POST['PRICE'] as $value) {
				$PRICE_ARRAY[$x] = $value;
				if ($PRICE_ARRAY[$x]=='' && $DELETE_ARRAY[$x] == 'FALSE') {
					$ERROR_PRICE_ARRAY[$x] = 'false';
					$ERROR_PRICE = 'false';
				} else {
					$ERROR_PRICE_ARRAY[$x] = 'true';
				}
				$x++;
			}
		} else {
			$ERROR_PRICE = 'false';
			$PRICE_ARRAY[] = '';
		}




		// FRAMED
		if ( isset($_POST['FRAMED']) ) {
			$FRAMED = $_POST['FRAMED'];
			if ($FRAMED == '') $ERROR_FRAMED = 'false';
		} else {
			$ERROR_FRAMED = 'false';
		}

		// SORT_ORDER - NOT REQUIRED
		if ( isset($_POST['SORT_ORDER']) ) {
			$SORT_ORDER = $_POST['SORT_ORDER'];
		} else {
			$ERROR_SORT_ORDER = 'false';
		}

		// VISIBLE
		if ( isset($_POST['VISIBLE']) ) {
			$VISIBLE = $_POST['VISIBLE'];
			if ($VISIBLE == '') $ERROR_VISIBLE = 'false';
		} else {
			$ERROR_SORT_ORDER = 'false';
		}

		// IMAGE_NAME
		if ( isset($_POST['IMAGE_NAME']) ) {
			$IMAGE_NAME = $_POST['IMAGE_NAME'];
			if ($IMAGE_NAME == '') $ERROR_IMAGE_NAME = 'false';
		} else {
			$ERROR_IMAGE_NAME = 'false';
		}

	} // END FORM VALIDATION


	//
	// IF ERRORS, display error messages.
	//
	$message = '<p class="bigText">Edit Reproduction</p>
					<p><br />Make any changes, then click on "Update" below to view them.
				    <br /><br /><br />
				    <span class="red">All fields required.</span><br /><br /></p>';


	if ($ERROR_ITEM_NUMBER == 'false' or $ERROR_ARTIST_ID == 'false' or $ERROR_FIRST_NAME == 'false' or
		$ERROR_LAST_NAME == 'false' or $ERROR_ARTIST_CODE == 'false' or $ERROR_TITLE_ID == 'false' or
		$ERROR_TITLE_NEW == 'false' or $ERROR_YEAR == 'false' or $ERROR_THEME_ID == 'false' or
		$ERROR_THEME_NEW == 'false' or $ERROR_MEDIUM == 'false' or $ERROR_MEDIUM_NEW == 'false' or
		$ERROR_SIZE == 'false' or $ERROR_PRICE == 'false' or $ERROR_FRAMED == 'false' or
		$ERROR_VISIBLE == 'false' or $ERROR_IMAGE_NAME == 'false') {
		$message .= '<p class=errortext><br />Please update any of the form fields marked in red.<br /><br /></p>';

		if ($ERROR_ITEM_NUMBER == 'false') $ERROR_ITEM_NUMBER_MSG = ' class="errortext"';
		if ($ERROR_ARTIST_ID == 'false') $ERROR_ARTIST_ID_MSG = ' class="errortext"';
		if ($ERROR_FIRST_NAME == 'false') $ERROR_FIRST_NAME_MSG = ' class="errortext"';
		// if ($ERROR_MIDDLE_NAME) $ERROR_MIDDLE_NAME_MSG = ' class="errortext"'; NOT REQUIRED
		if ($ERROR_LAST_NAME == 'false') $ERROR_LAST_NAME_MSG =' class="errortext"';
		if ($ERROR_ARTIST_CODE == 'false') $ERROR_ARTIST_CODE_MSG = ' class="errortext"';
		if ($ERROR_TITLE_ID == 'false') $ERROR_TITLE_ID_MSG = ' class="errortext"';
		if ($ERROR_TITLE_NEW == 'false') $ERROR_TITLE_NEW_MSG = ' class="errortext"';
		if ($ERROR_YEAR == 'false') $ERROR_YEAR_MSG = ' class="errortext"';
		// if ($ERROR_YEAR_ADD == 'false') $ERROR_YEAR_ADD_MSG = ' class="errortext"';
		if ($ERROR_THEME_ID == 'false') $ERROR_THEME_ID_MSG = ' class="errortext"';
		if ($ERROR_THEME_NEW == 'false') $ERROR_THEME_NEW_MSG = ' class="errortext"';
		if ($ERROR_MEDIUM == 'false') $ERROR_MEDIUM_MSG = ' class="errortext"';
		if ($ERROR_MEDIUM_NEW == 'false') $ERROR_MEDIUM_NEW_MSG = ' class="errortext"';
		if ($ERROR_SIZE == 'false') $ERROR_SIZE_MSG = ' class="errortext"';
		if ($ERROR_PRICE == 'false') $ERROR_PRICE_MSG = ' class="errortext"';
		if ($ERROR_FRAMED == 'false') $ERROR_FRAMED_MSG = ' class="errortext"';
		if ($ERROR_VISIBLE == 'false') $ERROR_VISIBLE_MSG = ' class="errortext"';
		if ($ERROR_IMAGE_NAME == 'false') $ERROR_IMAGE_NAME_MSG = ' class="errortext"';




	} else {

		//
		// SUCCESS: UPDATE the database.
		//
		$INSERT_SUCCESS = 'true';


		// ADD NEW ARTIST
		/*

		if ($ARTIST_ID == 'NEW' && $ERROR_ARTIST_CODE = 'true') {
			$query_addArtist = "INSERT into artists (artist_id, first_name, middle_name, last_name, artist_code) VALUES ('', '$FIRST_NAME', '$MIDDLE_NAME', '$LAST_NAME', '$ARTIST_CODE')";
			$result_addArtist = $db->query($query_addArtist);
			$ARTIST_ID = $db->insert_id();
		}

		// ADD NEW TITLE
		if ($TITLE_ID == 'NEW' && $ERROR_TITLE_NEW = 'true') {
			$query_addTitle = "INSERT into titles (title_id, title, year) VALUES ('', '$TITLE_NEW', '$YEAR_NEW')";
			$result_addTitle = $db->query($query_addTitle);
			$TITLE_ID = $db->insert_id();
		}

		// ADD NEW THEME
		if ($THEME_ID == 'NEW' && $ERROR_THEME_ID = 'true') {
			$query_addTheme = "INSERT into themes (theme_id, theme) VALUES ('', '$THEME_NEW')";
			$result_addTheme = $db->query($query_addTheme);
			$THEME_ID = $db->insert_id();
		}


		// ADD NEW MEDIUM
		// TO DO: ADD THIS


		// FIND SORT ORDER IF ITEM IS VISIBLE
		if ($VISIBLE == '1') {
			$query_getSort = "SELECT sort_order
							  FROM artwork AS a LEFT JOIN mediums USING ( medium_id ), titles AS t
							  WHERE a.title_id = t.title_id AND TYPE = 'print' AND theme_id = '$THEME_ID'
							  ORDER BY sort_order DESC LIMIT 0,1";
			$result_getSort = $db->query($query_getSort);
			while ($row_getSort = (result_getSort->fetch_assoc()) {
				$SORT_ORDER = 	$row_getSort['sort_order']+1;
			}
			// echo "new sort: ".$SORT_ORDER."\n";
		} else {
			$SORT_ORDER = "0";
		}



		//
		// INSERT, REMOVE OR UPDATE ITEM IN DB.
		//


		$y=0;
		foreach ($ITEM_NUMBER_ARRAY as $value) {

			$NEW_SIZE = html_entity_decode($SIZE_ARRAY[$y]); // This field uses quotes.

			if ($DELETE_ARRAY[$y]=='FALSE') { // UPDATE

				// Check to see if the Item Number exists.
				$query_itemLookUp = "SELECT * FROM artwork WHERE item_number = '$ITEM_NUMBER_ARRAY[$y]'";
				$result_itemLookUp = mysql_query($query_itemLookUp);
				$num_itemLookUp = mysql_num_rows($result_itemLookUp);
				if ($num_itemLookUp > 0) {
					// ADD TEST TO MAKE SURE IT DOESN'T EXIST WITH A DIFFERENT IMAGE_NUMBER!
					$query_updateITEM = "UPDATE artwork SET title_id='$TITLE_ID', theme_id='$THEME_ID', artist_id='$ARTIST_ID', medium_id='$MEDIUM_ARRAY[$y]', item_number='$value', size='$NEW_SIZE', framed='$FRAMED', price='$PRICE_ARRAY[$y]', visible='$VISIBLE', image_name='$IMAGE_NAME', sort_order='$SORT_ORDER' WHERE artwork_id = '$ARTWORK_ID_ARRAY[$y]'";
					$result_addITEM = mysql_query($query_updateITEM);
				} else {
					$query_addITEM = "INSERT into artwork (artwork_id, title_id, theme_id, artist_id, medium_id, item_number, size, framed, price, visible, image_name, sort_order, date_submitted)
									  VALUES ('', '$TITLE_ID', '$THEME_ID', '$ARTIST_ID', '$MEDIUM_ARRAY[$y]', '$value', '$NEW_SIZE', '$FRAMED', '$PRICE_ARRAY[$y]', '$VISIBLE', '$IMAGE_NAME', '$SORT_ORDER', NOW() )";
					$result_addITEM = mysql_query($query_addITEM);
					$ADDITEM_ID = mysql_insert_id();
					if ($ADDITEM_ID) {
					 	$ARTWORK_ID_ARRAY[$y] = $ADDITEM_ID;
					 } else {
					 	$INSERT_SUCCESS == 'false';
					}

					// UPDATE Year:
					$query_updateYEAR = "UPDATE titles SET year='$YEAR' WHERE title_id = '$TITLE_ID'";
					$result_addYEAR = $db->query($query_updateYEAR);

				}
				mysqli_free_result ($result_itemLookUp); // End query

			} else {  // REMOVE

				// Check to see if the Item Number exists.
				$query_itemLookUp = "SELECT * FROM artwork WHERE item_number = '$ITEM_NUMBER_ARRAY[$y]'";
				$result_itemLookUp = mysql_query($query_itemLookUp);
				$num_itemLookUp = mysql_num_rows($result_itemLookUp);
				if ($num_itemLookUp > 0) {
					$query_removeITEM = "DELETE FROM artwork WHERE artwork_id = '$ARTWORK_ID_ARRAY[$y]'";
					$result_removeITEM = mysql_query($query_removeITEM);
				} else {
					// echo '<br />Do Nothing';
				}

			}
			$y++;
		}
		*/






		/*  KEEP COMMENTED OUT

		if ($ARTWORK_ID_ERROR=='true') { // UPDATE OR REMOVE PRINT
			// print_r($_POST['ARTWORK_ID']);

			$y=0;
			foreach ($ITEM_NUMBER_ARRAY as $value) {
				$NEW_SIZE = html_entity_decode($SIZE_ARRAY[$y]); // This field uses quotes.


				if ($DELETE_ARRAY[$y]=='FALSE') { // UPDATE

					echo '<br />Update';
					// $query_updateITEM = "UPDATE artwork SET title_id='$TITLE_ID', theme_id='$THEME_ID', artist_id='$ARTIST_ID', medium_id='$MEDIUM_ARRAY[$y]', item_number='$value', size='$NEW_SIZE', framed='$FRAMED', price='$PRICE_ARRAY[$y]', visible='$VISIBLE', image_name='$IMAGE_NAME', sort_order='$SORT_ORDER' WHERE artwork_id = '$ARTWORK_ID_ARRAY[$y]'";
					// $result_addITEM = mysql_query($query_updateITEM);

				} else {  // REMOVE

					echo '<br />Remove';

				}
				$y++;

			}

			// UPDATE Year:
			$query_updateYEAR = "UPDATE titles SET year='$YEAR' WHERE title_id = '$TITLE_ID'";
			$result_addYEAR = mysql_query($query_updateYEAR);

		} else { // ADD NEW PRINT

			$y=0;
			foreach ($ITEM_NUMBER_ARRAY as $value) {

				$NEW_SIZE = html_entity_decode($SIZE_ARRAY[$y]); // This field uses quotes.


				if ($DELETE_ARRAY[$y]=='FALSE') {
					echo '<br />Add New';

				// $query_addITEM = "INSERT into artwork (artwork_id, title_id, theme_id, artist_id, medium_id, item_number, size, framed, price, visible, image_name, sort_order, date_submitted)
				//				  VALUES ('', '$TITLE_ID', '$THEME_ID', '$ARTIST_ID', '$MEDIUM_ARRAY[$y]', '$value', '$NEW_SIZE', '$FRAMED', '$PRICE_ARRAY[$y]', '$VISIBLE', '$IMAGE_NAME', '$SORT_ORDER', NOW() )";
				// $result_addITEM = mysql_query($query_addITEM);
				// $ADDITEM_ID = mysql_insert_id();
				// if ($ADDITEM_ID) {
				// 	$ARTWORK_ID_ARRAY[$y] = $ADDITEM_ID;
				// } else {
				// 	$INSERT_SUCCESS == 'false';
				// }
				} else {
					echo '<br />Do Nothing.';
				}
				$y++;

			}

		}
		*/





		/*
			//
			// Upload Images:
			//

			// THUMB_IMAGE
			if ($_FILES['THUMB_IMAGE']['error'] > 0) {
			  // ADD ERROR MESSAGE HERE IF IT DOESN'T UPLOAD;
			} else {
				$target_path = "../img/reproductions/";
				$target_path = $target_path . basename( $_FILES['THUMB_IMAGE']['name']);
				if(move_uploaded_file($_FILES['THUMB_IMAGE']['tmp_name'], $target_path)) {
					// SUCCESS
					$message .= '<p class="greentext">The Thumbnail Image Was Submitted Successfully!<br /><br /></p>';
				} else{
					// ERROR MESSAGE
					$message .= '<p class="errortext">There were problems uploading the Thumbnail Image.<br /><br /></p>';
				}
			}

			// MED_IMAGE
			if ($_FILES['MED_IMAGE']['error'] > 0) {
			  // ADD ERROR MESSAGE HERE IF IT DOESN'T UPLOAD;
			} else {
				$target_path = "../img/reproductions/";
				$target_path = $target_path . basename( $_FILES['MED_IMAGE']['name']);
				if(move_uploaded_file($_FILES['MED_IMAGE']['tmp_name'], $target_path)) {
					// SUCCESS
					$message .= '<p class="greentext">The Medium Image Was Submitted Successfully!<br /><br /></p>';
				} else{
					// ERROR MESSAGE
					$message .= '<p class="errortext">There were problems uploading the Medium Image.<br /><br /></p>';
				}
			}

			// LARGE_IMAGE
			if ($_FILES['LARGE_IMAGE']['error'] > 0) {
			  // ADD ERROR MESSAGE HERE IF IT DOESN'T UPLOAD;

			} else {
				$target_path = "../img/reproductions/";
				$target_path = $target_path . basename( $_FILES['LARGE_IMAGE']['name']);
				if(move_uploaded_file($_FILES['LARGE_IMAGE']['tmp_name'], $target_path)) {
					// SUCCESS
					$message .= '<p class="greentext">The Large Image Was Submitted Successfully!<br /><br /></p>';
				} else{
					// ERROR MESSAGE
					$message .= '<p class="errortext">There were problems uploading the Large Image.<br /><br /></p>';
				}
			}
			// chmod($filePath, 0755);



		if ($INSERT_SUCCESS == 'true') {
			$message .= '<p class="greentext">The Form Was Submitted Successfully!<br /><br /></p>';
		} else {
			// DIDN'T WORK
			$message .= '<p class="errortext">There were problems entering this information. Please try again.<br /><br /></p>';
		}
		*/



	}






































//
//
// 3. no iname, means add an Reproduction.
//
//
} else {
	//
	// ADD NEW PRINT
	//
	$message = '<p class="bigText">Add Reproduction</p>
					<p><br />Fill out the form below in order to add a new Reproduction, then click on "Submit" below.
				    <br /><br /><br />
				    <span class="red">All fields required.</span><br /><br /></p>';
	$buttonText = "SUBMIT";

	// Clear out fields:
	$num = 1;
	$ARTWORK_ID_ARRAY[] = '';
	$ITEM_NUMBER_ARRAY[] = '';
	$MEDIUM_ARRAY[] = '';
	$SIZE_ARRAY[] = '';
	$PRICE_ARRAY[] = '';
	$DELETE_ARRAY[0] = 'FALSE';

}



//
// ECHO VALUES:
//
/*
echo " MEDIUM_ARRAY : ";
echo " <pre>" . print_r($MEDIUM_ARRAY, true) . "</pre>";
echo " ERROR_MEDIUM : " . $ERROR_MEDIUM .  "<br />";
echo " ERROR_MEDIUM_MSG : " . $ERROR_MEDIUM_MSG . "<br />";
echo " ERROR_MEDIUM_ARRAY : ";
echo " <pre>" . print_r($ERROR_MEDIUM_ARRAY, true) . "</pre>";
echo " MEDIUM_NEW_ARRAY  : ";
echo " <pre>" . print_r($MEDIUM_NEW_ARRAY, true) . "</pre>";
echo " ERROR_MEDIUM_NEW: " .
$ERROR_MEDIUM_NEW .  "<br /> ERROR_MEDIUM_NEW_MSG : " .
$ERROR_MEDIUM_NEW_MSG . "<br /><br />";
*/




?>


<!-- CONTENT BEGIN -->
<div id="content">
	<br />


<?= $message ?>



	<form id="formName" name="formName" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">

        <div id="printForm">


<?php



			//
			// IMAGE NAME
			//
			// if ($ARTWORK_ID=='') {
			 	echo '<label for="IMAGE_NAME"'.$ERROR_IMAGE_NAME_MSG.'>Image Name: </label><input type="text" id="IMAGE_NAME" name="IMAGE_NAME" value="'.$IMAGE_NAME.'" class="smallInput" />';
			// } else {
			// 	echo '<label for="IMAGE_NAME">Image Name: </label><input type="text" id="IMAGE_NAME" name="IMAGE_NAME" value="'.$IMAGE_NAME.'" class="smallInput noborder" readonly="true" />';
			// }


			//
	        // ARTIST
			//
			// Query to pull the Artist Name from the database.
			$query_artists = "SELECT * FROM artists ORDER BY last_name ASC";
			$result_artists = $db->query($query_artists);
			// Begin the SELECT.
			$showOldArtistField = '';
			if ($ARTIST_ID == 'NEW') $showOldArtistField = ' style="display:none;"';
			echo '<fieldset id="ARTIST_ID_FIELD" '.$showOldArtistField.' class="oldItem">
				  <label for="ARTIST_ID"'.$ERROR_ARTIST_ID_MSG.'>Artist: </label>
			      <select name="ARTIST_ID" id="ARTIST_ID" name="ARTIST_ID">
				  <option value="NEW">ADD NEW</option>';
			// Fetch and print all the records.
			$artistCount=1;
			while ($row_artists = $result_artists->fetch_assoc()) {
				$selected = '';
				if ($row_artists['artist_id'] == $ARTIST_ID) {
					$selected = 'selected="true" ';
				} else if ($ARTIST_ID == '' && $artistCount == 1) {
					$selected = 'selected="true" ';
				}
				$artistCount++;
				echo "<option value=\"" . $row_artists['artist_id'] . "\" " . $selected . ">" . $row_artists['first_name'] . " " . $row_artists['middle_name'] . " " . $row_artists['last_name'] . "</option>\n";
			}
			// End the form and free up the resources.
			echo "</select></fieldset>\n";
			mysqli_free_result ($result_artists);
			$showNewArtistField = '';
			if ($ARTIST_ID != 'NEW') $showNewArtistField = ' style="display:none;"';
			echo '<fieldset id="ARTIST_ID_ADD"'.$showNewArtistField.' class="newItem"><legend>ADD A NEW ARTIST:</legend>
				  <span id="addArtist">
				  <label for="FIRST_NAME"'.$ERROR_FIRST_NAME_MSG.'>Artist First Name:</label> <input type="text" id="FIRST_NAME" name="FIRST_NAME" value="'.$FIRST_NAME.'"/><br />
				  <label for="MIDDLE_NAME"'.$ERROR_MIDDLE_NAME_MSG.'>Artist Middle Name:</label> <input type="text" id="MIDDLE_NAME" name="MIDDLE_NAME" value="'.$MIDDLE_NAME.'"/> <span class="notRequired">(Not Required)</span><br />
				  <label for="LAST_NAME"'.$ERROR_LAST_NAME_MSG.'>Artist Last Name:</label> <input type="text" id="LAST_NAME" name="LAST_NAME" value="'.$LAST_NAME.'"/><br />
				  <label for="ARTIST_CODE"'.$ERROR_ARTIST_CODE_MSG.'>Artist Code:</label> <input type="text" id="ARTIST_CODE" name="ARTIST_CODE" value="'.$ARTIST_CODE.'" maxlength="3" style="width:50px;"/>
				  <a href="'.$_SERVER['PHP_SELF'].'?c=artist" class="cancelBttn redLink">Cancel</a>
				  </span>
				  <div class="clear">&nbsp;</div>
				  </fieldset>';



			//
			// TITLE
			//
			// Query to pull the Titles from the database.
			$query_titles = "SELECT title_id, title FROM titles ORDER BY title ASC";
			$result_titles = $db->query($query_titles);
			// Begin the SELECT.
			$showOldTitleField = '';
			if ($TITLE_ID == 'NEW') $showOldTitleField = ' style="display:none;"';
			echo '<fieldset id="TITLE_ID_FIELD"'.$showOldTitleField.' class="oldItem">
				  <label for="TITLE_ID"'.$ERROR_TITLE_ID_MSG.'>Title: </label>
				  <select name="TITLE_ID" id="TITLE_ID" style="width:300px;">
				  <option value="NEW">ADD NEW</option>';
			// Fetch and print all the records.
			$titleCount = 1;
			while ($row_titles = $result_titles->fetch_assoc()) {
				$selected = '';
				if ($row_titles['title_id'] == $TITLE_ID) {
					$selected = 'selected="true" ';
				} else if ($TITLE_ID == '' && $titleCount == 1) {
					$selected = 'selected="true" ';
				}
				$titleCount++;
				echo "<option value=\"" . $row_titles['title_id'] . "\" " . $selected . ">" . $row_titles['title'] . "</option>\n";
			}
			// End the form and free up the resources.
			echo "</select><br />\n";

			// YEAR - PART OF TITLE OLD FIELDSET
			// Build Year SELECT
			echo '<label for="YEAR"'.$ERROR_YEAR_MSG.'>Year: </label><select id="YEAR" name="YEAR" style="width:60px;">';
			for ($y=$the_date['year']; $y>=1950; $y--) {
				$yearSelected = '';
				if ($YEAR == $y) $yearSelected = " selected";
				echo '<option value="'.$y.'"'.$yearSelected.'>'.$y.'</option>';
			}
			echo '</select>
				  <div class="clear">&nbsp;</div>
				  </fieldset>'."\n";

			// NEW TITLE FIELDSET
			mysqli_free_result ($result_titles);
			$showNewTitleField = '';
			if ($TITLE_ID != 'NEW') $showNewTitleField = ' style="display:none;"';
			echo '<fieldset id="TITLE_ID_ADD"'.$showNewTitleField.' class="newItem"><legend>ADD A NEW TITLE:</legend>
				  <span id="addTitle">
				  <label for="TITLE_NEW"'.$ERROR_TITLE_NEW_MSG.'>New Title:</label> <input type="text" id="TITLE_NEW" name="TITLE_NEW" value="'.$TITLE_NEW.'" /><br />';
			// YEAR - PART OF TITLE  NEW FIELDSET
			// Build Year SELECT
			echo '<label for="YEAR_NEW"'.$ERROR_YEAR_MSG.'>Year: </label><select id="YEAR_NEW" name="YEAR_NEW" style="width:60px;">';
			for ($y=$the_date['year']; $y>=1950; $y--) {
				echo '<option value="'.$y.'">'.$y.'</option>';
			}
			echo '</select>
				  <a href="'.$_SERVER['PHP_SELF'].'?c=title" class="cancelBttn redLink">Cancel</a></span>
				  <div class="clear">&nbsp;</div>
				  </fieldset>';



			//
 			// THEME
			//
			// Query to pull the Themes from the database.
			$query_themes = "SELECT * FROM themes ORDER BY theme ASC";
			$result_themes = $db->query($query_themes);
			// Begin the SELECT.
			$showOldThemeField = '';
			if ($THEME_ID == 'NEW') $showOldThemeField = ' style="display:none;"';
			echo '<fieldset id="THEME_ID_FIELD"'.$showOldThemeField.' class="oldItem">
				  <label for="THEME_ID"'.$ERROR_THEME_ID_MSG.'>Theme: </label>
				  <select name="THEME_ID" id="THEME_ID">
				  <option value="NEW">ADD NEW</option>';
			// Fetch and print all the records.
			$themeCount=1;
			while ($row_themes = $result_themes->fetch_assoc()) {
				$selected = '';
				if ($row_themes['theme_id'] == $THEME_ID) {
					$selected = 'selected="true" ';
				} else if ($THEME_ID == '' && $themeCount == 1) {
					$selected = 'selected="true" ';
				}
				$themeCount++;
				echo "<option value=\"" . $row_themes['theme_id'] . "\" " . $selected . ">" . $row_themes['theme'] . "</option>\n";
			}
			// End the form and free up the resources.
			echo "</select></fieldset>\n";
			mysqli_free_result ($result_themes);

			// NEW THEME FIELDSET
			$showNewThemeField = '';
			if ($THEME_ID != 'NEW') $showNewThemeField = ' style="display:none;"';
			echo '<fieldset id="THEME_ID_ADD" '.$showNewThemeField.' class="newItem"><legend>ADD A NEW THEME:</legend>
				  <span id="addTheme">
				  <label for="THEME_NEW"'.$ERROR_THEME_NEW_MSG.'>New Theme:</label> <input type="text" id="THEME_NEW" name="THEME_NEW" value="'.$THEME_NEW.'" /><br />
				  <a href="'.$_SERVER['PHP_SELF'].'?c=theme" class="cancelBttn redLink">Cancel</a>
				  </span>
				  <div class="clear">&nbsp;</div>
				  </fieldset>';



	//
	// Display Each Print's Unique Information:
	//

	if ( isset($num) ) {

		for ($x=0;$x<$num;$x++) {

				echo '<fieldset id="PRINT_'.$x.'_FIELD" class="printFieldSet">';

				//
				// ITEM_NUMBER
				//
				echo '<label for="ITEM_NUMBER_'.$x.'"';
				if ($ERROR_ITEM_NUMBER_ARRAY[$x]=='false') echo $ERROR_ITEM_NUMBER_MSG;
				echo '>Item Number: <a href="print.php" class="tooltip" title="Ex. &quot;LLJ2111B&quot;">(?)</a></label><input type="text" id="ITEM_NUMBER_'.$x.'" name="ITEM_NUMBER['.$x.']" value="'.$ITEM_NUMBER_ARRAY[$x].'" class="smallInput" /><br>';





				//
				// MEDIUM
				//
				// Query to pull the Mediums from the database.
				$query_mediums = "SELECT * FROM mediums WHERE type = 'print' ORDER BY medium ASC";
				$result_mediums = $db->query($query_mediums);
				// Begin the SELECT.
				$showOldMediumField = '';
				if ($MEDIUM_ARRAY[$x] === 'NEW') $showOldMediumField = ' style="display:none;"';
				echo '<fieldset id="MEDIUM_'.$x.'_FIELD"'.$showOldMediumField.' class="oldItem removePadding">
					  <label for="MEDIUM_'.$x.'"';
				if ($ERROR_MEDIUM_ARRAY[$x]=='t') echo $ERROR_MEDIUM_MSG;
				echo '>Medium: </label>
					  <select id="MEDIUM_'.$x.'" name="MEDIUM['.$x.']">
					  <option value="NEW">ADD NEW</option>';

				// Fetch and print all the records.
				$mediumCount=1;
				while ($row_mediums = $result_mediums->fetch_assoc()) {
					$selected = '';
					if ($row_mediums['medium_id'] == $MEDIUM_ARRAY[$x]) $selected = 'selected="true" ';


					// TO DO: FIX THIS - for when the user submits, but doesn't validate.
					/* else if ($MEDIUM == '' && $mediumCount == 1) {
						$selected = 'selected="true" ';

					// From Themes for reference.
					if ($row_themes['theme_id'] == $THEME_ID) {
						$selected = 'selected="true" ';
					} else if ($THEME_ID == '' && $themeCount == 1) {
						$selected = 'selected="true" ';
					}


					} */
					$mediumCount++;
					echo "<option value=\"" . $row_mediums['medium_id'] . "\" " . $selected . ">" . $row_mediums['medium'] . "</option>\n";
				}
				echo "</select>
					  </fieldset><br>\n";
				// mysqli_free_result ($result_mediums);



			// NEW MEDIUM FIELDSET
			$showNewMediumField = '';
			if ($MEDIUM_ARRAY[$x] != 'NEW') $showNewMediumField = ' style="display:none;"';
			echo '<fieldset id="MEDIUM_'.$x.'_ADD" '.$showNewMediumField.' class="newItem">
				  <legend>ADD A NEW MEDIUM:</legend>
				  <span id="addMedium">
				  <label for="MEDIUM_NEW['.$x.']"'.$ERROR_MEDIUM_NEW_MSG.'>New Medium:</label>
				  <input type="text" id="MEDIUM_NEW['.$x.']" name="MEDIUM_NEW['.$x.']" value="';
				  // if ($MEDIUM_NEW_ARRAY[$x]=='false') echo $ERROR_ITEM_NUMBER_MSG;
				  echo '" /><br />
				  <a href="'.$_SERVER['PHP_SELF'].'?c=medium" class="cancelBttn redLink">Cancel</a>
				  </span>
				  <div class="clear">&nbsp;</div>
				  </fieldset>';





				//
				// SIZE
				//
				echo '<label for="SIZE_'.$x.'"';
				if ($ERROR_SIZE_ARRAY[$x]=='false') echo $ERROR_SIZE_MSG;
				echo '>Size: </label><input type="text" id="SIZE_'.$x.'" name="SIZE['.$x.']" value="'.$SIZE_ARRAY[$x].'" class="smallInput" /><br>';


				//
				//  PRICE
				//
				echo '<label for="PRICE_'.$x.'"';
				if ($ERROR_PRICE_ARRAY[$x]=='false') echo $ERROR_PRICE_MSG;
				echo '>Price: </label><input type="text" id="PRICE_'.$x.'" name="PRICE['.$x.']" value="'.$PRICE_ARRAY[$x].'" class="smallInput" /><br>';

				//
				// REMOVE BUTTON
				//
				echo '<a href="#" id="REMOVE_'.$x.'" name="REMOVE_'.$x.'" class="removeBttn redLink">Remove</a>';

				//
				// ARTWORK_ID (HIDDEN)
				//
        		echo '<input type="hidden" id="ARTWORK_ID_'.$x.'" name="ARTWORK_ID['.$x.']" value="'.$ARTWORK_ID_ARRAY[$x].'" />';

				//
				// DELETE (HIDDEN)
				//
				echo '<input type="hidden" id="DELETE_'.$x.'" name="DELETE['.$x.']" value="'.$DELETE_ARRAY[$x].'" />';

				echo ' <div class="clear">&nbsp;</div></fieldset>';

			}

			echo '<a href="'.$_SERVER['PHP_SELF'].'?c=addMore&n='.($num).'" class="addMoreBttn redLink">Add Another Size</a><br /><br />';

		}


		//
		// NUMBER - Hidden
		//
	    echo '<input type="hidden" id="NUMBER" name="NUMBER" value="'.$num.'" />';



		//
		// FRAMED
		//
		// Build Framed SELECT
		echo '<label for="FRAMED"'.$ERROR_FRAMED_MSG.'>Framed: </label><select id="FRAMED" name="FRAMED" style="width:50px;">
			<option value="1" ';
		if ($FRAMED=='1') echo "selected";
		echo '>Yes</option>
			<option value="0" ';
	    if ($FRAMED=='0' or $FRAMED=='') echo "selected";
		echo '>No</option></select><br />';


		//
		// SORT ORDER
		//
		// if ($ITEM_NUMBER=='') {
		// 	echo '<label for="SORT_ORDER"'.$ERROR_SORT_ORDER_MSG.'>Sort Order: </label><input type="text" id="SORT_ORDER" name="SORT_ORDER" value="'.$SORT_ORDER.'" class="smallInput" /><br />';
		// } else {
		echo '<label for="SORT_ORDER">Sort Order: <a href="print.php" class="tooltip" title="Use &quot;View Reproductions&quot; List to Sort">(?)</a> </label><input type="text" id="SORT_ORDER" name="SORT_ORDER" value="'.$SORT_ORDER.'" class="smallInput noborder" readonly="true" style="width:50px;" /><br />';
		// }


		//
		// VISIBLE
		//
		// Build Visible SELECT
		/*
		echo '<label for="VISIBLE"'.$ERROR_VISIBLE_MSG.'>Visible: </label><select id="VISIBLE" name="VISIBLE" style="width:50px;">
		<option value="1" ';
		if ($VISIBLE=='1') echo "selected";
		echo '>Yes</option>
			<option value="0" ';
	    if ($VISIBLE=='0' or $VISIBLE=='') echo "selected";
		echo '>No</option></select><br />';
		*/
		echo '<label for="VISIBLE">Visible: <a href="print.php" class="tooltip" title="This feature will be available soon.">(?)</a> </label><input type="text" id="VISIBLE" name="VISIBLE" value="1" class="smallInput noborder" readonly="true" style="width:50px;" /><br />';


      	echo '</div>
		      <div class="imageDiv">';


 		//
		// IMAGES UPLOAD
		//

		echo '<p><br /><br /><span class="bigText">Images:</span> <a href="#" class="tooltip" title="Images must be named by &quot;ARTIST CODE + ITEM NUMBER + _SIZE.jpg&quot;. See each example below for help.">(?)</a><br /><br />
			  <input type="hidden" name="MAX_FILE_SIZE" value="200000" />';

		// THUMBNAIL IMAGE
		$thumbImageName = '../img/reproductions/' .  $IMAGE_NAME . '_thumb.jpg';
		if (file_exists($thumbImageName) ) {
			echo '<div id="thumbDiv"><span class="imageDetail">Thumbnail:
			<a href="../img/reproductions/' . $IMAGE_NAME . '_thumb.jpg" class="redLink external thickbox" target="_blank">' . $IMAGE_NAME . '_thumb.jpg</a></span></div><br />';
		} else {
			echo '<div id="thumbDiv"><span class="imageDetail">Thumbnail:
				  <strong>NONE</strong></span></div><br />';
		}
		echo '<fieldset id="THUMB_IMAGE_FIELD"><legend>UPLOAD A NEW THUMBNAIL IMAGE: <a href="#" class="tooltip" title="Example: LLJ10001_thumb.jpg">(?)</a></legend>
			  <label for="THUMB_IMAGE">File:</label> <input type="file" id="THUMB_IMAGE" name="THUMB_IMAGE" class="fileInput noborder">
			  </fieldset>';

		// MEDIUM IMAGE
		$medImageName = '../img/reproductions/' . $IMAGE_NAME . '_med.jpg';
		if (file_exists($medImageName) ) {
			echo '<div id="medDiv"><span class="imageDetail">Medium: <a href="../img/reproductions/' . $IMAGE_NAME . '_med.jpg" class="redLink external thickbox"  target="_blank">' . $IMAGE_NAME . '_med.jpg</a></span></div><br />';
		} else {
			echo '<div id="medDiv"><span class="imageDetail">Medium: <strong>NONE</strong></span></div><br />';
		}
		echo '<fieldset id="MED_IMAGE_FIELD"><legend>UPLOAD A NEW MEDIUM IMAGE: <a href="#" class="tooltip" title="Example: LLJ10001_med.jpg">(?)</a></legend>
			  <label for="MED_IMAGE">File:</label> <input id="MED_IMAGE" type="file" name="MED_IMAGE" class="fileInput noborder">
			  </fieldset>';


		$largeImageName = '../img/reproductions/' . $IMAGE_NAME . '_large.jpg';
		if (file_exists($largeImageName) ) {
			echo '<div id="largeDiv"><span class="imageDetail">Large: <a href="../img/reproductions/' . $IMAGE_NAME . '_large.jpg" class="lightwindow redLink external thickbox"  target="_blank">' . $IMAGE_NAME . '_large.jpg</a></span></div><br />';
		} else {
			echo '<div id="largeDiv"><span class="imageDetail">Large: <strong>NONE</strong></span></div><br />';
		}
		echo '<fieldset id="LARGE_IMAGE_FIELD"><legend>ADD A NEW LARGE IMAGE: <a href="#" class="tooltip" title="Example: LLJ10001_large.jpg">(?)</a></legend>
			  <label for="LARGE_IMAGE">File:</label> <input id="LARGE_IMAGE" type="file" name="LARGE_IMAGE" class="fileInput noborder">
			  </fieldset>';


 ?>


        </p></div>

     	<button name="submit" value="SUBMIT" type="submit" class="printFormBtn submitBtn"><?= $buttonText ?></button>

    </form> <!-- End of form -->


<br /><br /><br />
</div>


<!-- END CONTENT -->

<?php

$db->close_connection(); // close the database connection.

// FOOTER INCLUDE BEGIN
include_once ('inc/templates/footer.php');
// FOOTER INCLUDE END
?>