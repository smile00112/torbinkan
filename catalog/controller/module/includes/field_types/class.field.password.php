<?php  
class field_password extends field {
	
	public function render() {
		
		$output = '';
		
		if (!empty($this->field)) {
			
			$data = parent::setGeneralData();
			
			$data['maxlength'] = 128;
			
			$output = $this->renderField($data);
		}
		
		return $output;
	}
	
	static public function getInstance($data) {
		return new field_password($data);
	}
}
?>