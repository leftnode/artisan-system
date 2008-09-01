<?php

/**
 * The Sql_Select class for creating a Select statement to run against a database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Sql_Select extends Artisan_Sql {
	///< The actual SQL query in string form.
	protected $_sql = NULL;
	
	///< The main table the query is selecting FROM.
	protected $_from_table = NULL;
	
	///< The alias of the table the query is selecting FROM.
	protected $_from_table_alias = NULL;
	
	///< Whether or not to include a DISTINCT clause.
	protected $_distinct = false;
	
	///< The list of fields to use in the WHERE clause.
	protected $_where_field_list = array();
	
	///< Contains a list of tables to join with. No aliases are stored, they are calculated at runtime.
	protected $_join_table_list = array();
	
	///< The list of fields to use in the GROUP BY clause.
	protected $_group_field_list = array();
	
	///< Whether to sort ascending.
	protected $_asc = NULL;
	
	///< Or descending.
	protected $_desc = NULL;
	
	public function __construct() {
		$this->_sql = NULL;
	}
	
	public function __destruct() {
		unset($this->_sql, $this->_from_table, $this->_from_table_list, $this->_field_list);
	}
	
	public function from($table, $alias = NULL) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$table = trim($table);
		$this->_from_table = $table;
		$this->_from_table_alias = $alias;
		
		// Rather than having the customer supply an array, allow them
		// to supply as many parameters as they want for all of the fields.
		$fields = '*';
		$arg_length = func_num_args();
		if ( $arg_length > 2 ) {
			$args = func_get_args();
			$arg_len = count($args);
			$fields = array_splice($args, 2, $arg_len, array());
		}
		
		if ( '*' !== $fields ) {
			if ( false === is_array($fields) ) {
				$fields = array($fields);
			}
			
			$this->_field_list = asfw_create_field_list($this->_from_table, $fields, NULL);
		} else {
			$this->_field_list = array('*');
		}
		
		return $this;
	}
	
	public function distinct() {
		$this->_distinct = true;
	}
	
	
	
	
	
	
	
	
	public function where($where_fields) {
		if ( true === asfw_is_assoc($where_fields) ) {
			$this->_where_field_list = asfw_sanitize_fields($where_fields);
		}
		
		return $this;
	}
	
	
	
	public function join($join_type, $join_table, $field_a, $field_b) {
		
	}
	
	
	public function groupBy() {
		$group_fields = array();
		if ( func_num_args() > 0 ) {
			$group_fields = func_get_args();
		}
		
		if ( true === is_array($group_fields) && count($group_fields) > 0 ) {
			$this->_group_field_list = asfw_sanitize_fields($group_fields);
		}
		
		return $this;
	}
	
	
	public function orderBy() {
		$order_fields = array();
		if ( func_num_args() > 0 ) {
			$order_fields = func_get_args();
		}
		
		
	}
	
	public function asc() {
		$this->_asc = "ASC";
		$this->_desc = NULL;
		return $this;
	}
	
	public function desc() {
		$this->_desc = "DESC";
		$this->_asc = NULL;
		return $this;
	}
	
	public function __toString() {
		return $this->_sql;
	}
	
	abstract public function build();
	abstract public function query();
	abstract public function fetch($field = NULL);
	abstract public function fetchAll();
	abstract public function free();
	abstract public function escape($value);
}

?>