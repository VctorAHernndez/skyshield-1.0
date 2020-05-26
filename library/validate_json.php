<?php

	// Taken from: https://codeblogmoney.com/validate-json-string-using-php/
	function json_validator($data = NULL) {
		
		if(!empty($data)) {
			
			// Try naive decode while suppresing errors with @
			@json_encode($data);
			
			return (json_last_error() === JSON_ERROR_NONE);
			
		}
		
		return false;
		
	}