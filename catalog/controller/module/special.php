<?php
class ControllerModuleSpecial extends Controller {
	protected function index($setting) {
		$this->language->load('module/special');

      	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_cart'] = $this->language->get('button_cart');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$this->data['products'] = array();

		$data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => 5
		);

		$results = $this->model_catalog_product->getProductSpecials2($data);
       
		foreach ($results as $result) {



			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

            /* Добавляем опции  */

           foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
							if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
								$option_value_data = array();

								foreach ($option['option_value'] as $option_value) {
									if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
										if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
											$price_option = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax')));
										} else {
											$price_option = false;
										}

										$option_value_data[] = array(
											'product_option_value_id' => $option_value['product_option_value_id'],
											'option_value_id'         => $option_value['option_value_id'],
											'name'                    => $result['name'].' '.$option_value['name'],
                                            'size'                    => $option_value['name'],
                                            'points'                  => $option_value['points'],
                                            'amount_option'           => $option_value['amount_option'],
                                            'unit_option'             => $option_value['unit_option'],
                                            'model_option'            => $option_value['model_option'],
											'image'                   => isset($option_value['image']) ? $this->model_tool_image->resize($option_value['image'], 50, 50) : false,
											'price'                   => $price_option,
											'price_prefix'            => $option_value['price_prefix']
										);
									}
								}

								$options[] = array(
									'product_option_id' => $option['product_option_id'],
									'option_id'         => $option['option_id'],
									'name'              => $option['name'],
									'type'              => $option['type'],
									'option_value'      => $option_value_data,
									'required'          => $option['required']
								);
							} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
								$options[] = array(
									'product_option_id' => $option['product_option_id'],
									'option_id'         => $option['option_id'],
									'name'              => $option['name'],
									'type'              => $option['type'],
									'option_value'      => $option['option_value'],
									'required'          => $option['required']
								);
							}
						}



           /* Конец добавления опций */

			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
                'options'    => $options,
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);

            unset($option_value_data);
            unset($options);
            unset($price_option);

		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/special.tpl';
		} else {
			$this->template = 'default/template/module/special.tpl';
		}

		$this->render();
	}
}
?>