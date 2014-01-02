
$(document).ready(function () {
	
	//
	// Add stylesheet for Datepicker
	//
	$('head').append('<link id="dateLink" href="css/ui.datepicker.css" rel="stylesheet">')
	
	//
	// Start Date / End Date
	//
	$("#event_start_field,#event_end_field").datepicker({
		duration: "fast", 
    	showOn: "both",
		appendText: " (YYYY-MM-DD)",	
		dateFormat: "yy-mm-dd",
		mandatory: true,
		yearRange: '-0:+5',
		hideIfNoPrevNext: true,
		changeFirstDay: false,
		buttonImage: "img/calendar.gif",
		buttonImageOnly: true
	});
	
	  	
});
