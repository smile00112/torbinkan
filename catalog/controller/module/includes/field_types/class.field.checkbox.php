<?php  
class field_checkbox extends field {
	
	protected $field;
	protected $form_id;
	protected $form_info;

	public function render() {
		
		$output = '';
		
		if (!empty($this->field)) {
			
			$data = array();
			
			$data += parent::setGeneralData();
			
			$data['checked'] = array();
			
			if ($this->isPOST()) {
				if ($data['value']) {
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
		return new field_checkbox($data);
	}
}
?>