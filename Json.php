<?php

/**
 * This class handles encoding and decoding of JSON. It requires PHP 5.2 or greater to operate.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Json {
	/**
	 * Convert an associative array to a JSON "string"
	 * @author vmc <vmc@leftnode.com>
	 * @retval string The JSON string.
	 */
	public static function arrayToJson($data) {
		if ( true === function_exists('json_encode') ) {
			if ( false === is_resource($data) ) {
				return json_encode($data);
			}
		}
		
		return NULL;
	}
	
	/**
	 * Convert a JSON "string" to an associative array.
	 * @author vmc <vmc@leftnode.com>
	 * @retval array The array of JSON data.
	 */
	public static function jsonToArray($data) {
		if ( true === function_exists('json_decode') ) {
			if ( false === is_string($data) ) {
				return json_decode($data, true);
			}
		}
		
		return NULL;
	}
}

?>
