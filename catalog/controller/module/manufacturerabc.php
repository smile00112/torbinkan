<?php  
class ControllerModuleManufacturerabc extends Controller {
	protected function index($setting) {

		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/brandabc.css');
		
				
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
				
		$this->data['manufactureres'] = array();
					
		$results = $this->model_catalog_manufacturer->getManufacturers(0);
		foreach($results as $result)
		{
			$this->data['manufactureres'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'        => $result['name'] ,
				'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
			
		
				
			);
		}
	



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturerabc.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/manufacturerabc.tpl';
		} else {
			$this->template = 'default/template/module/manufacturerabc.tpl';
		}
		
		$this->render();
  	}
}
?>