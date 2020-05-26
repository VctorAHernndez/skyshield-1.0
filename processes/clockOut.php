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
	$ciid = filter_var($rawData->ciid, FILTER_SANITIZE_STRING);
	$comment = filter_var($rawData->comment, FILTER_SANITIZE_STRING);
	
	// Attempt Clock Out
	if(filter_var($ciid, FILTER_VALIDATE_INT)) {

		try {
		
			// Clock Out Employee
			$employeeModel = new Employee();
			$employee = $employeeModel->clockOut($ciid, $comment);
			
		} catch(Exception $e) {
		
			// Integrity Error (invalid ciid)
			// ... or some other error in the DB
			http_response_code(400);
			echo json_encode([
				"error" => "Error: " . $e->getMessage()
			]);
			
		}
		
	} else {
	
		// ciid is not numeric
		http_response_code(400);
		echo json_encode([
			"error" => "Invalid ciid given"
		]);
		
	}