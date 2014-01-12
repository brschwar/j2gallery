<?php

// Open file and XML parser. Set-up global variables.
if (!($fp=@fopen("paint.xml", "r"))) die ("Couldn't open XML.");
$paintCount=0;
$paintData=array();
$state='';

// Takes care of the attributes of the element.
// Handler for begining of the element.
function startPaintElementHandler ($parser,$name,$attrib){
	global $paintCount;
	global $paintData;
	global $state;

	// echo "paintCount=".$paintCount."<br />";
	// echo "name=".$name."<br />";
	// echo "attribute=".$attrib["TITLE"]."<br />";
	switch ($name) {
        case $name=="PAINTING" : {
		$paintData[$paintCount]["id"] = $attrib["ID"];
		$paintData[$paintCount]["title"] = $attrib["TITLE"];
		$paintData[$paintCount]["inum"] = $attrib["INUM"];
		$paintData[$paintCount]["date"] = $attrib["DATE"];
		$paintData[$paintCount]["artist"] = $attrib["ARTIST"];
		break;
	  }
	}
	// default : {$state=$name;break;}
}
// Handler for end of the element.
function endPaintElementHandler ($parser,$name){
	global $paintCount;
	global $paintData;
	global $state;
	$state='';
	if($name=="PAINTING") {$paintCount++;}
}

// Handler for character data within elements.
// Not used with this xml document.
function characterPaintDataHandler ($parser, $data) {
	global $paintCount;
	global $paintData;
	global $state;
	if (!$state) {return;}
	if ($state=="COMPANY") { $paintData[$paintCount]["bcompany"] = $data;}
}

// Read the data from the file, and parse it with above funcions.
if (!($xml_parser = xml_parser_create())) die("Couldn't create parser.");
xml_set_element_handler($xml_parser,"startPaintElementHandler","endPaintElementHandler");
xml_set_character_data_handler( $xml_parser, "characterPaintDataHandler");

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
// print out the contents of $paintCount.
for ($i=0;$i<$paintCount; $i++) {
	echo "<b>".$paintData[$i]["id"]."&nbsp;&nbsp;".$paintData[$i]["inum"] ."</b>&nbsp;&nbsp;". $paintData[$i]["title"]."<br>";
}
?>
</body>
</html>