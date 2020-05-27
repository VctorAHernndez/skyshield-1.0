    
    	<!-- BOOTSTRAP JS AND JQUERY JS-->
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- 		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		
		<!-- Custom Scripts -->
		<?php

			// Include depending on requested page
			if($requestedScript === 'employee.php') {
				echo "<script src=\"" . BASE_URL . "/public/js/register.js\"></script>\n";
			} elseif(isAdmin()) {
			
				echo "<script src=\"" . BASE_URL . "/public/js/client.js\"></script>";
				echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js\"></script>\n";
				echo "<script>feather.replace()</script>\n";
				//      <script>window.jQuery || document.write('<script src="/docs/4.4/assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
				//<!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
				//      <script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>	
				
				// ChartJS & Dashboard Home Script
				if($requestedScript === "dashboard.php") {			
// 					echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js\"></script>\n";
// 					echo "<script src=\"" . BASE_URL . "/public/js/dashboard.js\"></script>\n";
				}			
				
				// Employees Page Script
				if($requestedScript === "employees.php") {
					echo "<script src=\"" . BASE_URL . "/public/js/employees.js\"></script>\n";
				}
				
			}

		?>
	</body>
</html>