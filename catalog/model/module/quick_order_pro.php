<?php
class ModelModuleQuickOrderPro extends Model {
	
	public function getProductOptions($product_option_ids = array()) {
		
		$option_data = array();
		
		if (!empty($product_option_ids)) {
			
			$sql  = "SELECT po.product_option_id, ";
			$sql .= "po.option_id, ";
			$sql .= "od.name, ";
			$sql .= "o.type ";
			$sql .= "FROM " . DB_PREFIX . "product_option po ";
			$sql .= "LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) ";
			$sql .= "LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) ";
			$sql .= "WHERE po.product_option_id IN(" . implode(',', $product_option_ids) . ") AND ";
			$sql .= "od.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$query = $this->db->query($sql);
			
			foreach ($query->rows as $row) {
				$option_data[$row['product_option_id']] = $row;
			}
			
		}
		
		return $option_data;
	}
	
	public function getProductOptionValue($product_option_value_id) {
		
		$sql  = "SELECT ";
		$sql .= "ovd.name, ";
		$sql .= "pov.price, ";
		$sql .= "pov.price_prefix ";
		$sql .= "FROM " . DB_PREFIX . "product_option_value pov ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) ";
		$sql .= "WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ";
		$sql .= "ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		return $this->db->query($sql)->row;
		
	}
	
