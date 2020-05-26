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
	
	// Set Employee Variables
	$employeeName = explode(' ', $_SESSION['name'])[0];
	$employeeEmail = $_SESSION['email']; // "rikard0_1996@yahoo.com"
	$employeePhone = substr($_SESSION['phone'], 0, 3) . 		// Prettify Phone Number
					 '-' . substr($_SESSION['phone'], 3, 3) . 	// WARNING: assumes +1 extension and 9 digit phone number given
					 '-' . substr($_SESSION['phone'], 6, 4); 	// "787-939-5555"	
	
	// Fetch Employee Disabilities
	$employeeModel = new Employee();
	$employeeDisabilities = $employeeModel->getDisabilities($_SESSION['eid']);
	
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
					<h1 class="h2">Account Details</h1>
				</div>
					
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="staticName">Name</label>
<!-- 						<div class="col-sm-10"> -->
							<ul class="list-group" id="staticName">
								<li class="list-group-item">
									<?= $_SESSION['name'] ?>
								</li>
							</ul>
<!-- 						</div> -->
					</div>
					<div class="form-group col-md-6">
						<label for="staticEmail">Email</label>
<!-- 						<div class="col-sm-10"> -->
							<ul class="list-group" id="staticEmail">
								<li class="list-group-item">
									<a href="mailto:<?= $employeeEmail ?>"><?= $employeeEmail ?></a>
								</li>
							</ul>
<!-- 						</div> -->
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="staticDisabilities">Disabilities</label>
<!-- 						<div> -->
							<ul class="list-group" id="staticDisabilities">
							<?php if(count($employeeDisabilities) > 0): ?>
								<?php foreach($employeeDisabilities as $disability): ?>
								<li class="list-group-item">
									<?= $disability->name ?>
									<?php if($disability->qualifiesForTaxBreak === '1'): ?>
									<span class="float-right text-success">
										Qualifies for tax break
									</span>
									<?php elseif($disability->qualifiesForTaxBreak === '0'): ?>
									<span class="float-right text-danger">
										Doesn't qualify for tax break
									</span>
									<?php endif; ?>
								</li>
								<?php endforeach; ?>
							<?php else: ?>
								<li class="list-group-item">
									<span class="text-muted">No disabilities</span>
								</li>
							<?php endif; ?>
							</ul>
<!-- 						</div> -->
					</div>
					<div class="form-group col-md-6">
						<label for="staticPhone">Phone</label>
<!-- 						<div class="col-sm-10"> -->
							<ul class="list-group" id="staticPhone">
								<li class="list-group-item"><?= formatPhone($_SESSION['phone']) ?></li>
							</ul>
<!-- 						</div> -->
					</div>
				</div>
			
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center my-3 border-bottom">
					<h1 class="h2">Change Password</h1>
				</div>
			
				<!-- DISPLAY ALERTS HERE, IF ANY -->
				<?php if(isset($_GET['success'])): ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="alert-heading">Success!</h4>
					<p>Changed password.</p>
					<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
				</div>
				<?php elseif(isset($_GET['error']) && $_GET['error'] === 'incorrect'): ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="alert-heading">Error!</h4>
					<p>Incorrect old password.</p>
					<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
				</div>
				<?php elseif(isset($_GET['error']) && $_GET['error'] === 'unsure'): ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="alert-heading">Error!</h4>
					<p>New password and confirm password didn't match.</p>
					<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
				</div>
				<?php elseif(isset($_GET['error']) && $_GET['error'] === 'empty'): ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="alert-heading">Error!</h4>
					<p>One or more fields were empty.</p>
					<small class="mb-0">For any inconveniences, please contact <a class="alert-link" href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a></small>
				</div>
				<?php endif; ?>
			
				<form action="<?= BASE_URL . '/processes/changePassword.php' ?>" method="POST">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="oldPassword">Old Password</label>
							<input name="oldPassword" type="password" class="form-control <?= isset($_GET['error']) && ($_GET['error'] === 'incorrect' || $_GET['error'] === 'empty') ? 'is-invalid' : '' ?>" id="oldPassword" required>
						</div>
						<div class="form-group col-md-6">
							<label for="newPassword">New Password</label>
							<input name="newPassword" type="password" class="form-control <?= isset($_GET['error']) && ($_GET['error'] === 'unsure' || $_GET['error'] === 'empty') ? 'is-invalid' : '' ?>" id="newPassword" required>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="confirmPassword">Confirm Password</label>
							<input name="confirmPassword" type="password" class="form-control <?= isset($_GET['error']) && ($_GET['error'] === 'unsure' || $_GET['error'] === 'empty') ? 'is-invalid' : '' ?>" id="confirmPassword" required>
						</div>
						<div class="form-group col-md-6"><!--offset-md-2-->
							<label style="visibility: hidden;" for="changePasswordSubmit">Change Password</label>
							<button type="submit" class="btn btn-primary btn-block" id="changePasswordSubmit">Change Password</button>
						</div>
					</div>	
				</form>
			
				<div style="height: 200px"></div>

			</main>

			
		</div><!-- row -->
	</div><!-- container-fluid -->


<?php

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');

?>