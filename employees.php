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
		
	// Load Format Phone Function
	require_once(LIBRARY_PATH . "/format_phones.php");
	
	// Fetch All Clients
	$clientModel = new Client();
	$clients = $clientModel->getAll();
	
	// Fetch All Employees
	$employeeModel = new Employee();
	$employees = $employeeModel->getAll();
	
	// Set Employee Variables
	$employeeName = explode(' ', $_SESSION['name'])[0];
	
	// Load Header File
	require_once(TEMPLATES_PATH . '/header.php');

	// Load Dashboard Navbar
	require_once(TEMPLATES_PATH . '/dashboardNavbar.php');
	
?>


	<style>
		#employee-table tbody tr {
			cursor: pointer;
		}
	</style>


	<!-- MODAL FOR NEW EMPLOYEE -->
	<div class="modal fade" id="newEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="newEmployeeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="newEmployeeModalLabel">New Employee</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					
					<!-- DISPLAY NEW EMPLOYEE ALERTS -->
					<?php if(isset($_GET['error']) && $_GET['error'] === 'addition'): ?>
					
						<!-- SHOW MODAL -->
						<script>setTimeout(function() {$('#newEmployeeModal').modal('show')}, 500)</script>
					
						<!-- ACTUAL ALERT-->
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="alert-heading">Error!</h4>
							<p>One or more invalid fields. Please check that email is <strong>unique</strong>, phone has <strong>10 digits</strong> and hourly wage is a number <strong>between 0 and <?= MAX_WAGE ?></strong>.</p>
							<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
						</div>
					
					<?php endif; ?>
					
					<form action="<?= BASE_URL . '/processes/addEmployee.php' ?>" method="post" id="newEmployeeForm">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputNewName">Name</label>
								<input type="text" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'addition') ? 'is-invalid' : '' ?>" name="inputNewName" id="inputNewName" value="<?= (isset($_GET['error']) && $_GET['error'] === 'addition') ? $_GET['name'] : '' ?>" required>
							</div>
							<div class="form-group col-md-6">
								<label for="inputNewEmail">Email</label>
								<input type="email" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'addition') ? 'is-invalid' : '' ?>" name="inputNewEmail" id="inputNewEmail" value="<?= (isset($_GET['error']) && $_GET['error'] === 'addition') ? $_GET['email'] : '' ?>" required>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputNewPhone">Phone</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text">+1</div>
									</div>
									<input type="tel" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'addition') ? 'is-invalid' : '' ?>" name="inputNewPhone" id="inputNewPhone" value="<?= (isset($_GET['error']) && $_GET['error'] === 'addition') ? $_GET['phone'] : '' ?>">
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="inputNewHourlyWage">Hourly Wage</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text">$</div>
									</div>
									<input type="number" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'addition') ? 'is-invalid' : '' ?>" min="0" step="0.25" max="1000" onchange="this.value = parseFloat(this.value).toFixed(2)" name="inputNewHourlyWage" id="inputNewHourlyWage" value="<?= (isset($_GET['error']) && $_GET['error'] === 'addition') ? $_GET['hourlyWage'] : '' ?>">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="inputNewQualifiesOvertime" id="inputNewQualifiesOvertime" <?= (isset($_GET['error']) && $_GET['error'] === 'addition' && $_GET['qualifiesOvertime'] === '1') ? 'checked' : '' ?>>
								<label class="form-check-label" for="inputNewQualifiesOvertime">
									Will Be Payed Overtime
								</label>
							</div>
						</div>
					</form>	
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="validateNewEmployee()">Add Employee</button>
				</div>
			</div>
		</div>
	</div>


	<div class="container-fluid">
		<div class="row">

			<?php

				// Load Dashboard Sidebar
				require_once(TEMPLATES_PATH . '/dashboardSidebar.php');

			?>

			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
				
				<!-- SECTION TITLE -->
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mt-3">
					<h1 class="h2">Employees</h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#newEmployeeModal">
							<span data-feather="user-plus"></span>
							Add Employee
						</button>
					</div>
				</div>
				
				<!-- SUCCESS ALERT FOR ADDING/DELETING EMPLOYEE -->
				<?php if(isset($_GET['success']) && $_GET['success'] === 'addition'): ?>
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="alert-heading">Success!</h4>
						<p>Created new employee profile. Employee's password is <code><?= $_GET['password'] ?></code>. Please store it somewhere safe.</p>
						<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
					</div>
				<?php elseif(isset($_GET['success']) && $_GET['success'] === 'deletion'): ?>
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="alert-heading">Success!</h4>
						<p>Removed employee profile.</p>
						<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
					</div>
				<?php endif; ?>
				
				<!-- EMPLOYEE TABLE -->
				<div class="table-responsive">
					<table id="employee-table" class="table table-striped table-sm table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Hourly Wage</th>
								<th>Payed Overtime</th>
							</tr>
						</thead>
						<tbody>
						<?php if(count($employees)): ?>
							<?php foreach($employees as $employee): ?>
								<tr
									title="Click to edit <?= $employee->name ?>'s details"
									data-eid="<?= $employee->eid ?>"
									data-name="<?= $employee->name ?>"
									data-email="<?= $employee->email ?>"
									data-phone="<?= $employee->phone ?>"
									data-hourlywage="<?= sprintf('%.2f', (float)$employee->hourlyWage) ?>"
									data-qualifiesovertime="<?= $employee->qualifiesOvertime ?>"
									class="<?= $employee->eid === $_SESSION['eid'] ? 'table-warning' : '' ?>"
								>
									<td><?= $employee->name ?></td>
									<td><a href="mailto:<?= $employee->email ?>"><?= $employee->email ?></a></td>
									<td><?= formatPhone($employee->phone) ?></td>
									<td>
										<?php if($employee->hourlyWage == 0): ?>
											<span class="text-muted">No wage</span>
										<?php else: ?>
											<?= '$' . sprintf('%.2f', (float)$employee->hourlyWage) ?>
										<?php endif; ?>
									</td>
									<td>
										<span class="<?= $employee->qualifiesOvertime ? 'text-success' : 'text-danger' ?>">
											<?= $employee->qualifiesOvertime ? 'Yes' : 'No' ?>
										</span>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr class="text-muted text-center">
								<td colspan="5" class="py-2">You have no employees</td><!--WEIRD, SINCE YOU YOURSELF MUST BE AN EMPLOYEE ALTHOUGH THAT MIGHT CHANGE IN THE FUTURE-->
							</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div><!-- table-responsive -->
				
				<?php if(count($employees)): ?>
					<div id="employee-details">
				
						<!-- SECTION TITLE -->
						<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mt-3">
							<h1 class="h2">Edit Details</h1>
						</div>
					
						<!-- DISPLAY MODIFICATION OR DELETION ALERTS -->
						<?php if(isset($_GET['success']) && $_GET['success'] === 'edition'): ?>
							<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="alert-heading">Success!</h4>
								<p>Made changes to employee.</p>
								<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
							</div>
						<?php elseif(isset($_GET['error']) && $_GET['error'] === 'deletion'): ?>
							<div class="alert alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="alert-heading">Error!</h4>
								<p>Employee currently has a position and has clocked in at least once. Deletion denied.</p>
								<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
							</div>
						<?php elseif(isset($_GET['error']) && $_GET['error'] === 'edition'): ?>
							<div class="alert alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="alert-heading">Error!</h4>
								<p>One or more invalid fields. Please check that email is <strong>unique</strong>, phone has <strong>10 digits</strong> and hourly wage is a number <strong>between 0 and <?= MAX_WAGE ?></strong>.</p>
								<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
							</div>
						<?php endif; ?>
					
						<!-- EDITION/DELETION FORM -->
						<form action="<?= BASE_URL . '/processes/editEmployeeDetails.php' ?>" method="post" id="editEmployeeForm">
							<input type="hidden" value name="inputEid" id="inputEid">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputName">Name</label>
									<input type="text" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? 'is-invalid' : '' ?>" name="inputName" id="inputName" value="<?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? $_GET['name'] : '' ?>" <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? '' : 'disabled' ?> required>
								</div>
								<div class="form-group col-md-6">
									<label for="inputEmail">Email</label>
									<input type="email" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? 'is-invalid' : '' ?>" name="inputEmail" id="inputEmail" value="<?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? $_GET['email'] : '' ?>" <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? '' : 'disabled' ?> required>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputPhone">Phone</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">+1</div>
										</div>
										<input type="tel" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? 'is-invalid' : '' ?>" name="inputPhone" id="inputPhone" value="<?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? $_GET['phone'] : '' ?>" <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? '' : 'disabled' ?>>
									</div>
								</div>
								<div class="form-group col-md-6">
									<label for="inputHourlyWage">Hourly Wage</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">$</div>
										</div>
										<input type="number" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? 'is-invalid' : '' ?>" min="0" step="0.25" max="1000" onchange="this.value = parseFloat(this.value).toFixed(2)" name="inputHourlyWage" id="inputHourlyWage" value="<?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? $_GET['hourlyWage'] : '' ?>" <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? '' : 'disabled' ?>>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<div class="form-check">
										<input class="form-check-input <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? 'is-invalid' : '' ?>" type="checkbox" name="inputQualifiesOvertime" id="inputQualifiesOvertime" <?= (isset($_GET['error']) && $_GET['error'] === 'edition' && $_GET['qualifiesOvertime'] === '1') ? 'checked' : '' ?> <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? '' : 'disabled' ?>>
										<label class="form-check-label" for="inputQualifiesOvertime">
											Payed Overtime
										</label>
									</div>
								</div>
								<div class="ml-auto">
									<button type="submit" class="btn btn-sm btn-outline-primary" name="editEmployeeFormSubmit" id="editEmployeeFormSubmit" onclick="this.value = 1" <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? '' : 'disabled' ?>>
										<span data-feather="user-check"></span>
										Make Changes
									</button>
									<button type="submit" class="btn btn-sm btn-outline-danger" name="deleteEmployeeFormSubmit" id="deleteEmployeeFormSubmit" onclick="this.value = 1" <?= (isset($_GET['error']) && $_GET['error'] === 'edition') ? '' : 'disabled' ?>>
										<span data-feather="user-x"></span>
										Remove Employee
									</button>
								</div>
							</div>
						</form>
					
					</div><!--#employee-details-->
				<?php endif; ?>
	
				<!-- EXTRA HEIGHT FOR SCROLLING DOWN -->
				<div style="height: 200px;"></div>
				
			</main>
			
		</div><!-- row -->
	</div><!-- container-fluid -->


<?php

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');

?>