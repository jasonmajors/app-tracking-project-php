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
        // Set database information from .env file
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
    * @return string The SQL insert statement
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

    /**
    * Select data from a table
    *
    * @param string Name of the table
    * @param array Associative array where $key is the column name and $val is the value of interest
    * @return Array of the results with each row as an array of k => v pairs
    */
    public function select($table, array $conditions)
    {
        list($prepStatement, $executeValues) = $this->getSelectStatement($table, $conditions);
        $statement = $this->_conn->prepare($prepStatement);
        $statement->execute($executeValues);

        return $statement->fetchAll();
    }

    /**
    * Create a SELECT statement
    *
    * @param string Name of the table to select from
    * @param array $conditions Associative array where $key is the column name and $val is the value of interest
    * If $val is an array, the SQL statement will use IN
    * @return string The SQL select statement
    */
    private function getSelectStatement($table, array $conditions)
    {
        // Set the last key in $conditions to $end
        end($conditions);
        $end = key($conditions);
        // Reset the pointer
        reset($conditions);
        
        $prepStatement = "SELECT * FROM $table WHERE ";
        $executeValues = array();
        
        foreach($conditions as $key => $value) {
            // If $value is an array, build IN statement
            if (is_array($value)) {
                // Merge the $value array containing the values for the IN statement execution
                $executeValues = array_merge($executeValues, $value);
                $placeholders = rtrim(str_repeat('?, ', count($value)), ' ,'); 
                if ($key === $end ) {
                    $prepStatement .= $key . " IN ($placeholders)";
                } else {
                    $prepStatement .= $key . " IN ($placeholders) AND ";
                }
            // Value is not an array, basic WHERE column = value statement 
            } else {
                if ($key == $end ) {
                    $prepStatement .= $key . ' = ' . '?';
                } else {
                    $prepStatement .= $key . ' = ' . '?' . ' AND ';
                }
                // Add the value to be executed into the placeholder
                $executeValues[] = $value;
            }    
        }
        return array($prepStatement, $executeValues);
    }

}
