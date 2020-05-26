// Select all links with hashes
$('a[href*="#"]')
	// Remove links that don't actually link to anything
	.not('[href="#"]')
	.not('[href="#0"]')
	.click(function(event) {
		// On-page links
		if (
			location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
			&& 
			location.hostname == this.hostname
		) {
			// Figure out element to scroll to
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			// Does a scroll target exist?
			if (target.length) {
				// Only prevent default if animation is actually gonna happen
				event.preventDefault();
				$('html, body').animate({
					scrollTop: target.offset().top
				}, 1000, function() {
					// Callback after animation
					// Must change focus!
					var $target = $(target);
					$target.focus();
					if ($target.is(":focus")) { // Checking if the target was focused
						return false;
					} else {
						$target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
						$target.focus(); // Set focus again
					};
				});
			}
		}
	});
	
	
// Listen for clicks on each row
$('#employee-table tbody tr')
	.click(function(event) {
		// Prevent triggering for email or phone links
		if(
			event.target.tagName === 'A'
			&&
			(
				event.target.href.includes('mailto')
				||
				event.target.href.includes('tel')
			)
		) {
			return;
		}
		
		// Store reference to clicked table row
		let tableRow = $(event.currentTarget);
		
		// Format Phone (assumes 10 digit integer)
		let phone = String($(tableRow).data('phone'));
		
		if(phone) {
			phone = phone.substr(0, 3)
					+ '-' + phone.substr(3, 3)
					+ '-' + phone.substr(6, 4);
		}
		
		// Set the variables for the edit form
		$('#inputEid').val($(tableRow).data('eid'));
		$('#inputName').val($(tableRow).data('name'));
		$('#inputEmail').val((tableRow).data('email'));
		$('#inputPhone').val(phone); // previously $(tableRow).data('phone')
		$('#inputHourlyWage').val($(tableRow).data('hourlywage'));
		$('#inputQualifiesOvertime').prop('checked', $(tableRow).data('qualifiesovertime'));

		// Enable inputs
		$('#inputName').prop('disabled', false);
		$('#inputEmail').prop('disabled', false);
		$('#inputPhone').prop('disabled', false);
		$('#inputHourlyWage').prop('disabled', false);
		$('#inputQualifiesOvertime').prop('disabled', false);
		$('#editEmployeeFormSubmit').prop('disabled', false);
		$('#deleteEmployeeFormSubmit').prop('disabled', false);
		
		// Set focus
		$('html, body').animate({
			scrollTop: $('#employee-details').offset().top
		}, 500);
		
	});

// Validate new employee and submit
function validateNewEmployee() {
	
	// Store reference to form
	let form = $('#newEmployeeForm');
	
	// Validate only required fields
	let valid = true;
	valid &= $('#inputNewName').prop('validity').valid;
	valid &= $('#inputNewEmail').prop('validity').valid;
	
	// Submit if valid
	// ... or add 'is-invalid' classes to inputs
	if(valid) {
		form.submit();
	} else {
		$('#inputNewName').addClass('is-invalid');
		$('#inputNewEmail').addClass('is-invalid');
	}

}

// On New Employee Modal Show
$('#exampleModal').on('show.bs.modal', function (event) {
// 	var button = $(event.relatedTarget) // Button that triggered the modal
// 	var recipient = button.data('whatever') // Extract info from data-* attributes
	// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
// 	var modal = $(this)
// 	modal.find('.modal-title').text('New message to ' + recipient)
// 	modal.find('.modal-body input').val(recipient)
});