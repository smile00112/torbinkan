<?php  
class field_text extends field {
	
	public function render() {
		
		$output = '';
		
		if (!empty($this->field)) {
			
			$data = array();
			
			$data += parent::setGeneralData();
			
			if (isset($this->field['type']['maxlength']) && $this->field['type']['maxlength']) {
				$data['maxlength'] = $this->field['type']['maxlength'];
			} else {
				$data['maxlength'] = 32;
			}
			
			if ($this->field['code'] == 'captcha') {
				
				$setting = oc::registry()->config->get('quick_order_pro_setting');
				
				$data['maxlength'] = $setting['captcha_count_items'];
				$data['text_captcha_reload'] = oc::registry()->load->language->get('text_captcha_reload');
			}
			
			$data['mask'] = $data['placeholder'] = '';
			
			if (!empty($this->field['type']['use_mask']) && $this->field['type']['use_mask'] && !empty($this->field['type']['mask'])) {
				$data['mask'] = $this->field['type']['mask'];
				oc::registry()->document->addScript('catalog/view/javascript/quick_order_pro/jquery.maskedinput.min.js');
			}
			
			if (!empty($this->field['type']['placeholder'])) {
				$data['placeholder'] = $this->field['type']['placeholder'];
			}
			
			$output = $this->renderField($data);
		}
		
		return $output;
	}
	
	static public function getInstance($data) {
		return new field_text($data);
	}
}
?>