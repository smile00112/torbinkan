<?php
class ControllerModuleMattimeobanner extends Controller {
	protected function index($setting) {
		static $numMod = 0; 
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/mattimeobanner.css');
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['template'] = $this->config->get('config_template');
			
			$this->load->model('tool/image');
			
			$mod = $setting;

			
			// tab
			
			if (isset($mod['tabs'])) {
				foreach ($mod['tabs'] as &$tab) {
					
					if ($tab['image']) {
						$tab['image'] = $this->model_tool_image->resize($tab['image'], $mod['image_width'], $mod['image_height']);
					} else {
						$tab['image'] =  $this->model_tool_image->resize('no_image.jpg', $mod['image_width'], $mod['image_height']);
					}
				}
			}
			// end tab
	
			 foreach ($mod as $mod_key => $modVal){
			 $this->data[$mod_key] = $modVal;
			}
			$this->data['lang'] = $this->config->get('config_language_id');
			
			$this->data['module'] = $numMod;
	
	
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mattimeobanner.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/mattimeobanner.tpl';
			} else {
				$this->template = 'default/template/module/mattimeobanner.tpl';
			}
	
			$this->render();
		$numMod++;
	}
}
?>