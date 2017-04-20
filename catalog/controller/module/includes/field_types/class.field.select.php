<?php  
class field_select extends field {
	
	public function render() {
		
		$output = '';
		
		if (!empty($this->field)) {
			
			$data = array();
			
			$data += parent::setGeneralData();
			
			$data['text_select'] = oc::registry()->load->language->get('text_select');
			
			$data['multiple'] = $this->field['type']['multiple'];
			
			if ($data['multiple']) {
				$data['class'] .= ($data['class'] ? ' ' : '') . 'multiple';
			}
			
			$data['selected'] = array();
			
			if ($data['value']) {
				
				if (is_array($data['value'])) {
					$data['selected'] = $data['value'];
				} else {
					$data['selected'][] = $data['value'];
				}
				
			} elseif (!empty($this->field['type']['selected'])) {
				$data['selected'][] = $this->field['type']['selected'];
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
		return new field_select($data);
	}
}
?>