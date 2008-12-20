<?php

require_once 'Artisan/Functions/Database.php';

/**
 * The abstract Database class from which other database classes are extended.
 * Because this class is abstract and contains many abstract members, it is necessary
 * to extend it to use it. For example, if you want to connect to a MySQL database
 * using mysqli, you would use new Artisan_Database_Mysqli($config) and go from there.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Db {
	///< Holds the database configuration information, must be of type Artisan_Config.
	protected $CONFIG = NULL;

	///< Whether or not a current connection exists.
	protected $_is_connected = false;
	
	///< Whether or not a transaction has been started.
	private $_transaction_started = false;
	
	///< The instance of the Artisan_Sql_Select_* class for executing SELECT queries.
	protected $_select = NULL;
	
	protected $_update = NULL;
	
	protected $_insert = NULL;
	
	protected $_delete = NULL;
	
	/**
	 * Default constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C An Artisan_Config configuration instance, optional.
	 * @retval object New database instance, ready for connection.
	 */
	public function __construct(Artisan_Config &$C = NULL) {
		if ( true === is_object($C) ) {
			$this->setConfig($C);
		}
	}

	/**
	 * Default destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Destroys configuration and returns true.
	 */
	public function __destruct() {
		unset($this->CONFIG);
	}

	/**
	 * Returns the name of this class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the name of the class.
	 */
	public function name() {
		return __CLASS__;
	}

	/**
	 * Sets the configuration if not set through the constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C A configuration object.
	 * @retval boolean Returns true.
	 */
	public function setConfig(Artisan_Config &$C) {
		$this->CONFIG = $C;
		return true;
	}

	/**
	 * Returns the configuration object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the configuration object.
	 */
	public function &getConfig() {
		return $this->CONFIG;
	}

	/**
	 * Whether or not the database currently has a connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if the database has a connection, false otherwise.
	 */
	public function isConnected() {
		return $this->_is_connected;
	}
	
	/**
	 * Alias for disconnect();
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true upon successful disconnection, false otherwise.
	 */
	public function close() {
		return $this->disconnect();
	}
	
	
	/**
	 * Connects to the specified database.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Database_Exception If the connection fails.
	 * @retval object New connection to the database
	 */
	abstract public function connect();

	/**
	 * Disconnects from the specified database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true upon successful disconnection, false otherwise.
	 */
	abstract public function disconnect();

	abstract public function query($sql);
	
	abstract public function select();
	abstract public function insert();
	abstract public function update();
	abstract public function delete();
	
	abstract public function start();
	abstract public function commit();
	abstract public function rollback();
	
	abstract public function insertId();
	abstract public function affectedRows();
}