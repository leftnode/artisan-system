<?php

/**
 * @see Artisan_Log_Exception
 */
require_once 'Artisan/Log/Exception.php';

require_once 'Artisan/Functions/Array.php';

require_once 'Artisan/Functions/Input.php';

require_once 'Artisan/Functions/Database.php';

define('LOG_GENERAL', 100, false);
define('LOG_ERROR', 200, false);
define('LOG_SUCCESS', 300, false);
define('LOG_EXCEPTION', 400, false);

/**
 * Class for logging data into a specified place. This class exists as a singleton.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Log {
	///< Because this class is a singleton, the instance of this class.
	private static $_inst = NULL;

	///< Instance of the writer class.
	private $_writer = NULL;
	
	///< The array of log data to flush out.
	private $_log = array();
	
	///< The list of data to flush out, default is everything.
	private $_flush_level_list = array(100, 200, 300, 400);

	/**
	 * Private constructor because this class is a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	private function __construct() { }
	
	/**
	 * Private clone method because this class is a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	private function __clone() { }
	
	/**
	 * Public destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function __destruct() { }
	
	/**
	 * Returns this class for usage as a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the itself.
	 */
	public static function &get() {
		if ( true === is_null(self::$_inst) ) {
			self::$_inst = new self;
		}
		return self::$_inst;
	}
	
	/**
	 * Adds an item onto the log class.
	 * @author vmc <vmc@leftnode.com>
	 * @param $log_type The type of log to create, as defined by the constants above.
	 * @param $entry The text of the log.
	 * @param $class The class to log against.
	 * @param $function The method to log against.
	 * @param $trace If the log entry is an exception, this will contain the trace.
	 * @retval boolean Return true.
	 */
	public function add($log_type, $entry, $include_trace=false) {
		$ip_address = asfw_get_ipv4();

		$trace = debug_backtrace(false);
		
		$t = asfw_exists_return(1, $trace);
		$class = asfw_exists_return('class', $t);
		$function = asfw_exists_return('function', $t);

		$trace = print_r($trace, true);
		if ( false === $include_trace && $log_type != LOG_ERROR ) {
			$trace = NULL;
		}

		$log = array(
			'code_id' => NULL,
			'log_date' => asfw_now(),
			'entry' => $entry,
			'trace' => $trace,
			'class' => $class,
			'function' => $function,
			'ip_address' => $ip_address,
			'type' => $log_type
		);
		
		$this->_writer->flush($log);
		
		return true;
	}

	/**
	 * Adds an exception to the log class.
	 * @author vmc <vmc@leftnode.com>
	 * @param $E The exception to push onto the log class.
	 * @retval boolean Return true.
	 */
	public function addException(Artisan_Exception &$E) {
		$this->add(
			LOG_EXCEPTION,
			$E->toString(),
			$E->getClassName(),
			$E->getFunctionName(),
			$E->getTraceAsString()
		);
		return true;
	}
	
	/**
	 * Sets the different flush levels. See $_flush_level_list for default values.
	 * @author vmc <vmc@leftnode.com>
	 * @param $flush_level_list An array of flush levels.
	 * @retval boolean Returns true.
	 */
	public function setFlushLevels($flush_level_list) {
		if ( false === is_array($flush_level_list) ) {
			return false;
		}
		$this->_flush_level_list = $flush_level_list;
		return true;
	}

	/**
	 * Sets a writer class to allow flushing the log data to a specific location.
	 * @author vmc <vmc@leftnode.com>
	 * @param $W The Writer instance of type Artisan_Log_Writer.
	 * @retval boolean Returns true.
	 */
	public function setWriter(Artisan_Log_Writer $W) {
		$this->_writer = $W;
		return $this;
	}

	/**
	 * Flushes out the log to a specific location.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean True if the log was successfully flushed, false if the writer is not set up properly.
	 */
	/*public function flush() {
		$final_log = array();
		
		if ( true === is_null($this->_writer) || false === $this->_writer instanceof Artisan_Log_Writer ) {
			return false;
		}
		
		// Swap the values of $fll to keys for quick lookups
		$fll = asfw_make_values_keys($this->_flush_level_list);
		foreach ( $this->_log as $log ) {
			if ( true === asfw_exists($log['type'], $fll) ) {
				$final_log[] = $log;
			}
		}
		
		$this->_writer->flush($final_log);
		$this->_log = array();
		return true;
	}*/

	/**
	 * Writes the first element of the log array only and resets the array.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	/*public function write() {
		$log = array();
		@reset($this->_log);
		$log[] = current($this->_log);
		$this->_log = $log;
		$this->flush();
		return true;
	}*/
}