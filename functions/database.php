<?php

class MySQLDatabase
{

    private $host = "localhost"; //the host
    private $db_user = "root";  //Your username
    private $pass = "";  // Your password
    private $dbase = ""; // database name
    private $connection;

    function __construct()
    {
        $this->open_connection();
    }

    public function open_connection()
    {
        $this->connection = mysqli_connect($this->host, $this->db_user, $this->pass, $this->dbase);
        if (mysqli_connect_errno()) {
            die("Database connection failed: " .
                mysqli_connect_error() .
                " (" . mysqli_connect_errno() . ")");
        }
    }

    public function close_connection()
    {
        if (isset($this->connection)) {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->connection, $sql);
        $this->confirm_query($result);
        return $result;
    }

    private function confirm_query($result)
    {
        if (!$result) {
            die(mysqli_error($this->connection));
        }
    }

    public function escape_value($par)
    {
        if (is_array($par)) {
            $escaped = [];
            foreach ($par as $key => $value) {
                $escaped[$key] = mysqli_real_escape_string($this->connection, $value);
            }
        } else {
            $escaped = mysqli_real_escape_string($this->connection, $par);
        }
        return $escaped;
    }

    // "database neutral" functions

    public function fetch_array($result_set)
    {
        return mysqli_fetch_assoc($result_set);
    }

    public function num_rows($result_set)
    {
        return mysqli_num_rows($result_set);
    }

    public function insert_id()
    {
        // get the last id inserted over the current db connection
        return mysqli_insert_id($this->connection);
    }

    public function affected_rows()
    {
        return mysqli_affected_rows($this->connection);
    }
}

$database = new MySQLDatabase();
$db = &$database;