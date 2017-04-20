<?php 
class ControllerModuleQuickOrderPro extends Controller { 

	const VERSION = '2.0';
	private $error = array();
	private $breadcrumbs_separator = ' » ';
	private $template_key_prifix = 'quick_order_pro_template_';
	private $required_fields = array('firstname', 'telephone');
	private $available_fields = array('lastname', 'email', 'comment', 'address', 'captcha', 'custom_field');
	private $available_order_fields = array('firstname', 'lastname', 'telephone', 'email', 'comment', 'address');
	private $base_languages = array('russian', 'english');
	 
	public function index() { 
	
		$this->load->language('module/quick_order_pro');
		
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		$this->load->model('localisation/order_status');
		$this->load->model('sale/customer_group');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if ($this->request->post['apply']) {
				$url = $this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL');
			} else {
				$url = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
			}
			
			unset($this->request->post['apply']);
			
			$this->model_setting_setting->editSetting('quick_order_pro', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($url);
		}
		
		$this->document->addStyle('view/stylesheet/quick_order_pro/quick_order_pro.css');
		$this->document->addStyle('view/stylesheet/quick_order_pro/colorpicker/colorpicker.css');
		$this->document->addScript('view/javascript/jquery/quick_order_pro/quick_order_pro.js');
		$this->document->addScript('view/javascript/jquery/quick_order_pro/colorpicker/colorpicker.js');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_help_layout_other'] = $this->language->get('text_help_layout_other');
		$this->data['text_drag'] = $this->language->get('text_drag');
		$this->data['text_product_setting'] = $this->language->get('text_product_setting');
		$this->data['text_alert_email'] = $this->language->get('text_alert_email');
		$this->data['text_required'] = $this->language->get('text_required');
		$this->data['text_confirm_remove'] = $this->language->get('text_confirm_remove');
		
		$this->data['column_template_id'] = $this->language->get('column_template_id');
		$this->data['column_machine_name'] = $this->language->get('column_machine_name');
		$this->data['column_store'] = $this->language->get('column_store');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['entry_template'] = $this->language->get('entry_template');
		$this->data['entry_operations'] = $this->language->get('entry_operations');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_captcha_show_lines'] = $this->language->get('entry_captcha_show_lines');
		$this->data['entry_captcha_count_lines'] = $this->language->get('entry_captcha_count_lines');
		$this->data['entry_captcha_color_line'] = $this->language->get('entry_captcha_color_line');
		$this->data['entry_captcha_text_color'] = $this->language->get('entry_captcha_text_color');
		$this->data['entry_captcha_count_items'] = $this->language->get('entry_captcha_count_items');
		$this->data['entry_captcha_alphabet'] = $this->language->get('entry_captcha_alphabet');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_guest_checkout'] = $this->language->get('entry_guest_checkout');
		$this->data['entry_autch_checkout'] = $this->language->get('entry_autch_checkout');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_show_product_total'] = $this->language->get('entry_show_product_total');
		$this->data['entry_create_customer'] = $this->language->get('entry_create_customer');
		$this->data['entry_new_customer_group'] = $this->language->get('entry_new_customer_group');
		$this->data['entry_new_customer_password'] = $this->language->get('entry_new_customer_password');
		$this->data['entry_new_customer_random_password'] = $this->language->get('entry_new_customer_random_password');
		$this->data['entry_subtract'] = $this->language->get('entry_subtract');
		$this->data['entry_customer_email_show_ip'] = $this->language->get('entry_customer_email_show_ip');
		$this->data['entry_customer_email_show_date_added'] = $this->language->get('entry_customer_email_show_date_added');
		$this->data['entry_admin_email_same_customer'] = $this->language->get('entry_admin_email_same_customer');
		$this->data['entry_alert_admin'] = $this->language->get('entry_alert_admin');
		$this->data['entry_admin_emails'] = $this->language->get('entry_admin_emails');
		$this->data['entry_alert_customer'] = $this->language->get('entry_alert_customer');
		$this->data['entry_admin_email_type'] = $this->language->get('entry_admin_email_type');
		$this->data['entry_admin_email_send_password'] = $this->language->get('entry_admin_email_send_password');
		$this->data['entry_show_products'] = $this->language->get('entry_show_products');
		$this->data['entry_show_product_options'] = $this->language->get('entry_show_product_options');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_templates'] = $this->language->get('tab_templates');
		$this->data['tab_setting'] = $this->language->get('tab_setting');
		$this->data['tab_catcha'] = $this->language->get('tab_catcha');
		
		$this->data['tab_setting'] = $this->language->get('tab_setting');
		$this->data['tab_notifications'] = $this->language->get('tab_notifications');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_templates'] = $this->language->get('tab_templates');
		$this->data['tab_block'] = $this->language->get('tab_block');
		
		$this->data['button_insert_template'] = $this->language->get('button_insert_template');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($this->session->data['attention'])) {
			$this->data['attention'] = $this->session->data['attention'];
		
			unset($this->session->data['attention']);
		} else {
			$this->data['attention'] = '';
		}
		
