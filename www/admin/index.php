<?php # J2Gallery - Admin Home

// 
require_once('inc/initialize.php');

// Create $page_title and $section_title.
$section_title = "Home";
$page_title = 'Jackson-Junge Gallery | Admin ' . $section_title;


//  HEADER INCLUDE BEGIN
include_once ('inc/header.php');
//  HEADER INCLUDE END

$message = ""; // Error or Welcome Message.

if ( (isset($_POST['submited'])) && ($userLoggedIn == "false") ) {

// Check if the form has been submitted and the user has not already logged in.
	if (!(isset($_POST['username']))) { // Validate the username.
		$u = FALSE;
		$message = $message . '<li> Please enter a username.</li>';
	} else {
		$u = $_POST['username'];
	}
	
	if (!(isset($_POST['password']))) { // Validate the password.
		$p = FALSE;
		$message = $message . '<li>Please enter a password.</li>';
	} else {
		$p = $_POST['password'];
	}

	if ($u && $p) { // If everything's OK.
		// Query the database.
		$query = "SELECT admin_id, first_name FROM admin WHERE username='$u' AND password=PASSWORD('$p')";
		$result = mysql_query ($query)
		  or die ("Query failed: " . mysql_error() . " Actual query: " . $query);
		$row = mysql_fetch_array ($result, MYSQL_NUM);
		
		if ($row) { // A match was made.
			// Start session, register the values & redirect.
			$_SESSION['first_name'] = $row[1];
			$_SESSION['user_id'] = $row[0];
			$userLoggedIn = "true"; // Set these VARS since page won't see the session VARS yet.
			$message = $message . "Welcome, " . $row[1];
		} else { // No match was found.
			$message = $message . 'Please check to make sure that your username and password are correct.';
		}

	} else { // If everything wasn't OK.
			
	}

} else if ($userLoggedIn == "true") { // If user is logged in.
	$message = $message . "Welcome, " . $_SESSION['first_name'];
}

//  SIDE NAV BEGIN 
include_once ('inc/sideNav.php');
//  SIDE NAV END

?>


