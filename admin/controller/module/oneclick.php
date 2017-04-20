<?php
class ControllerModuleOneClick extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/oneclick');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('oneclick', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_response'] = $this->language->get('text_response');

		if(isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/oneclick', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('module/oneclick', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();

		if(isset($this->request->post['oneclick_settings'])) {
			$this->data['oneclick_settings'] = $this->request->post['oneclick_settings'];
		} elseif($this->config->get('oneclick_settings')) {
			$this->data['oneclick_settings'] = $this->config->get('oneclick_settings');
		}

		$this->load->model('localisation/order_status');

		$total = $this->model_localisation_order_status->getTotalOrderStatuses();

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses(array('start' => 0, 'limit' => $total));


		$this->template = 'module/oneclick.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function install() {
		$this->load->language('module/oneclick');

		$this->load->model('setting/setting');

		$this->model_setting_setting->editSetting('oneclick', array('oneclick_settings' =>
															array('response_text' => $this->language->get('text_response_default'),
																  'order_status_id' => $this->config->get('config_order_status_id'))));
	}

	private function validate() {
		if(!$this->user->hasPermission('modify', 'module/oneclick')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if(!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}

?>