	<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow fixed-top"><!-- flex-md-nowrap p-0 -->
		<a class="navbar-brand mr-0" href="<?= BASE_URL . '/dashboard.php' ?>">
			<img class="rounded" src="<?= BASE_URL . '/public/img/sky-shield-s.jpeg' ?>" class="d-inline-block align-top rounded" alt="sky-shield-logo">
			<span class="ml-1">Sky Shield</span>
		</a><!-- col-sm-3 col-md-2 mr-0 -->
		<span class="navbar-text ml-auto mr-3 text-white">
			<?= $_SESSION['name'] ?> <!--Welcome, $employeeName-->
		</span>
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