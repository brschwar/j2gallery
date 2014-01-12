(function($) {
    $(window).load(function(event) {

	//
	// Create then hide update buttons
	//
	$('#paintListSaveBtn > a').click(function () {
			 var result = $('#paintList').sortable('toArray');
			 updateXML(result, "#paintList");
			 return false;
    	});
	$('#paintListSaveBtn').hide();


	$('#printListSaveBtn > a').click(function () {
			 var result = $('#printList').sortable('toArray');
			 updateXML(result, "#printList");
			 return false;
    	});
	$('#printListSaveBtn').hide();



	//
	// GET DATA
	//
	$.ajax({
		//
		// GET ORIGINALS (paintings) FROM XML
		//
		url: '../xml/paint.xml',
		type: 'GET',
		dataType: 'xml',
		timeout: 1000,
		error: function(){
			$('#paintListMsg').children().remove();
			$('<p></p>')
				.html('Error loading "Featured Originals" XML document')
				.addClass('errortext')
				.appendTo('#paintListMsg');
		},
		success: function(xml){
			$(xml).find('painting').each(function(i){
				var paint_inum = $(this).attr('inum');
				var paint_title = $(this).attr('title');

				var pos = $('<div></div>').addClass('listPos').text(i+1);
				var inum = $('<div></div>').addClass('listNum').text(paint_inum);
				var title = $('<div></div>').addClass('listTitle').text(paint_title);
				var editBtn = $('<div></div>').addClass('listEdit').html('<a class="redLink" href="#">[edit]</a>');
				$('<li></li>')
					.attr('id', paint_inum)
					.addClass('listItem')
					.appendTo('#paintList').appendTo('#paintList').append(pos, inum, title, editBtn)
					.children('.listEdit').children('a').click(function(e) {
							if ( $(e.target).html() == "[edit]" ) {
								editButtonHandler(e, "#paintList");
							} else {
								updateButtonHandler(e, "#paintList");
							};
							return false;
						});
			});

			sortList("#paintList");
		}
	});

	$.ajax({
		 //
		// GET REPRODUCTIONS (prints) FROM XML
		//
		url: '../xml/print.xml',
		type: 'GET',
		dataType: 'xml',
		timeout: 1000,
		error: function(){
			$('#printListMsg').children().remove();
			$('<p></p>')
				.html('Error loading "Featured Reproductions" XML document')
				.addClass('errortext')
				.appendTo('#printListMsg');
		},
		success: function(xml){
			$(xml).find('print').each(function(i){
				var print_inum = $(this).attr('inum');
				var print_title = $(this).attr('title');
				var print_theme = $(this).attr('theme');

				var pos = $('<div></div>').addClass('listPos').text(i+1);
				var inum = $('<div></div>').addClass('listNum').text(print_inum);
				var title = $('<div></div>').addClass('listTitle').text(print_title);
				// var theme = $('<div></div>').addClass('listTheme').text(print_theme);
				var editBtn = $('<div></div>').addClass('listEdit').html('<a class="redLink" href="#">[edit]</a>');
				$('<li></li>')
					.attr('id', print_inum)
					.addClass('listItem')
					.appendTo('#printList').append(pos, inum, title, editBtn)
					.children('.listEdit').children('a').click(function(e) {
							if ( $(e.target).html() == "[edit]" ) {
								editButtonHandler(e, "#printList");
							} else {
								updateButtonHandler(e, "#printList");
							};
							return false;
						});
			});

			sortList("#printList");
		}
	});


	//
	// Add Actions to Edit Buttons
	//
	function editButtonHandler(event, list) {

		$(list+'Msg').children().remove(); // Clear any messages.
		var $target = $(event.target).parent().parent();
		var inum = $($target).attr("id");
		getTitles( $('#'+inum+' > .listTitle'), inum, list); // Title - pulldown
		$(list+'SaveBtn').hide('fast'); // Remove Save Button while editing.

		$('#'+inum).removeClass().addClass("listUpdate");
		$('#'+inum+' > .listNum').css("color","#bebcb2"); // inum
		$(event.target).html("[update]"); // 'edit' button - change to 'update'

		$($target).parent().sortable("disable")// Disable sort for this item.

	};

	function updateButtonHandler(event, list) {

		var $target = $(event.target).parent().parent();
		var inum = $($target).attr("id");
		var selectedTitle = $('#'+inum+' option:selected').text(); // Title from Select
		var selectedValue = $('#'+inum+' option:selected').attr("value"); // Value from Select

		$('#'+inum).removeClass().addClass("listItem");
		$('#'+inum+' > .listNum').css("color","#666666").html(selectedValue); // inum
		$('#'+inum+' > .listTitle').html(selectedTitle); // Title
		$(event.target).html("[edit]"); // 'edit' button - change to 'update'

		// Change the ID of the LI item.
		$($target).attr("id", selectedValue); // LI id value

		$($target).parent().sortable("enable")// Enable sort for this item.

		changeHandler(list); // Show Save Button

	};

	//
	// Gets Titles from Database.
	//
	function getTitles (elem, inum, list) {
		if (list  == '#paintList') {
			$.ajax({ // IF ORIGINALS
				url: 'inc/functions/featured_artwork.php',
				data: 'type=title&artwork=paint&inum='+inum,
				type: 'GET',
				dataType: 'html',
				timeout: 1000,
				error: function(){
					$('#paintListMsg').children().remove();
					$('<p></p>')
						.html('Error getting Titles. Please try again later.')
						.addClass('errortext')
						.appendTo('#paintListMsg');
				},
				success: function(html){
					var $str = "<select class='titleSelect' id='"+inum+"'>"+html+"</select>";
					elem.html($str);
				}
			});
		} else {
			$.ajax({  // IF REPRODUCTIONS
				url: 'inc/functions/featured_artwork.php',
				data: 'type=title&artwork=print&inum='+inum,
				type: 'GET',
				dataType: 'html',
				timeout: 1000,
				error: function(){
					$('#paintListMsg').children().remove();
					$('<p></p>')
						.html('Error getting Titles. Please try again later.')
						.addClass('errortext')
						.appendTo('#printListMsg');
				},
				success: function(html){
					var $str = "<select class='titleSelect' id='"+inum+"'>"+html+"</select>";
					elem.html($str);
				}
			});
		}
	};



	//
	// Create Sortable List
	//
	function sortList(list) {
		$(list).sortable({

			placeholder: "sortList-selected",
			axis: "y",
			cursor: "move",
			revert: true,
			change: function(e) {
					changeHandler(list);
				},
			start: function(e) {
					toggleEditButton(list);
				},
			stop: function(e) {
					stopHandler(list);
				}

		});
	};


	//
	// ChangeHandler: Show Update Button
	//
	function changeHandler(list) {
		$(list+'SaveBtn').slideDown('fast');
		$(list+'Msg').children().remove(); // Clear any messages.
	};


	//
	// StopHandler: Reorder Number in list
	//
	function stopHandler(list) {
		$(list+' li').each(function(i){
				$(this).children().each(function(){
					if ($(this).attr('class')=="listPos") $(this).html(i+1);
				});
			});
		toggleEditButton(list); // Show Edit Buttons
	};


	//
	// Toggle Edit Button When Dragging
	//
	function toggleEditButton(list) {
		$(list + ' > li > .listEdit').toggle();
	};


	//
	// Updates the XML files
	//
	function updateXML(result, list) {
		// console.log(result);
		var artType = (list == "#paintList") ? "paint" : "print";
		var dataStr = 'type=xml&artwork='+artType;
		jQuery.each(result, function(index, value) {
		   dataStr += ("&inum[]=" + value);
		 });

		$.ajax({
			url: 'inc/functions/featured_artwork.php',
			data: dataStr,
			type: 'GET',
			dataType: 'html',
			timeout: 1000,
			error: function(){
				$('<p></p>')
					.html('<p class="errortext">Error saving File. Please try again later.</p>')
					.appendTo(list+'Msg');
			},
			success: function(html){
				$(list+'Msg').children().remove();
				if (html.indexOf("rc=0") > 0) {
					$(list+'SaveBtn').slideUp('fast');
					$('<p></p>')
						.html('Update Successful')
						.addClass('greentext')
						.appendTo(list+'Msg');
				} else {
					$('<p></p>')
						.html('Error saving File. Please try again later.<br />'+html+'!')
						.addClass('errortext')
						.appendTo(list+'Msg');
				}
			}
		});

	};


    });
})(jQuery);
