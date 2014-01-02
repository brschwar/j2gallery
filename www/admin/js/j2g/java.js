// form check for updatable homepage.
function validateForm(form) {
	var alertMessage = '';
	if (form == "login") {
		with (document.formName) {
			if (username.value == '') {
				alertMessage += "Username\n";
			}
			if (password.value == '') {
				alertMessage += "Password\n";
			}

			if (alertMessage != '') {
				submited.value = "";
				alert ("Please fill out the following:\n" + alertMessage);
			} else {
				submited.value = "y";
				submit();
			}
		}
	} else if (form == "addEvent") {
		with (document.formName) {
			if (event_start.value == '') {
				alertMessage += "Start Date\n";
			}
			if (event_end.value == '') {
				alertMessage += "End Date\n";
			}
			if (event_title.value == '') {
				alertMessage += "Event Name\n";
			}
			if (alertMessage != '') {
				submited.value = "";
				alert ("Please fill out the following:\n" + alertMessage);
			} else {
				submited.value = "y";
				submit();
			}
		}
	}
}