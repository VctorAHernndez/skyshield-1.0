<?php

	// Start User Session
	session_start();
	
	// Load Config File
	require_once("config.php");
	
	// Check Login State
	checkLogin();
	
	// Load Employee Model
	require_once(LIBRARY_PATH . "/models/Employee.php");

	// Load Format Date Function
	require_once(LIBRARY_PATH . "/format_dates.php");
	
	// Load Generate Color Function
	require_once(LIBRARY_PATH . "/generate_color.php");
	
	// Fetch Employee Data
	$employee = new Employee();
	$positions = $employee->getPositions($_SESSION['eid']); // DECIDE HOW TO VIEW IF EMPLOYEE HAS MULTIPLE POSITIONS


	// If employee doesn't have positions,
	// ... it means he's a 'freelancer'
	// ... and hence, not in a contract
	if(count($positions)) {
		
		// Fetch Activity Payload
		$activityPayload = $employee->getActivityPayload($_SESSION['eid'], $positions[0]->pid); // DECIDE HOW TO VIEW IF EMPLOYEE HAS MULTIPLE POSITIONS
// 		$employeeCIIDs = $employee->getCurrentEmployeeCIIDs($_SESSION['eid'], $positions[0]->pid); // DECIDE HOW TO VIEW IF EMPLOYEE HAS MULTIPLE POSITIONS
		$employeeNotInContract = false;
		
		// Extract ciid (if any)
		$employeeCIID = $activityPayload['ciid'];
	
		// Extract view flags
		// CI (clock in), CO (clock out) or CX (optional fill in for somebody)
		$viewFlag = $activityPayload['flag'];
		
	} else {
	
		// View Flag is NA (not allow clocking in)
		$viewFlag = 'NA';
	
	}
// 	else {
// 		$activityPayload = $employee->getActivityPayload($_SESSION['eid'], NULL);
// 		$employeeCIIDs = $employee->getCurrentEmployeeCIIDs($_SESSION['eid'], NULL);
// 		$employeeNotInContract = true;		
// 	}



// 	var_dump($activityPayload);
// 	exit();


	// $employeeNotInContract = $employee->getEmployeeNotInContract($_SESSION['eid']);
	// don't know how to use employeeNotInContract...
	// if employeeNotInContract, then we don't have a pid to look for 



	
	
	// Calculate employee's CIIDs (how much clock-ins he has done 'today')
// 	$employeeEntered = count($employeeCIIDs);

	// If employee has CIIDs,
	// ... it means he already clocked in
	// ... and we have to check if he's clocked out
