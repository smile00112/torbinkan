<?php  
final class message {
	
	static private $_skey = 'quick_order_pro_messages';
	
	public $data = array();
	
	static public function getState($key, $form_id, $type = 'error', $wrapper = FALSE) {
		
		$message = NULL;
		
		if (!empty($_SESSION[self::$_skey][$form_id][$type][$key])) {
			
			$message = $_SESSION[self::$_skey][$form_id][$type][$key];
			
			if ($wrapper && $html = self::renderItem($message, $form_id, $type)) {
				$message = $html;
			}
			
			unset($_SESSION[self::$_skey][$form_id][$type][$key]);
		}
		
		return $message;
	}
	
	static public function renderItem($message, $form_id, $type = 'error') {
		
		if (!$message) return '';
		
		if (is_array($message)) {
			
			foreach ($message as $key => &$item) {
				$item = self::renderItem($item, $form_id, $type);
			}
			
			return $message;
			
		} else {
		
			$html = FALSE;
			
			if ($message && $options = id::decode($form_id)) {
				
				$template = new tpl(tpl::find($type . '-message', $options), array('message' => $message));
			
				$html = $template->render();
			}
			
			return $html;
			
		}
	}
	
	static public function setState($key, $message, $form_id, $type = 'error') {
		
		if (!isset($_SESSION[self::$_skey][$form_id][$type])) {
			$_SESSION[self::$_skey][$form_id][$type] = array();
		}
		
		if (!array_key_exists($key, $_SESSION[self::$_skey][$form_id][$type])) {
			$_SESSION[self::$_skey][$form_id][$type][$key] = $message;
		}
		
	}
	
}
?>