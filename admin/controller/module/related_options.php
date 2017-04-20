<?php
class ControllerModuleRelatedOptions extends Controller {
	private $error = array();
	
  public function index()
  {
    
    $this->load->language('module/related_options');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
    $this->load->model('module/related_options');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      
      if (isset($this->request->post['variants'])) {
        $this->model_module_related_options->set_variants_options($this->request->post['variants']);
        unset($this->request->post['variants']);
      } else {
				$this->model_module_related_options->set_variants_options(array());
			}
      
			$this->model_setting_setting->editSetting('related_options', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/related_options', 'token=' . $this->session->data['token'], 'SSL'));
      
		}
    
    //$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'));
    
    $this->data['entry_update_quantity']      		= $this->language->get('entry_update_quantity');
    $this->data['entry_update_quantity_help'] 		= $this->language->get('entry_update_quantity_help');
		$this->data['entry_stock_control']      			= $this->language->get('entry_stock_control');
    $this->data['entry_stock_control_help'] 			= $this->language->get('entry_stock_control_help');
    $this->data['entry_update_options']       		= $this->language->get('entry_update_options');
    $this->data['entry_update_options_help']  		= $this->language->get('entry_update_options_help');
		$this->data['entry_hide_inaccessible']    		= $this->language->get('entry_hide_inaccessible');
    $this->data['entry_hide_inaccessible_help'] 	= $this->language->get('entry_hide_inaccessible_help');
		$this->data['entry_spec_model']    						= $this->language->get('entry_spec_model');
    $this->data['entry_spec_model_help'] 					= $this->language->get('entry_spec_model_help');
		$this->data['entry_spec_price']    						= $this->language->get('entry_spec_price');
    $this->data['entry_spec_price_help'] 					= $this->language->get('entry_spec_price_help');
		$this->data['entry_spec_price_discount']			= $this->language->get('entry_spec_price_discount');
    $this->data['entry_spec_price_discount_help'] = $this->language->get('entry_spec_price_discount_help');
		$this->data['entry_select_first'] 						= $this->language->get('entry_select_first');
		$this->data['entry_select_first_help'] 				= $this->language->get('entry_select_first_help');
		$this->data['entry_step_by_step'] 						= $this->language->get('entry_step_by_step');
		$this->data['entry_step_by_step_help'] 				= $this->language->get('entry_step_by_step_help');
		$this->data['entry_allow_zero_select']				= $this->language->get('entry_allow_zero_select');
		$this->data['entry_allow_zero_select_help']		= $this->language->get('entry_allow_zero_select_help');
    
    if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
    
    if (isset($this->session->data['success'])) {
      $this->data['success'] = $this->session->data['success'];
      unset($this->session->data['success']);
    } 
		
		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = array();
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/related_options', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/related_options', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

    
		$this->data['modules'] = array();
    if (isset($this->request->post['related_options'])) {
			$this->data['modules'] = $this->request->post['related_options'];
		} elseif ($this->config->get('related_options')) {
			$this->data['modules'] = $this->config->get('related_options');
		}
		
		$current_version = $this->model_module_related_options->current_version();
		if ( !isset($this->data['modules']['related_options_version']) || $this->data['modules']['related_options_version'] < $current_version
				|| "".$this->data['modules']['related_options_version'] == ("".$current_version."b")) {
			$this->model_module_related_options->install_additional_tables();
			$this->data['modules']['related_options_version'] = $current_version;
			$this->model_setting_setting->editSetting('related_options', array('related_options' => $this->data['modules']) );
			$this->data['updated'] = $this->language->get('text_ro_updated_to')." ".($current_version);
		} 
    
    
    $this->data['heading_title']                  = $this->language->get('heading_title');
    $this->data['button_save']                    = $this->language->get('button_save');
		$this->data['button_cancel']                  = $this->language->get('button_cancel');
		$this->data['entry_ro_version']               = $this->language->get('entry_ro_version');
		$this->data['text_ro_support']                = $this->language->get('text_ro_support');
    $this->data['entry_ro_use_variants']          = $this->language->get('entry_ro_use_variants');
    $this->data['entry_ro_add_variant']           = $this->language->get('entry_ro_add_variant');
    $this->data['entry_ro_delete_variant']        = $this->language->get('entry_ro_delete_variant');
    $this->data['entry_ro_add_option']            = $this->language->get('entry_ro_add_option');
    $this->data['entry_ro_delete_option']         = $this->language->get('entry_ro_delete_option');
    $this->data['text_ro_clear_options']          = $this->language->get('text_ro_clear_options');
    $this->data['entry_ro_variant_name']          = $this->language->get('entry_ro_variant_name');
    $this->data['entry_ro_options']               = $this->language->get('entry_ro_options');
		$this->data['entry_show_clear_options']       = $this->language->get('entry_show_clear_options');
    $this->data['entry_show_clear_options_help']  = $this->language->get('entry_show_clear_options_help');
		$this->data['option_show_clear_options_not']  = $this->language->get('option_show_clear_options_not');
		$this->data['option_show_clear_options_top']  = $this->language->get('option_show_clear_options_top');
		$this->data['option_show_clear_options_bot']  = $this->language->get('option_show_clear_options_bot');
    
    $this->data['options'] = $this->model_module_related_options->get_compatible_options();
    $this->data['variants_options'] = $this->model_module_related_options->get_variants_options();
    
    
    $this->template = 'module/related_options.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
    
  }
  
  public function install()
  {
    $this->load->model('module/related_options');
    $this->model_module_related_options->install();
		
		$this->load->model('setting/setting');
		$msettings = array('related_options'=>array('update_quantity'=>1,'update_options'=>1,'related_options_version'=>$this->model_module_related_options->current_version()));
		$this->model_setting_setting->editSetting('related_options', $msettings);
		
		
  }
  
  public function uninstall()
  {
    $this->load->model('module/related_options');
    $this->model_module_related_options->uninstall();
  }
  
  private function validate() {
    if (!$this->user->hasPermission('modify', 'module/related_options')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }
    
    if (!$this->error) {
      return true;
    } else {
      return false;
    }	
  }
  
}