<?php
namespace Jason;

class Sql
{
	/**
	* @var PDO connection instance
	*/
	private $_conn;
    
    /**
    * Make a PDO connection with the DB information stored in the .env file
    *
    * @return bool
    */
    public function __construct()
    {
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        
        try {
            $this->_conn = new \PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        }  catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    /**
    * Insert an entry to a table in the database. Note that table column names 
    * must match the data array keys
    *
    * @param string Name of the table
    * @param array $data Associative array $key = column name, $val = data to be inserted
    * @return bool
    */
	public function insert($table, array $data)
	{
		$prepStatement = $this->getInsertStatement($table, $data);
		$statement = $this->_conn->prepare($prepStatement);
		$statement->execute($data);
	}

	/**
	* Creates a SQL insert statement 
	*
	* @param string Name of the table
	* @param array $data Associative array $key = column name, $val = data to be inserted
	*/
	private function getInsertStatement($table, array $data)
	{
	    $sqlVals = array();
	    foreach(array_keys($data) as $key) {
	        $sqlVals[] = ':' . $key;
	    }

	    $prepStatement = "INSERT INTO $table (" .
	                    implode(", ", array_keys($data)) . ") VALUES (" .
	                    implode(", ", $sqlVals) . ")";

	    return $prepStatement;
	}

	public function selectWhere($table, array $conditions)
	{
		$prepStatement = $this->getWhereStatement($table, $conditions);
		$statement = $this->_conn->prepare($prepStatement);
		$statement->execute($conditions);

		return $statement->fetchAll();
	}

	private function getWhereStatement($table, array $conditions)
	{
		// Get last key in array
		end($conditions);
		$end = key($conditions);
		reset($conditions);

		$prepStatement = "SELECT * FROM $table WHERE ";

		foreach(array_keys($conditions) as $key) {
			// End of array, don't need the AND
		    if ($key === $end) {
		        $prepStatement .= $key . ' = ' . ':' . $key;
		    } else {
		    	$prepStatement .= $key . ' = ' . ':' . $key . ' AND '; 
		    }
		}
		return $prepStatement;
	}

}
