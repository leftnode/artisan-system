<?php

require_once 'Artisan/Db/Sql/Exception.php';

require_once 'Artisan/Db/Sql.php';

/**
 * The Sql_Update class for creating a Update statement to run against a database.
 * @author vmc <vmc@leftnode.com>
 * @todo Finish implementing this!
 */
abstract class Artisan_Db_Sql_Update extends Artisan_Db_Sql {
	///< The name of the table to be updated.
	protected $_table = NULL;
	
	///< The list of fields to update.
	protected $_update_field_list = array();
	
	/**
	 * Default constructor for building a new UPDATE query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Sql_Update object.
	 */
	public function __construct() {
		$this->_sql = NULL;
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
	 * Sets up what table and fields to update.
	 * @author vmc <vmc@leftnode.com>
	 * @code
	 * // Example:
	 * $db->update->table('table_name', array('field1' => 'value1', 'field2' => 'value2'))->where('id = ?', $id)->query();
	 * @endcode
	 * @param $table The name of the table to update.
	 * @throw Artisan_Db_Sql_Exception If the table name is empty.
	 * @throw Artisan_Db_Sql_Exception If no fields are specified to be updated.
	 * @retval Object Returns an instance of itself to allow chaining.
	 */
	public function table($table, $field_list) {
		if ( true === empty($table) ) {
			throw new Artisan_Db_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL UPDATE class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$this->_table = $table;
		
		if ( false === is_array($field_list) || count($field_list) < 1 ) {
			throw new Artisan_Db_Sql_Exception(ARTISAN_WARNING, 'At least one field must be specified to be updated.', __CLASS__, __FUNCTION__);
		}
		
		$this->_update_field_list = $field_list;
		
		return $this;
	}
	
	public function build() {
		$update_sql = "UPDATE `" . $this->_table . "` ";
		
		$fl_len = count($this->_update_field_list)-1;
		$i=0;
		$field_list_sql = " SET ";
		foreach ( $this->_update_field_list as $field => $value ) {
			if ( '`' == $value[0] ) {
				$field_list_sql .= $field . " = " . $this->escape($value);
			} else {
				$field_list_sql .= $field . " = '" . $this->escape($value) . "'";
			}
			if ( $i != $fl_len ) {
				$field_list_sql .= ', ';
			}
			$i++;
		}
		
		$where_sql = $this->buildWhereClause();
		
		$this->_sql = $update_sql . $field_list_sql . $where_sql;
		
		return $this->_sql;
	}
	
	/**
	 * Executes the query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns an instance of itself for chaining.
	 */
	abstract public function query();
	
	/**
	 * Returns the number of rows inserted.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Returns the number of rows affected by the INSERT.
	 */
	abstract public function affectedRows();

	/**
	 * Escapes a string based on the character set of the current connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns a context escaped string.
	 */
	abstract public function escape($value);
}
