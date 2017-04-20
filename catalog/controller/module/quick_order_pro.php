<?php 
class ControllerModuleQuickOrderPro extends Controller { 
	
	private $error = array();
	private $available_types = array(0, 1); // 0 - other, 1 - product
	private $available_actions = array('p', 'o'); // p - load price, o - create order
	private $form_id;
	private $setting;
	static private $item_id = 0;
	
	public function __construct($registry) {
		
		parent::__construct($registry);
		
		require_once dirname(__FILE__) . '/includes/' . 'class.oc.php';
		oc::registry()->create($registry);
		
		$this->init();
	}
	
	public function __destruct() {
		$this->destruct();
	}
	
	public function index($module = array()) {
		
		self::$item_id++;
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		if (!empty($this->setting['customer_group']) && !in_array($customer_group_id, $this->setting['customer_group'])) {
			return;
		} elseif (!$this->setting['guest_checkout'] && !$this->customer->isLogged()) {
			return;
		} elseif (!is_null($this->setting['autch_checkout']) && !$this->setting['autch_checkout'] && $this->customer->isLogged()) {
			return;
		}
		
		$template_info = $this->getTemplate($module['template_id']);
		
		if (!$template_info) return;
		
		if (!array_key_exists('store', $template_info) || !in_array($this->config->get('config_store_id'), $template_info['store'])) {
			return;
		}
		
		if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/product') {
			
			if (isset($this->request->get['product_id'])) {
				$product_id = $this->request->get['product_id'];
			} else {
				$product_id = 0;
			}
			
			$this->load->model('catalog/product');
		
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$this->data['type'] = '1';
			} else {
				return; // product not found
			}
			
		} elseif (!$this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
	  		return; // cart empty
    	} else {
			$this->data['type'] = '0';
		}
		
		$position = (in_array($module['position'], array('column_left', 'column_right'))) ? 'sidebar' : 'content';
		
		$key = implode(id::$separator, array($module['template_id'], $this->data['type'], (int)($position == 'content'), self::$item_id, $module['layout_id']));
		
		oc::registry()->message->data['form_id'] = $this->data['form_id'] = $this->form_id = id::encode($key);
		
		$this->language->load('module/quick_order_pro');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			
			$this->doCreateOrder($this->request->post);
			
			if ($template_info['redirect']) {
				$redirect = $this->url->link('checkout/success');
			} else {
				$redirect = $this->request->server['HTTP_REFERER'];
				message::setState('success', $template_info['success_message'], $this->form_id, 'success');
				
				
				$this->session->data['quick_order_pro_success'][$this->form_id] = $template_info['success_message'];
			}
			
			$this->redirect($redirect);
		}
		
		$this->data['total'] = false;
		
		$this->data['fields'] = field::renderFields($template_info['fields']);
		
		if ($this->data['type'] == '1') { // product
			
			if ($product_info['quantity'] <= 0 && !$template_info['stock_checkout']) {
				return;
			}
			
			$this->data['product_info'] = array(
				'name'		=> $product_info['name'],
				'option'	=> array(),
				'quantity'	=> $product_info['minimum'],
			);
			
			if ($template_info['use_option'] && $template_info['show_option']) {
			
				foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				
					if (in_array($option['type'], array('text', 'textarea', 'date', 'datetime', 'time')) && !empty($option['option_value'])) {
						
						$this->data['product_info']['option'][] = array(
							'name'	=> $option['name'],
							'value'	=> (utf8_strlen($option['option_value']) > 20 ? utf8_substr($option['option_value'], 0, 20) . '..' : $option['option_value'])
						);
						
					}
				}
			
			}
			
			if ($template_info['show_total']) {
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $product_info['price'];
				}
				
				if ((float)$product_info['special']) {
					$total = $product_info['special'];
				}
				
				$quantity = $product_info['minimum'];
				
				if ($total && $quantity) {
					$this->data['total'] = $this->currency->format($this->calculateTax($total, $quantity, $product_info['tax_class_id']));
				}
			}
			
		} else {
			
			if ($template_info['show_total']) {
				
				// Totals
				$this->load->model('setting/extension');
				
				$total_data = array();					
				$total = 0;
				$taxes = $this->cart->getTaxes();
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$sort_order = array(); 
					
					$results = $this->model_setting_extension->getExtensions('total');
					
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}
					
					array_multisort($sort_order, SORT_ASC, $results);
					
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);
				
							$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
								
					}
				}
				
				$this->data['total'] = $this->currency->format($total);
			}
			
		}
		
		$this->document->addScript('catalog/view/javascript/quick_order_pro/quick_order_pro.js');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/quick_order_pro.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/quick_order_pro.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/quick_order_pro.css');
		}
		
		$language_id = $this->config->get('config_language_id');
		
		$this->data['text_process'] = $this->language->get('text_process');
		$this->data['text_required'] = $this->language->get('text_required');
		$this->data['text_total'] = $this->language->get('text_total');
		
		if (!empty($template_info['button'][$language_id])) {
			$this->data['button_send'] = $template_info['button'][$language_id];
		} else {
			$this->data['button_send'] = $this->language->get('button_send');
		}
		
		if (array_key_exists($language_id, $template_info['title']) && $this->_stripText($template_info['title'][$language_id]) != '') {
			$this->data['heading_title'] = $template_info['title'][$language_id];
		} else {
			$this->data['heading_title'] = FALSE;
		}
		
		if (array_key_exists($language_id, $template_info['description']) && $this->_stripText($template_info['description'][$language_id]) != '') {
			$this->data['description'] = html_entity_decode($template_info['description'][$language_id], ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['description'] = FALSE;
		}
		
		$class = $position;
		
		if ($this->_stripText($template_info['class']) != '') {
			$class .= ' ' . $this->_stripText($template_info['class']);
		}
		
		$this->data['class'] = $class;
		$this->data['template_info'] = $template_info;
		$this->data['item_id'] = self::$item_id;
		
		if (!empty(oc::registry()->message->data['error'])) {
			
			if (array_key_exists('fields', oc::registry()->message->data['error'])) {
				unset(oc::registry()->message->data['error']['fields']);
			}
			
			$this->data['error'] = oc::registry()->message->data['error'];
		} else {
			$this->data['error'] = NULL;
		}
		
		$this->data['success'] = message::getState('success', $this->form_id, 'success');

		$this->template = tpl::find($position, id::decode($this->form_id));
		
		$this->render();		
	}
	
	private function calculateTax($total, $quantity, $tax_class_id) {
		
		if (strpos(VERSION, '1.5.1') !== FALSE) {
			return $this->tax->calculate($total * $quantity, $tax_class_id, $this->config->get('config_tax'));
		}
		
		$taxes = array();
					
		$tax_rates = $this->tax->getRates($total, $tax_class_id);
		
		foreach ($tax_rates as $tax_rate) {
			
			if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
				$taxes[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $quantity);
			} else {
				$taxes[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $quantity);
			}
			
		}
		
		$total *= $quantity;
		
		foreach ($taxes as $key => $value) {
			if ($value > 0) {
				$total += $value;
			}
		}
		
		return $total;
	}
	
	public function captcha() {
		
		if (!isset($this->request->get['key']) || !$form_info = id::decode($this->request->get['key'])) {
			return;
		}
		
		if (isset($this->request->get['a']) && $this->request->get['a'] == 'reload') {
			
			$json = array(
				'url' => str_replace('&amp;', '&', $this->url->link('module/quick_order_pro/captcha', 'key=' . $this->request->get['key'] . '&r=' . uniqid()))
			);
			
			$this->response->setOutput(json_encode($json));
			
		} else {
			
			$setting = $this->getSetting();
			
			$captcha = new Captcha($setting['captcha_alphabet'], (int)$setting['captcha_count_items']);
			$captcha->line_color = explode(',', $setting['color_line']);
			$captcha->text_color = explode(',', $setting['captcha_color']);
			$captcha->showLine = (int)$setting['show_lines'];
			
			if ($form_info['position'] == 0) { // content
				$captcha->width	= 110;
				$captcha->height = 40;
			}
			
			if (!array_key_exists('quick_order_pro_captcha', $this->session->data)) {
				$this->session->data['quick_order_pro_captcha'] = array();
			}
			
			$this->session->data['quick_order_pro_captcha'][$this->request->get['key']] = $captcha->getCode();
			
			$captcha->showImage();
		}
	}
	
	public function ajax() {
		
		if ($this->isAjax() && $this->request->server['REQUEST_METHOD'] == 'POST') { // ajax only
		
			$json = array();
			
			$status = 'ok';
			
			if ($this->checkRequest()) {
				
				switch ($this->request->post['a']) {
					case 'p':
						$json += $this->getPrice();
						break;
					case 'o':
						$json += $this->_createOrder();
						break;
				}
				
			}
			
			if (!empty(oc::registry()->message->data['error'])) {
				
				$status = 'error';
				
				$json += array(
					'error' => oc::registry()->message->data['error']
				);
				
			} else {
				
			}
			
			$json['status'] = $status;
			
			$this->response->setOutput(json_encode($json));
			
		} else {
			return $this->forward('error/not_found');
		}
		
	}
	
	private function getTemplate($template_id) {
		return $this->config->get('quick_order_pro_template_' . $template_id);
	}
	
	private function _stripText($text) {
		
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		$text = strip_tags($text);
		$text = trim($text);
		
		return $text;
	}
	
	private function _getOptionValue($product_option_value_id) {
		
		$this->load->model('module/quick_order_pro');
		
		$data = array();
		
		$option_value_info = $this->model_module_quick_order_pro->getProductOptionValue($product_option_value_id);
		
		if ($option_value_info) {
			
			$data = array(
				'price'			=> $option_value_info['price'],
				'price_prefix'	=> $option_value_info['price_prefix'],
				'value'			=> (utf8_strlen($option_value_info['name']) > 20 ? utf8_substr($option_value_info['name'], 0, 20) . '..' :$option_value_info['name'])
			);								
		}
		
		return $data;
	}
	
	private function getPrice() {
		
		$this->language->load('module/quick_order_pro');
		$this->load->model('catalog/product');
		$this->load->model('module/quick_order_pro');
		
		$data = array();
		
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {
			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
			
			if (is_numeric($quantity) && $quantity > 0) {
				
				$option_price = 0;
				
				if (isset($this->request->post['option'])) {
					$options = array_filter($this->request->post['option']);
				} else {
					$options = array();
				}
				
				$product_option_ids = array_keys($options);
				$product_option_ids = array_map('intval', $product_option_ids);
				
				$product_options = $this->model_module_quick_order_pro->getProductOptions($product_option_ids);
				
				foreach ($options as $product_option_id => $option_value) {
					
					if (array_key_exists($product_option_id, $product_options)) {
						
						$option_info = $product_options[$product_option_id];
					
						if (in_array($option_info['type'], array('select', 'radio', 'image'))) {
							
							$option_value_info = $this->_getOptionValue($option_value);
							
							if ($option_value_info) {
								
								if ($option_value_info['price_prefix'] == '+') {
									$option_price += $option_value_info['price'];
								} elseif ($option_value_info['price_prefix'] == '-') {
									$option_price -= $option_value_info['price'];
								}
								
								$data['options'][] = array(
									'name'	=> $option_info['name'],
									'value'	=> $option_value_info['value']
								);
								
							}
							
						} elseif ($option_info['type'] == 'checkbox' && is_array($option_value)) {
							
							foreach ($option_value as $product_option_value_id) {
								
								$option_value_info = $this->_getOptionValue($product_option_value_id);
								
								if ($option_value_info) {
									
									if ($option_value_info['price_prefix'] == '+') {
										$option_price += $option_value_info['price'];
									} elseif ($option_value_info['price_prefix'] == '-') {
										$option_price -= $option_value_info['price'];
									}
									
									$data['options'][] = array(
										'name'	=> $option_info['name'],
										'value'	=> $option_value_info['value']
									);
									
								}
								
							}
												
						} elseif (in_array($option_info['type'], array('text', 'textarea', 'file', 'date', 'datetime', 'time'))) {
							
							if ($option_info['type'] != 'file') {
								$option_value = $option_value;
							} else {
								$this->load->library('encryption');
						
								$encryption = new Encryption($this->config->get('config_encryption'));
								$option_value = substr($encryption->decrypt($option_value), 0, strrpos($encryption->decrypt($option_value), '.'));
							}
							
							$data['options'][] = array(
								'name'	=> $option_info['name'],
								'value'	=> (utf8_strlen($option_value) > 20 ? utf8_substr($option_value, 0, 20) . '..' : $option_value)
							);						
						}
					}
				}
				
				$total = $product_info['price'];
				
				$discount = $this->model_module_quick_order_pro->getProductDiscount($product_id, $quantity);
				
				if ($discount !== FALSE) {
					$total = $discount;
				}
				
				$special = $this->model_module_quick_order_pro->getProductSpecial($product_id);
				
				if ($special !== FALSE) {
					$total = $special;
				}
				
				$total += $option_price;
				
				$data['quantity'] = $quantity;
				
				$taxes = array();
				
				$tax_rates = $this->tax->getRates($total, $product_info['tax_class_id']);
				
				foreach ($tax_rates as $tax_rate) {
					
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $quantity);
					} else {
						$taxes[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $quantity);
					}
					
				}
				
				$total *= $quantity;
				
				foreach ($taxes as $key => $value) {
					if ($value > 0) {
						$total += $value;
					}
				}
					
				$data['total'] = $this->currency->format($total);
				
			} else {
				$this->error['quantity'] = $this->language->get('error_quantity');
			}
		
		} else {
			$this->error['product'] = $this->language->get('error_product');
		}
		
		return $data;
		
	}
	
	private function isAjax() {
		return (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && $this->request->server['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}
	
	private function validate() {
		
		$post = $this->request->post;
		
		if (!array_key_exists('form_id', $this->request->post) || !$setting = id::decode($this->request->post['form_id'])) {
			oc::registry()->message->data['error']['warning'] = $this->language->get('error_system_fields');
		} else {
			
			oc::registry()->message->data['form_id'] = $form_id = $this->request->post['form_id'];
			
			if (isset($this->form_id) && $this->form_id != $form_id) return;
		
			$template_info = $this->getTemplate($setting['template_id']);
			
			if ($setting['type'] == '0') { // cart, category...
				
				if (!$this->cart->hasStock() && (!$template_info['stock_checkout'])) {
					oc::registry()->message->data['error']['warning'] = $this->language->get('error_stock');
				}
				
				if ($template_info['minimum']) {
				
					$products = $this->cart->getProducts();
					
					foreach ($products as $product) {
						$product_total = 0;
							
						foreach ($products as $product_2) {
							if ($product_2['product_id'] == $product['product_id']) {
								$product_total += $product_2['quantity'];
							}
						}			
						
						if ($product['minimum'] > $product_total) {
							oc::registry()->message->data['error']['warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
						}				
							
					}
				
				}
			
			} else { // product
				
				if ($this->isAjax()) {
					
					$this->load->model('catalog/product');
					
					if (isset($this->request->get['product_id'])) {
						$product_id = $this->request->get['product_id'];
					} elseif (isset($this->request->post['product_id'])) {
						$product_id = $this->request->post['product_id'];
					} else {
						$product_id = 0;
					}
				
					if ($template_info['minimum']) {
						
						$product_info = $this->model_catalog_product->getProduct($product_id);
						
						if (isset($this->request->post['quantity'])) {
							$quantity = $this->request->post['quantity'];
						} else {
							$quantity = 1;
						}
						
						if ($product_info['minimum'] > $quantity) {
							oc::registry()->message->data['error']['warning'] = sprintf($this->language->get('error_minimum'), $product_info['name'], $product_info['minimum']);
						}
						
					}
					
					if (strpos(VERSION, '1.6') !== FALSE) {
						
						if (isset($this->request->post['profile_id'])) {
							$profile_id = $this->request->post['profile_id'];
						} else {
							$profile_id = 0;
						}
						
						 $profiles = $this->model_catalog_product->getProfiles($product_id);
            
						if ($profiles) {
							
							$profile_ids = array();
							
							foreach ($profiles as $profile) {
								$profile_ids[] = $profile['profile_id'];
							}
							
							if (!in_array($profile_id, $profile_ids)) {
								$json['error']['profile'] = $this->language->get('error_profile_required');
							}
						}
						
					}
					
					if ($template_info['use_option'] && $template_info['option_required']) {
					
						if (isset($this->request->post['option'])) {
							$option = array_filter($this->request->post['option']);
						} else {
							$option = array();	
						}
						
						$required_options = array();
			
						$product_options = $this->model_catalog_product->getProductOptions($product_id);
						
						foreach ($product_options as $product_option) {
							
							if ($product_option['required'] && (!isset($this->request->post['option'][$product_option['product_option_id']]) || !$this->request->post['option'][$product_option['product_option_id']])) {
								$required_options[] = trim($product_option['name']);
							}
						}
						
						if (!empty($required_options)) {
							oc::registry()->message->data['error']['warning'] = sprintf($this->language->get('error_required_options'), implode(', ', $required_options));
						}
					
					}
				}
			}
			
			$language_id = $this->config->get('config_language_id');
			
			foreach ($template_info['fields'] as $field_id => $field_info) {
				
				if (!$field_info['status']) continue;				
					
				if (isset($post['fields'][$field_id]) || in_array($field_info['type']['type'], array('radio', 'checkbox'))) {
					
					$value = isset($post['fields'][$field_id]) ? $post['fields'][$field_id] : FALSE; // field value
					
					$isEmpty = ($value === FALSE || (empty($value) && $value !== '0'));
					
					if ($isEmpty) {
						
						if ($field_info['required']) {
							oc::registry()->message->data['error']['fields'][$field_id] = $this->language->get('error_required');
						}
						
					} elseif (!empty($field_info['validate']) && $validator = validator::getInstance($field_info)) {
							
						if (!$validator->validate($value)) {
							oc::registry()->message->data['error']['fields'][$field_id] = $validator->getErrorMessage();
						}
						
					}
					
				} else {
					oc::registry()->message->data['error']['system'] = sprintf($this->language->get('error_field_empty'), $field_info['title'][$language_id]);
					break;
				}
				
			}
			
		}
		
		return (bool)empty(oc::registry()->message->data['error']);
	}
	
	private function checkRequest() {
		
		if (!array_key_exists('a', $this->request->post) || !$this->validateAction($this->request->post['a'])) {
			$this->error['warning'] = $this->language->get('error_system_fields');
		}
		
		return (bool)!count($this->error);
	}
	
	private function validateAction($action) {
		return in_array($action, $this->available_actions);
	}
	
	private function _createOrder() {
		
		$this->language->load('module/quick_order_pro');
		
		$data = array();
		
		if ($this->validate()) {
			
			$setting = id::decode($this->request->post['form_id']);
			$template_info = $this->getTemplate($setting['template_id']);
			
			$this->doCreateOrder($this->request->post);
			
			if ($template_info['redirect']) {
				$data['redirect'] = str_replace('&amp;', '&', $this->url->link('checkout/success'));
			} else {
				$data['success'] = html_entity_decode($template_info['success_message'], ENT_QUOTES, 'UTF-8');
			}
			
		}
		
		return $data;
	}
	
	private function doCreateOrder($post = array()) {
		
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
		 
		$this->load->model('setting/extension');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		$this->load->model('module/quick_order_pro');
		
		$setting = id::decode($post['form_id']);
		$template_info = $this->getTemplate($setting['template_id']);
		
		$stored_cart = $this->session->data['cart'];
		
		if ($setting['type'] == 1) { // product page
			
			$this->cart->clear();
			
			if (isset($this->request->get['product_id'])) {
				$product_id = $this->request->get['product_id'];
			} elseif (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
			} else {
				$product_id = 0;
			}
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if (!$product_info) return;
			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = $product_info['minimum'];
			}
			
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();	
			}
			
			$profile_id = 0;
			
			if (strpos(VERSION, '1.6') !== FALSE) {
				
				if (isset($this->request->post['profile_id'])) {
					$profile_id = $this->request->post['profile_id'];
				}
			}
			
			$this->cart->add($product_id, $quantity, $option, $profile_id);
			
		}
		
		$sort_order = array();
		
		// Totals
		$total_data = array();					
		$total = 0;
		$taxes = $this->cart->getTaxes();
		
		$sort_order = array(); 
			
		$results = $this->model_setting_extension->getExtensions('total');
		
		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}
		
		array_multisort($sort_order, SORT_ASC, $results);
		
		foreach ($results as $result) {
			
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('total/' . $result['code']);
	
				$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
			}
			
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);			
		}
		
		$this->language->load('checkout/checkout');
		
		$data = array();
		
		$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');
		
		if ($data['store_id']) {
			$data['store_url'] = $this->config->get('config_url');		
		} else {
			$data['store_url'] = HTTP_SERVER;	
		}
		
		$language_id = $this->config->get('config_language_id');
		
		$data['custom_fields'] = array();
		
		foreach ($template_info['fields'] as $field_id => $field_info) {
			
			$isEmpty = ($value === FALSE || (empty($value) && $value !== '0'));
			
			if (isset($post['fields'][$field_id]) && !in_array($field_info['code'], array('captcha'))) {
				
				$value = $post['fields'][$field_id];
				
				$isEmpty = ($value === FALSE || (empty($value) && $value !== '0'));
				
				if (!$isEmpty) {
					
					if (in_array($field_info['type']['type'], array('select', 'radio', 'checkbox'))) {
						
						$result = array();
						
						$options = is_array($value) ? $value : array($value);
						
						foreach ($options as $option_id) {
							
							if (array_key_exists($option_id, $field_info['type']['option'])) {
								$result[] = $field_info['type']['option'][$option_id]['value'];
							}
							
						}
						
						$result = array_filter($result);
						
						if (!$result) continue;
						
						$value = implode(', ', $result);
					}
					
					$key = ($field_info['code'] == 'custom_field') ? $field_id : $field_info['code'];
					
					$data['custom_fields'][$key] = array(
						'title'		=> $field_info['title'][$language_id],
						'value'		=> $this->_stripText($value),	
						'weight'	=> $field_info['weight']
					);
				
				}
				
			}
			
		}
		
		$sort_order = array(); 
		  
		foreach ($data['custom_fields'] as $key => $value) {
			$sort_order[$key] = $value['weight'];
		}

		array_multisort($sort_order, SORT_ASC, $data['custom_fields']);	
		
		foreach (array('firstname', 'lastname', 'telephone', 'email', 'address', 'comment') as $field) {
			if (array_key_exists($field, $data['custom_fields'])) {
				$data[$field] = $data['custom_fields'][$field]['value'];
			} else {
				$data[$field] = '';
			}
		}
		
		if (!$data['email'] && $this->customer->isLogged()) {
			$data['email'] = $this->customer->getEmail();
		}
		
		if ($this->customer->isLogged()) {
			$data['customer_id'] = $this->customer->getId();
			$data['customer_group_id'] = $this->customer->getCustomerGroupId();
			$data['fax'] = $this->customer->getFax();
		} else {
			
			$data['fax'] = '';
			
			if ($this->setting['create_customer']) {
				
				$data['customer_group_id'] = $this->setting['new_customer_group'];
				
				if (!empty($this->setting['new_customer_random_password'])) {
					$password = substr(sha1(uniqid(mt_rand(), true)), 6, 32);
				} elseif ($this->setting['new_customer_password']) {
					$password = $this->setting['new_customer_password'];
				} else {
					$password = '123456';
				}
				
				oc::registry()->message->data['customer_password'] = $password;
				
				$customer_data = $data + array(
					'password' 		=> $password,
					'company'		=> '',
					'address_1'		=> '',
					'address_2'		=> '',
					'city'			=> '',
					'postcode'		=> '',
					'country_id'	=> $this->config->get('config_country_id'),
					'zone_id'		=> $this->config->get('config_zone_id')
				);
				
				$data['customer_id'] = $this->model_module_quick_order_pro->addCustomer($customer_data);
				
			} else {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			}
			
		}
		
		$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
		
		if ($country_info) {
			$country_name = $country_info['name'];
		} else {
			$country_name = '';
		}
		
		$zone_info = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));
		
		if ($zone_info) {
			$zone_name = $zone_info['name'];
		} else {
			$zone_name = '';
		}
		
		$data['payment_firstname'] = $data['shipping_firstname'] = $data['firstname'];
		$data['payment_lastname'] = $data['shipping_lastname'] = $data['lastname'];
		$data['payment_company'] = $data['shipping_company'] = '';
		$data['payment_company_id'] = '';
		$data['payment_tax_id'] = '';
		$data['payment_address_1'] = $data['shipping_address_1'] = $data['address'];
		$data['payment_address_2'] = $data['shipping_address_2'] = '';
		$data['payment_city'] = $data['shipping_city'] = '';
		$data['payment_postcode'] = $data['shipping_postcode'] = '';
		$data['payment_zone'] = $data['shipping_zone'] = $zone_name;
		$data['payment_zone_id'] = $data['shipping_zone_id'] = $this->config->get('config_zone_id');
		$data['payment_country'] = $data['shipping_country'] = $country_name;
		$data['payment_country_id'] = $data['shipping_country_id'] = $this->config->get('config_country_id');
		$data['payment_address_format'] = $data['shipping_address_format'] = '';
		$data['payment_method'] = $data['shipping_method'] = '';
		$data['payment_code'] = $data['shipping_code'] = '';
		
		$this->load->library('encryption');
		
		$product_data = array();
	
		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				
				if ($option['type'] != 'file') {
					$value = $option['option_value'];
				} else {
					$encryption = new Encryption($this->config->get('config_encryption'));
					$value = $encryption->decrypt($option['option_value']);
				}
				
				$option_data[] = array(
					'product_option_id'       => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id'               => $option['option_id'],
					'option_value_id'         => $option['option_value_id'],								   
					'name'                    => $option['name'],
					'value'                   => $value,
					'type'                    => $option['type']
				);	
				
			}
 
			$product_data[] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'subtract'   => $product['subtract'],
				'price'      => $product['price'],
				'total'      => $product['total'],
				'tax'        => $this->tax->getTax($product['total'], $product['tax_class_id']),
				'reward'     => isset($product['reward']) ? $product['reward'] : ''
			); 
		}
		 
		$data['vouchers'] = array();	
		$data['products'] = $product_data;
		$data['totals'] = $total_data;
		$data['total'] = $total;
		
		if (method_exists($this->cart, 'getTotalRewardPoints')) {
			$data['reward'] = $this->cart->getTotalRewardPoints();
		}
		
		if (isset($this->request->cookie['tracking'])) {
			$this->load->model('affiliate/affiliate');
			
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
			
			if ($affiliate_info) {
				$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
				$data['commission'] = ($total / 100) * $affiliate_info['commission']; 
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
		} else {
			$data['affiliate_id'] = 0;
			$data['commission'] = 0;
		}
		
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency_code'] = $this->currency->getCode();
		$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
		} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
		} else {
			$data['forwarded_ip'] = '';
		}
		
		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
		} else {
			$data['user_agent'] = '';
		}
		
		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
		} else {
			$data['accept_language'] = '';
		}
		
		$this->load->model('checkout/order');
		
		if (method_exists($this->model_checkout_order, 'addOrder')) {
			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($data);
		} elseif (method_exists($this->model_checkout_order, 'create')) {
			$this->session->data['order_id'] = $this->model_checkout_order->create($data);
		} else {
			$this->session->data['cart'] = $stored_cart;
			return FALSE;
		}
		
		$this->model_module_quick_order_pro->confirm($this->session->data['order_id'], $this->setting['order_status_id'], $data);
		
		if ($setting['type'] == 1) { // product page
			$this->session->data['cart'] = $stored_cart; // restore cart
		}
		
	}
	
	private function init() {
		
		$this->setting = $this->getSetting();
		
		spl_autoload_register(array($this, 'autoloader'));
		spl_autoload_extensions('.php');
		
		oc::registry()->message = new message;
	}
	
	private function destruct() {
		spl_autoload_unregister(array($this, 'autoloader'));
		spl_autoload_unregister(array('field', 'autoloader_field'));
	}
	
	private function getSetting() {
		return $this->config->get('quick_order_pro_setting');
	}
	
	static public function autoloader($name) {
		
		if (class_exists($name)) return;
		
		$file_name = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class.' . str_replace('_', '.', $name) . '.php';
		$file_name = strtolower($file_name);
		
		if (file_exists($file_name)) {
			require_once($file_name);
		} else {
			throw new Exception($file_name  . ' not found.');
		}
		
	}
	
}
?>