	public function getProductDiscount($product_id, $quantity) {
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		$sql = "SELECT price ";
		$sql .= "FROM " . DB_PREFIX . "product_discount ";
		$sql .= "WHERE product_id = '" . (int)$product_id . "' AND ";
		$sql .= "customer_group_id = '" . (int)$customer_group_id . "' AND ";
		$sql .= "quantity <= '" . (int)$quantity . "' AND ";
		$sql .= "((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ";
		$sql .= "ORDER BY quantity DESC, priority ASC, price ASC ";
		$sql .= "LIMIT 1";
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows) ? $query->row['price'] : FALSE;
	}
	
	public function getProductSpecial($product_id) {
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		$sql = "SELECT price ";
		$sql .= "FROM " . DB_PREFIX . "product_special ";
		$sql .= "WHERE product_id = '" . (int)$product_id . "' AND ";
		$sql .= "customer_group_id = '" . (int)$customer_group_id . "' AND ";
		$sql .= "((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ";
		$sql .= "ORDER BY priority ASC, price ASC ";
		$sql .= "LIMIT 1";
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows) ? $query->row['price'] : FALSE;
	}
	
	public function addCustomer($data) {
		
		$sql  = "INSERT INTO " . DB_PREFIX . "customer SET ";
		$sql .= "store_id = '" . (int)$this->config->get('config_store_id') . "', ";
		$sql .= "firstname = '" . $this->db->escape($data['firstname']) . "', ";
		$sql .= "lastname = '" . $this->db->escape($data['lastname']) . "', ";
		$sql .= "email = '" . $this->db->escape($data['email']) . "', ";
		$sql .= "telephone = '" . $this->db->escape($data['telephone']) . "', ";
		$sql .= "fax = '" . $this->db->escape($data['fax']) . "', ";
		$sql .= "password = '" . $this->db->escape(md5($data['password'])) . "', ";
		$sql .= "newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ";
		$sql .= "customer_group_id = '" . (int)$data['customer_group_id'] . "', ";
		$sql .= "status = '1', ";
		$sql .= "date_added = NOW()";
		
      	$this->db->query($sql);
      	
		$customer_id = $this->db->getLastId();
		
		$sql  = "INSERT INTO " . DB_PREFIX . "address SET ";
		$sql .= "customer_id = '" . (int)$customer_id . "', ";
		$sql .= "firstname = '" . $this->db->escape($data['firstname']) . "', ";
		$sql .= "lastname = '" . $this->db->escape($data['lastname']) . "', ";
		$sql .= "company = '" . $this->db->escape($data['company']) . "', ";
		$sql .= "address_1 = '" . $this->db->escape($data['address_1']) . "', ";
		$sql .= "address_2 = '" . $this->db->escape($data['address_2']) . "', ";
		$sql .= "city = '" . $this->db->escape($data['city']) . "', ";
		$sql .= "postcode = '" . $this->db->escape($data['postcode']) . "', ";
		$sql .= "country_id = '" . (int)$data['country_id'] . "', ";
		$sql .= "zone_id = '" . (int)$data['zone_id'] . "'";
			
      	$this->db->query($sql);
		
		$address_id = $this->db->getLastId();

      	$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (!$this->config->get('config_customer_approval')) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int)$customer_id . "'");
		}
		
		return $customer_id;
	}
	
	public function confirm($order_id, $order_status_id, $data) {
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		 
		if ($order_info && !$order_info['order_status_id']) {
			
			$setting = $this->getSetting();
			
			$sql  = "UPDATE `" . DB_PREFIX . "order` SET ";
			$sql .= "order_status_id = '" . (int)$order_status_id . "', ";
			$sql .= "date_modified = NOW() ";
			$sql .= "WHERE order_id = '" . (int)$order_id . "'";
			
			$this->db->query($sql);
			
			$sql  = "INSERT INTO " . DB_PREFIX . "order_history SET ";
			$sql .= "order_id = '" . (int)$order_id . "', ";
			$sql .= "order_status_id = '" . (int)$order_status_id . "', ";
			$sql .= "notify = '1', ";
			$sql .= "comment = '', ";
			$sql .= "date_added = NOW()";

			$this->db->query($sql);

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			
			if ($setting['subtract']) {
				
				if ($setting['subtract'] == 'default') {
					$subtract = ' AND subtract = "1"';
				} else {
					$subtract = '';
				}
			
				foreach ($order_product_query->rows as $order_product) {
					
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "'" . $subtract);
					
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");
				
					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "'" . $subtract);
					}
				}
				
				$this->cache->delete('product');
			}
			
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
			
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
			if ($order_status_query->num_rows) {
				$order_status = $order_status_query->row['name'];	
			} else {
				$order_status = '';
			}
			
			// Send out order confirmation mail
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/quick_order_pro');
			
			$subject = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);
			
			// HTML Mail
			$template = new Template();
			
			$template->data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
			
			$template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), $order_info['store_url'], html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
			$template->data['text_greeting_detail'] = $language->get('text_new_greeting_detail');
			$template->data['text_order_detail'] = $language->get('text_new_order_detail');
			$template->data['text_instruction'] = $language->get('text_new_instruction');
			$template->data['text_date_added'] = $language->get('text_new_date_added');
			$template->data['text_ip'] = $language->get('text_new_ip');
			$template->data['text_product'] = $language->get('text_new_product');
			$template->data['text_model'] = $language->get('text_new_model');
			$template->data['text_quantity'] = $language->get('text_new_quantity');
			$template->data['text_price'] = $language->get('text_new_price');
			$template->data['text_total'] = $language->get('text_new_total');
			$template->data['text_footer'] = $language->get('text_new_footer');

			$template->data['store_name'] = $order_info['store_name'];
			$template->data['store_url'] = $order_info['store_url'];
			
			$template->data['fields'] = array();
			
			foreach ($data['custom_fields'] as $code => $field_info) {
				
				if ($code == 'comment') continue;
				
				$template->data['fields'][] = array(
					'name'	=> $field_info['title'],
					'value'	=> $field_info['value']
				);

			}
			
			if ($setting['customer_email_show_date_added']) {			
				$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));    	
			}
			
			if ($setting['customer_email_show_ip']) {
				$template->data['ip'] = $order_info['ip'];
			}
			
			$template->data['comment'] = $order_info['comment'];
			
			$template->data['products'] = array();
				
			foreach ($order_product_query->rows as $product) {
				$option_data = array();
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value'])
						);
					} else {
						$filename = substr($option['value'], 0, strrpos($option['value'], '.'));
						
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($filename) > 20 ? utf8_substr($filename, 0, 20) . '..' : $filename)
						);	
					}
				}
			  
				$template->data['products'][] = array(
					'name'		=> $product['name'],
					'href'		=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'model'		=> $product['model'],
					'option'	=> $option_data,
					'quantity'	=> $product['quantity'],
					'price'		=> $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
					'total'		=> $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}
	
			$template->data['totals'] = $order_total_query->rows;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/quick_order_pro.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/mail/quick_order_pro.tpl');
			} else {
				$html = $template->fetch('default/template/mail/quick_order_pro.tpl');
			}
			
			// Text Mail
			$text  = sprintf($language->get('text_new_greeting'), $order_info['store_url'], html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			
			$text  .= $language->get('text_new_greeting_detail') . "\n\n";
			
			foreach ($data['custom_fields'] as $code => $field_info) {
				
				if ($code == 'comment') continue;
				
				$text .= $field_info['title']  . ': ' . $field_info['value'] . "\n";
			}
			
			$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
			$text .= "\n\n";
			
			$text .= $language->get('text_new_products') . "\n";
			
			foreach ($order_product_query->rows as $result) {
				$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
				}
			}
			
			$text .= "\n";
			
			$text .= $language->get('text_new_order_total') . "\n";
			
			foreach ($order_total_query->rows as $result) {
				$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
			}			
			
			$text .= "\n";
			
			if ($order_info['comment']) {
				$text .= $language->get('text_new_comment') . "\n\n";
				$text .= $order_info['comment'] . "\n\n";
			}
			
			$text .= $language->get('text_new_footer') . "\n\n";
		
			$mail = new Mail(); 
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');			
			$mail->setTo($order_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($order_info['store_name']);
			$mail->setSubject($subject);
			$mail->setHtml($html);
			$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
			
			if ($setting['alert_customer'] && $order_info['email'] != '') {
				$mail->send();
			}
			
			// Admin Alert Mail
			if ($setting['alert_admin']) {
				
				$subject = sprintf($language->get('text_new_subject_admin'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);
				
				// Text 
				$text  = $language->get('text_new_received') . "\n\n";
				$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
				$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
				$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
				
				$text .= $language->get('text_new_customer_fields') . "\n";
				
				foreach ($data['custom_fields'] as $code => $field_info) {
				
					if ($code == 'comment') continue;
					
					$text .= $field_info['title']  . ': ' . $field_info['value'] . "\n";
				}
				
				$text .= "\n\n";
				
				if ($setting['admin_email_send_password'] && $setting['create_customer']) {
					$text .= $language->get('text_customer_password') . ' ' . oc::registry()->message->data['customer_password'] . "\n\n";
				}
				
				$text .= $language->get('text_new_products') . "\n";
				
				foreach ($order_product_query->rows as $result) {
					$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");
					
					foreach ($order_option_query->rows as $option) {
						$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
					}
				}
				
				$text .= "\n";

				$text .= $language->get('text_new_order_total') . "\n";
				
				foreach ($order_total_query->rows as $result) {
					$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
				}			
				
				$text .= "\n";
				
				$comment = '';
				
				if ($order_info['comment'] != '') {
					$comment = ($order_info['comment'] .  "\n\n");
				}
				
				if ($comment) {
					$text .= $language->get('text_new_comment') . "\n\n";
					$text .= $comment . "\n\n";
				}
				
				if ($setting['admin_email_type'] == 'config') {
					$email_to = $this->config->get('config_email');
				} else {
					$email_to = $setting['alert_email'];
				}
				
				$mail = new Mail(); 
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');
				$mail->setTo($email_to);
				$mail->setFrom($email_to);
				$mail->setSender($order_info['store_name']);
				$mail->setSubject($subject);
				
				if ($setting['admin_email_same_customer']) {
					$mail->setHtml($html);
				}
				
				$mail->setText($text);
				$mail->send();
				
				// Send to additional alert emails
				$emails = explode(',', $setting['admin_emails']);
				
				foreach ($emails as $email) {
					if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}				
			}
			
			// Send Admins SMS if configure
			if ($this->config->get('config_sms_alert')) {
				$options = array(
					'to'       => $this->config->get('config_sms_to'),
					'copy'     => $this->config->get('config_sms_copy'),
					'from'     => $this->config->get('config_sms_from'),
					'username' => $this->config->get('config_sms_gate_username'),
					'password' => $this->config->get('config_sms_gate_password'),
					'message'  => str_replace(array('{ID}', '{DATE}', '{TIME}', '{SUM}', '{PHONE}'), 
											  array($order_id, date('d.m.Y'), date('H:i'), floatval($order_info['total']), $order_info['telephone']), 
											  $this->config->get('config_sms_message'))
				);

				$this->load->library('sms');

				$sms = new Sms($this->config->get('config_sms_gatename'), $options);
				$sms->send();
			}
			
		}
	}
	
	private function getSetting() {
		return $this->config->get('quick_order_pro_setting');
	}
}
?>