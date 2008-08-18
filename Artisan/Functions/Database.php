<?php

/**
 * This file holds methods for easily database manipulation and construction.
 * @author vmc <vmc@leftnode.com>
 */


/**
 * Turn a database field from `field_name` to `table_alias`.`field_name`.
 * @author vmc <vmc@leftnode.com>
 * @param $field The field to create a proper name for.
 * @param $table_alias The alias of the table the field is a member of.
 */
function artisan_create_field_alias($field, $table_alias) {
	return ( false === empty($table_alias) ? $table_alias . '.' : NULL ) . str_replace('`', NULL, $field);
}

function artisan_create_field_list($table, $fields, $table_alias = NULL) {
	$field_list = array();
	
	if ( true === is_array($fields) ) {
		$field_list = array_map("artisan_create_field_alias", $fields, array_fill(0, count($fields), $table_alias));
	}
	
	return $field_list;
}

/**
 * Returns an alias for a database table, for example, `my_customer_list` would
 * return as `mcl`. Takes first letter of each word separated by a space or underscore.
 * @author vmc <vmc@leftnode.com>
 * @param $table The name of the table to create an alias for.
 * @retval string The table alias name.
 */
function artisan_create_table_alias($table) {
	$alias = NULL;
	$table = trim($table);
	$table = str_replace('_', ' ', $table);
	
	$words = explode(' ', $table);
	foreach ( $words as $word ) {
		$word = trim($word);
		if ( false === empty($word) ) {
			$alias .= $word[0];
		}
	}
	
	return $alias;
}

?>