<?php
    class Database
    {
        private $_conn;

        public function __construct()
        {
            // USERNAME & PASSWORD defined in settings.php
            $username = USERNAME;
            $password = PASSWORD;

            try {
                $this->_conn = new PDO("mysql:host=localhost;dbname=jasonmaj_ats", $username, $password);

            }  catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
        }
        // TODO - Restructure the positions table and make it so users can add/remove positions
        public function updatePositions(array $open_positions) 
        {
            // Reset the 'positions' table to whatever the Employer selects.
            $this->_conn->query('TRUNCATE positions');
            $statement = $this->_conn->prepare("INSERT INTO positions (Position) VALUES (:position)");
            // Insert each available position from the open_positions array.
            foreach ($open_positions as $avail_position) {
                $statement->bindParam(':position', $avail_position);
                $statement->execute();
            }
        }
        
        public function getTable($table, $field='', $condition='') 
        {
            if ($field && $condition) {
                $statement = $this->_conn->prepare("SELECT * FROM $table WHERE $field = '$condition'");
            } else {
                $statement = $this->_conn->prepare("SELECT * FROM $table");
            }
            $statement->execute();
                        
            $result = $statement->fetchAll();

            return $result;
        }

        // Returns a column as a single array.
        public function build_column_array($table, $column_name)
        {
            $db_entries = $this->getTable($table=$table);
            $column_array = array();

            foreach($db_entries as $entry) {
                $value = $entry[$column_name];
                $column_array[] = $value;
            }
            
            return $column_array; 
        }

        public function register($firstname, $lastname, $username, $password)
        {
            $statement = $this->_conn->prepare("INSERT INTO users (FirstName, LastName, Username, Password) 
                                                VALUES (:firstname, :lastname, :username, :password)");
            $statement->bindParam(':firstname', $firstname);
            $statement->bindParam(':lastname', $lastname);
            $statement->bindParam(':username', $username);
            $statement->bindParam(':password', $password);
            $statement->execute();
        }

        public function submitApplication(array $application, $position) 
        { 
            $time = time();
            $status = 'Pending';

            $statement = $this->_conn->prepare("INSERT INTO applications (Last_Name, First_Name, Email, Position, Date, Phone_Number, App_Status, Work_History) 
                                        VALUES (:lastname, :firstname, :email, :position, $time, :phonenumber, '$status', :workhistory)");
            
            $statement->bindParam(':lastname', $application['Last_Name']);
            $statement->bindParam(':firstname', $application['First_Name']);
            $statement->bindParam(':email', $application['Email']);
            $statement->bindParam(':position', $position);
            $statement->bindParam(':phonenumber', $application['Phone_Number']);
            $statement->bindParam(':workhistory', $application['Work_History']);
            $statement->execute();
        }

        public function alterApplication(array $app_ids, $field, $value)
        {
            // Change a specified field to a new value.
            $statement = $this->_conn->prepare("UPDATE applications SET $field = :value WHERE ID = :identifier");
            foreach($app_ids as $id) {
                $statement->bindParam(':value', $value);
                $statement->bindParam(':identifier', $id);
                $statement->execute();
            }
        }
    }
?>