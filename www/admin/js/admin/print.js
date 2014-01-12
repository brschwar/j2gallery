
$(document).ready(function () {

	//
	// Alternating row colors on the Table
	//
	var alternateRowColors = function($table) {
		$('tbody tr:odd', $table).removeClass('even').addClass('odd');
		$('tbody tr:even', $table).removeClass('odd').addClass('even');
	};



	//
	//  Drag/Drop
	//
	initTable = function () {

		$(".sortable").each( function() {
			var arrayOfClasses = $(this).attr('class').split(' ');
			$(this).tableDnD({
				onDragClass: "myDragClass",
				onDrop: function(table, row) {

					// Re-color alternate rows.
					$(".sortable").each( function(i) { // Re-color alternate rows.
							alternateRowColors(i);
						});

					// Save the new order to the Database
					var serialAr = $.tableDnD.serialize();
					onDropHandler(serialAr, arrayOfClasses[0]);
				},
				onDragStart: function(table, row) {
					// Nothing.
				},
				dragHandle: "dragHandle",
				scrollAmount: 50
			});
		});


		$(".sortable .dragHandle").hover(function() {
			  $(this).addClass('showDragHandle');
		}, function() {
			  $(this).removeClass('showDragHandle');
		});


		$(".sortable").each( function(i) { // Re-color alternate rows.
				alternateRowColors(i);
			});

	};
	initTable();

	//
	// Update Sort in DB
	//
	onDropHandler = function (serialAr, elem) {
		var theData = serialAr + "&theme="+elem;
		// console.log(theData);

		$.ajax({
		   type: "POST",
		   url: "inc/functions/update_printList.php",
		   data: theData,
		   error: function(msg) {
				// console.log(msg);
				// Print out error.
				$('#errorMsg').append("Sorry, unable to update: "+msg.statusText+"<br /><br />");
		   },
		   success: function(result){
			 // console.log("result: "+result);
			 $('.'+elem).html(result);
			 initTable();
		   }
		 });

	};

});

