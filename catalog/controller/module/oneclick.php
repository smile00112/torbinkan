<?
class ControllerModuleOneClick extends Controller {
	public function order() {

		$this->load->model('setting/extension');
		$this->load->model('catalog/product');

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		if (isset($this->request->post['option'])) {
			$options = array_filter($this->request->post['option']);
		} else {
			$options = array();
		}
		$option_price = 0;
		$option_points = 0;
		$option_weight = 0;

		$option_data = array();
		$product_id = (int)$this->request->post['product_id'];
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		foreach ($options as $product_option_id => $option_value) {
			$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . $product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			if ($option_query->num_rows) {
				if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
					$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_value_query->num_rows) {
						if ($option_value_query->row['price_prefix'] == '+') {
							$option_price += $option_value_query->row['price'];
						} elseif ($option_value_query->row['price_prefix'] == '-') {
							$option_price -= $option_value_query->row['price'];
						}

						if ($option_value_query->row['points_prefix'] == '+') {
							$option_points += $option_value_query->row['points'];
						} elseif ($option_value_query->row['points_prefix'] == '-') {
							$option_points -= $option_value_query->row['points'];
						}

						if ($option_value_query->row['weight_prefix'] == '+') {
							$option_weight += $option_value_query->row['weight'];
						} elseif ($option_value_query->row['weight_prefix'] == '-') {
							$option_weight -= $option_value_query->row['weight'];
						}

						if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
							$stock = false;
						}

						$option_data[] = array(
							'product_option_id'       => $product_option_id,
							'product_option_value_id' => $option_value,
							'option_id'               => $option_query->row['option_id'],
							'option_value_id'         => $option_value_query->row['option_value_id'],
							'name'                    => $option_query->row['name'],
							'option_value'            => $option_value_query->row['name'],
							'type'                    => $option_query->row['type'],
							'quantity'                => $option_value_query->row['quantity'],
							'subtract'                => $option_value_query->row['subtract'],
							'price'                   => $option_value_query->row['price'],
							'price_prefix'            => $option_value_query->row['price_prefix'],
							'points'                  => $option_value_query->row['points'],
							'points_prefix'           => $option_value_query->row['points_prefix'],
							'weight'                  => $option_value_query->row['weight'],
							'weight_prefix'           => $option_value_query->row['weight_prefix']
						);
					}
				} elseif ($option_query->row['type'] == 'checkbox' && is_array($option_value)) {
					foreach ($option_value as $product_option_value_id) {
						$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($option_value_query->num_rows) {
							if ($option_value_query->row['price_prefix'] == '+') {
								$option_price += $option_value_query->row['price'];
							} elseif ($option_value_query->row['price_prefix'] == '-') {
								$option_price -= $option_value_query->row['price'];
							}

							if ($option_value_query->row['points_prefix'] == '+') {
								$option_points += $option_value_query->row['points'];
							} elseif ($option_value_query->row['points_prefix'] == '-') {
								$option_points -= $option_value_query->row['points'];
							}

							if ($option_value_query->row['weight_prefix'] == '+') {
								$option_weight += $option_value_query->row['weight'];
							} elseif ($option_value_query->row['weight_prefix'] == '-') {
								$option_weight -= $option_value_query->row['weight'];
							}

							if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
								$stock = false;
							}

							$option_data[] = array(
								'product_option_id'       => $product_option_id,
								'product_option_value_id' => $product_option_value_id,
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => $option_value_query->row['option_value_id'],
								'name'                    => $option_query->row['name'],
								'option_value'            => $option_value_query->row['name'],
								'type'                    => $option_query->row['type'],
								'quantity'                => $option_value_query->row['quantity'],
								'subtract'                => $option_value_query->row['subtract'],
								'price'                   => $option_value_query->row['price'],
								'price_prefix'            => $option_value_query->row['price_prefix'],
								'points'                  => $option_value_query->row['points'],
								'points_prefix'           => $option_value_query->row['points_prefix'],
								'weight'                  => $option_value_query->row['weight'],
								'weight_prefix'           => $option_value_query->row['weight_prefix']
							);
						}
					}
				} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
					$option_data[] = array(
						'product_option_id'       => $product_option_id,
						'product_option_value_id' => '',
						'option_id'               => $option_query->row['option_id'],
						'option_value_id'         => '',
						'name'                    => $option_query->row['name'],
						'option_value'            => $option_value,
						'type'                    => $option_query->row['type'],
						'quantity'                => '',
						'subtract'                => '',
						'price'                   => '',
						'price_prefix'            => '',
						'points'                  => '',
						'points_prefix'           => '',
						'weight'                  => '',
						'weight_prefix'           => ''
					);
				}
			}
		}
		$options = array();
		foreach($option_data as $option) {
			if($option['type'] != 'file') {
				$options[] = array(
					'product_option_id' => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'product_option_id' => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id' => $option['option_id'],
					'option_value_id' => $option['option_value_id'],
					'name' => $option['name'],
					'value' => $option['option_value'],
					'type' => $option['type']
				);
			} else {
				$encryption = new Encryption($this->config->get('config_encryption'));

				$options[] = array(
					'product_option_id' => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'product_option_id' => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id' => $option['option_id'],
					'option_value_id' => $option['option_value_id'],
					'name' => $option['name'],
					'value' => $encryption->decrypt($option['option_value']),
					'type' => $option['type']
				);
			}
		}

		$oneclick_settings = $this->config->get('oneclick_settings');

		$product = $this->model_catalog_product->getProduct($product_id);

		$discount_quantity = 1;

		$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . $product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");


		$price = $product['price'];

		if ($product_discount_query->num_rows) {
			$price = $product_discount_query->row['price'];
		}

		// Product Specials
		$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . $product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

		if ($product_special_query->num_rows) {
			$price = $product_special_query->row['price'];
		}

		$data = array();

		$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');

		if($data['store_id']) {
			$data['store_url'] = $this->config->get('config_url');
		} else {
			$data['store_url'] = HTTP_SERVER;
		}
		$data['customer_id'] = 0;
		$data['customer_group_id'] = $this->config->get('config_customer_group_id');

		if($this->customer->isLogged()) {
			$data['customer_id'] = $this->customer->getId();
			$data['customer_group_id'] = $this->customer->getCustomerGroupId();
		}

		$firstname = sprintf($this->language->get('text_one_click_firstname'), $this->request->post['phone']);
		$data['firstname'] = $firstname;
		$data['lastname'] = '';
		$data['email'] = 'null@nu.ll';
		$data['telephone'] = $this->request->post['phone'];
		$data['fax'] = "";

		$data['payment_firstname'] = $firstname;
		$data['payment_lastname'] = '';
		$data['payment_address_1'] = '';
		$data['shipping_address_1'] = '';

		$data['payment_company'] = "";
		$data['shipping_company'] = "";
		$data['payment_address_2'] = "";
		$data['payment_city'] = "";
		$data['payment_postcode'] = "";
		$data['payment_zone'] = "";
		$data['payment_zone_id'] = "";
		$data['payment_country'] = "";
		$data['payment_country_id'] = "";
		$data['payment_address_format'] = "";

		$data['shipping_firstname'] = $firstname;
		$data['shipping_lastname'] = '';
		$data['shipping_address_2'] = "";
		$data['shipping_city'] = "";
		$data['shipping_postcode'] = "";
		$data['shipping_zone'] = "";
		$data['shipping_zone_id'] = "";
		$data['shipping_country'] = "";
		$data['shipping_country_id'] = "";
		$data['shipping_method'] = '';
		$data['payment_company_id'] = "";
		$data['payment_tax_id'] = "";
		$data['payment_code in'] = "";
		$data['shipping_code'] = "";
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

		$data['vouchers'] = array();
		$data['payment_code'] = "";

		$data['payment_method'] = '';

		$data['shipping_address_format'] = '{telephone}';


        $total = ($price + $option_price) * $quantity;

		$product_data[] = array(
			'product_id' => $product['product_id'],
			'name' => $product['name'],
			'model' => $product['model'],
			'option' => $options,
			'download' => array(),
			'quantity' => $quantity,
			'subtract' => 1,
			'reward' => 0,
			'price' => ($price + $option_price),
			'total' => $total,
			'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
			'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
		);

		////////totals data
		$total_data = array();

		$this->load->language('total/sub_total');
		$total_data[] = array(
			'code' => 'sub_total',
			'title' => $this->language->get('text_sub_total'),
			'text' => $this->currency->format($total),
			'value' => $product['price'],
			'sort_order' => $this->config->get('sub_total_sort_order')
		);

		$this->language->load('checkout/checkout');

		$data['products'] = $product_data;
		$data['totals'] = $total_data;
		$data['comment'] = '';
		$data['total'] = $total;
		$data['reward'] = 0;

		if(isset($this->request->cookie['tracking'])) {
			$this->load->model('affiliate/affiliate');

			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

			if($affiliate_info) {
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

		$this->load->model('checkout/order');

		$order_id = $this->model_checkout_order->addOrder($data);
		$this->model_checkout_order->confirm($order_id, $oneclick_settings['order_status_id']);

		$this->response->setOutput(json_encode($oneclick_settings['response_text']));
	}

}

?>