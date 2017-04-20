<?php
class ControllerModuleLatest extends Controller {
	protected function index($setting) {
		$this->language->load('module/latest');
		
      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
$this->data['button_out_of_stock'] = $this->language->get('button_out_of_stock');
				
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		
		$this->data['products'] = array();
		
		$data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProducts($data);

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
			
												if ((float)$result['special']) {
				$economy = $this->currency->format((($result['special'])-($result['price']))*(-1)) ;
			} else {
				$special = false;
			}
			$this->data['products'][] = array(
					'product_id'  => $result['product_id'],
'quantity'	  => $result['quantity'],
            'stock' => $result['stock_status'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'model'        => $result['model'],
					'stock'   	 => $result['quantity'],
					'saving'      => $economy,
					'description' => utf8_substr(strip_tags(html_entity_decode($result['mini_description'], ENT_QUOTES, 'UTF-8')), 0, 70) . '',
					'price'       => $price,
					'special'     => $special,
					'rating'      => $result['rating'],
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/latest.tpl';
		} else {
			$this->template = 'default/template/module/latest.tpl';
		}


        	$this->data['position'] = $setting['position'];
			
			
		$this->render();
	}
}
?>