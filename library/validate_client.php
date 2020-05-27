<?php

	function redirectIfInvalidCID($cid, $action, $parameterString) {
	
		// Decide if to append or create parameter string
		if($action === 'clientEdit') {
			$redirect = '&';
		} elseif($action === 'client') {
			$redirect = '?';
		}
	
	
		// Check that eid isn't empty
		if($cid === '') {
			header("Location: " . $_SERVER['HTTP_REFERER'] . $redirect . "error=$action&$parameterString");
			exit();
		}
		
		// Check that eid exists
		$clientModel = new Client();
		if(!$clientModel->getByID($cid)) {
			header("Location: " . $_SERVER['HTTP_REFERER'] . $redirect . "error=$action&$parameterString");
			exit();
		}
		
	}

	function redirectIfEmpty($string, $action, $parameterString) {
	
		// Decide if to append or create parameter string
		if($action === 'clientEdit') {
			$redirect = '&';
		} elseif($action === 'client') {
			$redirect = '?';
		}
	
		// Check that name isn't empty
		if($string === '') {
			header("Location: " . $_SERVER['HTTP_REFERER'] . $redirect . "error=$action&$parameterString");
			exit();
		}
		
	}
	
	function redirectIfInvalidEmail($email, $action, $parameterString) {
	
		// Decide if to append or create parameter string
		if($action === 'clientEdit') {
			$redirect = '&';
		} elseif($action === 'client') {
			$redirect = '?';
		}
	
		// Check that email isn't empty
		if($email === '') {
			header("Location: " . $_SERVER['HTTP_REFERER'] . $redirect . "error=$action&$parameterString");
			exit();
		}
	
		// Validate with PHP filter
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			header("Location: " . $_SERVER['HTTP_REFERER'] . $redirect . "error=$action&$parameterString");
			exit();
		}
		
	}
	
	function redirectIfInvalidPhone($phone, $action, $parameterString) {
	
		// Decide if to append or create parameter string
		if($action === 'clientEdit') {
			$redirect = '&';
		} elseif($action === 'client') {
			$redirect = '?';
		}
	
		// First remove the + or -
		$phone = str_replace('-', '', $phone);
		$phone = str_replace('+', '', $phone);
	
		// If phone isn't empty, check if valid or not
		if($phone !== '') {
			if(filter_var($phone, FILTER_VALIDATE_INT)) {
				if(strlen($phone) !== PHONE_LENGTH) {
					header("Location: " . $_SERVER['HTTP_REFERER'] . $redirect . "error=$action&$parameterString");
					exit();
				}
			} else {
				header("Location: " . $_SERVER['HTTP_REFERER'] . $redirect . "error=$action&$parameterString");
				exit();
			}
		}
	
	}
	
	function redirectIfInvalidUnitPrice($hourlyWage, $action, $parameterString) {

		// If hourlyWage isn't empty, check if valid or not
		if($hourlyWage !== '') {
			if(filter_var($hourlyWage, FILTER_VALIDATE_FLOAT) >= 0) { // filter returns the number perse so 0.00 evaluates as negative
				if((float)$hourlyWage > MAX_WAGE) {
					header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
					exit();
				}
			} else {
				header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
				exit();
			}	
		}
	
	}