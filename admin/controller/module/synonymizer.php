<?php
class ControllerModuleSynonymizer extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/synonymizer');

		$this->document->setTitle($this->language->get('syn_heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('module/synonymizer');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('synonymizer', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/synonymizer', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['h1_text'] = $this->language->get('heading_title_h1');
		$this->data['h2_text'] = $this->language->get('heading_title_h2');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		

/* добавленное */

		$this->data['entry_main_category'] = $this->language->get('entry_main_category');
		$this->data['entry_description_syn'] = $this->language->get('entry_description_syn');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['last_cat_id'] = $this->language->get('last_cat_id');
		


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
			'href'      => $this->url->link('module/synonymizer', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['generate'] = $this->url->link('module/synonymizer/generate', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['modules'] = array();
		
		if (isset($this->request->post['synonymizer_module'])) {
			$this->data['modules'] = $this->request->post['synonymizer_module'];
		} elseif ($this->config->get('synonymizer_module')) { 
			$this->data['modules'] = $this->config->get('synonymizer_module');
		}	
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->template = 'module/synonymizer.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		setcookie('token', $this->session->data['token']);

				
		$this->getForm();

		$this->response->setOutput($this->render());
	}



	private function getForm() {

	$this->load->model('localisation/language');
	$this->data['languages'] = $this->model_localisation_language->getLanguages();

	$this->load->model('module/synonymizer');
	$categories = $this->model_module_synonymizer->getCategories(0);
	$this->data['categories'] = $categories;
	$this->data['token'] = $this->session->data['token'];

	}




public function test() {
	$this->load->model('module/synonymizer');
$json = array();
$json = $this->model_module_synonymizer->getAllAttributes();
$this->response->setOutput(json_encode($json));


}


	public function templates(){
	$this->load->model('module/synonymizer');
	$json = array();
	
	$json['template'] = $this->model_module_synonymizer->getTemplate($this->request->post['synonymizer']['category_id']);
	$json['success'] = 1;
	
	$this->response->setOutput(json_encode($json));
	
	}

	public function saveTemplate() {
	$this->load->model('module/synonymizer');
	$json = array();
	$this->model_module_synonymizer->saveTemplate($this->request->post['synonymizer']);
	$json['success'] = 1;
	
	$this->response->setOutput(json_encode($json));
	}

	public function generate() {
		if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
#print_r($this->request->post['synonymizer']);exit;

			$this->load->model('catalog/product');
			$this->load->model('module/synonymizer');
		
$update_type = $this->request->post['synonymizer']['update_type'];
$this->model_module_synonymizer->generateProductsSyn($this->request->post['synonymizer'],$update_type);
$fl=DIR_APPLICATION.'language/russian/module/synonymizer.php'; 
$content = file_get_contents($fl);
$content = preg_replace('#\$_\[\'last_cat_id\'\].+?;#is', '\$_[\'last_cat_id\']=\''.$this->request->post['synonymizer']['category_id'].'\';', $content);
file_put_contents($fl, $content);

$this->redirect(HTTPS_SERVER . 'index.php?route=module/synonymizer&token=' . $this->session->data['token']);
		}
	}


	public function install() {
		
        $this->load->model('module/synonymizer');
		$this->model_module_synonymizer->createFields();

	$this->load->model('setting/setting');
	       $this->model_setting_setting->editSetting('synonymizer', array('synonymizer_status'=>1));
        
		$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
        
	}
	
    public function uninstall() {
        
        $this->load->model('module/synonymizer');
		$this->model_module_synonymizer->deleteFields();

	 $this->load->model('setting/setting');
	        $this->model_setting_setting->editSetting('synonymizer', array('synonymizer_status'=>0));
        
		$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
    
    }
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/synonymizer')) {
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