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
// 	var_dump($_SERVER['HTTP_REFERER']);
// 	var_dump($POST);
// 	exit();

	// Get User Input
	$name = $POST['inputNewClientName']; // not empty
	$alias = $POST['inputNewClientAlias']; // not empty
	$address = $POST['inputNewClientAddress']; // not empty
	$phone = $POST['inputNewContactPhone']; // remove '-' and '+' and the remaining string has to be numeric of at least length 10
	$email = $POST['inputNewClientEmail']; // validate email
	$paysOvertime = isset($POST['inputClientPaysOvertime']); // 'on' or not set

		
	// Build Parameter String (in case error occurs)
	$parameterString = "name=$name&alias=$alias&address=$address&phone=$phone&email=$email&paysOvertime=$paysOvertime";


	// Validate
	redirectIfEmpty($name, 'client', $parameterString);
	redirectIfEmpty($alias, 'client', $parameterString);
	redirectIfEmpty($address, 'client', $parameterString);
	redirectIfInvalidPhone($phone, 'client', $parameterString);
	redirectIfInvalidEmail($email, 'client', $parameterString);
		
		
	// Remove '-' (separators) and '+' (extensions)
	$phone = str_replace('-', '', $phone);
	$phone = str_replace('+', '', $phone);
	$phone = ($phone === '') ? NULL : $phone;
		
		
	// Create Client
	$clientModel = new Client();
	$clientID = $clientModel->add($name, $alias, $address, $phone, $email, $paysOvertime);
			
			
	// Redirect back to new client's page
	header("Location: " . BASE_URL . "/client.php?view=$clientID");
	exit();


