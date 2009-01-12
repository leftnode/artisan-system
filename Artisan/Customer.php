<?php

/**
 * @see Artisan_User
 */
require_once 'Artisan/User.php';

require_once 'Artisan/Customer/Exception.php';

/**
 * This class allows for the management of customers as through an e-commerce
 * or other type of customer management interface.
 * @author <vmc@leftnode.com>
 */
abstract class Artisan_Customer extends Artisan_User {
	protected $_customer = NULL;
	protected $_customer_initial = NULL;
	protected $_customer_additional = NULL;

	///< Current revision number
	protected $_revision_current = 0;
	
	///< Revision number to load
	protected $_revision_load = NULL;

	///< Primary key
	protected $_customer_id = 0;





	const REV_ADDED = 'A';
	const REV_MODIFIED = 'M';
	const REV_DELETED = 'D';

	const REV_HEAD = 'head';
	
	public function __construct() {
		$this->_customer = new Artisan_Vo();
		$this->_customer_initial = new Artisan_Vo();
		$this->_customer_additional = new Artisan_Vo();
	}
	
	/**
	 * Checks to see if a value exists in the $_customer field. If not, then
	 * checks the $_customer_additional field. If not there, then returns NULL.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the variable to return from $_customer_additional.
	 * @retval string The specified value in $name or NULL if it's not found anywhere.
	 */
	public function __get($name) {
		if ( true === $this->_customer->exists($name) ) {
			return $this->_customer->$name;
		}
		if ( true === $this->_customer_additional->exists($name) ) {
			return $this->_customer_additional->$name;
		}
		return NULL;
	}
	
	public function __set($name, $value) {
		$name = trim($name);
		// If a new field is added, it should always go to the
		// $_customer_additional variable
		
	}
}