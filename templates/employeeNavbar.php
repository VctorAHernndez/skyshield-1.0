	<nav class="navbar navbar-expand-sm navbar-dark bg-dark shadow">
		<a class="navbar-brand" href="<?= BASE_URL . '/employee.php' ?>">
			<img class="rounded" src="<?= BASE_URL . '/public/img/sky-shield-s.jpeg' ?>" width="30" height="30" class="d-inline-block align-top rounded" alt="sky-shield-logo">
			<span class="ml-2">Sky Shield</span>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link <?= $requestedScript === 'employee.php' ? 'active' : '' ?>" href="<?= BASE_URL . '/employee.php' ?>">
						<svg class="bi bi-house-door" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M7.646 1.146a.5.5 0 01.708 0l6 6a.5.5 0 01.146.354v7a.5.5 0 01-.5.5H9.5a.5.5 0 01-.5-.5v-4H7v4a.5.5 0 01-.5.5H2a.5.5 0 01-.5-.5v-7a.5.5 0 01.146-.354l6-6zM2.5 7.707V14H6v-4a.5.5 0 01.5-.5h3a.5.5 0 01.5.5v4h3.5V7.707L8 2.207l-5.5 5.5z" clip-rule="evenodd"/>
							<path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5z" clip-rule="evenodd"/>
						</svg>
						Home
					</a>
				</li>
				<li class="nav-item <?= $requestedScript === 'employeeAccount.php' ? 'active' : ''  ?>">
					<a class="nav-link" href="<?= BASE_URL . '/employeeAccount.php' ?>">
<!-- 						<img src="<?= BASE_URL . '/public/img/person-lines-fill.svg' ?>" alt="account" title="Account"> -->
						<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
							<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="9" cy="7" r="4"></circle>
							<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
							<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
						</svg>
						Account
					</a>
				</li>
				<li class="nav-item">
					<a class="text-danger nav-link" href="<?= BASE_URL . '/processes/logout.php'?>">
						<svg class="bi bi-box-arrow-in-left" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M7.854 11.354a.5.5 0 000-.708L5.207 8l2.647-2.646a.5.5 0 10-.708-.708l-3 3a.5.5 0 000 .708l3 3a.5.5 0 00.708 0z" clip-rule="evenodd"/>
							<path fill-rule="evenodd" d="M15 8a.5.5 0 00-.5-.5h-9a.5.5 0 000 1h9A.5.5 0 0015 8z" clip-rule="evenodd"/>
							<path fill-rule="evenodd" d="M2.5 14.5A1.5 1.5 0 011 13V3a1.5 1.5 0 011.5-1.5h8A1.5 1.5 0 0112 3v1.5a.5.5 0 01-1 0V3a.5.5 0 00-.5-.5h-8A.5.5 0 002 3v10a.5.5 0 00.5.5h8a.5.5 0 00.5-.5v-1.5a.5.5 0 011 0V13a1.5 1.5 0 01-1.5 1.5h-8z" clip-rule="evenodd"/>
						</svg>
						Sign Out
					</a>
				</li>
			</ul>
		</div>
	</nav>
	
	<style>
		.navbar.navbar-dark.bg-dark {
			background-color: black !important;
		}
		
		.nav-link.text-danger {
			opacity: 0.75;
		}
		
		.nav-link.text-danger:hover {
			color: #dc3545 !important; /* Bootstrap changes color on hover */
			opacity: 1;
		}
	</style>