<!-- CONTENT BEGIN -->
<div id="content">
<br />
<?
// Check to see if the user is authenticated.
if ($userLoggedIn == "true") { // User is logged in.
?>
	<p class="bigText"><?=$message?></p>
	<br /><br />



	<!--//
    // Development Notes:
   
	<div id="sectionBoxBorder2" style="width:500px;"><div id="sectionBoxBorder" style="width:490px;"><div id="eventsBox">
	<p class="sectionBoxHeader"><b>DEVELOPMENT NOTES:</b></p><br />

    <p><strong>Recently Updated: <span class="smalltext">(1/5/09)</span></strong> 
    	<br />Added Calendar to Events, Edit/Sort Featured Items, Add/Edit Items to Database<br /><br /></p>
    <p><strong>Currently Working On:</strong> 
    	<br />Multiple Artists, Multiple Themes/Mediums, 2009 Website Updates, Laura Website, Discount Code in Admin<br /><br /></p>
    <p><strong>Later Additions:</strong> 
    	<br />Shopping Cart (Dynamic BluePay), Orders Section in Admin, Search Page, Add Google Tracking<br /><br /></p>
    
    </div></div></div>
    <br /><br />
	//-->	



    <!--//
    // Featured Originals:
    //-->	
	<div id="sectionBoxBorder2" style="width:500px;"><div id="sectionBoxBorder" style="width:490px;"><div id="eventsBox">
	<p class="sectionBoxHeader"><b>FEATURED ORIGINALS:</b></p><br />



    <div id="paintDiv">
		<ul id="paintList"></ul>
	</div>
    <div id="paintListMsg"></div>
    <div id="paintListSaveBtn">
    	<a href="#" class="updateBtn">Save Changes</a>
    </div>
    
    </div></div></div>
    <br /><br />
    
    
 
    <!--//
    // Featured Reproductions:
    //-->
    <div id="sectionBoxBorder2" style="width:500px;"><div id="sectionBoxBorder" style="width:490px;"><div id="eventsBox">
	<p class="sectionBoxHeader"><b>FEATURED REPRODUCTIONS:</b></p><br />
       
    <div id="printDiv">
		<ul id="printList"></ul>
	</div>
    <div id="printListMsg"></div>
    <div id="printListSaveBtn">
    	<a href="#" class="updateBtn">Save Changes</a>
    </div>
    
    </div></div></div>
    <br /><br />
    



	<!--//
    // Events Section:
    //-->
<?
// Pull data for events section.
// Query the database.
$query = "SELECT event_id, event_start, MONTHNAME(event_start) AS month, YEAR(event_start) AS year, DAYOFMONTH(event_start) as day1, 
event_end, DAYOFMONTH(event_end) as day2, event_title, event_desc, event_address1, event_URL 
FROM events WHERE event_end >= CURDATE() ORDER BY event_start LIMIT 1";
$result = @mysql_query ($query); // Run the query.
$num = mysql_num_rows ($result); // How many records are there?
// Print out the Events Box.
if ($num>0) { // If there are results.
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		// if event is only one day, don't print date2
		$returnDateStr = '';
		if ($row['day1'] ==  $row['day2']) {
			$returnDateStr = $row['day1'];
		} else {
			$returnDateStr = $row['day1'] . "-" . $row['day2'];
		}
		echo '<div id="sectionBoxBorder2" style="width:300px;"><div id="sectionBoxBorder" style="width:290px;"><div id="eventsBox">
			<p class="sectionBoxHeader"><b>CURRENT EVENT: ' . $row['year'] . "</b></p>
			<p><br /><br />
			<b>" . $row['month'] . " " . $returnDateStr;
			if  ($row['event_URL'] != "") { echo '<span class="eventsTitle"><a href="http://' . $row['event_URL'] . '" target="blank">'; }
			echo "<br />" . $row['event_title'] . "</b>";
			if  ($row['event_URL'] != "") { echo '</a></span>'; }
			if  ($row['event_desc'] != "") { echo "<br />" . $row['event_desc']; }
			if  ($row['event_address1'] != "") { echo "<br />" . $row['event_address1']; }
			echo '<br /><br /></p>
			<p><a href="events.php" class="redLink">View Events</a> or <a href="event_add.php" class="redLink">Add An Event</a>.<br /><br /></p>
			</div></div></div>';
	}// End of while loop.
} else { // If there are no events.
	echo '<div id="sectionBoxBorder2" style="width:300px;"><div id="sectionBoxBorder" style="width:290px;"><div id="eventsBox">
	<p class="sectionBoxHeader"><b>CURRENT EVENT:</b></p>
	<p><br /><br />There is no current event.</p>
	<p><a href="events.php" class="redLink">View Events</a> or <a href="event_add.php" class="redLink">Add An Event</a>.<br /><br /></p>
	</div></div></div>';
}
mysql_close(); // close the database connection.
?>
	<br /><br />
    


	<!--//
    // Discount Codes:
    //-->	
	<div id="sectionBoxBorder2" style="width:300px;"><div id="sectionBoxBorder" style="width:290px;"><div id="eventsBox">
	<p class="sectionBoxHeader"><b>DISCOUNT CODE:</b></p><br />

    <p><strong>NONE ACTIVE</strong></p>
    <p class="smalltext">Last used: 'FJK3D8'. From 11/1/08 to 12/20/08<br /><br /></p>
    
    </div></div></div>
    <br /><br />
    
    
    
    

<?
} else { // Otherwise, display the login form.
?>
	<p class="bigText">Please Login</p>
	<br /><br />
    <p>Welcome to J2Gallery Admin. To get started, enter you name and password.<br />Your browser must allow cookies in order to login.</p>
    <br /><br />
<?
	if ($message != '') {
		echo '<p><span class="errortext">Please correct the following issues, and try again:<ul> ' . $message . '</ul></span></p>';
	}
?>
	
	<!-- Begin form -->
	<form name="formName" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<table>
	<tr><td><p>Username: </p></td>
	<td><input type="text" name="username" size="10" maxlength="20" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" /></td>
	</tr>
	<tr>
	<td>Password: </td>
	<td><input type="password" name="password" size="10" maxlength="10" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>" /></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><input type="hidden" name="submited" value="">
	<br />
	<a href="#" onClick="javascript:validateForm('login');" class="submitBtn"><b>Submit</b></a></span></td>
	</tr>
	</table>
	<br /><!-- <A HREF="#" class="footer">Forgot your password?</A>--></p>
	</form>
	<!-- End of form -->
<?
} // END Content IF/ELSE
?>
</div>
<!-- END CONTENT -->

<?
// FOOTER INCLUDE BEGIN
include_once ('inc/footer.php');
// FOOTER INCLUDE END
?>