<?php

	// Load Config File
	require_once("../config.php");
	
	// Load hash_equals Function
	require_once(LIBRARY_PATH . "/hash_equals.php");
	
	// Load Employee Model
	require_once(LIBRARY_PATH . "/models/Employee.php");
	
	// Filter Input
	$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	
	// Get User Input
	$email = $POST['email'];
	$password = $POST['password'];

	// Fetch Employee Data
	$employeeModel = new Employee();
	$employee = $employeeModel->getByEmail($email);

	// Compare Passwords
	if(password_verify($password, $employee->password)) { // hash_equals($employee->password, crypt($password, PARSED_SALT))
	
		// Create User Session
		session_start();
		
		// Store Session Variables
		$_SESSION['eid'] = $employee->eid;
		$_SESSION['name'] = $employee->name;
		$_SESSION['email'] = $employee->email;
		$_SESSION['phone'] = $employee->phone;
		$_SESSION['hourlyWage'] = $employee->hourlyWage;
		$_SESSION['qualifiesOvertime'] = $employee->qualifiesOvertime;
		$_SESSION['logged_in'] = true;
		
		
		// Redirect Administrator Based on Email
		if(in_array($employee->email, $ADMIN_EMAILS)) {
			header("Location: " . BASE_URL . "/dashboard.php");
			exit();
		}
		
		
		// Redirect to Employee Page
		header("Location: " . BASE_URL . "/employee.php");
		exit();
		
	} else {
	
		// Redirect to Sign In Page
		header("Location: " . BASE_URL . "/?error&email=$email");
		exit();
		
	}
