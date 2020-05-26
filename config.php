<?php
 
/*
    The important thing to realize is that the config file should be included in every
    page of your project, or at least any page you want access to these settings.
    This allows you to confidently use these settings throughout a project because
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.

    I will usually place the following in a bootstrap file or some type of environment
    setup file (code that is run at the start of every page request), but they work 
    just as well in your config file if it's in php (some alternatives to php are xml or ini files).

    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
    
    Taken from:
	- https://code.tutsplus.com/tutorials/organize-your-next-php-project-the-right-way--net-5873

*/



// Load Dependencies
require_once("vendor/autoload.php");



// Load Environment Variables
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();



// Define Internal System Constants
defined("DB_HOST")
	or define("DB_HOST", getenv('DB_HOST'));

defined("DB_NAME")
	or define("DB_NAME", getenv('DB_NAME'));
	
defined("DB_USER")
	or define("DB_USER", getenv('DB_USER'));
	
defined("DB_PASSWORD")
	or define("DB_PASSWORD", getenv('DB_PASSWORD'));

defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", __DIR__ . '/library');
     
defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", __DIR__ . '/templates');
    
defined("RESOURCES_PATH")
	or define("RESOURCES_PATH", __DIR__ . '/public');
	
defined("PHONE_LENGTH")
	or define("PHONE_LENGTH", 10); // determines the length of the stored phone numbers (7875553333)
	
defined("MAX_WAGE")
	or define("MAX_WAGE", 1000); // determines the max hourly wage of a given employee
	
$ADMIN_EMAILS = [
	getenv('ADMIN_EMAIL1'),
	getenv('ADMIN_EMAIL2'),
	getenv('ADMIN_EMAIL3')
];



// Define External System Constants
defined("BASE_URL")
	or define("BASE_URL", getenv('BASE_URL'));
	
defined("CONTACT_EMAIL")
	or define("CONTACT_EMAIL", getenv('CONTACT_EMAIL'));
	
	

// Error Reporting
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);



// Extract Script Name from URL
$URI_ARRAY = explode('/', $_SERVER["REQUEST_URI"]);
$requestedScriptWithParams = end($URI_ARRAY); // !!!MAY CONTAIN GET PARAMETERS!!!
$SCRIPT_AND_PARAMS_ARRAY = explode('?', $requestedScriptWithParams);
$requestedScript = current($SCRIPT_AND_PARAMS_ARRAY); // !!!current is equivalent to first element!!!



// Define Other Constants Depending on Script Name
switch($requestedScript) {
	case "employee.php":
	case "dashboard.php":
		$pageTitle = "Welcome, "; // script later inserts user name
		break;
	case "employeeAccount.php":
		$pageTitle = "Account Details – "; // script later inserts user name
		break;
	case "employees.php":
		$pageTitle = "Your Employees"; // script later inserts user name
		break;
	case "comingsoon.php":
		$pageTitle = "Coming Soon!"; // script later inserts user name
		break;
	case "account.php":
		$pageTitle = "Your Account"; // script later inserts user name
		break;	
	default:
		$pageTitle = "Sky Shield Security";
}



// Bring Utility Functions to Namespace
require_once(LIBRARY_PATH . "/check_login.php");


