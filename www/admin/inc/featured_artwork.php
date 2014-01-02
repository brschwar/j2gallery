<?php 
//
// PHP to update paint.xml file or return list of paintings
// called from admin/index.php
//

require_once ('db_info.php'); // Connect to the database.
require_once ('classes/database.php'); // Connect to the database.


if ( isset($_GET['type']) ) {
	$reType = $_GET['type'];
		
	//
	// Type = titles, show titles.
	//
	if ($reType == "title") {
		
		if ( isset($_GET['artwork']) ) {
									
			if ($_GET['artwork'] == "paint") {
			
						
				// Query the database for the ORIGINALS information to display.
				$query = "SELECT item_number, title 
						 FROM artwork AS a LEFT JOIN mediums USING ( medium_id ), titles AS t 
						 WHERE a.title_id = t.title_id AND (TYPE = 'painting' OR TYPE = 'construction') AND visible = 1
						 ORDER BY title ASC";
				$result = mysql_query($query);
				$num = mysql_num_rows($result);
				
				if ($num > 0) { // There were paintings found.
					while ($row = mysql_fetch_assoc($result)) {
						$selected = '';
						if ( isset($_GET['inum']) ) {
							if ($_GET['inum'] == $row['item_number']) $selected = " selected";
						};
						echo "<option value='".$row['item_number']."'".$selected.">".$row['title']."</option>\n";
					}
				} else {
					echo "rc=2"; // No records.
				}
			
			
			} else {
			
			
				// Query the database for the REPRODUCTIONS information to display.
				$query = "SELECT DISTINCT title, image_name, CONCAT(first_name, ' ', last_name) as full_name, artist_code
						  FROM mediums LEFT  JOIN artwork AS a USING ( medium_id ) LEFT JOIN artists USING (artist_id), titles AS t, themes AS th
						  WHERE a.title_id = t.title_id AND a.theme_id = th.theme_id AND TYPE = 'print'
						  ORDER BY title ASC";
				$result = mysql_query($query);
				$num = mysql_num_rows($result);
				
				if ($num > 0) { // There were prints found.
					while ($row = mysql_fetch_assoc($result)) {
						$selected = '';
						if ( isset($_GET['inum']) ) {
							if ($_GET['inum'] == $row['image_name']) $selected = " selected";
						};
						echo "<option value='".$row['image_name']."'".$selected.">".$row['title']."</option>\n";
					}
				} else {
					echo "rc=2"; // No records.
				}
			
			} 
		
		} // END TITLE(S) LOOKUP.
	
	
	//
	// Type = 'xml', render XML file.
	//	
	} elseif ($reType == "xml") {
		
		if ( isset($_GET['artwork']) ) {

			
			$reWork = $_GET['artwork'];			
			if ($reWork == "paint") {
				// Open PAINT.XML file
				if (!($fp=@fopen("../../xml/paint.xml", "w"))) die ("rc=1");
				
				// Create initial XML
				$stringData = '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n<paintings>\n";
				fwrite($fp, $stringData);
		
				// Loop though the array of inum's sent.
				$inum_array = $_GET['inum'];
				
				
				for ($x=0;$x<count($inum_array);$x++) {
				
					$item_number = $inum_array[$x];
					// Query the database for the artwork information to display.
					$query = "SELECT item_number, image_name, title, year, CONCAT(first_name, ' ', last_name) as artist
							FROM artwork AS a 
							LEFT JOIN mediums USING ( medium_id )
							LEFT JOIN artists USING ( artist_id ), titles AS t 
							WHERE a.title_id = t.title_id AND (TYPE = 'painting' OR TYPE = 'construction') AND item_number = '$item_number'";
					$result = mysql_query($query);
					$num = mysql_num_rows($result);
										
					if ($num > 0) { // There were paintings found.		
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							$stringData = '<painting id="'.($x+1).'" title="'.$row['title'].'" inum="'.$inum_array[$x].'" date="'.$row['year'].'" artist="'.$row['artist'].'"/>'."\n";
							fwrite($fp, $stringData);
						}
					} else {
						echo "rc=2"; // No records.
					}
		
				}
				$stringData = "</paintings>";
				
				// Write to the file, then close it.
				fwrite($fp, $stringData);
				fclose($fp); // Close file.		
				
				
				echo "rc=0"; // Success
				
					
	
			} else {
				
				
				// Open PRINT.XML file
				if (!($fp=@fopen("../../xml/print.xml", "w"))) die ("rc=1");
	
				
				// Create initial XML
				$stringData = '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n<prints>\n";
				fwrite($fp, $stringData);
		
				// Loop though the array of inum's sent.
				$inum_array = $_GET['inum'];

				for ($x=0;$x<count($inum_array);$x++) {				
					$item_number = $inum_array[$x];
					// Query the database for the artwork information to display.
					$query = "SELECT DISTINCT image_name, title, year, CONCAT(first_name, ' ', last_name) as artist, theme
							  FROM artwork AS a 
							  LEFT JOIN mediums USING ( medium_id ) 
							  LEFT JOIN artists USING ( artist_id ), titles AS t, themes AS th
							  WHERE a.title_id = t.title_id AND TYPE = 'print' AND a.theme_id = th.theme_id AND image_name = '$item_number'";
					$result = mysql_query($query);
					$num = mysql_num_rows($result);
					
					if ($num > 0) { // There were paintings found.		
						while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
							$stringData = '<print id="'.($x+1).'" title="'.$row['title'].'" inum="'.$inum_array[$x].'" date="'.$row['year'].'" artist="'.$row['artist'].'" theme="'.$row['theme'].'"/>'."\n";
							fwrite($fp, $stringData);
						}
					} else {
						echo "rc=2"; // No records.
					}
		
				}
				$stringData = "</prints>";
				// Write to the file, then close it.
				fwrite($fp, $stringData);
				fclose($fp); // Close file.
			
				echo "rc=0"; // Success
				
				
				
			}
			
		} // END XML RE-WRITE	
	
		
	
	} else {
	
		echo "rc=3"; // Incorrect type passed.

	}
	

} else {


	echo "rc=3"; // No type passed.


}
?>