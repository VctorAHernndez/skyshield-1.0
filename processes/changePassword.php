<?php

	// Start Session
	session_start();

	// Load Config File
	require_once("../config.php");
	
	// Check Login State
	checkLogin();
	
	// Load Employee Model
	require_once(LIBRARY_PATH . "/models/Employee.php");
	
	// Filter Input
	$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	
	// Get User Input
	$oldPassword = $POST['oldPassword'];
	$newPassword = $POST['newPassword'];
	$confirmPassword = $POST['confirmPassword'];

	// Fetch Employee Data
	$employeeModel = new Employee();
	$employee = $employeeModel->getByID($_SESSION['eid']);
	
	// Decide where to redirect to depending on if admin or not
	if(isAdmin()) {
		$redirectScript = "account.php";
	} else {
		$redirectScript = "employeeAccount.php";
	}

	// Compare New Passwords
	if($oldPassword === '' || $newPassword === '' || $oldPassword === '') {

		header("Location: " . BASE_URL . "/" . $redirectScript . "?error=empty");
		exit();
	
	} else if($newPassword === $confirmPassword) {
		
		if(password_verify($oldPassword, $employee->password)) { // hash_equals($employee->password, crypt($oldPassword, PARSED_SALT))
		
			// Everything Matched, Proceed To Change Password
			$employeeModel->changePassword($_SESSION['eid'], $newPassword);
			
			// Redirect with Success
			header("Location: " . BASE_URL . "/" . $redirectScript . "?success");
			exit();
		
		} else {
		
			// Old Password and Real Password Didn't Match
			header("Location: " . BASE_URL . "/" . $redirectScript . "?error=incorrect");
			exit();
			
		}
	
	} else {
	
		// New Password and Confirm Password Didn't Match
		header("Location: " . BASE_URL . "/" . $redirectScript . "?error=unsure");
		exit();
		
	}

	header("Location: " . $_SERVER['HTTP_REFERRER']);
	exit();

