<?php

	// Start Session
	session_start();

	// Load Config File
	require_once("../config.php");
	
	// Check Login State
	checkLogin();
	redirectIfNotAdmin();
	
	// Load Employee Valdiation Functions
	require_once(LIBRARY_PATH . "/validate_employee.php");
	
	// Load Employee Model
	require_once(LIBRARY_PATH . "/models/Employee.php");
	
	// Filter Input
	$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

	// Get User Input
	$eid = $POST['inputEid']; // has to be in the database
	$name = $POST['inputName'];
	$email = $POST['inputEmail']; // validate email
	$phone = $POST['inputPhone']; // remove '-' and '+' and the remaining string has to be numeric of at least length 10
	$hourlyWage = $POST['inputHourlyWage']; // has to be a float between 0 and 1000
	$qualifiesOvertime = isset($POST['inputQualifiesOvertime']); // 'on' or not set
	$editAction = isset($POST['editEmployeeFormSubmit']) && $POST['editEmployeeFormSubmit'] === '1';
	$deleteAction = isset($POST['deleteEmployeeFormSubmit']) && $POST['deleteEmployeeFormSubmit'] === '1';

	// Instantiate Employee Model
	$employeeModel = new Employee();
	
	// Build Parameter String (in case error occurs)
	$parameterString = "name=$name&email=$email&phone=$phone&hourlyWage=$hourlyWage&qualifiesOvertime=$qualifiesOvertime";
	
	
	// You either delete or edit, not both (only happens with users who've tinkered with frontend)
	if(!($editAction xor $deleteAction)) {
		header("Location: " . BASE_URL . "/employees.php?error=unsure");
		exit();
	}
	
	
	// DELETE EMPLOYEE
	if($deleteAction) {


		// Validate	
		redirectIfInvalidEID($eid, 'edition', $parameterString);

		// Remove Employee (false means employee has already clocked in at least once)
		if($employeeModel->remove($eid)) {
			header("Location: " . BASE_URL . "/employees.php?success=deletion");
			exit();
		} else {
			header("Location: " . BASE_URL . "/employees.php?error=deletion");
			exit();
		}


	}
	
	
	// EDIT EMPLOYEE
	if($editAction) {
	
	
		// Validate
		redirectIfInvalidEID($eid, 'edition', $parameterString);
		redirectIfEmptyName($name, 'edition', $parameterString);
		redirectIfInvalidEmail($email, 'edition', $parameterString, $eid);
		redirectIfInvalidPhone($phone, 'edition', $parameterString);
		redirectIfInvalidHourlyWage($hourlyWage, 'edition', $parameterString);
			
			
		// Remove '-' (separators) and '+' (extensions)
		$phone = str_replace('-', '', $phone);
		$phone = str_replace('+', '', $phone);
		$phone = ($phone === '') ? NULL : $phone;
			
			
		// Set hourlyWage to 0 if empty
		$hourlyWage = ($hourlyWage === '') ? 0 : $hourlyWage;
			
			
		// Edit Employee Details
		$employeeModel->setDetails($eid, $name, $email, $phone, $hourlyWage, $qualifiesOvertime);
				
				
		// Redirect back to employees and prompt user to store random password
		header("Location: " . BASE_URL . "/employees.php?success=edition");
		exit();
		
		
	}


