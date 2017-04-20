<?php
class ControllerModuleSubscribe extends Controller {
	private $error = array();

	public function index($setting = array()) {
		if (isset($this->request->post['module'])) {
			$module = $this->request->post['module'];
		} else {
			$module = $setting['subscribe_id'];

			$this->document->addScript('catalog/view/javascript/subscribe.js');
		}

		$this->data['module'] = $module;

		$this->language->load('module/subscribe');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_subscribe'] = $this->language->get('button_subscribe');
		$this->data['text_enter_email'] = $this->language->get('text_enter_email');

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/subscribe.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/subscribe.tpl';
		} else {
			$this->template = 'default/template/module/subscribe.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function addSubscribe() {
		$json = array();

		$this->load->model('catalog/subscribe');

		$this->language->load('module/subscribe');

		$email_subscriber = $this->request->post['email'];

		if ((utf8_strlen($email_subscriber) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email_subscriber)) {
			$json['error'] = $this->language->get('error_email');
		} elseif ($this->model_catalog_subscribe->checkEmail($email_subscriber)) {
			$json['error'] = $this->language->get('error_customer');
		}

		if (!$json && isset($this->request->post['module'])) {
			$subscribes = $this->config->get('subscribe_module');

			$module = $this->request->post['module'];
			$email_alert = $subscribes[$module]['email_alert'];
			$subscribe_confirm = $subscribes[$module]['subscribe_confirm'];

			if ($subscribe_confirm) {
				$json['success'] = $this->language->get('text_subscribe_confirm');
				$status = 0;
			} else {
				$json['success'] = $this->language->get('text_success');
				$status = 1;
			}

			$data = array(
				'email' => $email_subscriber,
				'status' => $status
			);

			$this->model_catalog_subscribe->addSubscribe($data);

			$subject = sprintf($this->language->get('text_hello_subscriber'), $this->config->get('config_name'));

			/* client software does not support HTML email - derive the minimum information - $text */

			if ($subscribe_confirm) {
				$link = $this->url->link('module/subscribe/confirmSubscribe&key=' . $this->keyCoding($email_subscriber), '', 'SSL');

				$text = $this->language->get('text_subscribe_active') . $link . "\n\n";
				$html_message = sprintf($this->language->get('text_subscribe_active_html'), $link);
			} else {
				$text = sprintf($this->language->get('text_hello_subscriber'), $this->config->get('config_name')) . "\n\n";
				$html_message = sprintf($this->language->get('text_hello_subscriber'), $this->config->get('config_name'));
			}

			$text_mail = $this->model_catalog_subscribe->getAuthDescription((int) $this->config->get('config_language_id'));

			$html = '<html dir="ltr" lang="en">' . "\n";
			$html .= '  <head>' . "\n";
			$html .= '    <title>' . $subject . '</title>' . "\n";
			$html .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
			$html .= '  </head>' . "\n";
			$html .= '  <body><div>' . $html_message . '</div>' . html_entity_decode($text_mail, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
			$html .= '</html>' . "\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($email_subscriber);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setHtml($html);
			$mail->setText($text);
			$mail->send();

			if ($email_alert) {
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject(sprintf($this->language->get('text_email_subject_shop'), $this->config->get('config_name', $email_subscriber)));
				$mail->setText(sprintf($this->language->get('text_email_text'), $email_subscriber));
				$mail->send();
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function confirmSubscribe() {
		$this->load->model('catalog/subscribe');

		$this->language->load('module/subscribe');

		$this->document->setTitle($this->language->get('heading_title_confirm'));
		$this->document->setDescription($this->config->get('config_meta_description'));

		$this->data['heading_title'] = $this->language->get('heading_title_confirm');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('module/subscribe/confirmSubscribe', '', 'SSL'),
			'text' => $this->language->get('heading_title_confirm'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['text_message_confirm'] = $this->language->get('text_message_confirm');

		$this->data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['key'])) {
			$key = $this->request->get['key'];
		} else {
			$key = 0;
		}

		if ($this->keyValidate($key)) {
			$subscribe = $this->getUncheckingSubscriber($key);

			$data = array(
				'email' => $subscribe['email'],
				'status' => 1
			);

			$this->model_catalog_subscribe->editSubscribe($data);
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/subscribe.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/subscribe.tpl';
		} else {
			$this->template = 'default/template/account/subscribe.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	private function keyValidate($key) {
		$this->load->model('catalog/subscribe');

		$this->language->load('module/subscribe');

		if (isset($key) && !$this->getUncheckingSubscriber($key)) {
			$this->error['warning'] = sprintf($this->language->get('text_message_error'), $this->url->link('information/contact'));
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function getUncheckingSubscriber($key) {
		$data = array();

		foreach ($this->model_catalog_subscribe->getSubscribers() as $subscribe) {
			if ($this->keyDecoding($subscribe['email']) == $key) {
				$data = array(
					'email' => $subscribe['email']
				);

				break;
			}
		}

		return $data;
	}

	private function keyCoding($key) {
		$code_key = md5($key);

		return $code_key;
	}

	private function keyDecoding($key) {
		$decode_key = md5($key);

		return $decode_key;
	}

}

?>
