
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
		$("#adminTable").tableDnD({
			onDragClass: "myDragClass",
			onDrop: function(table, row) {
				alternateRowColors( "#adminTable" ); // Re-color alternate rows.
	
				// Save the new order to the Database
				var serialAr = $.tableDnD.serialize();
				onDropHandler(serialAr);
			},
			onDragStart: function(table, row) {
				// Nothing.
			},
			dragHandle: "dragHandle",
			scrollAmount: 50
		});

		
		$("#adminTable .dragHandle").hover(function() {
			  $(this).addClass('showDragHandle');
		}, function() {
			  $(this).removeClass('showDragHandle');
		});
		
		alternateRowColors( "#adminTable" );
		
	};
	initTable();

	//
	// Update Sort in DB
	//
	onDropHandler = function (serialAr) {
		// console.log(serialAr);
		
		$.ajax({
		   type: "POST",
		   url: "update_paintList.php",
		   data: serialAr,
		   error: function(msg) {
				// Print out error.
				$('#errorMsg').append("Sorry, unable to update: "+msg.statusText+"<br /><br />");
		   },
		   success: function(result){
			 // console.log(msg);
			 $('#adminTable.sortable').html(result);
			 initTable();
		   }
		 });
	};
	
});







	//
	// Sorting for the Table Headers - DISABLED due to sort order confusion.
	//
	/*	
	$('table.sortable').each(function() {
	
		$('th', $table).each(function(column) {
			var findSortKey;
			if ($(this).is('.sort-alpha')) {
				findSortKey = function($cell) {
					return $cell.find('.sort-key').text().toUpperCase() + ' ' + $cell.text().toUpperCase();
				};
			} else if ($(this).is('.sort-numeric')) {
				findSortKey = function($cell) {
					*/
					// var key = parseFloat($cell.text().replace(/^[^d.]*/, ''));
					/*
					return isNaN(key) ? 0 : key;
				};
			} else if ($(this).is('.sort-date')) {
				findSortKey = function($cell) {
					return Date.parse('1 ' + $cell.text());
				};
			}
			if (findSortKey) {
				$(this).addClass('clickable').hover(function() {
					$(this).addClass('hover');
					}, function() {
							$(this).removeClass('hover');
						}).click(function() {
							var newDirection = 1;
							if ($(this).is('.sorted-asc')) {
								newDirection = -1;
							}
							var rows = $table.find('tbody > tr').get();
							$.each(rows, function(index, row) {
								row.sortKey = findSortKey($(row).children('td').eq(column));
							});
							rows.sort(function(a, b) {			
								if (a.sortKey < b.sortKey) return -newDirection;	
								if (a.sortKey > b.sortKey) return newDirection;		
								return 0;		
							});
							$.each(rows, function(index, row) {
								$table.children('tbody').append(row);
								row.sortKey = null;
							});
							$table.find('th').removeClass('sorted-asc').removeClass('sorted-desc');
							var $sortHead = $table.find('th').filter(':nth-child(' + (column + 1) + ')');
							if (newDirection == 1) {
								$sortHead.addClass('sorted-asc');
							} else {
								$sortHead.addClass('sorted-desc');
							}
							$table.find('td').removeClass('sorted').filter(':nth-child(' + (column + 1) + ')').addClass('sorted');
							alternateRowColors( $table );
						});
					}
		});
		
	});
	*/
