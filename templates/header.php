<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Sky Shield Security Employee and Administrative Website">
		<meta name="author" content="Víctor Alfonso Hernández Castro">
		<meta name="theme-color" content="#f4f4f4">
		
<!--     <link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/sign-in/"> -->
		
		<link rel="apple-touch-icon" href="<?= BASE_URL . '/public/img/sky-shield-s.jpeg' ?>" sizes="180x180">
		<link rel="icon" href="<?= BASE_URL . '/public/img/sky-shield-s.jpeg' ?>" sizes="32x32" type="image/jpeg">
		<link rel="icon" href="<?= BASE_URL . '/public/img/sky-shield-s.jpeg' ?>" sizes="16x16" type="image/jpeg">
		
		    <!-- DONT UNDERSTAND THESE YET -->
<!-- <link rel="manifest" href="/docs/4.4/assets/img/favicons/manifest.json"> -->
<!-- <link rel="mask-icon" href="/docs/4.4/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c"> -->
<!-- <link rel="icon" href="/docs/4.4/assets/img/favicons/favicon.ico"> -->
<!-- <meta name="msapplication-config" content="/docs/4.4/assets/img/favicons/browserconfig.xml"> -->
		
		<title><?= $pageTitle ?></title>

		<!-- BOOTSTRAP CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		
		<!-- CUSTOM CSS -->
		<?php
		
			// Include Specific Stylesheets
			if($requestedScript === 'index.php' || $requestedScript === '') {
				echo "<link href=\"" . BASE_URL . "/public/css/signin.css\" rel=\"stylesheet\">\n";
			} elseif(isAdmin()) {
				echo "<link href=\"" . BASE_URL . "/public/css/dashboard.css\" rel=\"stylesheet\">";
			}
		
		?>
	</head>
	<body>