<?php
class ControllerPaymentCashplusplus extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('payment/cash_plusplus');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('cash_plusplus', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->data['token'] = $this->session->data['token'];
		$this->data['entry_inn'] = $this->language->get('entry_inn');
		$this->data['entry_rs'] = $this->language->get('entry_rs');
		$this->data['entry_bankuser'] = $this->language->get('entry_bankuser');
		$this->data['entry_bik'] = $this->language->get('entry_bik');
		$this->data['entry_ks'] = $this->language->get('entry_ks');
		$this->data['entry_kpp'] = $this->language->get('entry_kpp');
		$this->data['entry_tel'] = $this->language->get('entry_tel');
		$this->data['entry_ur'] = $this->language->get('entry_ur');
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');

		$this->data['entry_bank'] = $this->language->get('entry_bank');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
    	$this->data['entry_order_on_status'] = $this->language->get('entry_order_on_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
	    $this->data['entry_cash_plusplus_email_attach'] = $this->language->get('entry_cash_plusplus_email_attach');
	    $this->data['entry_cash_plusplus_nds'] = $this->language->get('entry_nds');
	    $this->data['text_yes'] = $this->language->get('text_yes');
	    $this->data['text_no'] = $this->language->get('text_no');
	    $this->data['entry_etext'] = $this->language->get('entry_etext');
	    $this->data['entry_comment'] = $this->language->get('entry_comment');
	    $this->data['entry_copey'] = $this->language->get('entry_copey');
	    $this->data['entry_bank_instruction'] = $this->language->get('entry_bank_instruction');
	    $this->data['entry_instruction'] = $this->language->get('entry_instruction');
	    $this->data['entry_invoi'] = $this->language->get('entry_invoi');
    	$this->data['entry_invoi_zakaz'] = $this->language->get('entry_invoi_zakaz');
    	$this->data['entry_invoi_noinvoice'] = $this->language->get('entry_invoi_noinvoice');
    	$this->data['entry_invoi_zakazd'] = $this->language->get('entry_invoi_zakazd');
    	$this->data['entry_invoi_zakazdc'] = $this->language->get('entry_invoi_zakazdc');
    	$this->data['entry_invoi_noinvoiced'] = $this->language->get('entry_invoi_noinvoiced');
    	$this->data['entry_invoi_noinvoicedc'] = $this->language->get('entry_invoi_noinvoicedc');
	    $this->data['entry_chelpay'] = $this->language->get('entry_chelpay');
	    $this->data['entry_chelpay_custom'] = $this->language->get('entry_chelpay_custom');
	    $this->data['entry_chelpay_fio'] = $this->language->get('entry_chelpay_fio');
	    $this->data['entry_chelpay_company'] = $this->language->get('entry_chelpay_company');
	    $this->data['entry_chelpay_company_fio'] = $this->language->get('entry_chelpay_company_fio');
	    $this->data['entry_chelpay_fio_company'] = $this->language->get('entry_chelpay_fio_company');
	    $this->data['entry_gruzpay'] = $this->language->get('entry_gruzpay');
	    $this->data['entry_gruzpay_fio'] = $this->language->get('entry_gruzpay_fio');
	    $this->data['entry_gruzpay_company'] = $this->language->get('entry_gruzpay_company');
	    $this->data['entry_gruzpay_company_fio'] = $this->language->get('entry_gruzpay_company_fio');
	    $this->data['entry_gruzpay_fio_company'] = $this->language->get('entry_gruzpay_fio_company');
	    $this->data['entry_gruzpay_custom'] = $this->language->get('entry_gruzpay_custom');
	    $this->data['entry_custom'] = $this->language->get('entry_custom');
	    $this->data['entry_custom_opis'] = $this->language->get('entry_custom_opis');
	    $this->data['entry_custom_text'] = $this->language->get('entry_custom_text');
	    $this->data['entry_custom_text_2'] = $this->language->get('entry_custom_text_2');
	    $this->data['entry_image'] = $this->language->get('entry_image');
	    $this->data['entry_logo'] = $this->language->get('entry_logo');
	    $this->data['text_browse'] = $this->language->get('text_browse');
	    $this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['bank_' . $language['language_id']])) {
				$this->data['error_bank_' . $language['language_id']] = $this->error['bank_' . $language['language_id']];
			} else {
				$this->data['error_bank_' . $language['language_id']] = '';
			}

			if (isset($this->error['inn_' . $language['language_id']])) {
				$this->data['error_inn_' . $language['language_id']] = $this->error['inn_' . $language['language_id']];
			} else {
				$this->data['error_inn_' . $language['language_id']] = '';
			}

			if (isset($this->error['rs_' . $language['language_id']])) {
				$this->data['error_rs_' . $language['language_id']] = $this->error['rs_' . $language['language_id']];
			} else {
				$this->data['error_rs_' . $language['language_id']] = '';
			}

			if (isset($this->error['bankuser_' . $language['language_id']])) {
				$this->data['error_bankuser_' . $language['language_id']] = $this->error['bankuser_' . $language['language_id']];
			} else {
				$this->data['error_bankuser_' . $language['language_id']] = '';
			}

			if (isset($this->error['bik_' . $language['language_id']])) {
				$this->data['error_bik_' . $language['language_id']] = $this->error['bik_' . $language['language_id']];
			} else {
				$this->data['error_bik_' . $language['language_id']] = '';
			}

			if (isset($this->error['ks_' . $language['language_id']])) {
				$this->data['error_ks_' . $language['language_id']] = $this->error['ks_' . $language['language_id']];
			} else {
				$this->data['error_ks_' . $language['language_id']] = '';
			}

			if (isset($this->error['ur_' . $language['language_id']])) {
				$this->data['error_ur_' . $language['language_id']] = $this->error['ur_' . $language['language_id']];
			} else {
				$this->data['error_ur_' . $language['language_id']] = '';
			}
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/cash_plusplus', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/cash_plusplus', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		foreach ($languages as $language) {
			//название организации
			if (isset($this->request->post['cash_plusplus_bank_' . $language['language_id']])) {
				$this->data['cash_plusplus_bank_' . $language['language_id']] = $this->request->post['cash_plusplus_bank_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_bank_' . $language['language_id']] = $this->config->get('cash_plusplus_bank_' . $language['language_id']);
			}
			//ИНН
			if (isset($this->request->post['cash_plusplus_inn_' . $language['language_id']])) {
				$this->data['cash_plusplus_inn_' . $language['language_id']] = $this->request->post['cash_plusplus_inn_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_inn_' . $language['language_id']] = $this->config->get('cash_plusplus_inn_' . $language['language_id']);
			}
			//Расчетный счет
			if (isset($this->request->post['cash_plusplus_rs_' . $language['language_id']])) {
				$this->data['cash_plusplus_rs_' . $language['language_id']] = $this->request->post['cash_plusplus_rs_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_rs_' . $language['language_id']] = $this->config->get('cash_plusplus_rs_' . $language['language_id']);
			}
			//Наименование банка получателя платежа
			if (isset($this->request->post['cash_plusplus_bankuser_' . $language['language_id']])) {
				$this->data['cash_plusplus_bankuser_' . $language['language_id']] = $this->request->post['cash_plusplus_bankuser_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_bankuser_' . $language['language_id']] = $this->config->get('cash_plusplus_bankuser_' . $language['language_id']);
			}
			//БИК
			if (isset($this->request->post['cash_plusplus_bik_' . $language['language_id']])) {
				$this->data['cash_plusplus_bik_' . $language['language_id']] = $this->request->post['cash_plusplus_bik_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_bik_' . $language['language_id']] = $this->config->get('cash_plusplus_bik_' . $language['language_id']);
			}
			//Номер кор./сч. банка получателя платежа
			if (isset($this->request->post['cash_plusplus_ks_' . $language['language_id']])) {
				$this->data['cash_plusplus_ks_' . $language['language_id']] = $this->request->post['cash_plusplus_ks_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_ks_' . $language['language_id']] = $this->config->get('cash_plusplus_ks_' . $language['language_id']);
			}
			//КПП
			if (isset($this->request->post['cash_plusplus_kpp_' . $language['language_id']])) {
				$this->data['cash_plusplus_kpp_' . $language['language_id']] = $this->request->post['cash_plusplus_kpp_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_kpp_' . $language['language_id']] = $this->config->get('cash_plusplus_kpp_' . $language['language_id']);
			}
			//Инструкция по переводу
		      if (isset($this->request->post['cash_plusplus_instruction_' . $language['language_id']])) {
		      $this->data['cash_plusplus_instruction_' . $language['language_id']] = $this->request->post['cash_plusplus_instruction_' . $language['language_id']];
				} else {
					$this->data['cash_plusplus_instruction_' . $language['language_id']] = $this->config->get('cash_plusplus_instruction_' . $language['language_id']);
				}
			//Телефон
				if (isset($this->request->post['cash_plusplus_tel_' . $language['language_id']])) {
				$this->data['cash_plusplus_tel_' . $language['language_id']] = $this->request->post['cash_plusplus_tel_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_tel_' . $language['language_id']] = $this->config->get('cash_plusplus_tel_' . $language['language_id']);
			}
			//Текст
			if (isset($this->request->post['cash_plusplus_etext_' . $language['language_id']])) {
				$this->data['cash_plusplus_etext_' . $language['language_id']] = $this->request->post['cash_plusplus_etext_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_etext_' . $language['language_id']] = $this->config->get('cash_plusplus_etext_' . $language['language_id']);
			}
			//Комментарий
			if (isset($this->request->post['cash_plusplus_comment_' . $language['language_id']])) {
				$this->data['cash_plusplus_comment_' . $language['language_id']] = $this->request->post['cash_plusplus_comment_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_comment_' . $language['language_id']] = $this->config->get('cash_plusplus_comment_' . $language['language_id']);
			}
			//Юр адрес
			if (isset($this->request->post['cash_plusplus_ur_' . $language['language_id']])) {
				$this->data['cash_plusplus_ur_' . $language['language_id']] = $this->request->post['cash_plusplus_ur_' . $language['language_id']];
			} else {
				$this->data['cash_plusplus_ur_' . $language['language_id']] = $this->config->get('cash_plusplus_ur_' . $language['language_id']);
			}
		}



		$this->data['languages'] = $languages;
    
	    	if (isset($this->request->post['cash_plusplus_email_attach'])) {
				$this->data['cash_plusplus_email_attach'] = $this->request->post['cash_plusplus_email_attach'];
			} else {
				$this->data['cash_plusplus_email_attach'] = $this->config->get('cash_plusplus_email_attach');
			}
				// Текст Ндс
			if (isset($this->request->post['cash_plusplus_nds'])) {
				$this->data['cash_plusplus_nds'] = $this->request->post['cash_plusplus_nds'];
			} else {
				$this->data['cash_plusplus_nds'] = $this->config->get('cash_plusplus_nds');
			}
			//Логотип
			if (isset($this->request->post['cash_plusplus_logo'])) {
				$this->data['cash_plusplus_logo'] = $this->request->post['cash_plusplus_logo'];
			} else {
				$this->data['cash_plusplus_logo'] = $this->config->get('cash_plusplus_logo');
			}
			//Изображение
			if (isset($this->request->post['cash_plusplus_image'])) {
				$this->data['cash_plusplus_image'] = $this->request->post['cash_plusplus_image'];
			} else {
				$this->data['cash_plusplus_image'] = $this->config->get('cash_plusplus_image');
			}
    //Инструкция по переводу
    if (isset($this->request->post['bank_instruction_attach'])) {
			$this->data['cash_plusplus_instruction_attach'] = $this->request->post['cash_plusplus_instruction_attach'];
		} else {
			$this->data['cash_plusplus_instruction_attach'] = $this->config->get('cash_plusplus_instruction_attach');
		}

	//Свое поле
	if (isset($this->request->post['cash_plusplus_custom'])) {
			$this->data['cash_plusplus_custom'] = $this->request->post['cash_plusplus_custom'];
		} else {
			$this->data['cash_plusplus_custom'] = $this->config->get('cash_plusplus_custom');
		}

	if (isset($this->request->post['cash_plusplus_custom_2'])) {
			$this->data['cash_plusplus_custom_2'] = $this->request->post['cash_plusplus_custom_2'];
		} else {
			$this->data['cash_plusplus_custom_2'] = $this->config->get('cash_plusplus_custom_2');
		}

	//Добавление копеек
	if (isset($this->request->post['cash_plusplus_copey'])) {
			$this->data['cash_plusplus_copey'] = $this->request->post['cash_plusplus_copey'];
		} else {
			$this->data['cash_plusplus_copey'] = $this->config->get('cash_plusplus_copey');
		}     

	//Вариант отображения счета
		if (isset($this->request->post['cash_plusplus_invoi'])) {
			$this->data['cash_plusplus_invoi'] = $this->request->post['cash_plusplus_invoi'];
		} else {
			$this->data['cash_plusplus_invoi'] = $this->config->get('cash_plusplus_invoi');
		}      

	//Вариант отображения платильщика
		if (isset($this->request->post['cash_plusplus_chelpay'])) {
			$this->data['cash_plusplus_chelpay'] = $this->request->post['cash_plusplus_chelpay'];
		} else {
			$this->data['cash_plusplus_chelpay'] = $this->config->get('cash_plusplus_chelpay');
		}
	//Вариант отображения грузополучателя
		if (isset($this->request->post['cash_plusplus_gruzpay'])) {
			$this->data['cash_plusplus_gruzpay'] = $this->request->post['cash_plusplus_gruzpay'];
		} else {
			$this->data['cash_plusplus_gruzpay'] = $this->config->get('cash_plusplus_gruzpay');
		}


		if (isset($this->request->post['cash_plusplus_order_status_id'])) {
			$this->data['cash_plusplus_order_status_id'] = $this->request->post['cash_plusplus_order_status_id'];
		} else {
			$this->data['cash_plusplus_order_status_id'] = $this->config->get('cash_plusplus_order_status_id');
		}
    
    if (isset($this->request->post['cash_plusplus_order_on_status_id'])) {
			$this->data['cash_plusplus_order_on_status_id'] = $this->request->post['cash_plusplus_order_on_status_id'];
		} else {
			$this->data['cash_plusplus_order_on_status_id'] = $this->config->get('cash_plusplus_order_on_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['scash_plusplus_geo_zone_id'])) {
			$this->data['cash_plusplus_geo_zone_id'] = $this->request->post['cash_plusplus_geo_zone_id'];
		} else {
			$this->data['cash_plusplus_geo_zone_id'] = $this->config->get('cash_plusplus_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['cash_plusplus_status'])) {
			$this->data['cash_plusplus_status'] = $this->request->post['cash_plusplus_status'];
		} else {
			$this->data['cash_plusplus_status'] = $this->config->get('cash_plusplus_status');
		}

		if (isset($this->request->post['cash_plusplus_sort_order'])) {
			$this->data['cash_plusplus_sort_order'] = $this->request->post['cash_plusplus_sort_order'];
		} else {
			$this->data['cash_plusplus_sort_order'] = $this->config->get('cash_plusplus_sort_order');
		}

		$this->template = 'payment/cash_plusplus.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cash_plusplus')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (!$this->request->post['cash_plusplus_bank_' . $language['language_id']]) {
				$this->error['bank_' .  $language['language_id']] = $this->language->get('error_bank');
			}
			if (!$this->request->post['cash_plusplus_inn_' . $language['language_id']]) {
				$this->error['inn_' .  $language['language_id']] = $this->language->get('error_bank');
			}
			if (!$this->request->post['cash_plusplus_rs_' . $language['language_id']]) {
				$this->error['rs_' .  $language['language_id']] = $this->language->get('error_bank');
			}
			if (!$this->request->post['cash_plusplus_bankuser_' . $language['language_id']]) {
				$this->error['bankuser_' .  $language['language_id']] = $this->language->get('error_bank');
			}
			if (!$this->request->post['cash_plusplus_bik_' . $language['language_id']]) {
				$this->error['bik_' .  $language['language_id']] = $this->language->get('error_bank');
			}
			if (!$this->request->post['cash_plusplus_ks_' . $language['language_id']]) {
				$this->error['ks_' .  $language['language_id']] = $this->language->get('error_bank');
			}
			if (!$this->request->post['cash_plusplus_ur_' . $language['language_id']]) {
				$this->error['ur_' .  $language['language_id']] = $this->language->get('error_bank');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>