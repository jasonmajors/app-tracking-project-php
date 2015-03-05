<?php

	class HandlePositions {
		private $database = "phpdb";
		private $user = "root";
		private $host = "localhost";
		private $positions = array();

		public function dbconnect() {
			mysql_connect($this->host, $this->user);
			mysql_select_db($this->database);
		}

		public function updatePositions() {
			$this->positions = $_POST['Positions'];
			$this->dbconnect();
			mysql_query("TRUNCATE TABLE positions");
			
			foreach ($this->positions as $position) {
				$sql = "INSERT INTO POSITIONS (Position) 
						VALUES ('$position')";
				mysql_query($sql);
			}
		}

		public function getPositions() {
			$this->dbconnect();
			$data = mysql_query("SELECT * FROM positions");
			$availablePositions = array();

			if ($data && mysql_num_rows($data)) {
				while ($row = mysql_fetch_assoc($data)) {
					array_push($availablePositions, $row['Position']);
				}		
			}
			return $availablePositions;
		}
	}

?>