<?php

/**
 * @see Artisan_Exception
 */
require_once 'Artisan/Exception.php';

/**
 * Database Exception Class.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db_Exception extends Artisan_Exception {
	function __construct($error_message) {
		parent::__construct(ARTISAN_ERROR, $error_message);
	}
}