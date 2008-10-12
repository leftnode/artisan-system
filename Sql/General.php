<?php

/**
 * The abstract General class for executing a general or very complex query against the database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Sql_General extends Artisan_Sql {
	/**
	 * Default constructor for building a new INSERT query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Sql_General object.
	 */
	public function __construct() {
	}
	
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {
		unset($this->_sql);
	}

	/**
	 * Sets the SQL to query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns an instance of itself for chaining.
	 */
	public function sql($sql) {
		$sql = trim($sql);
		
		if ( true === empty($sql) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The SQL query is empty.', __CLASS__, __FUNCTION__);
		}
		
		$this->_sql = $sql;
		
		return $this;
	}

	/**
	 * Executes the set query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns an instance of itself for chaining.
	 */
	abstract public function query();
	
	/**
	 * After the query has been successfully executed, this will return the result for further manipulation.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns an instance of the result object.
	 */
	abstract public function result();
}

?>