		$this->data['error'] = $this->error;
		
   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => strip_tags($this->language->get('heading_title')),
			'href'      => $this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
   		);
		
		$this->data['action'] = $this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['insert_template'] = $this->url->link('module/quick_order_pro/insert_template', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['status_variables'] = array($this->language->get('text_disabled'), $this->language->get('text_enabled'));
		
		$this->data['email_types'] = array(
			'config' => $this->language->get('text_email_type_config'), 
			'other'	 => $this->language->get('text_email_type_other')
		);
		
		$this->data['positions'] = array(
			'content_top'	 => $this->language->get('text_content_top'),
			'content_bottom' => $this->language->get('text_content_bottom'),
			'column_left'	 => $this->language->get('text_column_left'),
			'column_right'	 => $this->language->get('text_column_right')
		);
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('setting/store');
		
		$stores = array();
		$stores[] = array(
			'store_id' => 0,
			'name'	   => $this->config->get('config_name')
		);
		
		$stores = array_merge($stores, $this->model_setting_store->getStores());
		
		$this->data['templates'] = array();
		
		$templates = $this->getAllTemplates();
		
		/*if (!$templates) {
			$this->data['attention'] = sprintf($this->language->get('text_empty_templates'), $this->url->link('module/quick_order_pro/insert_template', 'token=' . $this->session->data['token'], 'SSL'));
		}*/
		
		foreach ($templates as $template) {
			
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $template['template_id'], 'SSL')
			);
			
			$action[] = array(
				'text' => $this->language->get('button_delete'),
				'href' => $this->url->link('module/quick_order_pro/delete_template', 'token=' . $this->session->data['token'] . '&template_id=' . $template['template_id'], 'SSL')
			);
			
			if (empty($template['store'])) {
				$template['store'] = '---';
			} else {
				
				$store_data = array();
				
				foreach ($template['store'] as $store_id) {
					$store_data[] = $stores[$store_id]['name'];
				}
				
				$template['store'] = implode(', ', $store_data);
			}
			
      		$this->data['templates'][] = array(
				'template_id'	=> $template['template_id'],
				'base_name'		=> $this->getShortTitle($template['base_name'], 30),
				'store'			=> $template['store'],
				'action'		=> $action
			);
		}
		
		if (isset($this->request->post['quick_order_pro_setting'])) {
			$this->data['setting'] = $this->request->post['quick_order_pro_setting'];
		} elseif ($this->config->get('quick_order_pro_setting')) {
			$this->data['setting'] = $this->config->get('quick_order_pro_setting');
		} else {
			$this->data['setting'] = array();
		}
		
		if (!isset($this->data['setting']['show_lines'])) {
			$this->data['setting']['show_lines'] = 1;
		}
		
		if (!isset($this->data['setting']['color_line'])) {
			$this->data['setting']['color_line'] = '56,169,237';
		}
		
		if (!isset($this->data['setting']['captcha_color'])) {
			$this->data['setting']['captcha_color'] = '56,169,237';
		}
		
		if (!isset($this->data['setting']['captcha_count_items'])) {
			$this->data['setting']['captcha_count_items'] = 6;
		}
		
		if (!isset($this->data['setting']['captcha_alphabet'])) {
			$this->data['setting']['captcha_alphabet'] = '1234567890abcdefghijklmnopqrstuvwxyz';
		}
		
		if (!isset($this->data['setting']['autch_checkout'])) {
			$this->data['setting']['autch_checkout'] = 1;
		}
		
		$this->data['captcha_count_items'] = array(3,4,5,6,7,8);
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['quick_order_pro_module'])) {
			$this->data['modules'] = $this->request->post['quick_order_pro_module'];
		} elseif ($this->config->get('quick_order_pro_module')) { 
			$this->data['modules'] = $this->config->get('quick_order_pro_module');
		}	
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->template = 'module/quick_order_pro.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	/**
	 * Добавление нового шаблона
	 */
	public function insert_template() {
		
		$this->load->language('module/quick_order_pro');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateTemplateForm()) {
			
			$template_info = $this->request->post['template'];
			
			$template_info += array(
				'base_name'		=> ($template_info['machine_name']) ? $template_info['machine_name'] : $this->language->get('text_template') . ' ' . $this->request->get['template_id'],
				'template_id'	=> $this->request->get['template_id'],
				'fields'		=> $this->getDefaultFields($this->request->get['template_id'])
			);
			
			$this->saveTemplate($template_info);
			
			if ($this->request->post['apply']) {
				$url = $this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL');
			} else {
				$url = $this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL') . '#tab=templates';
			}
			
			$this->session->data['success'] = $this->language->get('text_success_save_template');
			
			$this->redirect($url);
		}
		
		$this->getTemplateForm();
	}
	
	/**
	 * Редактирование шаблона
	 */
	public function update_template() {
		
		$this->load->language('module/quick_order_pro');
		$this->load->model('module/quick_order_pro');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateTemplateForm()) {
			
			if (!array_key_exists('template_id', $this->request->get) || !$template_info = $this->getTemplate($this->request->get['template_id'])) {
				$this->session->data['error'] = $this->language->get('error_template_not_found');
				$this->redirect($this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL')) . '#tab=templates';
			}
			
			$template = $this->request->post['template'];
			
			$fields = $template_info['fields'];
			
			foreach ($template['fields'] as $field_id => $field_info) {
				
				if (array_key_exists($field_id, $fields)) {
					$fields[$field_id]['weight'] = (int)$field_info['weight'];
				}
				
			}
			
			$template['fields'] = $fields;
			$template['base_name'] = ($template['machine_name']) ? $template['machine_name'] : $this->language->get('text_template') . ' ' . $this->request->get['template_id'];
			$template['template_id'] = $this->request->get['template_id'];
			
			$this->saveTemplate($template);
			
			if (array_key_exists('redirect', $this->request->get) && $this->request->get['redirect'] == 'fields') {
				$url = $this->url->link('module/quick_order_pro/field', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL');
			} elseif ($this->request->post['apply']) {
				$url = $this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL');
			} else {
				$url = $this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL') . '#tab=templates';
			}
			
			$this->session->data['success'] = $this->language->get('text_success_save_template');
			
			$this->redirect($url);
		}
		
		$this->getTemplateForm();
	}
	
	/**
	 * Удаление шаблона
	 */	
	public function delete_template() {
		
		$this->load->language('module/quick_order_pro');
		
		$this->document->setTitle($this->language->get('heading_title_template')); 
		
		if (array_key_exists('template_id', $this->request->get) && $template_info = $this->getTemplate($this->request->get['template_id'])) {
			
			$count_exists = 0;
			
			$modules = $this->config->get('quick_order_pro_module');
			
			if ($modules) {
				
				foreach ($modules as $module) {
					
					if ($module['template_id'] == $this->request->get['template_id']) {
						$count_exists++;
					}
					
				}
				
			}
			
			if ($count_exists > 0 && !array_key_exists('confirm', $this->request->get)) {
				
				$this->session->data['attention'] = sprintf($this->language->get('text_confirm_remove'), $count_exists, 'блок' . $this->declination($count_exists, array('e', 'ax', 'ax')), $this->url->link('module/quick_order_pro/delete_template', 'confirm=&token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL'));
				
			} else {
				
				$this->load->model('setting/setting');
				
				if ($count_exists > 0) {
					
					foreach ($modules as $key=> $module) {
						if ($module['template_id'] == $this->request->get['template_id']) {
							unset($modules[$key]);
						}
					}
					
					$this->model_setting_setting->editSetting('quick_order_pro', array('quick_order_pro_module' =>$modules));
				}
				
				$all_templates = $this->getAllTemplates();
				unset($all_templates[$this->getRealTemplateKey($this->request->get['template_id'])]);
				
				$this->model_setting_setting->editSetting('quick_order_pro_templates', $all_templates);
				
				$this->session->data['success'] = $this->language->get('text_success_delete_template');
			}
			
			$this->redirect($this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL')) . '#tab=templates';
			
		}
		
		$this->redirect($this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL')) . '#tab=templates';
	}
	
	/**
	 * Форма добавления/редактирование шаблона
	 */
	private function getTemplateForm() {
		
		$this->document->addScript('view/javascript/jquery/quick_order_pro/quick_order_pro.js');
		$this->document->addScript('view/javascript/jquery/quick_order_pro/jquery.tablednd.0.7.min.js');
		$this->document->addStyle('view/stylesheet/quick_order_pro/quick_order_pro.css');
		
		$this->load->model('localisation/language');
		$this->load->model('setting/store');
		
		$this->data['text_help_machine_name'] = $this->language->get('text_help_machine_name');
		$this->data['text_template'] = $this->language->get('text_template');
		$this->data['text_enabled_editor'] = $this->language->get('text_enabled_editor');
		$this->data['text_disable_editor'] = $this->language->get('text_disable_editor');
		$this->data['text_help_layout_product'] = $this->language->get('text_help_layout_product');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_help_custom_fields'] = $this->language->get('text_help_custom_fields');
		$this->data['text_add_field'] = $this->language->get('text_add_field');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select_field'] = $this->language->get('text_select_field');
		$this->data['text_drag'] = $this->language->get('text_drag');
		$this->data['text_required'] = $this->language->get('text_required');
		
		$this->data['column_field_id'] = $this->language->get('column_field_id');
		$this->data['column_field_title'] = $this->language->get('column_field_title');
		$this->data['column_field_type'] = $this->language->get('column_field_type');
		$this->data['column_required'] = $this->language->get('column_required');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_template_title'] = $this->language->get('entry_template_title');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_show_option'] = $this->language->get('entry_show_option');
		$this->data['entry_use_option'] = $this->language->get('entry_use_option');
		$this->data['entry_option_required'] = $this->language->get('entry_option_required');
		$this->data['entry_show_total'] = $this->language->get('entry_show_total');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');
		$this->data['entry_redirect_success'] = $this->language->get('entry_redirect_success');
		$this->data['entry_success_message'] = $this->language->get('entry_success_message');
		$this->data['entry_css_class'] = $this->language->get('entry_css_class');
		$this->data['entry_button_label'] = $this->language->get('entry_button_label');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_fields'] = $this->language->get('tab_fields');
		$this->data['tab_additional'] = $this->language->get('tab_additional');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_insert'] = $this->language->get('button_insert');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['boolean_variables'] = array($this->language->get('text_no'), $this->language->get('text_yes'));
		$this->data['status_variables'] = array($this->language->get('text_disabled'), $this->language->get('text_enabled'));
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error'])) {		
			
			$this->data['error_warning'] = $this->session->data['error'];
			
			unset($this->session->data['error']);
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['error'] = $this->error;
		
		if (isset($this->request->get['template_id'])) {
			$template_info = $this->getTemplate($this->request->get['template_id']);
		}
		
		if (!empty($template_info)) {
			$this->data['heading_title'] = sprintf($this->language->get('heading_title_edit_template'), $template_info['base_name']);
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title_new_template');
		}
		
		$this->document->setTitle($this->data['heading_title']);
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => strip_tags($this->language->get('heading_title')),
			'href'      => $this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL') . '#tab=templates',
      		'separator' => $this->breadcrumbs_separator
   		);
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->data['heading_title'],
			'href'      => '',
      		'separator' => $this->breadcrumbs_separator
   		);
		
		$url = '';
		
		if (!empty($template_info)) {
			$template_id = $this->request->get['template_id'];
		} else {
			$template_id = $this->getNewTemplateId();
		}
		
		if (empty($template_info)) {
			$this->data['action'] = $this->url->link('module/quick_order_pro/insert_template', 'token=' . $this->session->data['token'] . '&template_id=' . $template_id . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $template_id . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL') . '#tab=templates';
		
		$this->data['available_fields'] = array();
		
		foreach ($this->available_fields as $code) {
			$this->data['available_fields'][$code] = $this->language->get('text_' . $code) . (!in_array($code, $this->available_order_fields) ? ' ***' : '');
		}
		
		$this->data['fields'] = array();
		
		$this->data['new'] = (empty($template_info));
		
		if (!$this->data['new']) {
			
			$sort_order = array();
			
			foreach ($template_info['fields'] as $field_id => $field_info) {
				
				$action = array();
				
				if ($field_info['code'] != 'captcha') {
				
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('module/quick_order_pro/update_field', 'token=' . $this->session->data['token'] . '&template_id=' . $template_id . '&field_id=' . $field_id, 'SSL')
					);
					
				}
				
				if (!in_array($field_info['code'], $this->required_fields)) {
					
					$action[] = array(
						'text' => $this->language->get('button_delete'),
						'href' => $this->url->link('module/quick_order_pro/delete_field', 'token=' . $this->session->data['token'] . '&template_id=' . $template_id . '&field_id=' . $field_id, 'SSL')
					);
					
				}
				
				if ($field_info['code'] != 'custom_field') {
					unset($this->data['available_fields'][$field_info['code']]);
				}
				
				$this->data['fields'][$field_id] = array(
					'field_id'	=> $field_id,
					'title'		=> $field_info['title'][$this->config->get('config_language_id')] . (!in_array($field_info['code'], $this->available_order_fields) ? ' ***' : ''),
					'type'		=> $field_info['type']['type'],
					'required'	=> $field_info['required'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
					'weight'	=> $field_info['weight'],
					'action'	=> $action
				);
				
				$sort_order[$field_id] = $field_info['weight'];
			
			}
			
			array_multisort($sort_order, SORT_ASC, $this->data['fields']);
		}
		
		if ($this->data['new']) {
			$this->data['attention'] = $this->language->get('text_new_template_fields');
		}
		
		
		
		$this->data['base_url'] = html_entity_decode($this->url->link('module/quick_order_pro/insert_field', 'token=' . $this->session->data['token'] . '&template_id=' . $template_id, 'SSL')  . '&code=', ENT_QUOTES, 'UTF-8');
		
		if (isset($this->request->post['template']['machine_name'])) {
      		$this->data['machine_name'] = $this->request->post['template']['machine_name'];
    	} elseif (!empty($template_info)) {
			$this->data['machine_name'] = $template_info['machine_name'];
		} else {
      		$this->data['machine_name'] = '';
    	}
		
		if (isset($this->request->post['template']['title'])) {
      		$this->data['title'] = $this->request->post['template']['title'];
    	} elseif (!empty($template_info)) {
			$this->data['title'] = $template_info['title'];
		} else {
      		$this->data['title'] = '';
    	}
		
		if (isset($this->request->post['template']['description'])) {
      		$this->data['description'] = $this->request->post['template']['description'];
    	} elseif (!empty($template_info)) {
			$this->data['description'] = $template_info['description'];
		} else {
      		$this->data['description'] = '';
    	}
		
		if (isset($this->request->post['template']['editor'])) {
      		$this->data['editor'] = $this->request->post['template']['editor'];
    	} elseif (!empty($template_info)) {
			$this->data['editor'] = $template_info['editor'];
		} else {
      		$this->data['editor'] = array();
    	}
		
		if (isset($this->request->post['template']['store'])) {
      		$this->data['template_store'] = $this->request->post['template']['store'];
    	} elseif (!empty($template_info)) {
			$this->data['template_store'] = $template_info['store'];
		} else {
      		$this->data['template_store'] = array(0);
    	}
		
		if (isset($this->request->post['template']['stock_checkout'])) {
      		$this->data['stock_checkout'] = $this->request->post['template']['stock_checkout'];
    	} elseif (!empty($template_info)) {
			$this->data['stock_checkout'] = $template_info['stock_checkout'];
		} else {
      		$this->data['stock_checkout'] = 0;
    	}
		
		if (isset($this->request->post['template']['minimum'])) {
      		$this->data['minimum'] = $this->request->post['template']['minimum'];
    	} elseif (!empty($template_info)) {
			$this->data['minimum'] = $template_info['minimum'];
		} else {
      		$this->data['minimum'] = 1;
    	}
		
		if (isset($this->request->post['template']['use_option'])) {
      		$this->data['use_option'] = $this->request->post['template']['use_option'];
    	} elseif (!empty($template_info)) {
			$this->data['use_option'] = $template_info['use_option'];
		} else {
      		$this->data['use_option'] = 1;
    	}
		
		if (isset($this->request->post['template']['option_required'])) {
      		$this->data['option_required'] = $this->request->post['template']['option_required'];
    	} elseif (!empty($template_info)) {
			$this->data['option_required'] = $template_info['option_required'];
		} else {
      		$this->data['option_required'] = 1;
    	}
		
		if (isset($this->request->post['template']['show_option'])) {
      		$this->data['show_option'] = $this->request->post['template']['show_option'];
    	} elseif (!empty($template_info)) {
			$this->data['show_option'] = $template_info['show_option'];
		} else {
      		$this->data['show_option'] = 1;
    	}
		
		if (isset($this->request->post['template']['show_total'])) {
      		$this->data['show_total'] = $this->request->post['template']['show_total'];
    	} elseif (!empty($template_info)) {
			$this->data['show_total'] = $template_info['show_total'];
		} else {
      		$this->data['show_total'] = 1;
    	}
		
		if (isset($this->request->post['template']['redirect'])) {
      		$this->data['redirect'] = $this->request->post['template']['redirect'];
    	} elseif (!empty($template_info)) {
			$this->data['redirect'] = $template_info['redirect'];
		} else {
      		$this->data['redirect'] = 1;
    	}
		
		if (isset($this->request->post['template']['success_message'])) {
      		$this->data['success_message'] = $this->request->post['template']['success_message'];
    	} elseif (!empty($template_info)) {
			$this->data['success_message'] = $template_info['success_message'];
		} else {
      		$this->data['success_message'] = '';
    	}
		
		if (isset($this->request->post['template']['class'])) {
      		$this->data['class_name'] = $this->request->post['template']['class'];
    	} elseif (!empty($template_info)) {
			$this->data['class_name'] = $template_info['class'];
		} else {
      		$this->data['class_name'] = '';
    	}
		
		if (isset($this->request->post['template']['button'])) {
      		$this->data['button'] = $this->request->post['template']['button'];
    	} elseif (!empty($template_info['button'])) {
			$this->data['button'] = $template_info['button'];
		} else {
      		$this->data['button'] = '';
    	}
		
		$this->data['template_id'] = $template_id;
		
		$this->data['stores'] = array();
		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'	   => $this->config->get('config_name')
		);
		
		$this->data['stores'] = array_merge($this->data['stores'], $this->model_setting_store->getStores());
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->template = 'module/quick_order_pro_template_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
		
	}
	
	/**
	 * Добавление нового поля
	 */
	public function insert_field() {
		
		$this->load->language('module/quick_order_pro');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFieldForm()) {
			
			if (!array_key_exists('template_id', $this->request->get) || !$template_info = $this->getTemplate($this->request->get['template_id'])) {
				$this->session->data['error'] = $this->language->get('error_template_not_found');
				$this->redirect($this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL'));		
			}
			
			$field_info = $this->request->post['field'];
			
			if (array_key_exists($field_info['type']['type'], $field_info['type']['option'])) {
				$type_option = $field_info['type']['option'][$field_info['type']['type']];
			} else {
				$type_option = array();
			}
			
			unset($field_info['type']['option']);
			
			$field_info['type'] += $type_option;
			
			if ($field_info['validate']['type']) {
				$field_info['validate'] += $field_info['validate']['option'][$field_info['validate']['type']];
				unset($field_info['validate']['option']);
			
			} else {
				$field_info['validate'] = array();
			}
			
			$template_info['fields'][$field_info['field_id']] = $field_info;
			
			$this->saveTemplate($template_info);
			
			if ($this->request->post['apply']) {
				$url = $this->url->link('module/quick_order_pro/update_field', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'] . '&field_id=' . $this->request->post['field']['field_id'], 'SSL');
			} else {
				$url = $this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL') . '#tab=fields';
			}
			
			$this->session->data['success'] = sprintf($this->language->get('text_success_insert_field'), $field_info['title'][$this->config->get('config_language_id')]);
			
			$this->redirect($url);
		}
		
		$this->getFieldForm();
	}
	
	/**
	 * Редактирование поля
	 */
	public function update_field() {
		
		$this->load->language('module/quick_order_pro');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFieldForm()) {
			
			if (!array_key_exists('template_id', $this->request->get) || !$template_info = $this->getTemplate($this->request->get['template_id'])) {
				$this->session->data['error'] = $this->language->get('error_template_not_found');
				$this->redirect($this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL'));		
			}
			
			$field_info = $this->request->post['field'];
			
			if (array_key_exists($field_info['type']['type'], $field_info['type']['option'])) {
				$type_option = $field_info['type']['option'][$field_info['type']['type']];
			} else {
				$type_option = array();
			}
			
			unset($field_info['type']['option']);
			
			$field_info['type'] += $type_option;
			
			if ($field_info['validate']['type']) {
				$field_info['validate'] += $field_info['validate']['option'][$field_info['validate']['type']];
				unset($field_info['validate']['option']);
			} else {
				$field_info['validate'] = array();
			}
			
			$template_info['fields'][$field_info['field_id']] = $field_info;
			
			$this->saveTemplate($template_info);
			
			if ($this->request->post['apply']) {
				$url = $this->url->link('module/quick_order_pro/update_field', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'] . '&field_id=' . $this->request->post['field']['field_id'], 'SSL');
			} else {
				$url = $this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL') . '#tab=fields';
			}
			
			$this->session->data['success'] = sprintf($this->language->get('text_success_update_field'), $field_info['title'][$this->config->get('config_language_id')]);
			
			$this->redirect($url);
		}
		
		$this->getFieldForm();
	}
	
	public function delete_field() {
		
		$this->load->language('module/quick_order_pro');
		
		if (array_key_exists('template_id', $this->request->get) && $template_info = $this->getTemplate($this->request->get['template_id'])) {
			
			if (array_key_exists('field_id', $this->request->get) && array_key_exists($this->request->get['field_id'], $template_info['fields'])) {
				
				$field_info = $template_info['fields'][$this->request->get['field_id']]; 
				
				if (in_array($field_info['code'], $this->required_fields)) {
					$this->session->data['attention'] = sprintf($this->language->get('error_remove_required_field'), $field_info['title'][$this->config->get('config_language_id')]);
				} else {
					$this->session->data['success'] = sprintf($this->language->get('text_success_remove_field'), $field_info['title'][$this->config->get('config_language_id')]);
					unset($template_info['fields'][$this->request->get['field_id']]);
					$this->saveTemplate($template_info);
				}
				
				$this->redirect($this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL') . '#tab=fields');
			}
			
			
		}
		
		$this->redirect($this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL')) . '#tab=templates';
	}
	
	/**
	 * Форма добавления/редактирование поля шаблона
	 */
	private function getFieldForm() {
		
		if (!array_key_exists('template_id', $this->request->get) || !$template_info = $this->getTemplate($this->request->get['template_id'])) {
			$this->session->data['error'] = $this->language->get('error_template_not_found');
			$this->redirect($this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL'));		
		}
		
		if (array_key_exists('field_id', $this->request->get) && array_key_exists($this->request->get['field_id'], $template_info['fields'])) {
			
			$field_id = $this->request->get['field_id'];
			$field_info = $template_info['fields'][$field_id];
			
		} elseif (array_key_exists('code', $this->request->get) && in_array($this->request->get['code'], $this->available_fields)) {
			
			$field_id = $this->getNewCustomFieldId($this->request->get['code'], $template_info);
			
		} else {
			
			$this->session->data['error'] = $this->language->get('error_field');
			$this->redirect($this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL'));		
			
		}
		
		$this->document->addScript('view/javascript/jquery/quick_order_pro/quick_order_pro.js');
		$this->document->addScript('view/javascript/jquery/quick_order_pro/jquery.tablednd.0.7.min.js');
		$this->document->addStyle('view/stylesheet/quick_order_pro/quick_order_pro.css');
		
		$this->data['text_required'] = $this->language->get('text_required');
		$this->data['text_drag'] = $this->language->get('text_drag');
		$this->data['text_edit_sort_value'] = $this->language->get('text_edit_sort_value');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_from'] = $this->language->get('text_from');
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_help_validate'] = $this->language->get('text_help_validate');
		$this->data['text_help_field_required'] = $this->language->get('text_help_field_required');
		$this->data['text_tokens'] = $this->language->get('text_tokens');
		$this->data['text_token_field_name'] = $this->language->get('text_token_field_name');
		$this->data['text_token_field_min'] = $this->language->get('text_token_field_min');
		$this->data['text_token_field_max'] = $this->language->get('text_token_field_max');
		$this->data['text_token_field_value'] = $this->language->get('text_token_field_value');
		$this->data['text_token_field_domain'] = $this->language->get('text_token_field_domain');
		
		$this->data['entry_field_use_mask'] = $this->language->get('entry_field_use_mask');
		$this->data['entry_field_mask'] = $this->language->get('entry_field_mask');
		$this->data['text_mash_example'] = $this->language->get('text_mash_example');
		$this->data['entry_field_textarea_rows'] = $this->language->get('entry_field_textarea_rows');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_css_class_field'] = $this->language->get('entry_css_class_field');
		$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_field_description'] = $this->language->get('entry_field_description');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_field_select_multiple'] = $this->language->get('entry_field_select_multiple');
		$this->data['entry_field_type'] = $this->language->get('entry_field_type');
		$this->data['entry_field_placeholder'] = $this->language->get('entry_field_placeholder');
		$this->data['entry_field_maxlength'] = $this->language->get('entry_field_maxlength');
		$this->data['entry_field_validate'] = $this->language->get('entry_field_validate');
		$this->data['entry_pcre_pattern'] = $this->language->get('entry_pcre_pattern');
		$this->data['entry_field_use_value'] = $this->language->get('entry_field_use_value');
		$this->data['entry_int_value'] = $this->language->get('entry_int_value');
		$this->data['entry_int_min_value'] = $this->language->get('entry_int_min_value');
		$this->data['entry_int_max_value'] = $this->language->get('entry_int_max_value');
		$this->data['entry_error_message'] = $this->language->get('entry_error_message');
		$this->data['entry_error_message_email_domain'] = $this->language->get('entry_error_message_email_domain');
		$this->data['entry_blacklist'] = $this->language->get('entry_blacklist');
		$this->data['entry_specific_value_list'] = $this->language->get('entry_specific_value_list');
		$this->data['entry_field_email_check_domain'] = $this->language->get('entry_field_email_check_domain');
		
		$this->data['column_value'] = $this->language->get('column_value');
		$this->data['column_active_item'] = $this->language->get('column_active_item');
		$this->data['column_token'] = $this->language->get('column_token');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_type'] = $this->language->get('tab_type');
		$this->data['tab_validate'] = $this->language->get('tab_validate');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_apply'] = $this->language->get('button_apply');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_value'] = $this->language->get('button_add_value');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['error'] = $this->error;
		
		if (!empty($field_info)) {
			$this->data['heading_title'] = sprintf($this->language->get('heading_title_edit_field'), $field_info['title'][$this->config->get('config_language_id')]);
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title_new_field');
		}
		
		$this->document->setTitle($this->data['heading_title']);
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => strip_tags($this->language->get('heading_title')),
			'href'      => $this->url->link('module/quick_order_pro', 'token=' . $this->session->data['token'], 'SSL') . '#tab=templates',
      		'separator' => $this->breadcrumbs_separator
   		);
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => $template_info['base_name'],
			'href'      => $this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $template_info['template_id'], 'SSL'),
      		'separator' => $this->breadcrumbs_separator
   		);
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->data['heading_title'],
			'href'      => '',
      		'separator' => $this->breadcrumbs_separator
   		);
		
		if (!empty($field_info)) {
			$code = $field_info['code'];
		} else {
			$code = $this->request->get['code'];
		}
		
		if ($code == 'captcha') {
			
			$field_info = array(
				'field_id'		=> $field_id,
				'code'			=> $code,
				'title'			=> $this->getMultipleLanguageValue('captcha', 'text_show_'),
				'description'	=> array(),
				'required'		=> 1,
				'base'			=> 0,
				'type'			=> array(
					'type'			=> 'text',
					'maxlength'		=> '6'
				),
				'validate'		=> array(
					'type'			=> 'captcha',
					'error_message'	=> $this->language->get('error_captcha')
				),
				'status'		=> 1,
				'class'			=> '',
				'weight'		=> 0
			);
			
			$template_info['fields'][$field_info['field_id']] = $field_info;
			$this->saveTemplate($template_info);
			$this->redirect($this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'], 'SSL') . '#tab=fields');
		}
		
		if (in_array($code, $this->available_order_fields)) {
			$this->data['text_help_field'] = sprintf($this->language->get('text_help_field'), $this->language->get('text_' . $code));
		}
		
		$url = '';
		
		if (empty($field_info)) {
			$this->data['action'] = $this->url->link('module/quick_order_pro/insert_field', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'] . '&code=' . $code . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/quick_order_pro/update_field', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'] . '&field_id=' . $field_id . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('module/quick_order_pro/update_template', 'token=' . $this->session->data['token'] . '&template_id=' . $this->request->get['template_id'] . $url, 'SSL') . '#tab=fields';
		
		$this->data['boolean_variables'] = array($this->language->get('text_no'), $this->data['text_yes']);
		$this->data['status_variables'] = array($this->language->get('text_disabled'), $this->data['text_enabled']);
		
		if (isset($this->request->post['field']['title'])) {
      		$this->data['title'] = $this->request->post['field']['title'];
    	} elseif (!empty($field_info)) {
			$this->data['title'] = $field_info['title'];
		} elseif (in_array($code, $this->available_order_fields)) {
			$this->data['title'] = $this->getMultipleLanguageValue($code);
		} else {
      		$this->data['title'] = '';
    	}
		
		if (isset($this->request->post['field']['description'])) {
      		$this->data['description'] = $this->request->post['field']['description'];
    	} elseif (!empty($field_info)) {
			$this->data['description'] = $field_info['description'];
		} else {
      		$this->data['description'] = '';
    	}
		
		if (isset($this->request->post['field']['status'])) {
      		$this->data['status'] = $this->request->post['field']['status'];
    	} elseif (!empty($field_info)) {
			$this->data['status'] = $field_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}
		
		if (isset($this->request->post['field']['required'])) {
      		$this->data['required'] = $this->request->post['field']['required'];
    	} elseif (!empty($field_info)) {
			$this->data['required'] = $field_info['required'];
		} else {
      		$this->data['required'] = '';
    	}
		
		if (isset($this->request->post['field']['class'])) {
      		$this->data['class_name'] = $this->request->post['field']['class'];
    	} elseif (!empty($field_info)) {
			$this->data['class_name'] = $field_info['class'];
		} else {
      		$this->data['class_name'] = '';
    	}
		
		if (isset($this->request->post['field']['weight'])) {
      		$this->data['weight'] = $this->request->post['field']['weight'];
    	} elseif (!empty($field_info)) {
			$this->data['weight'] = $field_info['weight'];
		} else {
      		$this->data['weight'] = 0;
    	}
		
		$this->data['required_field'] = in_array($code, $this->required_fields);
		$this->data['order_field'] = in_array($code, $this->available_order_fields) && $code != 'comment';
		
		$this->data['field_types'] = $this->getFieldTypes();
		
		if (in_array($code, array('firstname', 'telephone', 'lastname', 'email'))) {
			$this->data['field_types'] = array_intersect_key($this->data['field_types'], array_flip(array('text')));
		} elseif (in_array($code, array('comment', 'address'))) {
			$this->data['field_types'] = array_intersect_key($this->data['field_types'], array_flip(array('text', 'textarea')));
		}
		
		if (isset($this->request->post['field']['type']['type'])) {
      		$this->data['type'] = $this->request->post['field']['type']['type'];
    	} elseif (!empty($field_info)) {
			$this->data['type'] = $field_info['type']['type'];
		} else {
      		$this->data['type'] = 'text';
    	}
		
		if (isset($this->request->post['field']['type']['option'])) {
      		$this->data['type_option'] = $this->request->post['field']['type']['option'];
    	} elseif (!empty($field_info)) {
			$this->data['type_option'] = array($field_info['type']['type'] => $field_info['type']);
		} else {
      		$this->data['type_option'] = array();
    	}
		
		$this->data['validate_types'] = $this->getValidateTypes();
		
		if ($code == 'firstname') {
			$this->data['validate_types'] = array_intersect_key($this->data['validate_types'], array_flip(array('number_of_words', 'words_blacklist', 'plain_text')));
		} elseif ($code == 'telephone') {
			$this->data['validate_types'] = array_intersect_key($this->data['validate_types'], array_flip(array('pcre', 'plain_text', 'php_code')));
		}
		
		if (isset($this->request->post['field']['validate']['type'])) {
      		$this->data['validate'] = $this->request->post['field']['validate']['type'];
    	} elseif (!empty($field_info['validate']['type'])) {
			$this->data['validate'] = $field_info['validate']['type'];
		} else {
      		$this->data['validate'] = '';
    	}
		
		if (isset($this->request->post['field']['validate']['option'])) {
      		$this->data['validate_option'] = $this->request->post['field']['validate']['option'];
    	} elseif (!empty($field_info['validate'])) {
			$this->data['validate_option'] = array($field_info['validate']['type'] => $field_info['validate']);
		} else {
      		$this->data['validate_option'] = array();
    	}
		
		$this->data['field_id'] = $field_id;
		$this->data['code'] = $code;
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->template = 'module/quick_order_pro_field_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	/**
	 * Валидация полей
	 */
	private function validateFieldForm() {
		
		if (!$this->user->hasPermission('modify', 'module/quick_order_pro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['field']['title'] as $language_id => $title) {
			if ((utf8_strlen($title) < 1) || (utf8_strlen($title) > 255)) {
        		$this->error['title'][$language_id] = $this->language->get('error_title');
      		}
		}
		
		if (trim($this->request->post['field']['weight']) && !is_numeric($this->request->post['field']['weight'])) {
			$this->error['weight'] = $this->language->get('error_numeric');
		}
		
		foreach (array('text', 'textarea', 'radio', 'select', 'checkbox') as $type) {
			
			if ($this->request->post['field']['type']['type'] == $type) {
				
				if (array_key_exists($type, $this->request->post['field']['type']['option'])) {
					$type_info = $this->request->post['field']['type']['option'][$type];
				} else {
					$type_info = array();
				}
				
				switch ($type) {
					case 'text':
						
						if ($type_info['use_mask'] && trim($type_info['mask']) == '') {
							$this->error['type'][$type]['mask'] = $this->language->get('error_mask');
						}
						
						if (trim($type_info['maxlength']) && !is_numeric($type_info['maxlength'])) {
							$this->error['type'][$type]['maxlength'] = $this->language->get('error_numeric');
						}
					
						break;
					case 'textarea':
					
						if (trim($type_info['rows']) && !is_numeric($type_info['rows'])) {
							$this->error['type'][$type]['rows'] = $this->language->get('error_numeric');
						}
					
						break;
					case 'radio':
					case 'select':
					case 'checkbox':
					
						if (!empty($type_info['option'])) {
							
							foreach ($type_info['option'] as $option_id => $option_info) {
								
								if (utf8_strlen($option_info['value']) < 1) {
									$this->error['type'][$type]['option'][$option_id] = $this->language->get('error_option_value');
								}
								
							}
							
						} else {
							$this->error['type'][$type]['option'] = $this->language->get('error_option');
						}
					
				}
			}
		}
		
		foreach ($this->request->post['field']['validate']['option'] as $validate_id => $validate_info) {
			
			if ($this->request->post['field']['validate']['type'] == $validate_id) {
				
				switch ($validate_id) {
					case 'pcre':
					
						if (utf8_strlen(trim($validate_info['pattern'])) < 1) {
							$this->error['validate'][$validate_id]['pattern'] = $this->language->get('error_option');
						} else {
							
							function tempErrorHandler($errNo, $errStr, $errFile, $errLine, array $errContext) {
								if (0 === error_reporting()) {
									return false;
								}
								throw new ErrorException($errStr, 0, $errNo, $errFile, $errLine);
							}
							
							set_error_handler('tempErrorHandler');
						
							$test = FALSE;
							
							try {
								ob_start();
								preg_match($validate_info['pattern'], 'test');
								ob_end_clean();
							} catch (ErrorException $e) {
								$this->error['validate'][$validate_id]['pattern'] = sprintf($this->language->get('error_pcre_pattern'), str_replace('preg_match(): ', '', $e->getMessage()));
							}
							
						}
						
						if (utf8_strlen(trim($validate_info['error_message'])) < 1 || utf8_strlen(trim($validate_info['error_message'])) > 128) {
							$this->error['validate'][$validate_id]['error_message'] = $this->language->get('error_error_message');
						}
					
						break;
					case 'int':
					case 'numeric':
					case 'length':
					case 'number_of_words':
					
						if ($validate_info['min'] != '' && !is_numeric($validate_info['min'])) {
							$this->error['validate'][$validate_id]['min'] = $this->language->get('error_numeric');
						}
						
						if ($validate_info['max'] != '' && !is_numeric($validate_info['max'])) {
							$this->error['validate'][$validate_id]['max'] = $this->language->get('error_numeric');
						}
						
						if (is_numeric($validate_info['min']) && is_numeric($validate_info['max'])) {
							
							if ($validate_info['min'] > $validate_info['max']) {
								$this->error['validate'][$validate_id]['max'] = $this->language->get('error_length_max');
							}
														
						}
					
						if (utf8_strlen(trim($validate_info['error_message'])) < 1 || utf8_strlen(trim($validate_info['error_message'])) > 128) {
							$this->error['validate'][$validate_id]['error_message'] = $this->language->get('error_error_message');
						}
					
						break;
					case 'url':
					case 'plain_text':
					case 'specific_value':
					case 'words_blacklist':
					
						if (utf8_strlen(trim($validate_info['error_message'])) < 1 || utf8_strlen(trim($validate_info['error_message'])) > 128) {
							$this->error['validate'][$validate_id]['error_message'] = $this->language->get('error_error_message');
						}
					
						break;
					case 'email':
					
						if ($validate_info['check_domain'] && (utf8_strlen(trim($validate_info['error_message_domain'])) < 1 || utf8_strlen(trim($validate_info['error_message_domain'])) > 128)) {
							$this->error['validate'][$validate_id]['error_message_domain'] = $this->language->get('error_error_message');
						}
					
						if (utf8_strlen(trim($validate_info['error_message'])) < 1 || utf8_strlen(trim($validate_info['error_message'])) > 128) {
							$this->error['validate'][$validate_id]['error_message'] = $this->language->get('error_error_message');
						}
					
						break;
				}
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return empty($this->error);
	}
	
	/**
	 * Валидация шаблона
	 */
	private function validateTemplateForm() {
	
		if (!$this->user->hasPermission('modify', 'module/quick_order_pro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['template']['redirect'] && trim($this->request->post['template']['success_message']) == '') {
			$this->error['success_message'] = $this->language->get('error_required');
		}
	
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return empty($this->error);
	}

	/**
	 * Валидация страницы блоков
	 */
	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'module/quick_order_pro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['quick_order_pro_module'])) {
			foreach ($this->request->post['quick_order_pro_module'] as $key => $value) {
				if (!$value['template_id']) {
					$this->error['content'][$key] = $this->language->get('error_template');
				}			
			}
		}
		
		$setting = $this->request->post['quick_order_pro_setting'];
		
		if ($setting['captcha_alphabet'] == '') {
			$this->error['captcha_alphabet'] = $this->language->get('error_option');
		}
		
		if ($setting['alert_admin'] && $setting['admin_email_type'] == 'other') {
			if ($setting['alert_email'] == '') {
				$this->error['alert_email'] = $this->language->get('error_required');
			} else {
				
				if (!filter_var($setting['alert_email'], FILTER_VALIDATE_EMAIL)) {
					$this->error['alert_email'] = $this->language->get('error_email');
				} else {
					
					$valid = true;
					
					$domain = rtrim(substr($setting['alert_email'], strpos($setting['alert_email'],'@')+1), '>');
					
					if (function_exists('checkdnsrr')) {
						$valid = checkdnsrr($domain, 'MX');
					} elseif (function_exists('getmxrr')) {
						$valid = getmxrr($domain);
					}
					
					if (!$valid) {
						$this->error['alert_email'] = sprintf($this->language->get('error_domain'), $domain);
					}
				}
				
			}
		}
		
		if ($setting['create_customer'] && empty($setting['new_customer_random_password']) && $setting['new_customer_password'] != '') {
			
			if (utf8_strlen(trim($setting['new_customer_password'])) < 6 || utf8_strlen(trim($setting['new_customer_password'])) > 32) {
				$this->error['new_customer_password'] = $this->language->get('error_password');
			}
			
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return empty($this->error);
	}
	
	private function getFieldTypes() {
		
		static $types = FALSE;
		
		if (!$types) {
		
			$this->load->language('module/quick_order_pro');
		
			$types = array(
				'text' => array(
					'description' => $this->language->get('text_type_text')
				),
				'textarea' => array(
					'description' => $this->language->get('text_type_textarea')
				),
				'select' => array(
					'description' => $this->language->get('text_type_select')
				),
				'checkbox' => array(
					'description' => $this->language->get('text_type_checkbox')
				),
				'radio' => array(
					'description' => $this->language->get('text_type_radio')
				),
				'password' => array(
					'description' => $this->language->get('text_type_password')
				)
			);
			
		}
		
		return $types;
	}
	
	private function getValidateTypes() {
		
		static $types = FALSE;
		
		if (!$types) {
		
			$this->load->language('module/quick_order_pro');
		
			$types = array(
				'pcre' => array(
					'title' => $this->language->get('text_validate_pcre'),
					'description' => $this->language->get('text_validate_pcre_description'),
					'destination' => array('text', 'textarea', 'password')
				),
				'length' => array(
					'title' => $this->language->get('text_validate_length'),
					'description' => '',
					'destination' => array('text', 'textarea', 'password')
				),
				'number_of_words' => array(
					'title' => $this->language->get('text_validate_number_of_words'),
					'description' => '',
					'destination' => array('text', 'textarea', 'password')
				),
				'words_blacklist' => array(
					'title' => $this->language->get('text_validate_words_blacklist'),
					'description' => '',
					'destination' => array('text', 'textarea', 'password')
				),
				'specific_value' => array(
					'title' => $this->language->get('text_validate_specific_value'),
					'description' => $this->language->get('text_validate_specific_value_description'),
					'destination' => array('text', 'textarea', 'password')
				),
				'numeric' => array(
					'title' => $this->language->get('text_validate_numeric'),
					'description' => $this->language->get('text_validate_numeric_description'),
					'destination' => array('text', 'textarea', 'password')
				),
				'int' => array(
					'title' => $this->language->get('text_validate_int'),
					'description' => $this->language->get('text_validate_int_description'),
					'destination' => array('text', 'textarea', 'password')
				),
				'email'	=> array(
					'title' => $this->language->get('text_validate_email'),
					'description' => $this->language->get('text_validate_email_description'),
					'destination' => array('text', 'textarea')
				),
				'url' => array(
					'title' => $this->language->get('text_validate_url'),
					'description' => $this->language->get('text_validate_url_description'),
					'destination' => array('text', 'textarea')
				),
				'plain_text' => array(
					'title' => $this->language->get('text_validate_plain_text'),
					'description' => '',
					'destination' => array('text', 'textarea')
				)
			);
			
		}
		
		return $types;
	}
	
	private function getTemplate($template_id) {
		
		static $all_templates = FALSE;
		
		if (!is_array($all_templates)) {
			$all_templates = $this->getAllTemplates();
		}
		
		$real_key = $this->getRealTemplateKey($template_id);
		
		return array_key_exists($real_key, $all_templates) ? $all_templates[$real_key] : FALSE;
	}
	
	private function getAllTemplates() {
		
		static $all_templates = FALSE;
		
		if (!is_array($all_templates)) {
			
			$this->load->model('module/quick_order_pro');
			$all_templates = $this->model_module_quick_order_pro->getSetting('quick_order_pro_templates', (int)$this->config->get('config_store_id'));
					
		}
		
		return $all_templates;
	}
	
	private function saveTemplate($data) {
		
		$all_templates = $this->getAllTemplates();
		$all_templates[$this->getRealTemplateKey($data['template_id'])] = $data;
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('quick_order_pro_templates', $all_templates);
	}
	
	private function getRealTemplateKey($template_id) {
		return $this->template_key_prifix . $template_id;
	}
	
	private function getNewTemplateId() {
		
		$full_keys = array_keys($this->getAllTemplates());
		
		$keys = array(0);
		
		foreach ($full_keys as $key) {
			$keys[] = ltrim($key, $this->template_key_prifix);
		}
		
		return max($keys) + 1;
	}
	
	private function getNewCustomFieldId($code, $template_info) {
		
		$field_id = 'field_';
		
		if ($code == 'custom_field') {
		
			$fields = array();
			
			foreach ($template_info['fields'] as $field) {
				
				if ($field['code'] == 'custom_field') {
					$fields[] = (int)ltrim($field['field_id'], 'field_');
				}
				
			}
			
			if (!empty($fields)) {
				$field_id .= max($fields) + 1;
			} else {
				$field_id .= 1;
			}
		
		} else {
			$field_id .= $code;
		}
		
		return $field_id;
	}
	
	private function getShortTitle($full_name, $length = 30) {
		
		if (function_exists('mb_strlen')) {
			$strlen = mb_strlen($full_name, 'UTF-8');
		} else {
			$strlen = preg_match_all("/.{1}/us", $full_name, $match);
		}
		
		if ($strlen > $length) {
			$short_title = utf8_substr($full_name, 0, $length) . '...';
		} else {
			$short_title = $full_name;
		}
		
		return $short_title;
	}
	
	private function getMultipleLanguageValue($name, $prefix = 'text_') {
		
		$response = array();
		
		foreach ($this->getLanguages() as $language_id => $language) {
			$response[$language_id] = $language->get($prefix . $name);
		}
		
		return $response;
	}
	
	private function getLanguages($file = 'module/quick_order_pro') {
		
		$languages = FALSE;
		
		if (!is_array($languages)) {
			
			$this->load->library('language');
			$this->load->model('localisation/language');
			
			foreach ($this->model_localisation_language->getLanguages() as $language_info) {
				
				if (!in_array($language_info['directory'], $this->base_languages)) continue;
				
				$language = new Language($language_info['directory']);
				$language->load($file);
				
				$languages[$language_info['language_id']] = $language;
			}
			
		}
		
		return $languages;
	}
	
	private function getDefaultFields($template_id) {
		
		$fields = array();
		
		foreach ($this->required_fields as $weight => $field_name) {
			
			$field_id = 'field_' . $field_name;
			
			$field = array(
				'field_id'		=> $field_id,
				'code'			=> $field_name,
				'title'			=> $this->getMultipleLanguageValue($field_name),
				'description'	=> array(),
				'required'		=> 1,
				'base'			=> 1,
				'type'			=> array(
					'type'			=> 'text',
					'maxlength'		=> '128'
				),
				'validate'		=> array(
					'type'			=> ''
				),
				'status'		=> 1,
				'class'			=> '',
				'weight'		=> $weight
			);
			
			$fields[$field_id] = $field;
		}
		
		return $fields;
	}
	
	private function declination($number, $titles) {  
		$cases = array (2, 0, 1, 1, 1, 2);  
		return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];  
	}
	
	public function uninstall() {
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('quick_order_pro');
		$this->model_setting_setting->deleteSetting('quick_order_pro_setting');
		$this->model_setting_setting->deleteSetting('quick_order_pro_templates');
		
	}
}
?>