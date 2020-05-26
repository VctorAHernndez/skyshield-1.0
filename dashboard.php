<?php 

	// Start User Session
	session_start();

	// Load Config File
	require_once("config.php");
	
	// Check Login State & Redirect If Not Admin
	checkLogin();
	redirectIfNotAdmin();

	// Load Employee Model
	require_once(LIBRARY_PATH . "/models/Employee.php");
	
	// Load Client Model
	require_once(LIBRARY_PATH . "/models/Client.php");
	
	// Load Format Date Function
	require_once(LIBRARY_PATH . "/format_dates.php");
		
	// Load Format Phone Function
	require_once(LIBRARY_PATH . "/format_phones.php");
	
	// Fetch All Clients
	$clientModel = new Client();
	$clients = $clientModel->getAll();
	
	// Fetch All Employees At Work
	$employeeModel = new Employee();
	$employeesAtWork = $employeeModel->getAllAtWork();
	$employeesToday = $employeeModel->getAllWhoWorkedToday();
	$employeesAllTime = $employeeModel->getAllWhoWorked();
	
// 	var_dump($clients);
// 	exit();
	
	// Set Employee Variables
	$employeeName = explode(' ', $_SESSION['name'])[0];
	
	// Rename Page According to Employee Name
	$pageTitle .= $employeeName;
	
	// Load Header File
	require_once(TEMPLATES_PATH . '/header.php');

	// Load Dashboard Navbar
	require_once(TEMPLATES_PATH . '/dashboardNavbar.php');
	
?>


	<style>
		.employee-comment {
			width: 30%;
			max-width: 200px;
			font-size: 0.9em;
		}
		.shift-date {
			font-size: 0.9em;
		}
	</style>


	<div class="container-fluid">
		<div class="row">

			<?php

				// Load Dashboard Sidebar
				require_once(TEMPLATES_PATH . '/dashboardSidebar.php');

			?>

			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 my-3 border-bottom">
					<h1 class="h2">Dashboard</h1>
<!-- 
					<div class="btn-toolbar mb-2 mb-md-0">
						<div class="btn-group mr-2">
							<button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
							<button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
						</div>
						<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
							<span data-feather="calendar"></span>
							This week
						</button>
					</div>
 -->
				</div>

				
				<br>
	

				<!-- SHOW EMPLOYEES THAT ARE CURRENTLY AT WORK -->
				<h3>Currently Working</h3>
				<div class="table-responsive">
					<table class="table table-striped table-sm table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th>Phone</th>
								<th>Position</th>
								<th>Entry Time</th>
							</tr>
						</thead>
						<tbody>
						<?php if(count($employeesAtWork)): ?>
							<?php foreach($employeesAtWork as $employee): ?>
							<tr>
								<td><?= $employee->name ?></td>
								<td><?= formatPhone($employee->phone) ?></td>
								<td>
									<?= $employee->pname ?>
									<small class="font-italic d-block text-muted">(<?= $employee->cname ?>)</small>
								</td>
								<td class="text-secondary font-weight-light shift-date"><?= formatDate($employee->entered) ?></td>
							</tr>
							<?php endforeach; ?>
						<?php else: ?>
						<tr class="text-muted text-center">
							<td colspan="4" class="py-2">No one is currently working</td>
						</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div><!-- table-responsive -->
				
				
				<br>
				
				
				<!-- SHOW EMPLOYEES THAT WORKED TODAY -->
				<h3>Today</h3>
				<div class="table-responsive">
					<table class="table table-striped table-sm table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th>Position</th>
								<th>Shift</th>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody>
						<?php if(count($employeesToday)): ?>
							<?php foreach($employeesToday as $employee): ?>
							<tr>
								<td><?= $employee->name ?></td>
								<td>
									<?= $employee->pname ?>
									<small class="font-italic d-block text-muted">(<?= $employee->cname ?>)</small>
								</td>
								<td class="text-secondary font-weight-light shift-date"><?= formatDate($employee->entered, $employee->left) ?></td>
								<td class="text-muted employee-comment"><?= $employee->comment ?></td>
							</tr>
							<?php endforeach; ?>
						<?php else: ?>
						<tr class="text-muted text-center">
							<td colspan="4" class="py-2">No one has worked today</td>
						</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div><!-- table-responsive -->


				<br>
				
				
				<!-- SHOW ALL EMPLOYEE SHIFTS -->
				<h3>All Time</h3>
				<div class="table-responsive">
					<table class="table table-striped table-sm table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th>Position</th>
								<th>Shift</th>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody>
						<?php if(count($employeesAllTime)): ?>
							<?php foreach($employeesAllTime as $employee): ?>
							<tr>
								<td><?= $employee->name ?></td>
								<td>
									<?= $employee->pname ?>
									<small class="font-italic d-block text-muted">(<?= $employee->cname ?>)</small>
								</td>
								<td class="text-secondary font-weight-light shift-date"><?= formatDate($employee->entered, $employee->left) ?></td>
								<td class="text-muted employee-comment"><?= $employee->comment ?></td>
							</tr>
							<?php endforeach; ?>
						<?php else: ?>
						<tr class="text-muted text-center">
							<td colspan="4" class="py-2">No one has worked a single day yet</td>
						</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div><!-- table-responsive -->			

<!-- 				<canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas> -->
				
				<div style="height: 200px;"></div>
				
			</main>
			
		</div><!-- row -->
	</div><!-- container-fluid -->


<?php

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');

?>