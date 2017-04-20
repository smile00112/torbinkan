<?php  
class tpl {
	
	private $template;
	private $data = array();
	
	public function __construct($template, $data = array()) {
		
		$this->template = $template;
		
		if ($data && is_array($data)) {
			$this->data = $data;
		}
		
	}
	
	public function setData($data) {
		
		if (!is_array($data)) return;
		
		$this->data = $data;
	}
	
	public function setTemplate($template) {
		
		if (!$template) return;
		
		$this->template = $template;
	}
	
	public function load($file, $options) {
		$this->template = self::find($file, $options);
	}
	
	public function render() {
		
		if (file_exists(DIR_TEMPLATE . $this->template)) {
			
			extract($this->data);
			
      		ob_start();
      
	  		require(DIR_TEMPLATE . $this->template);
      
	  		$output = ob_get_contents();

      		ob_end_clean();
      		
			return $output;
			
    	} else {
			trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
			exit();				
    	}
		
	}
	
	static public function find($file, $setting = array()) {
		
		$template = '';
		$available_directories = array();
		
		$available_directories[] = DIRECTORY_SEPARATOR;
		
		if (isset($setting['position'])) {
			$available_directories[] = (($setting['position']) ? 'content' : 'sidebar') . DIRECTORY_SEPARATOR;
		}
		
		if (isset($setting['template_id'])) {
			$available_directories[] = 'template_' . $setting['template_id'] . DIRECTORY_SEPARATOR;
		}
		
		if (isset($setting['layout_id'])) {
			$available_directories[] = 'layout_' . $setting['layout_id'] . DIRECTORY_SEPARATOR;
		}
		
		foreach (array(oc::registry()->config->get('config_template'), 'default') as $theme) {
			
			$directory = $theme . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR .  'quick_order_pro';
			
			$iterations = $available_directories;
			
			while (!empty($iterations)) {
				
				$available_directory = implode('', $iterations);
				
				if (file_exists(DIR_TEMPLATE . $directory . $available_directory . $file . '.tpl')) {
					$template = $directory . $available_directory . $file . '.tpl';
				}
				
				if ($template) break 2;
				
				array_pop($iterations);
			}
			
		}
		
		return $template;
	}
	
	static public function getInstance($template, $data = array()) {
		return new tpl($template, $data);
	}
}
?>