<?php

	// Load PDO Database Connector
	require_once(LIBRARY_PATH . "/pdoDB.php");

	class Disability {
	
		private $db;

		
		# ESTABLISHES CONNECTION TO DB		
		public function __construct() {
			$this->db = new Database();
		}


		# ADDS NEW CLIENT TO DB
		public function add($name, $qualifiesForTaxBreak) {
		
			// Prepare Query
			$this->db->query("INSERT INTO Disability (name, qualifiesForTaxBreak) VALUES (:name :qualifiesForTaxBreak)");

			// Bind Values
			$this->db->bind(':name', $name);
			$this->db->bind(':qualifiesForTaxBreak', $qualifiesForTaxBreak);
			
			// Execute
			$this->db->execute();
			
			// Return Insert ID
			return $this->db->lastInsertId();
			
		}
		
		
		# USED FOR CLIENT PAGE
		public function getByID($did) {
		
			// Prepare Query
			$this->db->query("SELECT * FROM Disability WHERE did = :did");
			
			// Bind Values
			$this->db->bind(':did', $did);
			
			// Return Results
			return $this->db->single();
		
		}
		
		
		# FETCHES ALL CLIENTS IN DB
		public function getAll() {
		
			// Prepare Query
			$this->db->query("SELECT * FROM Disability");
			
			// Return Results
			return $this->db->resultset();
		
		}
		
	}