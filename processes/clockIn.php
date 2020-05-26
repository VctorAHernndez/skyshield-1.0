<?php

	// Start Session
	session_start();

	// Load Config File
	require_once("../config.php");
	
	// Load json_validator Function
	require_once(LIBRARY_PATH . "/validate_json.php");
	
	// Check Login State
	checkLogin();
	
	// Load Employee Model
	require_once(LIBRARY_PATH . "/models/Employee.php");

	// Fetch JSON
	$rawJSON = file_get_contents('php://input');
	
	// Check if input is actually JSON
	if(!json_validator($rawJSON)) {
	
		http_response_code(400);
		echo json_encode([
			"error" => "Invalid format given"
		]);
		
	}
	
	// Decode JSON
	$rawData = json_decode($rawJSON);
	
	// Filter and Get User Input
	$pid = filter_var($rawData->pid, FILTER_SANITIZE_STRING);
	
	// Attempt Clock In
	if(filter_var($pid, FILTER_VALIDATE_INT)) { // or $pid === '' (not done anymore because of bucket position)
	
		// If pid is empty, it means employee
		// ... doesn't have a position
		// ... and hence is a freelancer
		// ... which we'll represent it with NULL in the DB
// 		if($pid === '') {
// 			$pid = NULL;
// 		}

		try {
		
			// Clock In Employee
			$employeeModel = new Employee();
			$success = $employeeModel->clockIn($_SESSION['eid'], $pid);
			
			if(!$success) {
				http_response_code(400);
				echo json_encode([
					"error" => "Employee doesn't have that position"
				]);
			}
			
		} catch(Exception $e) {
		
			// Integrity Error (invalid eid or pid)
			// ... or some other error in the DB
			// ... although $_SESSION is set by login.php
			// ... which uses already clean database data
			http_response_code(400);
			echo json_encode([
				"error" => "Error: " . $e->getMessage()
			]);
			
		}
		
	} else {
	
		// pid is not numeric
		http_response_code(400);
		echo json_encode([
			"error" => "Invalid pid given"
		]);
		
	}