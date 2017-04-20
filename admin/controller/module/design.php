<?php

class ControllerModuleDesign extends Controller
{


    public function index()
    { 	$this->load->language('module/design');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
			$this->model_setting_setting->editSetting('design', $this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_banner'] = $this->language->get('entry_banner');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension'); 
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
		
		if (isset($this->error['dimension'])) {
			$this->data['error_dimension'] = $this->error['dimension'];
		} else {
			$this->data['error_dimension'] = array();
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
			'href'      => $this->url->link('module/design', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/design', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		
		
		$this->template = 'module/design.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
    }

    public function install()
    {
        if (!$this->user->hasPermission('modify', 'extension/feed')) {
            $this->session->data['error'] = $this->language->get('error_permission');

            $this->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "option_value_description` (
	`option_value_id` INT(11) NOT NULL,
	`language_id` INT(11) NOT NULL,
	`option_id` INT(11) NOT NULL,
	`name` VARCHAR(128) NOT NULL COLLATE 'utf8_bin',
	PRIMARY KEY (`option_value_id`, `language_id`)
);");

            $this->add_column_if_not_exist(DB_PREFIX . 'product', 'product_design', "INT(5) NOT NULL DEFAULT '0'");

            $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."product_option_to_parent` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11) NOT NULL,
	`product_option_value_id` INT(11) NOT NULL,
	`product_option_value_parent_id` INT(11) NOT NULL,
	`option_id` INT(11) NOT NULL,
	`option_value_id` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
);");
            $this->add_column_if_not_exist(DB_PREFIX . 'product_option', 'step_name', 'VARCHAR(255) NOT NULL');

            $this->add_column_if_not_exist(DB_PREFIX . 'product_option_value', 'description', 'TEXT NULL');
        }
    }

    private function add_column_if_not_exist($db, $column, $column_attr = "")
    {
        $exists = false;
        $columns = mysql_query("show columns from $db");
        while ($c = mysql_fetch_assoc($columns)) {
            if ($c['Field'] == $column) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $this->db->query("ALTER TABLE `$db` ADD `$column`  $column_attr");
        }
    }

    public function uninstall()
    {
        if (!$this->user->hasPermission('modify', 'extension/feed')) {
            $this->session->data['error'] = $this->language->get('error_permission');
        }
    }
}