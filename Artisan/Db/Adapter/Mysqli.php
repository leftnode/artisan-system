<?php

require_once 'Artisan/Db.php';

require_once 'Artisan/Db/Exception.php';

require_once 'Artisan/Db/Result/Mysqli.php';

require_once 'Artisan/Db/Sql/Select/Mysqli.php';

require_once 'Artisan/Db/Sql/Insert/Mysqli.php';

require_once 'Artisan/Db/Sql/Update/Mysqli.php';

require_once 'Artisan/Db/Sql/Delete/Mysqli.php';

/**
 * The Mysqli class for connecting to a mysql database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db_Adapter_Mysqli extends Artisan_Db {
	/**
	 * Destructor, disconnects from the database if currently connected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {
		if ( true === $this->_is_connected && true === is_object($this->CONN) ) {
			$this->disconnect();
			$this->_is_connected = false;
		}
		unset($this->CONFIG);
	}

	/**
	 * Connect to the database.
	 * @throw Artisan_Database_Exception Throws a new exception if the database connection can not be made.
	 * @retval Object New database connection
	 */
	public function connect() {
		$server = $this->CONFIG->server;
		$username = $this->CONFIG->username;
		$password = $this->CONFIG->password;
		$database = $this->CONFIG->database;

		$port = 3306;
		if ( true === @isset($this->CONFIG->port) ) {
			if ( intval($this->CONFIG->port) > 0 ) {
				$port = intval($this->CONFIG->port);
			}
		}

		// Although generally against supressing errors, the @ is
		// to supress a misconnection error
		// to allow the framework to handle it gracefully
		$this->CONN = @new mysqli($server, $username, $password, $database, $port);

		if ( 0 != mysqli_connect_errno() || false === $this->CONN ) {
			$this->_is_connected = false;
			throw new Artisan_Db_Exception(ARTISAN_WARNING, mysqli_connect_error(), __CLASS__, __FUNCTION__);
		}

		$this->_is_connected = true;

		return $this->CONN;
	}

	/**
	 * Disconnect from the database if already connected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	public function disconnect() {
		if ( true === $this->_is_connected ) {
			$this->CONN->close();
			$this->CONN = NULL;
			$this->_is_connected = false;
		}

		return true;
	}

	public function query($sql) {
		$sql = trim($sql);
		
		if ( true === empty($sql) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'The SQL statement is empty.', __CLASS__, __FUNCTION__);
		}
		
		if ( true === is_object($this->CONN) ) {
			$result = $this->CONN->query($sql);
			
			if ( true === $result instanceof mysqli_result ) {
				return new Artisan_Db_Result_Mysqli($result);
			}
		}
		
		if ( false === $result ) {
			$error_string = $this->CONN->error;
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'Failed to execute query: "' . $sql . '", MySQL said: ' . $error_string, __CLASS__, __FUNCTION__);
		}
		
		return $result;
	}
	
	public function select() {
		if ( NULL == $this->_select ) {
			$this->_select = new Artisan_Db_Sql_Select_Mysqli($this);
		}
		return $this->_select;
	}
	
	public function insert() {
		if ( NULL == $this->_insert ) {
			$this->_insert = new Artisan_Db_Sql_Insert_Mysqli($this);
		}
		$this->_insert->setReplace(false);
		return $this->_insert;
	}
	
	public function update() {
		if ( NULL == $this->_update ) {
			$this->_update = new Artisan_Db_Sql_Update_Mysqli($this);
		}
		return $this->_update;
	}
	
	public function delete() {
		if ( NULL == $this->_delete ) {
			$this->_delete = new Artisan_Db_Sql_Delete_Mysqli($this);
		}
		return $this->_delete;
	}
	
	public function replace() {
		if ( NULL == $this->_insert ) {
			$this->_insert = new Artisan_Db_Sql_Insert_Mysqli($this);
		}
		$this->_insert->setReplace(true);
		return $this->_insert;
	}
	
	public function start() {
		exit('start transaction');
	}
	
	public function commit() {
		exit('commit transaction');
	}
	
	public function rollback() {
		exit('rollback transaction');
	}
	
	public function insertId() {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->insertId;
		}
		return 0;
	}
	
	public function affectedRows() {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->affected_rows;
		}
		return 0;
	}
	
	public function escape($value) {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->real_escape_string($value);
		}
		return addslashes($value);
	}
}