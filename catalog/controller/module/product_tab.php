<?php
class ControllerModuleProductTab extends Controller {
	
	protected function index($setting) {

		if(!isset($this->request->get['route']) || $this->request->get['route'] != 'product/product'){
		$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
		}

		static $module = 0;

		$this->language->load('module/product_tab');
		
      	$this->data['heading_title'] = $this->language->get('heading_title');

      	$this->data['tab_latest'] = $this->language->get('tab_latest');
      	$this->data['tab_featured'] = $this->language->get('tab_featured');
      	$this->data['tab_bestseller'] = $this->language->get('tab_bestseller');
      	$this->data['tab_special'] = $this->language->get('tab_special');

		
		$this->data['button_cart'] = $this->language->get('button_cart');
				
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');

		//Latest Products
		
		$this->data['latest_products'] = array();
		
		$latest_results = $this->model_catalog_product->getLatestProducts($setting['limit']);

		foreach ($latest_results as $result) {
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
			
			$this->data['latest_products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'stock'   	 => $result['quantity'],
				'name'    	 => $result['name'],
				'model'    	 => $result['model'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['mini_description'], ENT_QUOTES, 'UTF-8')), 0, 70) . '',
				'price'   	 => $price,
				'saving'      => $economy,
				'special' 	 => $special,
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

		//Specials product

		$this->data['special_products'] = array();

			$special_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);


		
		$special_results = $this->model_catalog_product->getProductSpecials($special_data);

		foreach ($special_results as $result) {
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
			$this->data['special_products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'stock'   	 => $result['quantity'],
				'model'    	 => $result['model'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['mini_description'], ENT_QUOTES, 'UTF-8')), 0, 70) . '',
				'price'   	 => $price,
				'special' 	 => $special,
				'saving'      => $economy,
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

		//BestSeller
		$this->data['bestseller_products'] = array();

		$bestseller_results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
		
		foreach ($bestseller_results as $result) {
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
			$this->data['bestseller_products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'stock'   	 => $result['quantity'],
				'model'    	 => $result['model'],
				'saving'      => $economy,
				'description' => utf8_substr(strip_tags(html_entity_decode($result['mini_description'], ENT_QUOTES, 'UTF-8')), 0, 70) . '',
				'price'   	 => $price,
				'special' 	 => $special,
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}


		$this->data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/product_tab.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/product_tab.tpl';
		} else {
			$this->template = 'default/template/module/product_tab.tpl';
		}

		$this->render();
	}
}
?>