<!-- END BORDER DIV TAGS -->
</div></div></div>


<p class="footer">


<!-- Any questions, contact: support@j2gallery.com or call 773.533.1580<br /> -->
&copy; Copyright <?=$the_date['year']?> Jackson-Junge Gallery<br /><br />
<!-- <a href="home.htm" class="footer">home</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<a href="home.htm" class="footer">events</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<a href="home.htm" class="footer">featured Images</a>
--></p>



<script language="JavaScript" src="js/vendor/jquery-1.2.6.js"></script>
<script language="JavaScript" src="js/plugins.js"></script>
<script language="JavaScript" src="js/main.js"></script>

<!--//
    // Page Specific JavaScript - TO DO: Use Dependency Manager and clean this up!
    //-->
<?php


if ($page_name == "Home") echo '<script language="JavaScript" src="js/admin/login.js">'."\n"
        .'</script><script language="JavaScript" src="js/admin/home.js"></script>';
if (strpos($page_name, "Event")) echo '<script language="JavaScript" src="js/admin/events.js"></script>'."\n"
        .'<script language="JavaScript" src="js/admin/java.js"></script';
if ($page_name == "Originals") echo '<script language="JavaScript" src="js/admin/paint.js"></script>';
if ($page_name == "Add/Edit Original") echo '<script language="JavaScript" src="js/admin/paint-edit.js"></script>';
if ($page_name == "Reproductions") echo '<script language="JavaScript" src="js/admin/print.js"></script>';
if ($page_name == "Add/Edit Reproduction") echo '<script language="JavaScript" src="js/admin/print-edit.js"></script>';

?>


</body>
</html>

<?php
ob_end_flush(); // Flush the buffered output to the Web browser.
?>