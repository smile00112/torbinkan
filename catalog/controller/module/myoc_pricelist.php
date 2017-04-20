<?php
class ControllerModuleMyocPricelist extends Controller {
	protected function index($setting = array()) {
		$this->language->load('myoc/pricelist');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$myocwpl_data = $this->config->get('myocwpl_data');
    	$this->data['pricelists'] = array();
    	if($myocwpl_data) {
    		foreach ($myocwpl_data as $pricelist) {
	    		if($pricelist['status'] && isset($setting['pricelist']) && in_array($pricelist['pricelist_id'], $setting['pricelist']) && isset($pricelist['store']) && in_array($this->config->get('config_store_id'), $pricelist['store']) && (!$pricelist['login'] || $this->customer->isLogged() && isset($pricelist['customer_group']) && in_array($this->customer->getCustomerGroupId(), $pricelist['customer_group']))) {
		    		$this->data['pricelists'][] = array(
		    			'url' => $this->url->link('product/pricelist', 'pricelist_id=' . $pricelist['pricelist_id'], 'SSL'),
		    			'name' => $pricelist['name'][$this->config->get('config_language_id')],
		    		);
				}
	    	}
	    }
	    if(empty($this->data['pricelists'])) {
	    	return false;
	    }

    	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/myoc_pricelist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/myoc_pricelist.tpl';
		} else {
			$this->template = 'default/template/module/myoc_pricelist.tpl';
		}
		
		$this->render();
	}
}
?>