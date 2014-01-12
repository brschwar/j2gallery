
$(document).ready(function () {
		
	// 
	// ToolTip
	//
	tooltip();
	
	
	//
	// Prepare Fields
	//
	// $('fieldset').css({'display' : 'none'}); // Hide "NEW" Forms (now done in PHP)
	$('#SORT_ORDER').focus(function(){ this.blur(); });
	
	
	//
	// SELECT Element Change Event ('ADD NEW')
	//
	$('select').each(function(i){
			$(this).change(function () { 
					var theVal = $('#'+$(this).attr('id')+' :selected').text();
					if (theVal == 'ADD NEW') {
						$( '#'+$(this).attr('id') + '_ADD').show();
						$( '#'+$(this).attr('id') + '_FIELD').css({'display' : 'none'});
					}
					return false;
				});

		});
	
	//
	// Title_ID Select: Change Event to change year.
	//
	$('#TITLE_ID').change(function(e) {
			var theVal = $('#'+$(this).attr('id')+' :selected');
			// console.log(theVal);
		});
	
	
	//
	// CANCEL Button Click Event
	//
	$('.cancelBttn').each(function(i){
		$(this).click(function () { 
					var theElem = '#'+$(this).parent().parent().attr('id');
					var indNum = theElem.indexOf('_ADD');
					$( theElem ).css({'display' : 'none'});
					$( theElem.substring(0, indNum) + '_FIELD' ).show();

					console.log( "the element: "+$( theElem.substring(0, indNum) + " option:selected").attr('value') );

					$( theElem.substring(0, indNum) + " option:selected").attr('selected', 'selected');
					return false;
				});
		});

	
	//
	// ADD MORE Button Click Event
	//
	$('.addMoreBttn').click(function () { 

		var s = $(this).attr('href');
		var q = s.substring(s.indexOf('?') + 1);
		var qVars = Array();
			jQuery.each(q.split('&'), function() {
					var splitted = this.split('=');
					qVars[splitted[0]] = splitted[1];
				});
		
	
			var newFieldSet = $("#PRINT_0_FIELD").clone();
			
			newFieldSet.attr("id","PRINT_"+qVars.n+"_FIELD").css({"display": "block"})
				.find("label[for='ITEM_NUMBER_0']").attr("for","ITEM_NUMBER_"+qVars.n).removeClass()
				.end()
				.find("#ITEM_NUMBER_0").attr("name","ITEM_NUMBER["+qVars.n+"]").attr("id","ITEM_NUMBER_"+qVars.n).attr("value","")
				.end()
				.find("#MEDIUM_ID_0_FIELD").attr("id","MEDIUM_ID_"+qVars.n)
				.end() // End Medium Fieldset
				.find("label[for='MEDIUM_ID_0']").attr("for","MEDIUM_ID_"+qVars.n)
				.end() // End medium label
				.find("#MEDIUM_ID_0").attr("name","MEDIUM_ID["+qVars.n+"]").attr("id","MEDIUM_ID_"+qVars.n).attr("value","")
				.end() // End Medium select 
				// NEED TO ATTACH EVENT FOR SELECT BUTTON? FIGURE OUT ERRORS - Might be with close button...
				.find("label[for='SIZE_0']").attr("for","ITEM_NUMBER_"+qVars.n).removeClass()
				.end()
				.find("#SIZE_0").attr("name","SIZE["+qVars.n+"]").attr("id","SIZE_"+qVars.n).attr("value","")
				.end()
				.find("label[for='PRICE_0']").attr("for","PRICE_"+qVars.n).removeClass()
				.end()
				.find("#PRICE_0").attr("name","PRICE["+qVars.n+"]").attr("id","PRICE_"+qVars.n).attr("value","")
				.end()
				.find("#ARTWORK_ID_0").attr("name","ARTWORK_ID["+qVars.n+"]").attr("id","ARTWORK_ID_"+qVars.n).attr("value","")
				.end()
				.find("#DELETE_0").attr("name","DELETE["+qVars.n+"]").attr("id","DELETE_"+qVars.n).attr("value","FALSE")
				.end()
				.find("#REMOVE_0").attr("id","REMOVE"+qVars.n).attr("name","REMOVE_"+qVars.n).click(function () { 
					var theElem = "#"+$(this).parent().attr('id');
					// console.log('remove attached');
					$( theElem ).css({'display' : 'none'});
					$( theElem ).find("#DELETE_"+qVars.n).val("TRUE");
					return false;
				});
	
			newFieldSet.insertAfter("#PRINT_"+(parseInt(qVars.n, 10)-1)+"_FIELD");
			// console.log( (parseInt(qVars.n)-1) );
						
			$(this).attr( 'href', '/j2gallery_NEW/www/admin/print_edit.php?c=addMore&n='+(parseInt(qVars.n, 10)+1) );
			$('#NUMBER').attr("value", (parseInt(qVars.n, 10)+1) );

			return false;
				
		});
	


	//
	// REMOVE Button Click Event
	//
	$('.removeBttn').each(function(i){
		$(this).click(function () { 
					var theElem = '#'+$(this).parent().attr('id');
					$( theElem ).css({'display' : 'none'});
					$( theElem ).find("input:hidden").each(function(i){
							var theInput = $(this).attr('name');
							if (theInput.search('DELETE') != -1) {
								$(this).val("TRUE");
							}
						});					
					return false;
				});
		});

	
	//
	// HIDE Print Info if "Deleted/Removed"
	//
	$('input:hidden').each(function(i){
			var theInput = $(this).attr('name');
			if ($(this).val() == "TRUE") {
				var theElem = "#"+$(this).parent().attr('id');
				$( theElem ).css({'display' : 'none'});
			}
		});
	
});


