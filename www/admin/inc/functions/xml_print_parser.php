<?php

// Open file and XML parser. Set-up global variables.
if (!($fp=@fopen("print.xml", "r"))) die ("Couldn't open XML.");
$printCount=0;
$printData=array();
$state='';

// Takes care of the attributes of the element.
// Handler for begining of the element.
function startPrintElementHandler ($parser,$name,$attrib){
	global $printCount;
	global $printData;
	global $state;

	// echo "printCount=".$printCount."<br />";
	// echo "name=".$name."<br />";
	// echo "attribute=".$attrib["TITLE"]."<br />";
	switch ($name) {
        case $name=="PRINT" : {
		$printData[$printCount]["id"] = $attrib["ID"];
		$printData[$printCount]["title"] = $attrib["TITLE"];
		$printData[$printCount]["inum"] = $attrib["INUM"];
		$printData[$printCount]["date"] = $attrib["DATE"];
		$printData[$printCount]["artist"] = $attrib["ARTIST"];
		$printData[$printCount]["theme"] = $attrib["THEME"];
		break;
	  }
	}
	// default : {$state=$name;break;}
}
// Handler for end of the element.
function endPrintElementHandler ($parser,$name){
	global $printCount;
	global $printData;
	global $state;
	$state='';
	if($name=="PRINT") {$printCount++;}
}

// Handler for character data within elements.
// Not used with this xml document.
function characterPrintDataHandler ($parser, $data) {
	global $printCount;
	global $printData;
	global $state;
	if (!$state) {return;}
	// if ($state=="COMPANY") { $printData[$printCount]["bcompany"] = $data;}
}

// Read the data from the file, and parse it with above funcions.
if (!($xml_parser = xml_parser_create())) die("Couldn't create parser.");
xml_set_element_handler($xml_parser,"startPrintElementHandler","endPrintElementHandler");
xml_set_character_data_handler( $xml_parser, "characterPrintDataHandler");

while( $data = fread($fp, 4096)){
	if(!xml_parse($xml_parser, $data, feof($fp))) {
		break;
	}
}
xml_parser_free($xml_parser);
?>

<html>
<head><title>xml test</title></head>
<body>

<?php
// print out the contents of $printCount.
for ($i=0;$i<$printCount; $i++) {
	echo "<b>".$printData[$i]["id"]."&nbsp;&nbsp;".$printData[$i]["inum"] ."</b>&nbsp;&nbsp;". $printData[$i]["title"].": ". $printData[$i]["theme"]."<br>";
}
?>
</body>
</html>