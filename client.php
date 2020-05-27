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
	
	if(isset($_GET['view'])) {
	
		$clientID = filter_var($_GET['view'], FILTER_SANITIZE_STRING);
		$currentClient = $clientModel->getByID($clientID);
		
		if(!$currentClient) {
			echo "<pre>Incorrect Client ID</pre>";
			exit();
		}
		
	} else {
		echo "<pre>No Client ID</pre>";
		exit();
	}
	


	
	// Fetch All Employees
	$employeeModel = new Employee();
// 	$employees = $employeeModel->getAll();
	
	// Set Employee Variables
	$employeeName = explode(' ', $_SESSION['name'])[0];
	
	// Load Header File
	require_once(TEMPLATES_PATH . '/header.php');

	// Load Dashboard Navbar
	require_once(TEMPLATES_PATH . '/dashboardNavbar.php');
	
?>


	<style>
		.client-details {
			border-width: 3px !important;
			border-radius: 2px;
		}
		
		.client-address {
			white-space: pre-line;
		}
	</style>


	<!-- MODAL FOR EDIT CLIENT -->
	<div class="modal fade" id="editClientModal" tabindex="-1" role="dialog" aria-labelledby="editClientModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editClientModalLabel">Edit Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					
					<!-- DISPLAY NEW EMPLOYEE ALERTS -->
					<?php if(isset($_GET['error']) && $_GET['error'] === 'clientEdit'): ?>
					
						<!-- SHOW MODAL -->
						<script>setTimeout(function() {$('#editClientModal').modal('show')}, 500)</script>
					
						<!-- ACTUAL ALERT-->
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="alert-heading">Error!</h4>
							<p>One or more invalid fields. All are required. Please check that phone has <strong>10 digits</strong>.</p>
							<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
						</div>
					
					<?php endif; ?>
					
					<form action="<?= BASE_URL . '/processes/editClientDetails.php' ?>" method="post" id="editClientForm">
					
						<input type="hidden" name="inputCid" id="inputCid" value="<?= $currentClient->cid ?>">
					
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputEditClientName">Name</label>
								<input type="text" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? 'is-invalid' : '' ?>" name="inputEditClientName" id="inputEditClientName" value="<?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? $_GET['name'] : $currentClient->name ?>" required>
							</div>
							<div class="form-group col-md-6">
								<label for="inputEditClientAlias">Alias</label>
								<input type="text" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? 'is-invalid' : '' ?>" name="inputEditClientAlias" id="inputEditClientAlias" value="<?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? $_GET['alias'] : $currentClient->alias ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label for="inputEditClientAddress">Address</label>
							<textarea rows="4" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? 'is-invalid' : '' ?>" name="inputEditClientAddress" id="inputEditClientAddress" required><?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? $_GET['address'] : $currentClient->address ?></textarea>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputEditContactPhone">Contact Phone</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text">+1</div>
									</div>
									<input type="tel" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? 'is-invalid' : '' ?>" name="inputEditContactPhone" id="inputEditContactPhone" value="<?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? $_GET['phone'] : $currentClient->contactPhone ?>" required>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="inputEditClientEmail">Contact Email</label>
								<input type="email" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? 'is-invalid' : '' ?>" name="inputEditClientEmail" id="inputEditClientEmail" value="<?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? $_GET['email'] : $currentClient->contactEmail ?>" required>
							</div>
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="inputEditClientPaysOvertime" id="inputEditClientPaysOvertime" <?= (isset($_GET['error']) && $_GET['error'] === 'clientEdit') ? ($_GET['paysOvertime'] === '1' ? 'checked' : '') : ($currentClient->paysOvertime === '1' ? 'checked' : '') ?>>
								<label class="form-check-label" for="inputEditClientPaysOvertime">
									Pays Overtime
								</label>
							</div>
						</div>
					</form>	
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="document.getElementById('editClientForm').submit();">Make Changes</button>
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
					<h1 class="h2"><?= $currentClient->name ?> <small class="text-muted">[<?= $currentClient->alias ?>]</small></h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#editClientModal">
							<span data-feather="edit-2"></span>
							Edit Details
						</button>
					</div>
				</div>
				
				
				<blockquote class="client-details border-left border-primary pl-3 py-2 text-secondary bg-light font-weight-light">
					<div class="row">
						<div class="client-address col-sm-6 font-italic"><?= $currentClient->address ?></div>
						<div class="col-sm-6">
							<h5 class="mb-1">Contact Information</h5>
							<div class="pl-2">
								<strong>Phone:</strong> <?= formatPhone($currentClient->contactPhone) ?>
								<br>
								<strong>Email:</strong> <a href="mailto:<?= $currentClient->contactEmail ?>"><?= $currentClient->contactEmail ?></a>
							</div>
						</div>
					</div>
				</blockquote>

	
				<!-- EXTRA HEIGHT FOR SCROLLING DOWN -->
				<div style="height: 200px;"></div>
				
			</main>
			
		</div><!-- row -->
	</div><!-- container-fluid -->


<?php

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');

?>