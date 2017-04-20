<?php

class validator_specific_value extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value != '') {
			
			$option = $this->field_info['validate'];
			
			$specific_values = explode(',', $option['list']);
    		$specific_values = array_map('trim', $specific_values);
			
			if (!in_array(trim($value), $specific_values)) {
				$validate = FALSE;
			}
			
		}
		
		return $validate;	
	}
	
}
?>