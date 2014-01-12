	<div id="sideNavBorder"><div id="sideNav">
	<?php #SideNav.php
	
	// Display links based upon the log-in status.
	if ($userLoggedIn == "true") {
		echo '<ul>
		<li class="sideNav"><a href="../index.php" class="text">J2Gallery.com</a></li>
		<li class="sideNav"><a href="logout.php" class="text">Logout</a></li><br />';
		
		echo '<hr class="linebreak" />';
		
		if ($section_title == "Home") {
			echo '<li class="sideNav"><span class="text"><b>Admin Home</b></span></li><br />';
		} else {
			echo '<li class="sideNav"><a href="index.php" class="text">Admin Home</a></li><br />';
		}
		
		echo '<hr class="linebreak" />';
		
		if ($section_title == "Events") {
			echo '<li class="sideNav"><span class="text"><b>Events &#187;</b></span>';
		} else {
			echo '<li class="sideNav"><span class="text">Events &#187;</span>';
		}
		echo '<ul>
			<li class="sideNavSub"><a href="events.php" class="text">View Events</a></li>
			<li class="sideNavSub"><a href="event_add.php" class="text">Add An Event</a></li>
			</ul>
		</li><br />';
		
		echo '<hr class="linebreak" />';
		
		if ($section_title == "Originals") {
			echo '<li class="sideNav"><span class="text"><b>Originals &#187;</b></span>';
		} else {
			echo '<li class="sideNav"><span class="text">Originals &#187;</span>';
		}
		echo '<ul>
			<li class="sideNavSub"><a href="paint.php" class="text">View Originals</a></li>
			<li class="sideNavSub"><a href="paint_edit.php" class="text">Add an Original</a></li>
			</ul>
		</li><br />';
		
		
		echo '<hr class="linebreak" />';
		
		if ($section_title == "Reproductions") {
			echo '<li class="sideNav"><span class="text"><b>Reproductions &#187;</b></span>';
		} else {
			echo '<li class="sideNav"><span class="text">Reproductions &#187;</span>';
		}
		echo '<ul>
			<li class="sideNavSub"><a href="print.php" class="text">View Reproductions</a></li>
			<li class="sideNavSub"><a href="print_edit.php" class="text">Add a Reproduction</a></li>
			</ul>
		</li><br />';
		
				
		echo '</ul>
		<br />';
	} else {
		echo '<ul>
		<li class="sideNav"><a href="../index.php" class="text">J2Gallery.com</a></li>
		<li class="sideNav"><span class="text"><b>Login</b></span></li>
		</ul>';
	}

	?>
	</div></div>