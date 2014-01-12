<?php


class MysqliDatabase {

	private $mysqli;
    private $result;
    private $stmt;
	private $magic_quotes_active;
	private $real_escape_string_exists;

    function __construct() {

        $this->open_connection();
        $this->magic_quotes_active = get_magic_quotes_gpc();
        $this->real_escape_string_exists = function_exists( "mysql_real_escape_string" );

    }


    public function open_connection() {

        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->$mysqli->connect_error;
        }

    }

    public function close_connection() {

        if(isset($this->mysqli)) {
            mysqli_free_result($this->result);
        }

    }


    /*
    *  Non-Prepared Statements (ie SELECT)
    */
	public function query($sql) {

		$this->result = $this->mysqli->query($sql);
        $this->confirm_query($this->result);
        return $this->result;

	}


    /*
    *  Prepared Statements (ie INSERT or if you want to type the SELECT results)
    */
    // Prepared statement, stage 1: prepare
    public function prep($sql) {

        if (!($this->stmt = $mysqli->prepare($sql))) {
             echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
        }

    }

    // Prepared statement, stage 2: bind and execute
    public function prep_execute($params) {

        if (!$this->stmt->bind_param($params)) {  // Figure out what "i" is...and how to bind multiple values.
            echo "Binding parameters failed: (" . $this->stmt->errno . ") " . $this->stmt->error;
        }
        if (!$this->stmt->execute()) {
            echo "Execute failed: (" . $this->stmt->errno . ") " . $this->stmt->error;
        }

    }

    // Prepared statement, stage 3: close
    public function prep_close() {

        if(isset($this->stmt)) {
            $this->stmt->close();
        }

    }


    public function num_rows() {            // Num Rows

        return $this->result->num_rows;

    }


    public function affected_rows() {       // Affected Rows

        return $this->result->affected_rows;

    }


    public function fetch_all() {           // Fetch All

        return $this->result->fetch_all();

    }


    public function insert_id() {           // Last inserted ID

        return $this->mysqli->insert_id;

    }


    public function escape_value($value) {    // Escape value for insertion

        if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
            // undo any magic quote effects so mysql_real_escape_string can do the work
            if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
            $value = mysql_real_escape_string( $value );
        } else { // before PHP v4.3.0
            // if magic quotes aren't already on then add slashes manually
            if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
            // if magic quotes are active, then the slashes already exist
        }
        return $value;

    }


    private function confirm_query() {  // TO DO: send to error handler class

    	if (!$this->result) {
            $output = "<br /><span class='errortext'>Database query failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error . "</span><br />";
            die( $output );
    	}

    }

}

?>