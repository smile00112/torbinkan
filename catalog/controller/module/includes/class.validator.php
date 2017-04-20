<?php

abstract class validator {
	
	static protected $has_init = FALSE;
	protected $field_info;
	protected $error_message;
	
	protected function __construct($field_info) {
		$this->field_info = $field_info;
		$this->error_message = $field_info['validate']['error_message'];
	}
	
	abstract public function validate($value);
	
	public function getErrorMessage() {
		return strtr($this->error_message, $this->getTokens());
	}
	
	static public function getInstance($field_info) {
		
		$validate_info = $field_info['validate'];
		
		self::init();
		
		if (!empty($validate_info['type'])) {
			
			$action = self::getAction($validate_info['type']);
			
			if (!empty($action['class'])) {
				return new $action['class']($field_info);
			}
			
		}
		
		return false;		
	}
	
	protected function getTokens() {
		return array(
			'{field_name}' => $this->field_info['title'][oc::registry()->config->get('config_language_id')]
		);
	}
	
	static protected function init() {
		
		if (!self::$has_init) {
			
			spl_autoload_register(array('validator', 'autoloader'), FALSE, TRUE);
			
			self::$has_init = TRUE;
		}
		
	}
	
	static private function getAction($type) {
		
		$data = array();
		
		if (self::checkValidator($type)) {
			
			$data = array(
				'class'	=> 'validator_' . $type
			);
			
		}
		
		return $data;
	}
	
	static private function checkValidator($type) {
		
		$validate = TRUE;
		
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'validator' . DIRECTORY_SEPARATOR . 'class.validator.' . $type . '.php';
		
		if (!file_exists($file) || !is_file($file)) {
			$validate = FALSE;
		}
		
		return $validate;
	}
	
	static public function autoloader($name) {
		
		if (class_exists($name)) return;
		
		$name = str_replace('validator_', '', $name);
		
		$file_name = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'validator' . DIRECTORY_SEPARATOR . 'class.validator.' . str_replace(array(' ', '-'), '_', strtolower($name)) . '.php';
		
		if (file_exists($file_name)) {
			require_once($file_name);
		}
	}
}
?>