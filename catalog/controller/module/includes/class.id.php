<?php  
abstract class id {
	
	static public $separator = ':';
	
	static public function encode($str) {
		return base64_encode($str);
	}
	
	static public function decode($key = '') {
		
		$key_info = array();
		
		ob_start();
		$data = base64_decode($key);
		ob_end_clean();
		
		if ($data && strpos($data, self::$separator) !== FALSE) {
			
			$parts = explode(self::$separator, $data);
			
			$key_info = array(
				'template_id'	=> $parts[0],
				'type'			=> $parts[1], // 0 — other, 1 — product
				'position'		=> $parts[2], // 1 — sidebar, 0 — content
				'item_id'		=> $parts[3],
				'layout_id'		=> $parts[4]
			);
			
		}
		
		return $key_info ? $key_info : FALSE;
	}
	
}
?>