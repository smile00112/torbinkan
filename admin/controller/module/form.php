<?php
class ControllerModuleForm extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/form');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('form', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_banner'] = $this->language->get('entry_banner');
		$this->data['entry_button'] = $this->language->get('entry_button'); 
        $this->data['entry_modal'] = $this->language->get('entry_modal'); 
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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
			'href'      => $this->url->link('module/form', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/form', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['form_module'])) {
			$this->data['modules'] = $this->request->post['form_module'];
		} elseif ($this->config->get('form_module')) { 
			$this->data['modules'] = $this->config->get('form_module');
		}	
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('catalog/form');
		
		$this->data['forms'] = $this->model_catalog_form->getForms();
				
		$this->template = 'module/form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
    
    public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "form (
                      `form_id` int(11) NOT NULL AUTO_INCREMENT,
                      `prefix` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                      `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                      `file` int(1) DEFAULT '0',
                      `status` int(1) DEFAULT '0',
                      `use_type` int(1) DEFAULT '0',
                      `databaseon` int(1) DEFAULT '0',
                      `newsletteron` int(1) DEFAULT '0',
                      `useron` int(1) DEFAULT '0',
                      PRIMARY KEY (`form_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "form_description (
                      `form_id` int(11) NOT NULL,
                      `language_id` int(11) NOT NULL,
                      `name` varchar(255) COLLATE utf8_bin NOT NULL,
                      `button` varchar(255) COLLATE utf8_bin DEFAULT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "form_item (
                      `item_id` int(11) NOT NULL AUTO_INCREMENT,
                      `form_id` int(10) DEFAULT NULL,
                      `item_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                      `sort_order` int(10) DEFAULT NULL,
                      `required` int(1) DEFAULT '0',
                      `validation` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                      `status` int(1) DEFAULT '0',
                      `setfrom` int(1) DEFAULT '0',
                      `setsender` int(1) DEFAULT '0',
                      `letter` int(1) DEFAULT '0',
                      PRIMARY KEY (`item_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "form_item_description (
                      `item_id` int(11) DEFAULT NULL,
                      `language_id` int(11) DEFAULT NULL,
                      `label` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                      `pattern` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                      `value` text COLLATE utf8_bin
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "form_data (
                      `form_data_id` int(11) NOT NULL AUTO_INCREMENT,
                      `form_id` int(11) DEFAULT NULL,
                      `form_data` text COLLATE utf8_bin,
                      `date_added` datetime DEFAULT NULL,
                      `not_view` int(1) DEFAULT '0',
                      PRIMARY KEY (`form_data_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;"); 
	}
	public function uninstall(){
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "form;");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "form_description;");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "form_item;");
        $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "form_item_description;");
        $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "form_data;");
	}
    
    
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/form')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>