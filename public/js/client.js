// Validate new client and submit
function validateNewClient() {
	
	// Store reference to form
	let form = $('#newClientForm');
	
	// Validate only required fields
	let valid = true;
	valid &= $('#inputNewClientName').prop('validity').valid;
	valid &= $('#inputNewClientAlias').prop('validity').valid;
	valid &= $('#inputNewClientAddress').prop('validity').valid;
	valid &= $('#inputNewClientAlias').prop('validity').valid;
	valid &= $('#inputNewContactPhone').prop('validity').valid;
	valid &= $('#inputNewClientEmail').prop('validity').valid;
	
	// Submit if valid
	// ... or add 'is-invalid' classes to inputs
	if(valid) {
		form.submit();
	} else {
		$('#inputNewClientName').addClass('is-invalid');
		$('#inputNewEmailAlias').addClass('is-invalid');
		$('#inputNewClientAddress').addClass('is-invalid');
		$('#inputNewClientAlias').addClass('is-invalid');
		$('#inputNewContactPhone').addClass('is-invalid');
		$('#inputNewClientEmail').addClass('is-invalid');
	}

}