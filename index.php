<?php

	// Start Session
	session_start();

	// Load Config File
	require_once("config.php");
	
	// Redirect User to Dashboard If Logged In
	redirectIfLoggedIn();

	// Load Header File
	require_once(TEMPLATES_PATH . "/header.php");

?>


	<style>
		#sky-shield-logo {
			--size: 100%;
/* 			height: var(--size); */
			width: var(--size);
			/* we should use a square photo, if not, we can clear the width line */
		}
	</style>


	<main class="text-center py-5">
		<form class="form-signin" action="<?= BASE_URL . '/processes/login.php' ?>" method="POST">
			<img id="sky-shield-logo" class="mb-4 rounded" src="<?= BASE_URL . '/public/img/sky-shield-logo.jpg' ?>" alt="sky-shield-logo">
			<h1 class="display-4">Sky Shield</h1>
			<h6 class="mb-3 font-weight-normal">Please Login</h6>
			<label for="inputEmail" class="sr-only">Email address</label>
			<input name="email" type="email" id="inputEmail" class="form-control <?= isset($_GET['error']) ? 'is-invalid' : '' ?>" placeholder="Email address" value="<?= isset($_GET['email']) ? $_GET['email'] : '' ?>" required>
			<label for="inputPassword" class="sr-only">Password</label>
			<input name="password" type="password" id="inputPassword" class="form-control <?= isset($_GET['error']) ? 'is-invalid' : '' ?>" placeholder="Password" required>
<!--       <div class="checkbox mb-3"> -->
<!--         <label> -->
<!--           <input type="checkbox" value="remember-me"> Remember me -->
<!--         </label> -->
<!--       </div> -->
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			<p class="my-5 text-muted">Nández &copy; 2020</p>
		</form>
	</main>

    
<?php

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');

?>