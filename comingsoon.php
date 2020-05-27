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
	
	// Fetch All Clients
	$clientModel = new Client();
	$clients = $clientModel->getAll();
	
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
	
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 my-3 border-bottom">
					<h1 class="h2">Coming Soon</h1>
				</div>
					
				<br>
				
				<h4>Overdue</h4>
				<ul class="list-group ml-5">
					<li class="list-group-item"><span class="text-primary">Display</span> the positions the clients host, along with the possibility of <span class="text-success">creating</span> a new one, and <span class="text-success">assigning</span> an employee a particular position.</li>
					<li class="list-group-item"><span class="text-primary">Display</span> all the employees that work for a given client in the client's page. And maybe the last <code>n</code> shifts the employee has done.</li>
					<li class="list-group-item"><span class="text-primary">Display</span>, <span class="text-success">add</span>, and <span class="text-danger">delete</span> disabilities and the employees that <span class="text-success">have</span> them.</li>
					<li class="list-group-item"><span class="text-danger">Delete</span> or <span class="text-warning">modify</span> "unexpected" and "unapproved" shifts.</li>
				</ul>
				
				<br>
				
				<h4>In Progress</h4>
				<ul class="list-group ml-5">
					<li class="list-group-item">Support multiple positions for a single employee<sup><code>*</code></sup> <small>(solved, must confirm with Sonny; we only allow clocking in for one position at a time)</small></li>
					<li class="list-group-item">Migrate to AWS or GCP, separating the Web, API, and Database servers <small>(rewriting front-end on React or Angular)</small></li>
					<li class="list-group-item">Prevent the deletion of an admin account (by another admin).</li>
					<li class="list-group-item">Prevent the deletion of yourself.</li>
				</ul>
				
				<br>
				
				<h4>Planned Improvements</h4>
				<ul class="list-group ml-5">
					<li class="list-group-item">Only permit <span class="text-success">clocking in</span> when employee is within 15 min of the start of their shift (need <code>Shift</code> table first).</li>
					<li class="list-group-item"><span class="text-primary">Distinguish</span> between employees that are working and those who forgot to clock out (need <code>Shift</code> table first)</li>
					<li class="list-group-item"><span class="text-success">Generate</span> Excel spreadsheet with the employee's due payments (<code>Payment</code> table).</li>
					<li class="list-group-item"><span class="text-success">Generate</span> Bills (in PDF) to a Client for a given fortnight (i.e. "quincena") (<code>Bill</code> table).</li>
				</ul>
							
				<div style="height: 200px"></div>

			</main>

			
		</div><!-- row -->
	</div><!-- container-fluid -->


<?php

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');

?>