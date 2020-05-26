function setLoadingState(button, promptElement, commentInput) {

	// Create Loader
	let loader = document.createElement('div');
	loader.classList.add('spinner-border');
	loader.classList.add('text-secondary'); // for style
	loader.classList.add('my-2'); // for alignment
	loader.setAttribute('role', 'status'); // for accessibility
			
	// Create fallback content for loader
	let fallback = document.createElement('span');
	fallback.classList.add('sr-only'); // for style
	fallback.innerText = 'Loading...';
			
	// Insert fallback content inside loader
	loader.appendChild(fallback);
	
	// Hide prompt message
	promptElement.style.visibility = 'hidden';
	
	// Lock comment textarea (if it exists)
	if(commentInput) {
		commentInput.disabled = true;
	}
	
	// Put loader adjacent to button and remove button
	button.insertAdjacentElement('afterend', loader);
	button.remove();
	
	// Return loader for future manipulation
	return loader;
	
}
		
function setDoneState(loader, successMsg, commentInput) {

	// Create Done!
	let h5 = document.createElement('h5');
	let sm = document.createElement('sm');
	sm.classList.add('text-success');
	sm.innerText = successMsg;
	h5.appendChild(sm);
	
	// Remove comment textarea (if it exists)
	if(commentInput) {
		commentInput.remove();
	}
        			
	// Insert Done! and remove loader
	loader.insertAdjacentElement('afterend', h5);
	loader.remove();
	
}
		
function setRetryState(loader, promptElement, failMsg, leaving, commentInput) {

	// Set context color depending on if leaving or not
	let context = leaving ? 'btn-danger' : 'btn-primary'; // for style
	
	// Set the function the button should have on click
	let fn = leaving ? 'leave(event)' : 'enter(event)';

	// Create Retry Button
	let retry = document.createElement('button');
	retry.className = `btn btn-lg ${context} btn-block`;
	retry.setAttribute('onclick', fn);
	retry.innerText = 'Retry';
    
    // Change prompt message to failure message
    promptElement.innerText = failMsg;
    promptElement.style.visibility = 'initial';
    
    // Unlock comment textarea (if it exists)
    if(commentInput) {
    	commentInput.disabled = false;
    }
    
	// Insert Retry and remove loader
	loader.insertAdjacentElement('afterend', retry);
	loader.remove();
	
}
	
function fillIn(event, leaving) {

	/* Fetch elements and set info */
	let button = event.currentTarget;
	let promptElement = document.getElementById('prompt-text'); // button.previousElementSibling;
	let successMsg = leaving ? 'Thank you for your hard work.'
							 : 'Thanks, have a great day.';
	let failMsg = 'Oops! Something went wrong...';
	let baseURI = document.location.protocol + '//ada.uprrp.edu/~victor.hernandez17/skyshield/processes';
	let fullURI = baseURI + (leaving ? '/clockOut.php' : '/clockIn.php');
	let pidInput = document.querySelector('input[name="pid"]'); // null for clocking out
	let ciidInput = document.querySelector('input[name="ciid"]'); // null for clocking in
	let commentInput = document.querySelector('textarea[name="comment"]'); // null for clocking in
	let fields = leaving ? {ciid: ciidInput.value, comment: commentInput.value} : {pid: pidInput.value};
	let config = {
		method: 'POST',
// 		mode: 'no-cors',
// 		cache: 'no-cache',
		headers: {
			'Content-Type': 'application/json'
			//'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: JSON.stringify(fields)
	};
	
	console.log(JSON.stringify(fields));
	

	/* Set Loading State */
	let loader = setLoadingState(button, promptElement, commentInput);
	

	/* Make Request To Server */	
	// Unset Loading State
	// - Success: Display Checkmark and success message
	// - Fail: Display Retry Button and fail message
	
	fetch(fullURI, config)
		.then(response => {
			if(response.ok) {
				setDoneState(loader, successMsg, commentInput);
			} else {
				setRetryState(loader, promptElement, failMsg, leaving, commentInput);
				response.text().then(text => console.log(text));
			}
		})
		.catch(error => {
			setRetryState(loader, promptElement, failMsg, leaving, commentInput);
			console.error(error);
		});

}

function enter(event) {
	fillIn(event, false);
}

function leave(event) {
	fillIn(event, true)
}