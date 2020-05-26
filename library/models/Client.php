<?php

	// Load PDO Database Connector
	require_once(LIBRARY_PATH . "/pdoDB.php");

	class Client {
	
		private $db;

		
		# ESTABLISHES CONNECTION TO DB		
		public function __construct() {
			$this->db = new Database();
		}


		# ADDS NEW CLIENT TO DB
		public function add($name, $alias, $address, $contactPhone, $contactEmail, $paysOvertime = false) {
		
			// Prepare Query
			$this->db->query("INSERT INTO Client (name, alias, address, contactPhone, contactEmail, paysOvertime) VALUES (:name, :alias, :address, :contactPhone, :contactEmail, :paysOvertime)");

			// Bind Values
			$this->db->bind(':name', $name);
			$this->db->bind(':alias', $alias);
			$this->db->bind(':address', $address);
			$this->db->bind(':contactPhone', $contactPhone);
			$this->db->bind(':contactEmail', $contactEmail);
			$this->db->bind(':paysOvertime', $paysOvertime);
			
			// Execute
			return $this->db->execute() ? true : false;
			
		}
		
		
		# FETCHES ALL CLIENTS IN DB
		public function getAll() {
		
			// Prepare Query
			$this->db->query("SELECT * FROM Client");
			
			// Return Results
			return $this->db->resultset();
		
		}
		
	}