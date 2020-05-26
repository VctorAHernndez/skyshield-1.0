<?php

	function redirectIfInvalidEID($eid, $action, $parameterString) {
	
		// Check that eid isn't empty
		if($eid === '') {
			header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
			exit();
		}
		
		// Check that eid exists
		$employeeModel = new Employee();
		if(!$employeeModel->getByID($eid)) {
			header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
			exit();
		}
		
	}

	function redirectIfEmptyName($name, $action, $parameterString) {
	
		// Check that name isn't empty
		if($name === '') {
			header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
			exit();
		}
		
	}
	
	function redirectIfInvalidEmail($email, $action, $parameterString, $eid = '') {
	
		// Check that email isn't empty
		if($email === '') {
			header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
			exit();
		}
	
		// Validate with PHP filter
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
			exit();
		}
		
		// Check that email isn't already registered
		if($action === 'edition') {
		
			// Fetch employee
			$employeeModel = new Employee();
			$employee = $employeeModel->getByEmail($email);
			
			// If requested email already corresponds to another employee, trow error
			if($employee->eid !== $eid) {
				header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
				exit();
			}
		
		} elseif($action === 'addition') {
		
			$employeeModel = new Employee();
			if($employeeModel->getByEmail($email)) {
				header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
				exit();
			}
			
		}
		
		
	}
	
	function redirectIfInvalidPhone($phone, $action, $parameterString) {
	
		// First remove the + or -
		$phone = str_replace('-', '', $phone);
		$phone = str_replace('+', '', $phone);
	
		// If phone isn't empty, check if valid or not
		if($phone !== '') {
			if(filter_var($phone, FILTER_VALIDATE_INT)) {
				if(strlen($phone) !== PHONE_LENGTH) {
					header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
					exit();
				}
			} else {
				header("Location: " . BASE_URL . "/employees.php?error=$action&$parameterString");
				exit();
			}
		}
	
	}
	
	function redirectIfInvalidHourlyWage($hourlyWage, $action, $parameterString) {

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