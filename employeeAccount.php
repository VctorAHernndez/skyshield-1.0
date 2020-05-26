<?php

	// Start User Session
	session_start();
	
	// Load Config File
	require_once("config.php");
	
	// Check Login State
	checkLogin();
	
	// Load Employee Model
	require_once(LIBRARY_PATH . "/models/Employee.php");
	
	// Load Format Phone Function
	require_once(LIBRARY_PATH . "/format_phones.php");
	
	// Set Employee Variables
	$employeeName = $_SESSION['name']; //"Ricardo Valentín Marquez"
	$employeeEmail = $_SESSION['email']; // "rikard0_1996@yahoo.com"
	$employeePhone = formatPhone($_SESSION['phone']); // link with phone if valid, "No phone" if invalid
	
	// Fetch Employee Disabilities
	$employeeModel = new Employee();
	$employeeDisabilities = $employeeModel->getDisabilities($_SESSION['eid']);
	/*
	[
		[
			'name' => 'Blindness',
			'qualifiesForTaxBreak' => '1'
		],
		[
			'name' => 'Diabetes',
			'qualifiesForTaxBreak' => '0'
		]
	];
	*/
	
	// Rename Page According to Employee Name
	$employeeFirstName = explode(' ', $employeeName)[0];
	$pageTitle .= $employeeFirstName;
	
	
	// Load Header File
	require_once(TEMPLATES_PATH . '/header.php');

	// Load Employee Navbar
	require_once(TEMPLATES_PATH . '/employeeNavbar.php');
	
?>


	<style>
		.employee-dashboard {
			--navBarHeight: 54px;
			background-color: #f4f4f4;
			min-height: calc(100vh - var(--navBarHeight));
		}
		
		.employee-details {
			width: 70%;
			margin: 0 auto;
		}
		
		@media screen and (max-width: 576px) {
			
			.employee-details {
				width: 100%;
			}
		
		}
	</style>


	<main class="container-fluid py-5 employee-dashboard">
		
		<div class="employee-details">
		
			<h1 class="text-center mb-3">Account Details</h1>
			
			<div class="form-group row">
				<label for="staticName" class="col-sm-2 col-form-label font-weight-bold">Name</label>
				<div class="col-sm-10">
					<ul class="list-group" id="staticName">
						<li class="list-group-item">
							<?= $employeeName ?>
						</li>
					</ul>
				</div>
			</div>
			<div class="form-group row">
				<label for="staticEmail" class="col-sm-2 col-form-label font-weight-bold">Email</label>
				<div class="col-sm-10">
					<ul class="list-group" id="staticEmail">
						<li class="list-group-item">
							<a href="mailto:<?= $employeeEmail ?>"><?= $employeeEmail ?></a>
						</li>
					</ul>
				</div>
			</div>
			<div class="form-group row">
				<label for="staticDisabilities" class="col-sm-2 col-form-label font-weight-bold">Disabilities</label>
				<div class="col-sm-10">
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
				</div>
			</div>
			<div class="form-group row">
				<label for="staticPhone" class="col-sm-2 col-form-label font-weight-bold">Phone</label>
				<div class="col-sm-10">
					<ul class="list-group" id="staticPhone">
						<li class="list-group-item"><?= $employeePhone ?></li>
					</ul>
				</div>
			</div>
			
			<hr class="mt-5">
			
			<h1 class="text-center my-4">Change Password</h1>
			
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
				<div class="form-group row">
					<div class="col-sm-12">
						<input name="oldPassword" type="password" class="form-control <?= isset($_GET['error']) && ($_GET['error'] === 'incorrect' || $_GET['error'] === 'empty') ? 'is-invalid' : '' ?>" id="oldPassword" placeholder="Old Password" required>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<input name="newPassword" type="password" class="form-control <?= isset($_GET['error']) && ($_GET['error'] === 'unsure' || $_GET['error'] === 'empty') ? 'is-invalid' : '' ?>" id="newPassword" placeholder="New Password" required>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<input name="confirmPassword" type="password" class="form-control <?= isset($_GET['error']) && ($_GET['error'] === 'unsure' || $_GET['error'] === 'empty') ? 'is-invalid' : '' ?>" id="confirmPassword" placeholder="Confirm Password" required>
					</div>
				</div>
				<div class="row my-4">
					<div class="col-sm-12">
						<button type="submit" class="btn btn-primary btn-block">Submit</button>
					</div>
				</div>
			</form>
			
			<!-- EXTRA SPACE FOR MOBILE -->
			<div style="height: 100px"></div>
			
		</div>

	</main>
    
    
<?php 

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');
	
?>