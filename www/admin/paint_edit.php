<?php # J2Gallery - Admin Add or Edit Painting

//
require_once('inc/initialize.php');

// Create $page_title and $section_title.
$section_title  = " | Admin ";
$page_name       = "Add/Edit Original";
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
// Form Fields - Turn inot ARRAY or OBJECT
//
// ARTWORK_ID
$ARTWORK_ID = '';
// ITEM_NUMBER
$ITEM_NUMBER = '';
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
// MEDIUM_ID
$MEDIUM_ID = '';
$ERROR_MEDIUM_ID = 'true';
$ERROR_MEDIUM_ID_MSG = '';
// MEDIUM_NEW
$MEDIUM_NEW = '';
$ERROR_MEDIUM_NEW = 'true';
$ERROR_MEDIUM_NEW_MSG = '';
// MEDIUM_TYPE_NEW
$MEDIUM_TYPE = '';
$ERROR_MEDIUM_TYPE = 'true';
$ERROR_MEDIUM_TYPE_MSG = '';
// SIZE
$SIZE = '';
$ERROR_SIZE = 'true';
$ERROR_SIZE_MSG = '';
// PRICE
$PRICE = '';
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
// 1. inum passed via GET, show info. 2. page submitted, handle form. 3. no inum, means add an original.
//

