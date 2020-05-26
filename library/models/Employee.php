<?php

	// Load PDO Database Connector
	require_once(LIBRARY_PATH . "/pdoDB.php");

	class Employee {
	
		private $db;

		
		# ESTABLISHES CONNECTION TO DB		
		public function __construct() {
			$this->db = new Database();
		}


		# ADDS NEW EMPLOYEE TO DB
		public function add($name, $email, $phone, $password, $hourlyWage, $qualifiesOvertime = false) {
		
			// Prepare Query
			$this->db->query("INSERT INTO Employee (name, email, phone, password, hourlyWage, qualifiesOvertime) VALUES (:name, :email, :phone, :password, :hourlyWage, :qualifiesOvertime)");

			// Bind Values
			// - Password Encryption from https://www.php.net/manual/es/function.crypt.php
			$this->db->bind(':name', $name);
			$this->db->bind(':email', $email);
			$this->db->bind(':phone', $phone);
			$this->db->bind(':password', password_hash($password, PASSWORD_DEFAULT)); // crypt($password, PARSED_SALT)
			$this->db->bind(':hourlyWage', $hourlyWage);
			$this->db->bind(':qualifiesOvertime', $qualifiesOvertime);
			
			// Execute
			return $this->db->execute() ? true : false;
			
		}
	
		
		# REMOVES AN EMPLOYEE IN DB
		public function remove($eid) {
		
			if($this->hasClockedIn($eid)) {
				return false;
			}

			// Delete Employee's Disability Relation(s)
			$this->db->query("DELETE FROM hasDisability WHERE eid = :eid");
			$this->db->bind(':eid', $eid);
			$this->db->execute();
			
			// Delete Employee's Position Relation(s)
			$this->db->query("DELETE FROM hasPosition WHERE eid = :eid");
			$this->db->bind(':eid', $eid);
			$this->db->execute();
			
			// Delete Employee
			$this->db->query("DELETE FROM Employee WHERE eid = :eid");
			$this->db->bind(':eid', $eid);
			return $this->db->execute() ? true: false;
			
		}
		
		
		# EDITS EMPLOYEE DETAILS
		public function setDetails($eid, $name, $email, $phone, $hourlyWage, $qualifiesOvertime) {
			
			// Prepare Query
			$this->db->query("UPDATE Employee SET name = :name, email = :email, phone = :phone, hourlyWage = :hourlyWage, qualifiesOvertime = :qualifiesOvertime WHERE eid = :eid");
			
			// Bind Values
			$this->db->bind(':eid', $eid);
			$this->db->bind(':name', $name);
			$this->db->bind(':email', $email);
			$this->db->bind(':phone', $phone);
			$this->db->bind(':hourlyWage', $hourlyWage);
			$this->db->bind(':qualifiesOvertime', $qualifiesOvertime);
			
			// Execute
			return $this->db->execute() ? true : false;
			
		}
		
		
		# CHECKS IF EMPLOYEE HAS CLOCKED IN AT LEAST ONCE
		private function hasClockedIn($eid) {
		
			// Prepare Query
			$this->db->query("SELECT * FROM ClockIn WHERE eid = :eid");
			
			// Bind Values
			$this->db->bind(':eid', $eid);
			
			// Execute
			$this->db->execute();
		
			// Return Result
			return $this->db->rowCount() > 0;
		
		}
		
		
		# FETCHES ALL EMPLOYEES IN DB
		public function getAll() {
		
			// Prepare Query
			$this->db->query("SELECT * FROM Employee");
			
			// Return Results
			return $this->db->resultset();
		
		}
		
		
		# USED FOR CHANGING PASSWORD AND OTHER 'SINGLE VIEW' PAGES
		public function getByID($eid) {
		
			// Prepare Query
			$this->db->query("SELECT * FROM Employee WHERE eid = :eid");
			
			// Bind Values
			$this->db->bind(':eid', $eid);
			
			// Return Employee
			return $this->db->single();
		
		}
		
		
		# USED FOR LOGIN AND ADDING NEW EMPLOYEES
		public function getByEmail($email) {
		
			// Prepare Query
			$this->db->query("SELECT * FROM Employee WHERE email = :email");
			
			// Bind Values
			$this->db->bind(':email', $email);
			
			// Return Employee
			return $this->db->single();
		
		}
		
		
		# USED TO GET ALL EMPLOYEES WHO HAVEN'T CLOCKED OUT
		public function getAllAtWork() {
			
			// Prepare Query
			$this->db->query("SELECT eid, Employee.name, phone, entered, Position.name AS pname, Client.name AS cname
							  FROM Employee NATURAL JOIN (
						    		SELECT eid, pid, entered
									FROM ClockIn
									LEFT JOIN ClockOut
									ON ClockIn.ciid = ClockOut.ciid
									WHERE coid IS NULL
							  ) AS Clocks JOIN Position JOIN Client
							  WHERE Clocks.pid = Position.pid
							  AND Position.cid = Client.cid");
							
			// Return Results
			return $this->db->resultset();

		}


		# USED TO GET ALL EMPLOYEES WHO WORKED TODAY
		public function getAllWhoWorkedToday() {
			
			// Prepare Query
			$this->db->query("SELECT eid, Employee.name, phone, entered, `left`, Position.name AS pname, Client.name AS cname, comment
							  FROM Employee NATURAL JOIN ClockIn NATURAL JOIN ClockOut JOIN Position JOIN Client
							  WHERE ClockIn.pid = Position.pid
							  AND Position.cid = Client.cid
                              AND (DATE(entered) = CURRENT_DATE OR DATE(`left`) = CURRENT_DATE)");
							
			// Return Results
			return $this->db->resultset();

		}
		
		
		# USED TO GET ALL EMPLOYEES WHO HAVE WORKED
		public function getAllWhoWorked() {
			
			$limit = 10;
			
			// Prepare Query
			$this->db->query("SELECT eid, Employee.name, phone, entered, `left`, Position.name AS pname, Client.name AS cname, comment
							  FROM Employee NATURAL JOIN ClockIn NATURAL JOIN ClockOut JOIN Position JOIN Client
							  WHERE ClockIn.pid = Position.pid
							  AND Position.cid = Client.cid
							  ORDER BY `left` DESC
							  LIMIT $limit");
							
			// Return Results
			return $this->db->resultset();

		}
		
		
		# USED FOR CHANGING PASSWORD
		public function changePassword($eid, $newPassword) {
		
			// Prepare Query
			$this->db->query("UPDATE Employee SET password = :password WHERE eid = :eid");
			
			// Bind Values
			$this->db->bind(':eid', $eid);
			$this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT)); // crypt($newPassword, PARSED_SALT)
			
			// Execute
			return $this->db->execute() ? true : false;
		
		}
		
		
		# FOR ADMINISTRATIVE USE AND ACCOUNT DISPLAY
		public function getDisabilities($eid) {
		
			// Prepare Query
			$this->db->query("SELECT d.name, d.qualifiesForTaxBreak FROM Disability AS d NATURAL JOIN hasDisability AS hd WHERE hd.eid = :eid");
		
			// Bind Values
			$this->db->bind(':eid', $eid);
			
			// Return Results
			return $this->db->resultset();
		
		}
		
		
		# USED FOR EMBEDDING pid IN HTML TO FETCH FROM WHEN USING THE 'ENTER' BUTTON
		/* Change to adapt multiple pid  */
		/* Only gets 'active' positions (until is set to null) */
		public function getPositions($eid) {
		
			// Prepare Query
			$this->db->query("SELECT p.name, p.pid FROM Position AS p NATURAL JOIN hasPosition AS hp WHERE hp.eid = :eid AND until IS NULL");
		
			// Bind Values
			$this->db->bind(':eid', $eid);
			
			// Return Results
			return $this->db->resultset();
		
		}
		
		
		# USED FOR KNOWING IF EMPLOYEE IS CURRENTLY CLOCKED IN
		// Gets the clock-in
		// ... that doesn't have a corresponding clock-out
		// ... whose date matches that of the most recent registered clock-in
		// ... (that way, you can leave behind the past clock-ins that don't have a clock-out)
		// ... for a given employee and his/her position
		// Also sets flags for view states
		/* POSSIBLY FETCH THE NAME OF THE POSITION THAT IS CURRENTLY TAKING PLACE TO MAKE EMPLOYEE AWARE OF WHAT HE'S CLOCKING IN FOR  */
		public function getActivityPayload($eid, $pid) {
		
			// Flag Meanings:
			// 	1. CO - prompt employee to clock out
			//	2. CI - prompt employee to clock in
			//	3. CX - notify employee he's already clocked out
			//			... and give him a chance to fill in for somebody else
		
			// TO DO:
			// Add logic to check...
			/// If employee has a shift,
				// ... and the current hour is within 15 min of the shift's start
					// ... and there's a CIID available
						// -> return the CIID (notify user he can clock out)
					// ... and there's no CIID available
						// -> return flag to notify employee he can clock in
				// ... and the current hour is greater than 15 min of the shift's start
					// ... and there's a CIID available
						// -> return the CIID (notify user he can clock out)
					// ... and there's no CIID available
						// -> return flag to notify employee he's done for the day
			// If employee doesn't have a shift,
				// -> return flag to notify employee he can clock in (although he can't since clockIn() function won't let him)
		
			// Prepare Query & Bind pid (depending on if NULL or not)
// 			if($pid === NULL) {
// 				$this->db->query("SELECT ciid FROM ClockIn WHERE eid = :eid AND pid IS NULL AND ciid NOT IN (SELECT ciid FROM ClockOut) AND entered = (SELECT MAX(entered) FROM ClockIn WHERE eid = :eid)"); // was ORDER BY entered DESC LIMIT 1
// 			} else {
				$this->db->query("SELECT ciid FROM ClockIn WHERE eid = :eid AND pid = :pid AND ciid NOT IN (SELECT ciid FROM ClockOut) AND entered = (SELECT MAX(entered) FROM ClockIn WHERE eid = :eid)"); // was ORDER BY entered DESC LIMIT 1
				$this->db->bind(':pid', $pid);
// 			}
			
			// Bind eid
			$this->db->bind(':eid', $eid);
			
			// Fetch ciid
			$data = $this->db->single(); // will return false if no ciid
			$ciid = $data ? $data->ciid : '';
			
			// Set Flag
			/* WILL CHANGE WITH SHIFT LOGIC */
			$flag = ($ciid === '') ? 'CI' : 'CO';
			
			// Prepare Payload
			$payload = [
				'ciid' => $ciid,
				'flag' => $flag
			];
			
			// Return Result
			return $payload;
		
		}
		
		
		# USED FOR KNOWING IF EMPLOYEE HAS CLOCKED IN 'TODAY'
		/* POR ALGUNA RAZÓN EN EL INSERT SE GUARDA CON 3 HORAS DE ATRASO... Y EL CURRENT_DATE LE PERSIGUE */
		/* QUIZÁS HAY QUE ESPECIFICAR QUE SI SACO HORAS O LO QUE SEA DE LA DB LO SAQUE CON NUESTRO TIMEZONE (POR AHORA SON 3 HORAS DE ADELANTO) */
// 		public function getCurrentEmployeeCIIDs($eid, $pid) {
			
			// Prepare Query & Bind pid (depending on if NULL or not)
// 			if($pid === NULL) {
// 				$this->db->query("SELECT ciid FROM ClockIn WHERE eid = :eid AND pid IS NULL AND DATE(entered) = CURRENT_DATE ORDER BY entered DESC");
// 			} else {
// 				$this->db->query("SELECT ciid FROM ClockIn WHERE eid = :eid AND pid = :pid AND DATE(entered) = CURRENT_DATE ORDER BY entered DESC");
// 				$this->db->bind(':pid', $pid);
// 			}
			
			// Bind eid
// 			$this->db->bind(':eid', $eid);
			
			// Return Results
// 			return $this->db->resultset();
			
// 		}
		
		
		# USED FOR KNOWING IF EMPLOYEE HAS CLOCKED OUT 'TODAY'
		/* POR ALGUNA RAZÓN EN EL INSERT SE GUARDA CON 3 HORAS DE ATRASO... Y EL CURRENT_DATE LE PERSIGUE */
		/* QUIZÁS HAY QUE ESPECIFICAR QUE SI SACO HORAS O LO QUE SEA DE LA DB LO SAQUE CON NUESTRO TIMEZONE (POR AHORA SON 3 HORAS DE ADELANTO) */
// 		public function getEmployeeLeft($ciid) {
		
			// Prepare Query
// 			$this->db->query("SELECT * FROM ClockOut WHERE ciid = :ciid AND DATE(`left`) = CURRENT_DATE ORDER BY `left` DESC");
			
			// Bind Values
// 			$this->db->bind(':ciid', $ciid);
			
			// Execute
// 			$this->db->execute();
			
			// Return Results
// 			return $this->db->rowCount() > 0;
			
// 		}
		
		
		# USED FOR KNOWING IF EMPLOYEE 'IS IN A CONTRACT' (i.e. IN hasPosition TABLE)
// 		public function getEmployeeNotInContract($eid) {
		
			// Prepare Query
// 			$this->db->query("SELECT * FROM hasPosition WHERE eid = :eid");
			
			// Bind Values
// 			$this->db->bind(':eid', $eid);
			
			// Execute
// 			$this->db->execute();
			
			// Return Results
// 			return $this->db->rowCount() === 0;
			
// 		}

		# USED FOR CHECKING IF EMPLOYEE HAS A CERTAIN POSITION BEFORE CLOCKING IN
		private function hasPosition($eid, $pid) {
		
			// Prepare Query
			$this->db->query("SELECT * FROM hasPosition WHERE eid = :eid AND pid = :pid");
			
			// Bind Values
			$this->db->bind(':eid', $eid);
			$this->db->bind(':pid', $pid);	

			// Execute
			$this->db->execute();
			
			// Return Result
			return $this->db->rowCount() > 0;
				
		}
		
		
		# USED FOR CLOCKING IN
		/* This can change, as hasPosition may have a different PRIMARY KEY afterwards */
		public function clockIn($eid, $pid) {
		
			// Only continue if employee has a position
			if(!$this->hasPosition($eid, $pid)) {
				return false;
			}

			// Prepare Query
			$this->db->query("INSERT INTO ClockIn (eid, pid) VALUES (:eid, :pid)");
			
			// Bind values
			$this->db->bind(':eid', $eid);
			$this->db->bind(':pid', $pid);	
			
			// Execute
			return $this->db->execute() ? true : false;
		
		}
		
		
		# USED FOR CLOCKING OUT
		/* This can change, as hasPosition may have a different PRIMARY KEY afterwards */
		/* MAKE APPROVED DEPEND ON IF USER IS WITHIN 15MIN OF LEAVE FROM SCHEDULE */
		public function clockOut($ciid, $comment) {

			// Prepare Query
			$this->db->query("INSERT INTO ClockOut (ciid, comment, approved) VALUES (:ciid, :comment, 1)");
			
			// Bind Values & Execute
			$this->db->bind(':ciid', $ciid);
			$this->db->bind(':comment', $comment);
			$this->db->execute();
			
			// Fetch Last Insert ID
			$coid = $this->db->lastInsertId();
			
			// Disapprove the clock-out if employee doesn't have an established position (pid equal to the bucket position id)
			$this->db->query("UPDATE ClockOut SET approved = 0 WHERE coid = :coid AND ciid IN (SELECT ciid FROM ClockIn WHERE pid = 0 AND ciid = :ciid)");
			$this->db->bind(':coid', $coid);
			$this->db->bind(':ciid', $ciid);
			
			// Execute
			return $this->db->execute() ? true : false;
		
		}
		
		
		# USED FOR DISPLAY PURPOSES IN EMPLOYEE PAGE
		/* QUIZÁS HAY QUE HACER UN LEFT JOIN (APUNTANDO A CLOCKIN) PARA INCLUIR LAS SESIONES DE LAS QUE TODAVÍA NO HA SALIDO */
		public function getLastSessions($eid) {
		
			// Set Limit
			$limit = 3;
		
			// Prepare First Query (as if employee has a position)
			$this->db->query("SELECT ciid, coid, pid, entered, `left`, `comment`, p.name AS role, c.name AS client FROM ClockOut NATURAL JOIN ClockIn NATURAL JOIN Position AS p JOIN Client AS c WHERE c.cid = p.cid AND eid = :eid ORDER BY `left` DESC LIMIT $limit");
			
			// Bind values
			$this->db->bind(':eid', $eid);
			$this->db->execute();
			
			// Prepare & Bind Second Query (if employee doesn't have a position)
// 			if($this->db->rowCount() === 0) {
// 				$this->db->query("SELECT ciid, coid, NULL AS pid, entered, `left`, `comment`, 'Independent Work' AS role, 'Sky Shield Security' AS client FROM ClockOut NATURAL JOIN ClockIn WHERE pid is NULL AND eid = :eid ORDER BY `left` DESC LIMIT $limit");
// 				$this->db->bind(':eid', $eid);
// 			}
			
			// Execute
			return $this->db->resultset();
		
		
		}
	
	}