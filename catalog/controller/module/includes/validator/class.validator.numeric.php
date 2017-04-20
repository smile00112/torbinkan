<?php

class validator_numeric extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value !== '' &&  !is_null($value)) {
			
			$option = $this->field_info['validate'];
			
			if (!is_numeric($value)) {
				$validate = FALSE;
			} elseif ($option['min'] != '' && $value < $option['min']) {
				$validate = FALSE;
			} elseif ($option['max'] != '' && $value > $option['max']) {
				$validate = FALSE;
			}
		}
		
		return $validate;	
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