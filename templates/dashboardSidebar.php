	<nav class="col-md-2 d-none d-md-block bg-light sidebar mt-2">
		<div class="sidebar-sticky">
		
			<ul class="nav flex-column">
				<li class="nav-item">
					<a class="nav-link <?= $requestedScript === 'dashboard.php' ? 'active' : '' ?>" href="<?= BASE_URL . '/dashboard.php' ?>">
						<span data-feather="home"></span>
						Home
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= $requestedScript === 'employees.php' ? 'active' : '' ?>" href="<?= BASE_URL . '/employees.php' ?>">
						<span data-feather="users"></span>
						Employees
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= $requestedScript === 'disabilities.php' ? 'active' : '' ?>" href="<?= BASE_URL . '/disabilities.php' ?>">
						<span data-feather="activity"></span><!--alert-octagon or alert-triangle or alert-circle-->
						Disabilities
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= $requestedScript === 'account.php' ? 'active' : '' ?>" href="<?= BASE_URL . '/account.php' ?>">
						<span data-feather="settings"></span>
						Account
					</a>
				</li>
<!-- 
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="file"></span>
						Orders
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="shopping-cart"></span>
						Products
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="users"></span>
						Customers
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="bar-chart-2"></span>
						Reports
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="layers"></span>
						Integrations
					</a>
				</li>
 -->
			</ul>
			
			<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
				<span>Clients</span>
				<a type="button" class="d-flex align-items-center text-muted" data-toggle="modal" data-target="#newClientModal" title="Add a new client" aria-label="Add a new client">
					<span data-feather="plus-circle"></span>
				</a>
			</h6>
			
			<?php if(count($clients)): ?>
			<ul class="nav flex-column">
			<?php foreach($clients as $client): ?>
				<li class="nav-item col-sm-12 px-0">
					<a class="nav-link text-truncate" href="#">
						<span data-feather="briefcase"></span>
						<?= $client->name ?>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php else: ?>
			<ul class="nav flex-column">
				<li class="nav-item">
					<small class="nav-link text-secondary font-weight-light pl-5">No clients</small>
				</li>
			</ul>
			<?php endif; ?>
<!-- 

			<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
				<span>Saved reports</span>
				<a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
					<span data-feather="plus-circle"></span>
				</a>
			</h6>
			
			<ul class="nav flex-column mb-2">
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="file-text"></span>
						Current month
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="file-text"></span>
						Last quarter
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="file-text"></span>
						Social engagement
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">
						<span data-feather="file-text"></span>
						Year-end sale
					</a>
				</li>
			</ul>		
 -->

			<h6 class="sidebar-heading px-3 my-4 mb-1 text-muted align-bottom">
				<a class="<?= $requestedScript === 'comingsoon.php' ? 'text-primary' : 'text-secondary' ?>" href="<?= BASE_URL . '/comingsoon.php' ?>">Coming Soon</a>
			</h6>
			
			<h6 class="sidebar-heading px-3 my-4 text-muted fixed-bottom">
				<span>Nández &copy; 2020</span>
			</h6>
			
		</div>
	</nav>
	
	
	<!-- MODAL FOR NEW CLIENT -->
	<div class="modal fade" id="newClientModal" tabindex="-1" role="dialog" aria-labelledby="newClientModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="newClientModalLabel">New Client</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					
					<!-- DISPLAY NEW EMPLOYEE ALERTS -->
					<?php if(isset($_GET['error']) && $_GET['error'] === 'client'): ?>
					
						<!-- SHOW MODAL -->
						<script>setTimeout(function() {$('#newClientModal').modal('show')}, 500)</script>
					
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
					
					<form action="<?= BASE_URL . '/processes/addClient.php' ?>" method="post" id="newClientForm">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputNewName">Name</label>
								<input type="text" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'client') ? 'is-invalid' : '' ?>" name="inputNewName" id="inputNewName" value="<?= (isset($_GET['error']) && $_GET['error'] === 'client') ? $_GET['name'] : '' ?>" required>
							</div>
							<div class="form-group col-md-6">
								<label for="inputNewEmail">Email</label>
								<input type="email" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'client') ? 'is-invalid' : '' ?>" name="inputNewEmail" id="inputNewEmail" value="<?= (isset($_GET['error']) && $_GET['error'] === 'client') ? $_GET['email'] : '' ?>" required>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="inputNewPhone">Phone</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text">+1</div>
									</div>
									<input type="tel" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'client') ? 'is-invalid' : '' ?>" name="inputNewPhone" id="inputNewPhone" value="<?= (isset($_GET['error']) && $_GET['error'] === 'client') ? $_GET['phone'] : '' ?>">
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="inputNewHourlyWage">Hourly Wage</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text">$</div>
									</div>
									<input type="number" class="form-control <?= (isset($_GET['error']) && $_GET['error'] === 'client') ? 'is-invalid' : '' ?>" min="0" step="0.25" max="1000" onchange="this.value = parseFloat(this.value).toFixed(2)" name="inputNewHourlyWage" id="inputNewHourlyWage" value="<?= (isset($_GET['error']) && $_GET['error'] === 'client') ? $_GET['hourlyWage'] : '' ?>">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="inputNewQualifiesOvertime" id="inputNewQualifiesOvertime" <?= (isset($_GET['error']) && $_GET['error'] === 'client' && $_GET['qualifiesOvertime'] === '1') ? 'checked' : '' ?>>
								<label class="form-check-label" for="inputNewQualifiesOvertime">
									Will Be Payed Overtime
								</label>
							</div>
						</div>
					</form>	
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="validateNewClient()">Add Client</button>
				</div>
			</div>
		</div>
	</div>
	