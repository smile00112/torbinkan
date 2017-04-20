<?php
class ControllerModuleBestSeller extends Controller {
	protected function index($setting) {
		$this->language->load('module/bestseller');
 
      	$this->data['heading_title'] = $this->language->get('heading_title');
				
		$this->data['button_cart'] = $this->language->get('button_cart');
$this->data['button_out_of_stock'] = $this->language->get('button_out_of_stock');
		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');

		$this->data['products'] = array();

		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
		
		foreach ($results as $result) {

			$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
                $dop_img = array();
                foreach ($results_img as $result_img) {
                if ($result_img['image']) {
                $image_dop = $this->model_tool_image->resize($result_img['image'], $setting['image_width'], $setting['image_height']);
                } else {
                $image_dop = false;
                }
                 $dop_img[] = $image_dop;
                }
			
			
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
							
			$this->data['products'][] = array(

			'dop_img' => $dop_img,
			'quickview'        => $this->url->link('product/quickview', 'product_id=' . $result['product_id']),
			
			
				'product_id' => $result['product_id'],
'quantity'	  => $result['quantity'],
            'stock' => $result['stock_status'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,

		'saving'	 => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
		
				'rating'     => $rating,
				'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
				'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bestseller.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/bestseller.tpl';
		} else {
			$this->template = 'default/template/module/bestseller.tpl';
		}


        	$this->data['position'] = $setting['position'];
			
			
		$this->render();
	}
}
?>