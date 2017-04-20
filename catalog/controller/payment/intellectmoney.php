<?php
/*
 * OpenCart
 * Модуль для подключения платежной системы IntellectMoney
 *
Last Changed Rev: 13322
Last Changed Date: 2011-12-19 09:14:49 +0400 (Mon, 19 Dec 2011)
 */
?>
<?php

class ControllerPaymentIntellectmoney extends Controller
{
	function ob_exit($status = null)
	{
		if($status) {
			ob_end_flush();
			isset($_REQUEST['debug']) ? exit($status) : exit();
		}
		else {
			ob_end_clean();
			header("HTTP/1.0 200 OK");
			echo "OK";
			exit();
		}
	}

	function debug_file()
	{
		header('Content-type: text/plain; charset=utf-8');
		echo file_get_contents(__FILE__);
	}

	function check_im()
	{
		$iplist = gethostbynamel('www.intellectmoney.ru');
		foreach($iplist as $ip)
			if(preg_match("/^$ip/", (isset($_SERVER['HTTP_REFERER']))?$_SERVER['HTTP_REFERER']:''))
				return true;
		return false;
	}
	
	function from_request($name)
	{
		return isset($_REQUEST[$name]) ? trim(stripslashes($_REQUEST[$name])) : null;
	}
	
	protected function index()
	{
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		
		$this->data['action'] = 'https://merchant.intellectmoney.ru/ru/';
	
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$products = '';
		
		foreach ($this->cart->getProducts() as $product) {
    		$products .= $product['quantity'] . ' x ' . $product['name'] . ', ';
    	}		

		
		//
		$this->data['eshopId'] = $this->config->get('intellectmoney_eshopid');
		$this->data['orderId'] = $this->session->data['order_id'];
		
		$this->data['serviceName'] = $products;
		$this->data['recipientAmount'] = number_format($order_info['total'], 2, '.', '');
		
		$this->data['recipientCurrency'] = isset($order_info['currency']) ? $order_info['currency'] : $order_info['currency_code'];
		$this->data['successUrl'] = HTTP_SERVER . 'index.php?route=checkout/success';
		$this->data['failUrl'] = HTTP_SERVER . 'index.php?route=checkout/payment';
		
				
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['cancel_return'] = HTTP_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['cancel_return'] = HTTP_SERVER . 'index.php?route=checkout/guest_step_2';
		}
				
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTP_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTP_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/intellectmoney.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/intellectmoney.tpl';
		} else {
			$this->template = 'default/template/payment/intellectmoney.tpl';
		}	
		
		$this->render();	
	}
	
	
	public function callback()
	{
		if(isset($_REQUEST['debug_file'])) {
			$this->debug_file();
			exit;
		}
		ob_start();

		$eshopId = $this->from_request('eshopId');
		$orderId = $this->from_request('orderId');
		$serviceName = $this->from_request('serviceName');
		$eshopAccount = $this->from_request('eshopAccount');
		$recipientAmount = $this->from_request('recipientAmount');
		$recipientCurrency = $this->from_request('recipientCurrency');
		$paymentStatus = $this->from_request('paymentStatus');
		$userName = $this->from_request('userName');
		$userEmail = $this->from_request('userEmail');
		$paymentData = $this->from_request('paymentData');
		$secretKey = $this->from_request('secretKey');
		$hash = $this->from_request('hash');
		
		if(!isset($_REQUEST['orderId']) ) {
			$this->ob_exit("ERROR: EMPTY REQUEST!\n");
		}
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($orderId);
		
		// ORDER ID CHECK
		if(!$order_info) {
			$this->ob_exit("ERROR: ORDER NOT EXISTS!\n");
		}
		
		$get_orderid = $order_info['order_id'];
		$get_amount = number_format($order_info['total'], 2, '.', '');
		$get_currency = strtolower(isset($order_info['currency']) ? $order_info['currency'] : $order_info['currency_code']);
		$get_currency = ($get_currency == 'rur') ? 'rub' : $get_currency;
		$get_eshopid = $this->config->get('intellectmoney_eshopid');
		$get_secret_key = $this->config->get('intellectmoney_secret_key');

		$this->model_checkout_order->confirm($get_orderid, $this->config->get('config_order_status_id'));
		
		// AMOUNT and CURRENCY CODE CHECK
		if($recipientAmount != $get_amount || strtolower($recipientCurrency) != $get_currency) {
			$err = "ERROR: AMOUNT/CURRENCY MISMATCH!\n";
			$err .= "recipientAmount: $recipientAmount; amount: $get_amount;\nrecipientCurrency: $recipientCurrency; currency: $get_currency;\n\n";
			$this->ob_exit($err);
		}
		
		// Прислали секретный ключ, проверяем
		if($secretKey)
		{
			// Проверка источника данных (по секретному ключу)
			if ($secretKey != $get_secret_key) {
				$err = "ERROR: SECRET_KEY MISMATCH!\n";
				$err .= $this->check_im() ? ("secretKey: $secretKey; IM_SECRET_KEY: $get_secret_key;\n\n") : "\n";
				$this->ob_exit($err);
			}
			
			// Проверка номера сайта продавца
			if ($eshopId != $get_eshopid) {
				$err = "ERROR: INCORRECT ESHOP_ID!\n";
				$err .= "eshopId: $eshopId; IM_ESHOP_ID: $get_eshopid;\n\n";
				$this->ob_exit($err);
			}
		}
		// Проверяем хэш
		else
		{
			$control_hash_str = implode('::', array(
				$get_eshopid,
				$get_orderid,
				$serviceName, $eshopAccount, $recipientAmount, $recipientCurrency,
				$paymentStatus, $userName, $userEmail, $paymentData,
				$get_secret_key,
			));
			$control_hash = md5($control_hash_str);
			$control_hash_urldecode = md5(urldecode($control_hash_str));
			$control_hash_utf8 = md5(iconv('windows-1251', 'utf-8', $control_hash_str));

			if (($hash != $control_hash && $hash != $control_hash_utf8 && $hash != $control_hash_urldecode) || !$hash) {
				$err = "ERROR: HASH MISMATCH\n";
				$err .= $this->check_im() ? "Control hash string: $control_hash_str;\n" : "";
				$err .= "Control hash win-1251: $control_hash;\nControl hash utf-8: $control_hash_utf8;\nhash: $hash;\n\n";
				$this->ob_exit($err);
			}
		}
		
		if($this->from_request('paymentStatus') == 3) {
			$this->model_checkout_order->update($get_orderid, $this->config->get('intellectmoney_order_status_pending_id'), 'IntellectMoney', TRUE);
			$this->ob_exit();
		}
		if($this->from_request('paymentStatus') == 5) {
			$this->model_checkout_order->update($get_orderid, $this->config->get('intellectmoney_order_status_id'), 'IntellectMoney', TRUE);
			$this->ob_exit();
		}
		
		$this->ob_exit("ERROR: UNKNOWN PAYMENT STATUS!\n\n");
	}

}
?>