// 	if($employeeEntered) {
// 		$employeeLeft = $employee->getEmployeeLeft($employeeCIIDs[0]->ciid); // DECIDE WHAT TO DO FOR MULTIPLE CIIDs
// 	} else {
// 		$employeeLeft = false;
// 	}
	
	
	// Fetch employee's last sessions
	$employeeSessions = $employee->getLastSessions($_SESSION['eid']);
	/*
	[
		[
			'ciid' => 5,
			'coid' => 3,
			'pid' => 1,
			'entered' => "2020-05-16 01:53:39",
			'left' => "2020-05-16 17:20:33",
			'comment' => "Everything's good. Nothing new.",
			'role' => "Ronda Main Gate",
			'client' => "Embassy Suites Hotel Dorado"
		],
		[
			'ciid' => 7,
			'coid' => 5,
			'pid' => 1,
			'entered' => "2020-05-14 00:00:00",
			'left' => "2020-05-14 08:33:12",
			'comment' => "Helped María clean the toilets out of boredom.",
			'role' => "Ronda Main Gate",
			'client' => "Embassy Suites Hotel Dorado"
		],
		[
			'ciid' => 6,
			'coid' => 4,
			'pid' => 2,
			'entered' => "2020-05-13 00:00:00",
			'left' => "2020-05-13 14:10:42",
			'comment' => "Everything was fine. Came in a bit late because of traffic.",
			'role' => "Hotel",
			'client' => "Embassy Suites Hotel Dorado"
		]
	];
	*/
	
	// Set Employee Variables
	$employeeName = explode(' ', $_SESSION['name'])[0];
	
	// Rename Page According to Employee Name
	$pageTitle .= $employeeName;
	
	
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
		
		.employee-welcome {
			width: 40%;
			margin: 0 auto;
		}
		
		.employee-sessions {
			width: 80%;
			margin: 0 auto;
		}
		
		@media screen and (max-width: 512px) {
			
			.employee-welcome,
			.employee-sessions {
				width: 100%;
			}
		
		}
	</style>


	<main class="container-fluid py-5 employee-dashboard">
		
		<div class="text-center employee-welcome">
			<h1>Hello, <?= $employeeName ?>!</h1>
			<?php if($viewFlag === 'CX'): ?>
				<p id="prompt-text" class="text-muted">You have no pending position to fill in.</p>
				<button class="btn btn-lg btn-outline-secondary btn-block" onclick="">Filling in for someone?</button>
			<?php elseif($viewFlag === 'CO'): ?>
				<p id="prompt-text" class="text-muted">Already leaving?</p>
				<textarea name="comment" class="form-control mb-3" placeholder="Leave a comment..." rows="3"></textarea>
				<button class="btn btn-lg btn-danger btn-block" onclick="leave(event)">Clock Out</button>
			
				<!-- DECIDE HOW TO VIEW IF EMPLOYEE HAS MULTIPLE CIIDs (DECIDED ON NOT HAVING MULTIPLE CIIDs SINCE YOU SHOULDN'T WORK TWO SHIFTS AT THE SAME TIME) -->
				<input type="hidden" name="ciid" value="<?= $employeeCIID ?>" />
				<!-- $employeeCIIDs[0]->ciid -->
			
			<?php elseif($viewFlag === 'CI'): ?>
			
				<p id="prompt-text" class="text-muted">Ready to work?</p>
			
				<?php if(count($positions) === 1): ?>
					<input type="hidden" name="pid" value="<?= $positions[0]->pid ?>" />
				<?php else: ?>
					<div class="form-group">
						<label for="selectPID">Choose a Position</label>
						<select class="form-control w-75 mx-auto" name="pid" id="selectPID">
						<?php foreach($positions as $position):	?>
							<option value="<?= $position->pid ?>"><?= $position->name ?></option>
						<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
			
				<button class="btn btn-lg btn-primary btn-block" onclick="enter(event)">Clock In</button>
			
			<?php elseif($viewFlag === 'NA'): ?>
				<p id="prompt-text" class="text-muted mb-0">You need a position in order to clock in.</p>
				<p id="prompt-text" class="text-muted mt-0">Contact <a href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a> for more info.</p>
				<button class="btn btn-lg btn-primary btn-block" disabled>Clock In</button>
			<?php endif; ?>
		</div>

		<div class="my-5 p-3 bg-white rounded shadow-sm employee-sessions">
			<h6 class="border-bottom border-gray pb-2 mb-0">Your Last Sessions</h6>
			<?php if(count($employeeSessions)): ?>		
				<?php foreach($employeeSessions as $session): ?>
					<div class="media text-muted pt-3">
						<svg class="bd-placeholder-img mr-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 32x32">
							<rect width="100%" height="100%" fill="<?= generateColor(formatDate($session->entered, $session->left)) ?>"></rect>
						</svg>
						<p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
							<strong class="d-block text-gray-dark"><?= $session->client ?> – <?= $session->role ?></strong>
							<?= $session->comment ?>
							<small class="d-block"><?= formatDate($session->entered, $session->left) ?></small>
						</p>
					</div>
				<?php endforeach; ?>
				<small class="d-block text-right mt-3">
					<a href="#">All sessions</a>
				</small>
			<?php else: ?>
				<div class="media text-muted pt-3 d-flex justify-content-center">
					<small> You have no sessions </small>
				</div>
			<?php endif; ?>
		</div>

	</main>
    
    
<?php 

	// Load Footer File
	require_once(TEMPLATES_PATH . '/footer.php');
	
?>