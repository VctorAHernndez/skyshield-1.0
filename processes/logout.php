<?php

	// Load Config File
	require_once("../config.php");
	
	// Destroy Session
	session_start();
	session_unset();
	session_destroy();
	
	// Redirect User to Sign In Page
	header("Location: " . BASE_URL);
	exit();
	