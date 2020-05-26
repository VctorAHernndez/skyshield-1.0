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
	$name = $POST['inputNewName'];
	$email = $POST['inputNewEmail']; // validate email
	$phone = $POST['inputNewPhone']; // remove '-' and '+' and the remaining string has to be numeric of at least length 10
	$hourlyWage = $POST['inputNewHourlyWage']; // has to be a float between 0 and 1000
	$qualifiesOvertime = isset($POST['inputNewQualifiesOvertime']); // 'on' or not set

	// Instantiate Employee Model
	$employeeModel = new Employee();
		
	// Build Parameter String (in case error occurs)
	$parameterString = "name=$name&email=$email&phone=$phone&hourlyWage=$hourlyWage&qualifiesOvertime=$qualifiesOvertime";


	// Validate
	redirectIfEmptyName($name, 'addition', $parameterString);
	redirectIfInvalidEmail($email, 'addition', $parameterString);
	redirectIfInvalidPhone($phone, 'addition', $parameterString);
	redirectIfInvalidHourlyWage($hourlyWage, 'addition', $parameterString);
		
		
	// Remove '-' (separators) and '+' (extensions)
	$phone = str_replace('-', '', $phone);
	$phone = str_replace('+', '', $phone);
	$phone = ($phone === '') ? NULL : $phone;
		
		
	// Set hourlyWage to 0 if unspecified
	$hourlyWage = ($hourlyWage === '') ? 0 : $hourlyWage;
		

	// Generate random password (16-byte string, shouldn't have '/' nor '+')
	$factory = new RandomLib\Factory;
	$generator = $factory->getGenerator(new SecurityLib\Strength(SecurityLib\Strength::MEDIUM));
	$password = $generator->generateString(16, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
		
		
	// Create Employee
	$employeeModel->add($name, $email, $phone, $password, $hourlyWage, $qualifiesOvertime);
			
			
	// Redirect back to employees and prompt user to store random password
	header("Location: " . BASE_URL . "/employees.php?success=addition&password=$password");
	exit();


