<?php
class ControllerModulefeaturedreview extends Controller {
	protected function index($setting) {
		$this->language->load('module/featuredreview');
		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/featuredreview.css'); 

      	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['no_reviews'] = $this->language->get('no_reviews');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		
		$this->load->model('localisation/language');
	    $languages = $this->model_localisation_language->getLanguages();
	    $this->data['customtitle'] = $this->config->get('popular_customtitle' . $this->config->get('config_language_id'));
		if (!$this->data['customtitle']) { $this->data['customtitle'] = $this->data['heading_title']; } 
		

		
		$this->load->model('catalog/product'); 
		
		$this->load->model('tool/image');
		
		$this->load->model('module/reviews');
		
		$this->language->load('product/product');
		
		$this->load->model('catalog/review');
		$this->load->model('module/reviews');


		$this->data['products'] = array();

		$products = explode(',', $this->config->get('featuredreview_product'));		

		if (empty($setting['limit'])) {
			$setting['limit'] = 5;
		}
		
		if (empty($setting['limitrev'])) {
			$setting['limitrev'] = 3;
		}
		
		$products = array_slice($products, 0, (int)$setting['limit']);
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['image_width'], $setting['image_height']);
					$this->data['widthimg']   = $setting['image_width'];
				} else {
					$image = false;
					$this->data['widthimg']   = 0;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}
				
		        $reviews = $this->model_catalog_review->getReviewsByProductId($product_id); 
				$reviews = array_slice($reviews, 0, (int)$setting['limitrev']);
				
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $product_info['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 220) . '..',
					'price'   	 => $price,
					'special' 	 => $special,
					'saving'	=> round((($product_info['price'] - $product_info['special'])/($product_info['price'] + 0.01))*100, 0),
					'quickview'        => $this->url->link('product/quickview', 'product_id=' . $product_info['product_id']),
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'reviews2'    => $reviews
				);
			}
			
		}

	

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featuredreview.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/featuredreview.tpl';
		} else {
			$this->template = 'default/template/module/featuredreview.tpl';
		}

		$this->render();
	}
	
	
}

?>