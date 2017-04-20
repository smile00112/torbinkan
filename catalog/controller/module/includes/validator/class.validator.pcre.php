<?php

class validator_pcre extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value != '') {
			
			$option = $this->field_info['validate'];
			
			$pattern = $option['pattern'];
			
			if (PHP_VERSION < 5.3) { 
				$pattern = preg_replace('/-/', '\-', $pattern); // PHP bug #47229
			}
			
			ob_start();
						
			if (!preg_match($pattern, $value)) {
				$validate = FALSE;
			}
			
			ob_end_clean();
			
		}
		
		return $validate;	
	}
	
}
?>