if ( isset($_GET['inum']) ) {
	//
	// Item number has been passed. Show info.
	//

	$ITEM_NUMBER = rtrim($_GET['inum'], '#');
	// Query the database for the artwork information to display.
	$query = "SELECT *
			FROM artwork AS a LEFT JOIN titles AS t USING ( title_id )
			WHERE a.title_id = t.title_id AND item_number = '$ITEM_NUMBER'";
    $result = $db->query($query);   // Using mysqli
    $num = $result->num_rows;

	if ($num > 0) { // There was a painting found.
		$row = $result->fetch_assoc();

		$ARTWORK_ID = $row['artwork_id'];
		$ITEM_NUMBER = $row['item_number'];
		$ARTIST_ID = $row['artist_id'];
		$TITLE_ID = $row['title_id'];
		$YEAR = $row['year'];
		$MEDIUM_ID = $row['medium_id'];
		$SIZE = htmlspecialchars($row['size']);
		$PRICE = $row['price'];
		$FRAMED = $row['framed'];
		$IMAGE_NAME = $row['image_name'];
		$SORT_ORDER = $row['sort_order'];
		$VISIBLE = $row['visible'];
		$message = '<p class="bigText">Edit Painting</p>
				    <p><br />Make any changes, then click on "Update" below to view them.
				    <br /><br /><br />
				    <span class="red">All fields required.</span><br /><br /></p>';
	} else {
		$message = '<p class="bigText">Edit Painting</p>
				    <p><br />Unable to find that Original. Please try again.
				    <br /><br /><br />
				    <span class="red">All fields required.</span><br /><br /></p>';
	}
	mysqli_free_result ($result);
	// End GET.


} else if ( isset($_POST['ITEM_NUMBER'])  ) {

	//
	// Item number posted back on submit. Handle Form.
	//


	//
	// Validate Fields
	//
	if ( isset($_POST['submit']) ) { // Handle the form on Submit.

		// ARTWORK_ID - NOT REQUIRED
		if ( isset($_POST['ARTWORK_ID']) ) {
			$ARTWORK_ID = $_POST['ARTWORK_ID'];
		}

		// ITEM_NUMBER
		if ( isset($_POST['ITEM_NUMBER']) ) {
			$ITEM_NUMBER = $_POST['ITEM_NUMBER'];
			if ($ITEM_NUMBER == '') {
			 	$ERROR_ITEM_NUMBER = 'false';
			}
		} else {
			$ERROR_ITEM_NUMBER = 'false';
		}

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
					$YEAR = $_POST['YEAR'];
					if ($YEAR == '') $ERROR_YEAR = 'false';
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

		// MEDIUM_ID
		if ( isset($_POST['MEDIUM_ID']) ) {
			$MEDIUM_ID = $_POST['MEDIUM_ID'];
			if ($MEDIUM_ID == '') {
				$ERROR_MEDIUM_ID = 'false';
			} else if ($MEDIUM_ID == 'NEW') {

				// MEDIUM_NEW - Check for duplicates.
				if ( isset($_POST['MEDIUM_NEW']) ) {
					$MEDIUM_NEW = $_POST['MEDIUM_NEW'];
					if ($MEDIUM_NEW == '') {
						$ERROR_MEDIUM_NEW = 'false';
					} else {
						$query_mediums = "SELECT * FROM mediums";
						$result_mediums = $db->query($query_mediums);
						while ($row_mediums = $result_mediums->fetch_assoc()) {
							if ($row_mediums['medium'] == $MEDIUM_NEW) $ERROR_MEDIUM_NEW = 'false';
						}
						mysqli_free_result ($result_mediums); // End query
					}
				} else {
					$ERROR_MEDIUM_NEW = 'false';
				}

				// MEDIUM_TYPE_NEW
				if ( isset($_POST['MEDIUM_TYPE']) ) {
					$MEDIUM_TYPE = $_POST['MEDIUM_TYPE'];
					if ($MEDIUM_TYPE == '') $ERROR_MEDIUM_TYPE = 'false';
				} else {
					$ERROR_MEDIUM_TYPE = 'false';
				}
			}
		} else {
			$ERROR_MEDIUM_ID = 'false';
		}

		// SIZE
		if ( isset($_POST['SIZE']) ) {
			$SIZE = stripslashes(htmlspecialchars($_POST['SIZE']));
			if ($SIZE == '') $ERROR_SIZE = 'false';
		} else {
			$ERROR_SIZE = 'false';
		}

		// PRICE
		if ( isset($_POST['PRICE']) ) {
			$PRICE = $_POST['PRICE'];
			if ($PRICE == '') $ERROR_PRICE = 'false';
		} else {
			$ERROR_PRICE = 'false';
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

		// IMAGE_NAME - NOT REQUIRED
		if ( isset($_POST['IMAGE_NAME']) ) {
			$IMAGE_NAME = $_POST['IMAGE_NAME'];
		}


	} // END FORM VALIDATION


	//
	// IF ERRORS, display error messages.
	//
	$message = '<p class="bigText">Edit Painting</p>
					<p><br />Make any changes, then click on "Update" below to view them.
				    <br /><br /><br />
				    <span class="red">All fields required.</span><br /><br /></p>';

	if ($ERROR_ITEM_NUMBER == 'false' or $ERROR_ARTIST_ID == 'false' or $ERROR_FIRST_NAME == 'false' or $ERROR_LAST_NAME == 'false' or $ERROR_ARTIST_CODE == 'false' or $ERROR_TITLE_ID == 'false' or $ERROR_TITLE_NEW == 'false' or $ERROR_YEAR == 'false' or $ERROR_MEDIUM_ID == 'false' or $ERROR_MEDIUM_NEW == 'false' or $ERROR_MEDIUM_TYPE == 'false' or $ERROR_SIZE == 'false' or $ERROR_PRICE == 'false' or $ERROR_FRAMED == 'false' or $ERROR_VISIBLE == 'false') {
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
		if ($ERROR_MEDIUM_ID == 'false') $ERROR_MEDIUM_ID_MSG = ' class="errortext"';
		if ($ERROR_MEDIUM_NEW == 'false') $ERROR_MEDIUM_NEW_MSG = ' class="errortext"';
		if ($ERROR_MEDIUM_TYPE == 'false') $ERROR_MEDIUM_TYPE_MSG = ' class="errortext"';
		if ($ERROR_SIZE == 'false') $ERROR_SIZE_MSG = ' class="errortext"';
		if ($ERROR_PRICE == 'false') $ERROR_PRICE_MSG = ' class="errortext"';
		if ($ERROR_FRAMED == 'false') $ERROR_FRAMED_MSG = ' class="errortext"';
		if ($ERROR_VISIBLE == 'false') $ERROR_VISIBLE_MSG = ' class="errortext"';
		// if ($ERROR_IMAGE_NAME == 'false') $ERROR_IMAGE_NAME_MSG = ' class="errortext"';

	} else {

		//
		// SUCCESS: UPDATE the database.
		//

		$INSERT_SUCCESS = 'true';

		// ADD NEW ARTIST
		if ($ARTIST_ID == 'NEW' && $ERROR_ARTIST_CODE == 'true') {
			$query_addArtist = "INSERT into artists (first_name, middle_name, last_name, artist_code)
				VALUES ('$FIRST_NAME', '$MIDDLE_NAME', '$LAST_NAME', '$ARTIST_CODE')";
			$result_addArtist = $db->query($query_addArtist);
			$ARTIST_ID = $db->insert_id();

		}

		// ADD NEW TITLE
		if ($TITLE_ID == 'NEW' && $ERROR_TITLE_NEW == 'true') {
			$query_addTitle = "INSERT into titles (title, year) VALUES ('$TITLE_NEW', '$YEAR_NEW')";
			$result_addTitle = $db->query($query_addTitle);
			$TITLE_ID = $db->insert_id();
		}

		// ADD NEW MEDIUM
		if ($MEDIUM_ID == 'NEW' && $ERROR_MEDIUM_ID == 'true') {
			$query_addMedium = "INSERT into mediums (medium, type) VALUES ('$MEDIUM_NEW', '$MEDIUM_TYPE')";
			$result_addMedium = $db->query($query_addMedium);
			$MEDIUM_ID = $db->insert_id();
		}

		// FIND SORT ORDER IF ITEM IS VISIBLE
		if ($VISIBLE == '1') {
			$query_getSort = "SELECT sort_order
							  FROM artwork AS a LEFT JOIN mediums USING ( medium_id ), titles AS t
							  WHERE a.title_id = t.title_id AND (TYPE = 'painting' OR TYPE = 'construction')
							  ORDER BY sort_order DESC LIMIT 0,1";
			$result_getSort = $db->query($query_getSort);
			while ($row_getSort = $result_getSort->fetch_assoc()) {
				$SORT_ORDER = 	$row_getSort['sort_order']+1;
			}
			// echo "new sort: ".$SORT_ORDER."\n";
		} else {
			$SORT_ORDER = "0";
		}

		//
		// INSERT ITEM INTO DB OR UPDATE ITEM IN DB.
		//
		if ($ARTWORK_ID == '') { // INSERT PAINTING
			$NEW_SIZE = html_entity_decode($SIZE); // This field uses quotes.
			$query_addITEM = "INSERT into artwork (title_id, theme_id, artist_id, medium_id, item_number, size, framed, price, visible, image_name, sort_order, date_submitted)
							  VALUES ($TITLE_ID, '0', '$ARTIST_ID', '$MEDIUM_ID', '$ITEM_NUMBER', '$NEW_SIZE', '$FRAMED', '$PRICE', '$VISIBLE', '$ITEM_NUMBER', '$SORT_ORDER', NOW() )";
			$result_addITEM = $db->query($query_addITEM);
			$ADDITEM_ID = $db->insert_id();
			if ($ADDITEM_ID) {
				$ARTWORK_ID = $ADDITEM_ID;
			} else {
				$INSERT_SUCCESS == 'false';
			}

		} else { // UPDATE PAINTING

			$NEW_SIZE = html_entity_decode($SIZE); // This field uses quotes.
			$query_updateITEM = "UPDATE artwork SET title_id='$TITLE_ID', theme_id='0', artist_id='$ARTIST_ID', medium_id='$MEDIUM_ID', item_number='$ITEM_NUMBER', size='$NEW_SIZE', framed='$FRAMED', price='$PRICE', visible='$VISIBLE', image_name='$ITEM_NUMBER', sort_order='$SORT_ORDER', date_submitted=NOW() WHERE artwork_id = '$ARTWORK_ID'";
			$result_addITEM = $db->query($query_updateITEM);


			// UPDATE Year:
			$query_updateYEAR = "UPDATE titles SET year='$YEAR' WHERE title_id = '$TITLE_ID'";
			$result_addYEAR = $db->query($query_updateYEAR);
		}



		// FIGURE OUT HOW TO DETERMINE UPDATE SUCCESS/FAILURE!



		//
		// Upload Images:
		//

		// THUMB_IMAGE
		if ($_FILES['THUMB_IMAGE']['error'] > 0) {
		  // ADD ERROR MESSAGE HERE IF IT DOESN'T UPLOAD.
		} else {
			$target_path = "../img/originals/";
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
			$target_path = "../img/originals/";
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
			$target_path = "../img/originals/";
			$target_path = $target_path . basename( $_FILES['LARGE_IMAGE']['name']);
			if(move_uploaded_file($_FILES['LARGE_IMAGE']['tmp_name'], $target_path)) {
				// SUCCESS
				$message .= '<p class="greentext">The Large Image Was Submitted Successfully!<br /><br /></p>';
			} else{
				// ERROR MESSAGE
				$message .= '<p class="errortext">There were problems uploading the Large Image.<br /><br /></p>';
			}
		}





		if ($INSERT_SUCCESS == 'true') {
			$message .= '<p class="greentext">The Form Was Submitted Successfully!<br /><br /></p>';
		} else {
			// DIDN'T WORK
			$message .= '<p class="errortext">There were problems entering this information. Please try again.<br /><br /></p>';
		}

	}



} else {
	//
	// ADD NEW PAINTING
	//
	$message = '<p class="bigText">Add Painting</p>
					<p><br />Fill out the form below in order to add a new Original, then click on "Submit" below.
				    <br /><br /><br />
				    <span class="red">All fields required.</span><br /><br /></p>';
	$buttonText = "SUBMIT";
}


?>


<!-- CONTENT BEGIN -->
<div id="content">
	<br />


<?= $message ?>



	<form id="formName" name="formName" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">

        <div id="paintForm">


<?php

			//
			// ITEM NUMBER
			//
			if ($ARTWORK_ID=='') {
			 	echo '<label for="ITEM_NUMBER"'.$ERROR_ITEM_NUMBER_MSG.'>Item Number: </label><input type="text" id="ITEM_NUMBER" name="ITEM_NUMBER" value="'.$ITEM_NUMBER.'" class="smallInput" />';
			} else {
				echo '<label for="ITEM_NUMBER">Item Number: </label><input type="text" id="ITEM_NUMBER" name="ITEM_NUMBER" value="'.$ITEM_NUMBER.'" class="smallInput noborder" readonly="true" />';
			}


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
			// mysqli_free_result ($result_artists);
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
			$result_titles = $db->query($query_titles);   // Using mysqli

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
 			// MEDIUM
			//
			// Query to pull the Mediums from the database.
			$query_mediums = "SELECT * FROM mediums WHERE type = 'painting' OR type = 'construction' ORDER BY medium ASC";
			$result_mediums = $db->query($query_mediums);   // Using mysqli

			// Begin the SELECT.
			$showOldMediumField = '';
			if ($MEDIUM_ID == 'NEW') $showOldMediumField = ' style="display:none;"';
			echo '<fieldset id="MEDIUM_ID_FIELD"'.$showOldMediumField.' class="oldItem">
				  <label for="MEDIUM_ID"'.$ERROR_MEDIUM_ID_MSG.'>Medium: </label>
				  <select name="MEDIUM_ID" id="MEDIUM_ID">
				  <option value="NEW">ADD NEW</option>';
			// Fetch and print all the records.
			$mediumCount=1;
			while ($row_mediums = $result_mediums->fetch_assoc()) {
				$selected = '';
				if ($row_mediums['medium_id'] == $MEDIUM_ID) {
					$selected = 'selected="true" ';
				} else if ($MEDIUM_ID == '' && $mediumCount == 1) {
					$selected = 'selected="true" ';
				}
				$mediumCount++;
				echo "<option value=\"" . $row_mediums['medium_id'] . "\" " . $selected . ">" . $row_mediums['medium'] . "</option>\n";
			}
			// End the form and free up the resources.
			echo "</select></fieldset>\n";
			// mysqli_free_result ($result_mediums);
			$showNewMediumField = '';
			if ($MEDIUM_ID != 'NEW') $showNewMediumField = ' style="display:none;"';
			echo '<fieldset id="MEDIUM_ID_ADD" '.$showNewMediumField.' class="newItem"><legend>ADD A NEW MEDIUM:</legend>
				  <span id="addMedium">
				  <label for="MEDIUM_NEW"'.$ERROR_MEDIUM_NEW_MSG.'>New Medium:</label> <input type="text" id="MEDIUM_NEW" name="MEDIUM_NEW" value="'.$MEDIUM_NEW.'" /><br />
				  <label for="MEDIUM_TYPE"'.$ERROR_MEDIUM_TYPE_MSG.'>Medium Type:</label><select id="MEDIUM_TYPE" name="MEDIUM_TYPE" value="'.$MEDIUM_TYPE.'">
				  	<option value="painting">painting</option>
					<option value="construction">construction</option></select></label>
				  <a href="'.$_SERVER['PHP_SELF'].'?c=medium" class="cancelBttn redLink">Cancel</a>
				  </span>
				  <div class="clear">&nbsp;</div>
				  </fieldset>';


 		//
		// SIZE AND PRICE
 		//
 ?>

 		<label for="SIZE"<?= $ERROR_SIZE_MSG ?>>Size: </label><input type="text" id="SIZE" name="SIZE" value="<?= $SIZE ?>" class="smallInput" /><br>

		<label for="PRICE"<?= $ERROR_PRICE_MSG ?>>Price: </label><input type="text" id="PRICE" name="PRICE" value="<?= $PRICE ?>" class="smallInput" /><br>

<?php

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
		echo '<label for="SORT_ORDER">Sort Order: <a href="paint.php" class="tooltip" title="Use &quot;View Originals&quot; List to Sort">(?)</a> </label><input type="text" id="SORT_ORDER" name="SORT_ORDER" value="'.$SORT_ORDER.'" class="smallInput noborder" readonly="true" style="width:50px;" /><br />';
		// }


		//
		// VISIBLE
		//
		// Build Visible SELECT
		echo '<label for="VISIBLE"'.$ERROR_VISIBLE_MSG.'>Visible: </label><select id="VISIBLE" name="VISIBLE" style="width:50px;">
		<option value="1" ';
		if ($VISIBLE=='1') echo "selected";
		echo '>Yes</option>
			<option value="0" ';
	    if ($VISIBLE=='0' or $VISIBLE=='') echo "selected";
		echo '>No</option></select><br />';


		//
		// ARTWORK_ID: HIDDEN
		//
?>
        <input type="hidden" id="ARTWORK_ID" name="ARTWORK_ID" value="<?= $ARTWORK_ID ?>" />

      	</div>

        <div class="imageDiv">


 <?php

 		//
		// IMAGES UPLOAD
		//

		echo '<p><br /><br /><span class="bigText">Images:</span> <a href="#" class="tooltip" title="Images must be named by &quot;ARTIST CODE + ITEM NUMBER + _SIZE.jpg&quot;. See each example below for help.">(?)</a><br /><br />
			  <input type="hidden" id="IMAGE_NAME" name="IMAGE_NAME" value="' . $IMAGE_NAME . '"/>
			  <input type="hidden" name="MAX_FILE_SIZE" value="200000" />';

		// THUMBNAIL IMAGE
		$thumbImageName = '../img/originals/' .  $IMAGE_NAME . '_thumb.jpg';
		if (file_exists($thumbImageName) ) {
			echo '<div id="thumbDiv"><span class="imageDetail">Thumbnail:
			<a href="../img/originals/' . $IMAGE_NAME . '_thumb.jpg" class="redLink external thickbox" target="_blank">' . $IMAGE_NAME . '_thumb.jpg</a></span></div><br />';
		} else {
			echo '<div id="thumbDiv"><span class="imageDetail">Thumbnail:
				  <strong>NONE</strong></span></div><br />';
		}
		echo '<fieldset id="THUMB_IMAGE_FIELD"><legend>UPLOAD A NEW THUMBNAIL IMAGE: <a href="#" class="tooltip" title="Example: LLJ10001_thumb.jpg">(?)</a></legend>
			  <label for="THUMB_IMAGE">File:</label> <input type="file" id="THUMB_IMAGE" name="THUMB_IMAGE" class="fileInput noborder">
			  </fieldset>';

		// MEDIUM IMAGE
		$medImageName = '../img/originals/' . $IMAGE_NAME . '_med.jpg';
		if (file_exists($medImageName) ) {
			echo '<div id="medDiv"><span class="imageDetail">Medium: <a href="../img/originals/' . $IMAGE_NAME . '_med.jpg" class="redLink external thickbox"  target="_blank">' . $IMAGE_NAME . '_med.jpg</a></span></div><br />';
		} else {
			echo '<div id="medDiv"><span class="imageDetail">Medium: <strong>NONE</strong></span></div><br />';
		}
		echo '<fieldset id="MED_IMAGE_FIELD"><legend>UPLOAD A NEW MEDIUM IMAGE: <a href="#" class="tooltip" title="Example: LLJ10001_med.jpg">(?)</a></legend>
			  <label for="MED_IMAGE">File:</label> <input id="MED_IMAGE" type="file" name="MED_IMAGE" class="fileInput noborder">
			  </fieldset>';


		$largeImageName = '../img/originals/' . $IMAGE_NAME . '_large.jpg';
		if (file_exists($largeImageName) ) {
			echo '<div id="largeDiv"><span class="imageDetail">Large: <a href="../img/originals/' . $IMAGE_NAME . '_large.jpg" class="lightwindow redLink external thickbox"  target="_blank">' . $IMAGE_NAME . '_large.jpg</a></span></div><br />';
		} else {
			echo '<div id="largeDiv"><span class="imageDetail">Large: <strong>NONE</strong></span></div><br />';
		}
		echo '<fieldset id="LARGE_IMAGE_FIELD"><legend>ADD A NEW LARGE IMAGE: <a href="#" class="tooltip" title="Example: LLJ10001_large.jpg">(?)</a></legend>
			  <label for="LARGE_IMAGE">File:</label> <input id="LARGE_IMAGE" type="file" name="LARGE_IMAGE" class="fileInput noborder">
			  </fieldset>';


 ?>


        </p></div>

     	<button name="submit" value="SUBMIT" type="submit" class="paintFormBtn submitBtn"><?= $buttonText ?></button>

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