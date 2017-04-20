<?php

class validator_captcha extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		$form_id = oc::registry()->message->data['form_id'];
		
		if (!$form_id || !isset(oc::registry()->session->data['quick_order_pro_captcha'][$form_id]) || (oc::registry()->session->data['quick_order_pro_captcha'][$form_id] != $value)) {
			$validate = FALSE;
		}
		
		return $validate;
	}
	
}
?>