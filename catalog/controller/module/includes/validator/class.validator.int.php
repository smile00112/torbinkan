<?php

class validator_int extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value !== '' &&  !is_null($value)) {
			
			$option = $this->field_info['validate'];
			
			$data = array();
			
			if ($option['min'] != '') {
				$data['options']['min_range'] = $option['min'];
			}
			
			if ($option['max'] != '') {
				$data['options']['max_range'] = $option['max'];
			}
			
			if (filter_var($value, FILTER_VALIDATE_INT, $data) === FALSE) {
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