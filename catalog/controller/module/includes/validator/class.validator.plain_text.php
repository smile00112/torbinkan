<?php

class validator_plain_text extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value != '' && (strcmp($value, strip_tags(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))))) {
			$validate = FALSE;
		}
		
		return $validate;	
	}
	
}
?>