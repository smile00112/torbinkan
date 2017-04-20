<?php

class validator_number_of_words extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value != '') {
			
			$option = $this->field_info['validate'];
			
			$count = $this->getCount($value);
			
			if ($option['min'] != '' && $count < $option['min']) {
				$validate = FALSE;
			}
			
			if ($option['max'] != '' && $count > $option['max']) {
				$validate = FALSE;
			}
		}
		
		return $validate;	
	}
	
	private function getCount($text) {
		
		$count = count(explode(' ', trim(preg_replace('/\s+/', ' ', str_replace('&nbsp;', ' ', (strip_tags(str_replace('<', ' <', $text))))))));
		
		return $count;
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