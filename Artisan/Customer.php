<?php

/**
 * @see Artisan_User
 */
require_once 'Artisan/User.php';

/**
 * This class allows for the management of customers as through an e-commerce
 * or other type of customer management interface.
 * @author <vmc@leftnode.com>
 */
abstract class Artisan_Customer extends Artisan_User {
	//protected $_customer_id = 0;
	//protected $_customer = NULL;
	
	public function __construct() {
		parent::__construct();
	}
	
	
	
	
	/*
	public function write() { }
	
	protected function _insert();
	protected function _update();
	protected function _load($customer_id);
	*/
}