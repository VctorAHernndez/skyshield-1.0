<?php 

	function checkLogin() {
	
		// Start Session (new or existing)
		if(session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		
		// Check logged_in Field
		if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
			http_response_code(400); // just in case clockIn.php or clockOut.php and still logged out
			header("Location: " . BASE_URL . "/processes/logout.php");
			exit();
		}
	
	}
	
	function redirectIfLoggedIn() {
	
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
	
			// Administrator goes to Dashboard
			// Employee goes to Employee Page
			if(in_array($_SESSION['email'], $ADMIN_EMAILS)) {
				header("Location: " . BASE_URL . "/dashboard.php");
				exit();
			} else {
				header("Location: " . BASE_URL . "/employee.php");
				exit();
			}
		
		}
	
	}
	
	function redirectIfNotAdmin() {
	
		if(!isAdmin()) {
			echo $_SESSION['email'] . $ADMIN_EMAILS;
			header("Location: " . BASE_URL . "/employee.php");
			exit();
		}
		
	}
	
	function isAdmin() {
	
		// Give this function access to $ADMIN_EMAILS
		global $ADMIN_EMAILS;
		
		// If logged in AND admin, return true
		return isset($_SESSION['logged_in'])
				&& $_SESSION['logged_in'] === true
				&& in_array($_SESSION['email'], $ADMIN_EMAILS);
		
	}