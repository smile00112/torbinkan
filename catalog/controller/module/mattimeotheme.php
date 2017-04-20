<?php  
class ControllerModuleMattimeoTheme extends Controller {
	protected function index() {
		
		$this->language->load('module/mattimeotheme');
		$this->data['mattimeomod'] = $this->config->get('mattimeomod');
		$this->data['mattimg_icon'] 	= $this->config->get('mattimg_icon');
		$this->data['matt_array'] 	= $this->config->get('matt_array');
		$this->data['mattimeolink'] 	= $this->config->get('mattimeolink');
		$this->data['mattimeolinkheader'] 	= $this->config->get('mattimeolinkheader');
		$this->data['mattimg'] 	= $this->config->get('mattimg');
		$this->data['mattnetwork'] 	= $this->config->get('mattnetwork');

		/* LANGUAGE */
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['language_id'] = array();
		foreach ($this->data['languages'] as $result) {
      		$this->data['language_id'][] = array(
        		'language_id' => $result['language_id']
      		);
    	}
		
		$this->load->model('tool/image');

		$this->data['cusom_h']	= array();
		$this->data['cusom_p']	= array();
		$this->data['cusom_p_tab']	= array();
		$this->data['cusom_f']	= array();
		$this->data['Top_m_link']	= array();
		$this->data['Header_m_link']	= array();
		$this->data['htmlmenu_t']	= array();
        $this->data['informations'] = array();
		$this->data['mattimg_f']		= array();
		$this->data['cusom_payment']	= array();
		$this->data['cusom_network']	= array();

		
		/* Information*/
		foreach ($this->model_catalog_information->getInformations() as $result) {
			$this->data['informations'][] = array(
				'title' => $result['title'],
				'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
			);
		}
		
		if($this->data['matt_array']){
			
          
			/* Custom header contact*/
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				$this->data['cusom_h'][$this->data['language_id'][$i]['language_id']] = array(
					'comptext_header_text'	=>	$result['comptext_header_text']
				);
				$i++;
			}
			
			/* Custom product information*/
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				$this->data['cusom_p'][$this->data['language_id'][$i]['language_id']] = array(
					'product_text'	=>	$result['product_text']
				);
				$i++;
			}
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				$this->data['cusom_p_tab'][$this->data['language_id'][$i]['language_id']] = array(
				    'product_title_tab'	=>	$result['product_title_tab'],
					'product_text_tab'	=>	$result['product_text_tab']
				);
				$i++;
			}
          
			/* Custom footer contact*/
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				$this->data['cusom_f'][$this->data['language_id'][$i]['language_id']] = array(
					'comptext_text'	=>	$result['comptext_text']
				);
				$i++;
			}
			
			/* Custom payment text*/
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				$this->data['cusom_payment'][$this->data['language_id'][$i]['language_id']] = array(
				'compfootertext_title'		=>	$result['compfootertext_title'],
					'footer_payment_text'	=>	$result['footer_payment_text']
				);
				$i++;
			}	
			
		} else {
			$this->data['cusom_h'][$this->config->get('config_language_id')]['comptext_header_text'] = '';
			$this->data['cusom_p'][$this->config->get('config_language_id')]['product_text'] = '';
			$this->data['cusom_p_tab'][$this->config->get('config_language_id')]['product_title_tab'] = '';
			$this->data['cusom_p_tab'][$this->config->get('config_language_id')]['product_text_tab'] = '';
			$this->data['cusom_f'][$this->config->get('config_language_id')]['comptext_text'] = '';
			$this->data['cusom_payment'][$this->config->get('config_language_id')]['compfootertext_title'] ='';
			$this->data['cusom_payment'][$this->config->get('config_language_id')]['footer_payment_text'] = '';
		}
		
		if($this->data['mattimeolink']){
			
			/* Custom menu links*/
			$i = 0;
            if ((isset($this->data['mattimeolink'])) && (!function_exists('cmp3'))){
               function cmp3($a,$b){
                  return ($a['sort']<$b['sort'])?-1:1;
               }
     				$this->data['Top_m_link'] = $this->data['mattimeolink']['Top_m_links'];
               uasort($this->data['Top_m_link'],'cmp3');
			}
		}
		
		if($this->data['mattimeolinkheader']){
			
			/* Custom menu header links*/
			$i = 0;
            if ((isset($this->data['mattimeolinkheader'])) && (!function_exists('cmp2'))){
               function cmp2($a,$b){
                  return ($a['sort']<$b['sort'])?-1:1;
               }
     				$this->data['Header_m_link'] = $this->data['mattimeolinkheader']['Header_m_links'];
               uasort($this->data['Header_m_link'],'cmp2');
			}
		}
		
		
		if($this->data['mattimeomod']){

			
          /* Custom html menu*/
			$i = 0;
			foreach ($this->data['mattimeomod'] as $result) {
				
				$this->data['htmlmenu_t'][$this->data['language_id'][$i]['language_id']] = array(
					'topmenulink_lang'		=>	$result['topmenulink_lang'],
					'topmenulink_custom'	=>	$result['topmenulink_custom']
				);
				$i++;
			}	
		}  else {
			$this->data['htmlmenu_t'][$this->config->get('config_language_id')]['topmenulink_lang'] = '';
			$this->data['htmlmenu_t'][$this->config->get('config_language_id')]['topmenulink_custom'] = '';	
			}
		
			if($this->data['mattimg']){
					/* Payments icons*/

			if(isset($this->data['mattimg'])){
				foreach ($this->data['mattimg'] as $result) {
					if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['mattimg_f'][] = array(
					'title' => $result['title'],
					'image' => $this->model_tool_image->resize($result['image'],50, 30)
				);
			}
				}
			   }
			}
			
			
			if($this->data['mattnetwork']){
					/* Network icons*/

			if(isset($this->data['mattnetwork'])){
				foreach ($this->data['mattnetwork'] as $result) {
					if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['cusom_network'][] = array(
					'title' => $result['title'],
					'href' => $result['href'],
					'image' => $this->model_tool_image->resize($result['image'],30, 30),
				);
			}
				}
			   }
			}

		
		
		
		/* Featured products*/
		$this->load->model('catalog/product'); 

		$this->data['products'] = array();

		$products = explode(',', $this->config->get('featured_product2'));	
		
		$this->data['parall_limit'] = $this->config->get('parall_limit');
		
		if (($this->data['parall_limit'] !=='') && (isset($this->data['parall_limit']))) {
			 $setting['limit'] = $this->data['parall_limit']; 
			 } else {
			 $setting['limit'] = 5; }	

		
		$products = array_slice($products, 0, (int)$setting['limit']);
		
		$this->data['parall_p_width'] = $this->config->get('parall_p_width');
		$this->data['parall_p_height'] = $this->config->get('parall_p_height');
		$this->data['parall_limit2'] = $this->config->get('parall_limit2');; 
		
		if (($this->data['parall_limit2'] !=='') && (isset($this->data['parall_limit2']))) {
			 $this->data['parall_limit2'] = $this->data['parall_limit2'];
			 } else {
			 $this->data['parall_limit2'] = 2;
			 }
		
		if (($this->data['parall_p_width'] !=='') && (isset($this->data['parall_p_width']))) {
			 $width = $this->data['parall_p_width'];
			 } else {
			 $width = 200;
			 }
			 
		if (($this->data['parall_p_height'] !=='') && (isset($this->data['parall_p_height']))) {
			 $height = $this->data['parall_p_height']; 
			 } else {
			 $height = 200;
}	 

		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'],  $width, $height);
				} else {
					$image = false;
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
					
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'quickview'        => $this->url->link('product/quickview', 'product_id=' . $product_info['product_id']),
					'thumb'   	 => $image,
					'name'    	 => $product_info['name'],
					'saving'	=> round((($product_info['price'] - $product_info['special'])/($product_info['price'] + 0.01))*100, 0),
					'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 120) . '..',
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}
		
	    $this->registry->set('cusom_h',htmlspecialchars_decode($this->data['cusom_h'][$this->config->get('config_language_id')]['comptext_header_text'], ENT_QUOTES ));
		$this->registry->set('cusom_p',htmlspecialchars_decode($this->data['cusom_p'][$this->config->get('config_language_id')]['product_text'], ENT_QUOTES ));
		
		$this->registry->set('cusom_p_tab1',htmlspecialchars_decode($this->data['cusom_p_tab'][$this->config->get('config_language_id')]['product_title_tab'], ENT_QUOTES ));
		$this->registry->set('cusom_p_tab2',htmlspecialchars_decode($this->data['cusom_p_tab'][$this->config->get('config_language_id')]['product_text_tab'], ENT_QUOTES ));
		
		$this->registry->set('cusom_f',htmlspecialchars_decode($this->data['cusom_f'][$this->config->get('config_language_id')]['comptext_text'], ENT_QUOTES ));
		
		$this->registry->set('Top_m_link',$this->data['Top_m_link']);
		$this->registry->set('Header_m_link',$this->data['Header_m_link']);
		
		$this->registry->set('htmlmenu_t1',$this->data['htmlmenu_t'][$this->config->get('config_language_id')]['topmenulink_lang']);
		$this->registry->set('htmlmenu_t2',htmlspecialchars_decode($this->data['htmlmenu_t'][$this->config->get('config_language_id')]['topmenulink_custom'], ENT_QUOTES ));
		$this->registry->set('Information_menu',$this->data['informations']);
		
		$this->registry->set('mattimg_f',$this->data['mattimg_f']);
		
         $this->registry->set('cusom_payment1',$this->data['cusom_payment'][$this->config->get('config_language_id')]['compfootertext_title']);
		$this->registry->set('cusom_payment2',htmlspecialchars_decode($this->data['cusom_payment'][$this->config->get('config_language_id')]['footer_payment_text'], ENT_QUOTES ));
		
		$this->registry->set('cusom_network',$this->data['cusom_network']);
		
		$this->registry->set('featured_product2',$this->data['products']);
		
		$this->registry->set('parall_limit2',$this->data['parall_limit2']);
			


	}
}


?>