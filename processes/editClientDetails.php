<?php

	// Start Session
	session_start();

	// Load Config File
	require_once("../config.php");
	
	// Check Login State
	checkLogin();
	redirectIfNotAdmin();
	
	// Load Client Valdiation Functions
	require_once(LIBRARY_PATH . "/validate_client.php");
	
	// Load Client Model
	require_once(LIBRARY_PATH . "/models/Client.php");
	
	// Filter Input
	$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
	
// 	echo "<pre>";
// 	var_dump($POST);
// 	exit();

	// Get User Input
	$cid = $POST['inputCid']; // has to be in the database
	$name = $POST['inputEditClientName'];
	$alias = $POST['inputEditClientAlias']; // validate email
	$address = $POST['inputEditClientAddress']; // 
	$phone = $POST['inputEditContactPhone']; // remove '-' and '+' and the remaining string has to be numeric of at least length 10
	$email = $POST['inputEditClientEmail'];
	$paysOvertime = isset($POST['inputEditClientPaysOvertime']); // 'on' or not set
// 	$editAction = isset($POST['editEmployeeFormSubmit']) && $POST['editEmployeeFormSubmit'] === '1';
// 	$deleteAction = isset($POST['deleteEmployeeFormSubmit']) && $POST['deleteEmployeeFormSubmit'] === '1';

	// Instantiate Client Model
	$clientModel = new Client();
	
	// Build Parameter String (in case error occurs)
	$parameterString = "name=$name&alias=$alias&address=$address&phone=$phone&email=$email&paysOvertime=$paysOvertime";
	
	
	// You either delete or edit, not both (only happens with users who've tinkered with frontend)
// 	if(!($editAction xor $deleteAction)) {
// 		header("Location: " . BASE_URL . "/employees.php?error=unsure");
// 		exit();
// 	}
	
	
	// DELETE CLIENT
// 	if($deleteAction) {


		// Validate	
// 		redirectIfInvalidCID($cid, 'clientEdit', $parameterString);

		// Remove Client (false means client has employees that've clocked in at least once)
// 		if($clientModel->remove($cid)) {
// 			header("Location: " . BASE_URL . "/dashboard.php?success=clientDeletion");
// 			exit();
// 		} else {
// 			header("Location: " . BASE_URL . "/client.php?view=$cid&error=clientDeletion");
// 			exit();
// 		}


// 	}
	
	
	// EDIT CLIENT
// 	if($editAction) {
	
	
		// Validate
		redirectIfInvalidCID($cid, 'clientEdit1', $parameterString);
		redirectIfEmpty($name, 'clientEdit2', $parameterString);
		redirectIfEmpty($alias, 'clientEdit3', $parameterString);
		redirectIfEmpty($address, 'clientEdit4', $parameterString);
		redirectIfInvalidPhone($phone, 'clientEdit5', $parameterString);
		redirectIfInvalidEmail($email, 'clientEdit6', $parameterString);
			
			
		// Remove '-' (separators) and '+' (extensions)
		$phone = str_replace('-', '', $phone);
		$phone = str_replace('+', '', $phone);
		$phone = ($phone === '') ? NULL : $phone;
			
			
		// Edit Client Details
		$clientModel->setDetails($cid, $name, $alias, $address, $phone, $email, $paysOvertime);
				
				
		// Redirect back to client page
		header("Location: " . BASE_URL . "/client.php?view=$cid&success=edition");
		exit();
		
		
// 	}


