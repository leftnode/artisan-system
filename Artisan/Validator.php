<?php

require_once 'Library.php';
require_once 'Validate/Email.php';

abstract class Artisan_Validator {
	protected $_model = array();
	protected $_model_data = array();
	protected $_model_name = NULL;
	protected $_error_list = array();
	
	public function __construct() {
		$this->_model = array();
	}
	
	public function __destruct() {
		unset($this->_model, $this->_model_data, $this->_model_name, $this->_error_list);
	}
	
	public function load($model_name) {
		$this->init($model_name);
		return $this;
	}
	
	public function validate() {
		$model = er($this->_model_name, $this->_model, array());
		$len_model = count($model);
		$len_data = count($this->_model_data);
		
		if ( 0 == $len_model || 0 == $len_data ) {
			return false;
		}
		
		$this->_error_list = array();
		
		// First ensure the keys of the model are equal to the keys of the data.
		// This prevents fake fields from being added through Firebug.
		// This also means all keys must be defined in the list, even if they aren't
		// being validated.
		$model_keys = array_keys($model);
		$data_keys = array_keys($this->_model_data);
		
		$bad_ley_list = array();
		$bad_key = false;
		foreach ( $data_keys as $k ) {
			if ( false === isset($model[$k]) ) {
				$bad_key = true;
				$bad_key_list[] = $k;
			}
		}
		
		if ( true == $bad_key ) {
			throw new Artisan_Exception('The following fiels were absent: ' . implode(', ', $bad_key_list));
		}
		
		$success = true;
		foreach ( $model as $key => $list ) {
			$data_value = er($key, $this->_model_data);
			
			$label = er('label', $list);
			$rule_list = er('rule_list', $list, array());
			
			$error = NULL;
			
			if ( count($rule_list) > 0 ) {
				foreach ( $rule_list as $type => $rule_value ) {
					switch ( $type ) {
						case 'min_length': {
							$min_length = intval($rule_value);
							if ( strlen($data_value) < $min_length ) {
								$error = sprintf('The field <strong>%s</strong> must have a length greater than or equal to <strong>%d</strong> characters.', $label, $min_length);
							}
							break;
						}
						
						case 'max_length': {
							$max_length = intval($rule_value);
							if ( strlen($data_value) > $max_length ) {
								$error = sprintf('The field <strong>%s</strong> must have a length less than or equal to <strong>%d</strong> characters.', $label, $max_length);
							}
							break;
						}
						
						case 'not_empty': {
							if ( true === empty($data_value) ) {
								$error = sprintf('The field <strong>%s</strong> cannot be empty.', $label);
							}
							break;
						}
						
						case 'not_empty_dropdown': {
							if ( true === empty($data_value) ) {
								$error = sprintf('Please select a value from the <strong>%s</strong> dropdown list.', $label);
							}
							break;
						}
						
						case 'not_zero': {
							$data_value = intval($data_value);
							if ( 0 == $data_value ) {
								$error = sprintf('The field <strong>%s</strong> can not have a value of 0', $label);
							}
							break;
						}
						
						case 'numeric': {
							if ( false === is_numeric($data_value) ) {
								$error = sprintf('The field <strong>%s</strong> must have a numeric value.', $label);
							}
							break;
						}
						
						case 'email': {
							$emailValidator = new Artisan_Validate_Email($data_value);
							if ( false === $emailValidator->isValid() ) {
								$error = sprintf('The field <strong>%s</strong> is not a valid e-mail address.', $label);
							}
							break;
						}
						
						case 'in_array': {
							if ( false === is_array($rule_value) ) {
								$rule_value = array($rule_value);
							}
							
							if ( false === in_array($data_value, $rule_value) ) {
								$error = sprintf('The field <strong>%s</strong> cannot be empty.', $label);
							}
						}
					}
					
					if ( false === empty($error) ) {
						$success = false;
						$this->_error_list[$key] = $error;
						break;
					}
				}
			}
		}
		
		if ( false === $success ) {
			throw new Artisan_Exception('The data failed to validate.');
		}
		
		return true;
	}
	
	public function setData(array $model) {
		$this->_model_data = $model;
		return $this;
	}
	
	public function getModel() {
		return $this->_model;
	}
	
	public function getErrorList() {
		return $this->_error_list;
	}
	
	abstract public function init($model);
}