<?php

class validator_length extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value != '') {
			
			$option = $this->field_info['validate'];
			
			$length = $this->getLength($value);
			
			if ($option['min'] != '' && $length < $option['min']) {
				$validate = FALSE;
			}
			
			if ($option['max'] != '' && $length > $option['max']) {
				$validate = FALSE;
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
		
		$option = $this->field_info['validate'];
		
		$tokens = array(
			'{field_min}' => $option['min'],
			'{field_max}' => $option['max']
		);
		
		return parent::getTokens() + $tokens;
	}
}
?>