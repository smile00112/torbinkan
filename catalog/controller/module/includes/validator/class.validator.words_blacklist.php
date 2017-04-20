<?php

class validator_words_blacklist extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value != '') {
			
			$option = $this->field_info['validate'];
			
			$blacklist = explode(',', $option['list']);
    		$blacklist = array_map('trim', $blacklist);
			
			if (preg_match("/(?:" . implode('|', $blacklist) . ")/isu", $value)) {
				$validate = FALSE;
			}
			
		}
		
		return $validate;	
	}
	
}
?>