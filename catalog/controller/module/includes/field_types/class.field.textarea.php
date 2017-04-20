<?php  
class field_textarea extends field {
	
	public function render() {
		
		$output = '';
		
		if (!empty($this->field)) {
			
			$data = array();
			
			$data += parent::setGeneralData();
			
			if (isset($this->field['type']['rows']) && (int)$this->field['type']['rows']) {
				$data['rows'] = (int)$this->field['type']['rows'];
			} else {
				$data['rows'] = 2;
			}
			
			$data['placeholder'] = $this->field['type']['placeholder'];
			
			$output = $this->renderField($data);
		}
		
		return $output;
	}
	
	static public function getInstance($data) {
		return new field_textarea($data);
	}
}
?>