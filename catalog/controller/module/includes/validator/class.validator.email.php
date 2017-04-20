<?php

class validator_email extends validator {
	
	private $tokens;
	
	protected function __construct($field_info) {
		
		parent::__construct($field_info);
		
		$this->tokens = parent::getTokens();
		
	}
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value != '') {
			
			$option = $this->field_info['validate'];
			
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$validate = false;
			} elseif ($option['check_domain']) {
				
				$domain = rtrim(substr($value, strpos($value,'@')+1), '>');
				
				if (function_exists('checkdnsrr')) {
					$validate = checkdnsrr($domain, 'MX');
				} elseif (function_exists('getmxrr')) {
					$validate = getmxrr($domain);
				}
				
				if (!$validate) {
					
					$this->tokens += array(
						'{field_domain}' => $domain
					);
					
					$this->error_message = $option['error_message_domain'];
				}
				
			}
			
		}
		
		return $validate;	
	}
	
	private function getLength($text) {
		
		if (function_exists('mb_strlen')) {
			$length = mb_strlen($text);
		} else {
			$length = strlen(preg_replace("/[\x80-\xBF]/", '', $text));
		}
		
		return $length;
	}
	
	protected function getTokens() {
		return $this->tokens;
	}
}
?>