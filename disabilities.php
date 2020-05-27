<?php

	// Start User Session
	session_start();

	// Load Config File
	require_once("config.php");
	
	// Check Login State & Redirect If Not Admin
	checkLogin();
	redirectIfNotAdmin();
	
	// Load Client Model
	require_once(LIBRARY_PATH . "/models/Client.php");
	
	// Load Employee Model
	require_once(LIBRARY_PATH . "/models/Employee.php");
	
	// Load Disability Model
	require_once(LIBRARY_PATH . "/models/Disability.php");
		
	// Load Format Phone Function
	require_once(LIBRARY_PATH . "/format_phones.php");
	
	// Fetch All Clients
	$clientModel = new Client();
	$clients = $clientModel->getAll();
	
	// Fetch All Employees
	$employeeModel = new Employee();
// 	$employees = $employeeModel->getAll();

	// Fetch All Disabilities
	$disabilityModel = new Disability();
	$disabilities = $disabilityModel->getAll();
	
	// Set Employee Variables
	$employeeName = explode(' ', $_SESSION['name'])[0];
	
	// Load Header File
	require_once(TEMPLATES_PATH . '/header.php');

	// Load Dashboard Navbar
	require_once(TEMPLATES_PATH . '/dashboardNavbar.php');
	
?>


	<div class="container-fluid">
		<div class="row">

			<?php

				// Load Dashboard Sidebar
				require_once(TEMPLATES_PATH . '/dashboardSidebar.php');

			?>

			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
				
				<!-- SECTION TITLE -->
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mt-3">
					<h1 class="h2">Disabilities Covered</h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editClientModal">
							<span data-feather="plus"></span>
							Add Disability
						</button>
					</div>
				</div>
				
				<pre><?php print_r($disabilities) ?></pre>
	
				<!-- EXTRA HEIGHT FOR SCROLLING DOWN -->
				<div style="height: 200px;"></div>
				
			</main>
			
		</div><!-- row -->
	</div><!-- container-fluid -->


<?php

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');

?>