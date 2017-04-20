<?php  
class field_radio extends field {
	
	public function render() {
		
		$output = '';
		
		if (!empty($this->field)) {
			
			$data = array();
			
			$data += parent::setGeneralData();
			
			$data['checked'] = -1;
			
			if ($this->isPOST()) {
				if ($data['value'] !== FALSE) {
					$data['checked'] = $data['value'];
				}
			} elseif (!empty($this->field['type']['checked'])) {
				$data['checked'] = $this->field['type']['checked'];
			}
			
			if (!empty($this->field['type']['option']) && $this->field['type']['option']) {
				$data['option'] = $this->field['type']['option'];
			} else {
				return $output;
			}
			
			$output = $this->renderField($data);
		}
		
		return $output;
	}
	
	static public function getInstance($data) {
		return new field_radio($data);
	}
}
?>