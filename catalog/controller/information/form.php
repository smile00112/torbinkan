<?php 
class ControllerInformationForm extends Controller {
    
    private $error = array(); 
    
    
	public function index() {  
    	
        $this->language->load('information/form');
		$this->load->model('catalog/form');
        
        if (isset($this->request->get['form_id'])) {
			$form_id = $this->request->get['form_id'];
		} else {
			$form_id = 0;
		}
        
        $form_info = $this->model_catalog_form->getForm($form_id);
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST')  && $this->validate($form_id) ) {
        
        $form_data = $_POST;
        $html = '<div style="background:#f0f0f0; border-top:1px solid #777; box-shadow:0 -2px 2px #999; -webkit-box-shadow:0 -2px 2px #999;">';
        $html .= '<table cellpadding="0" cellspacing="0" style="width:auto; margin: 0.2em 2em 2em; font-size: 100%;"><tr><td style="color:#555; padding:1em 0 0.4em; font-size: 110%; font-weight:bold; text-shadow:0 1px 0 #fff;" colspan="2">'.$form_info['name'].'</td></tr>';
        
        $from = $this->config->get('config_email');
        $sender = $this->config->get('config_name');
        $user_email = false;
        $user_name = false;
        $letter = false;
        
        foreach($form_data as $key => $val) {
            
            $if = str_replace('item-','',$key);
            
            if($key != 'captcha'){
            $item_info = $this->model_catalog_form->getItem($if);
            
            if($item_info['setfrom'] == 1) {
                $from = $val;
                $user_email = $val;
                if($item_info['letter'] == 1) {
                    $letter = true;
                }
            }
            if ($item_info['setsender'] == 1) {
                $sender = $val;
                $user_name = $val;
            }
            if ($item_info['item_type'] == 'checkbox' or $item_info['item_type'] == 'multiselect') {
                $val = implode(', ',$val);
                $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$item_info['label'].'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$val.'</td></tr>';
            } else {
               $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$item_info['label'].'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$val.'</td></tr>';
                
                }
            }
            
        }
        
        if($form_info['use_type'] == 2 && $this->cart->hasProducts() ) {
            $product_list = array();
            $products = $this->cart->getProducts();
            
            foreach ($products as $product) {
				$product_total = 0;
					
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}			
				$option_data = array();

        		foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
					
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
        		}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$total = false;
				}
				
        		$product_list[]  = array(
          			'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'quantity' => $product['quantity'],
          			'price'    => $price,
					'total'    => $total,
					'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id']),					
				);
      		}
            
            foreach($product_list as $product_item) {
               $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">&nbsp;</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">&nbsp;</td></tr>';
                
                $p_name = '<a href="'.$product_item['href'].'">'.$product_item['name'].'</a>';
                $p_name .= '<div>';
                    foreach($product_item['option'] as $option) {
                        $p_name .= '- <small>'.$option['name'].': '.$option['value'].'</small><br />';
                    }
                $p_name .= '</div>';
                
                $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_product').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$p_name.'</td></tr>';
                
                $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_model').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$product_item['model'].'</td></tr>';
                
                $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_quantity').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$product_item['quantity'].'</td></tr>';
                
                $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_price').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$product_item['price'].'</td></tr>';
                
                $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_total').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$product_item['total'].'</td></tr>';
                
                
            }
            
            $this->cart->clear();
        }
        
        $user_html = $html;
        $db_data = $html;
        $html .= '</table></div>';
        if(!empty($_FILES)) {
            $file_data = $_FILES;
        }
        
        
        $html_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title></head><body style="margin:0; padding:0; font-family: Verdana, Arial; font-size: 13px; color:#555;">';
        
        $html_head .= $html;
        $html = $html_head;
        $html .= '</body></html>';
        
        
        if (utf8_strlen($form_info['email']) !=0) {
            $to = $form_info['email'];
        } else {
            $to = $this->config->get('config_email');
        }
        
        $mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($to);
	  		$mail->setFrom($from);
	  		$mail->setSender($sender);
	  		$mail->setSubject(html_entity_decode(sprintf($form_info['name'], ENT_QUOTES, 'UTF-8')));
	  		$mail->setHtml($html);
            
            if(!empty($file_data)) {
            
            
            foreach($file_data as $file) {
                $mail->addAttachment($file['tmp_name'],$file['name']);
            }
            
        }
            
      		$mail->send();
            
            $user_data = array();
            $user_html_login = '';
            if($form_info['newsletteron'] == 1 && $user_email && $user_name && !$this->customer->isLogged()) {
                $this->load->model('account/customer');
                $user_by_email = $this->model_account_customer->getCustomerByEmail($user_email);
                if(empty($user_by_email)){
                    $customer_group = $this->model_catalog_form->getCustomerGroup($form_id);
                    $customer_group_id = $customer_group['customer_group_id'];
                    $data = array(
                        'firstname' => $user_name,
                        'email' => $user_email,
                        'customer_group_id' => $customer_group_id,
                        'useron' => $form_info['useron'],
                        'password' => uniqid()
                    
                    );
                    $this->model_catalog_form->addCustomer($data);
                    if($form_info['useron'] == 1){
                        $user_data = $data;
                        $user_html_login .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">&nbsp;</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$this->language->get('text_customer_acaunt').'</td></tr>';
                        $user_html_login .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_customer_login').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$user_data['email'].'</td></tr>';
                        $user_html_login .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_customer_pass').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$user_data['password'].'</td></tr>';
                        $user_html_login .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_customer_log').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;"><a href="'.$this->url->link('account/login').'">'.$this->url->link('account/login').'</a></td></tr>';
                        
                    }
                    
                }
            }
            
            
            if($letter && !empty($user_data)){
                $user_html .= $user_html_login;
                $user_html .= '</table></div>';
                $html_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title></head><body style="margin:0; padding:0; font-family: Verdana, Arial; font-size: 13px; color:#555;">';
                $html_head .= $user_html;
                $user_html = $html_head;
                $user_html .= '</body></html>';
                $mail = new Mail();
    			$mail->protocol = $this->config->get('config_mail_protocol');
    			$mail->parameter = $this->config->get('config_mail_parameter');
    			$mail->hostname = $this->config->get('config_smtp_host');
    			$mail->username = $this->config->get('config_smtp_username');
    			$mail->password = $this->config->get('config_smtp_password');
    			$mail->port = $this->config->get('config_smtp_port');
    			$mail->timeout = $this->config->get('config_smtp_timeout');				
    			$mail->setTo('selovs@yandex.ruÿ');
    	  		$mail->setFrom($to);
    	  		$mail->setSender($this->config->get('config_name'));
    	  		$mail->setSubject($this->language->get('text_customer').$form_info['name']);
    	  		$mail->setHtml($user_html);
                
                if(!empty($file_data)) {
                
                    foreach($file_data as $file) {
                        $mail->addAttachment($file['tmp_name'],$file['name']);
                    }
                }
                
          		$mail->send();
                
            } elseif($letter && empty($user_data)) {
                $html_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title></head><body style="margin:0; padding:0; font-family: Verdana, Arial; font-size: 13px; color:#555;">';
                $html_head .= $user_html;
                $user_html = $html_head;
                $user_html .= '</table></div>';
                $user_html .= '</body></html>';
                $mail = new Mail();
    			$mail->protocol = $this->config->get('config_mail_protocol');
    			$mail->parameter = $this->config->get('config_mail_parameter');
    			$mail->hostname = $this->config->get('config_smtp_host');
    			$mail->username = $this->config->get('config_smtp_username');
    			$mail->password = $this->config->get('config_smtp_password');
    			$mail->port = $this->config->get('config_smtp_port');
    			$mail->timeout = $this->config->get('config_smtp_timeout');				
    			$mail->setTo($from);
    	  		$mail->setFrom($to);
    	  		$mail->setSender($this->config->get('config_name'));
    	  		$mail->setSubject($this->language->get('text_customer').$form_info['name']);
    	  		$mail->setHtml($user_html);
                
                if(!empty($file_data)) {
                
                    foreach($file_data as $file) {
                        $mail->addAttachment($file['tmp_name'],$file['name']);
                    }
                }
                
          		$mail->send();
            } elseif(!$letter && !empty($user_data)) {
                $user_html_reg = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title></head><body style="margin:0; padding:0; font-family: Verdana, Arial; font-size: 13px; color:#555;">';
                $user_html_reg .= '<div style="background:#f0f0f0; border-top:1px solid #777; box-shadow:0 -2px 2px #999; -webkit-box-shadow:0 -2px 2px #999;">';
                $user_html_reg .= '<table cellpadding="0" cellspacing="0" style="width:auto; margin: 0.2em 2em 2em; font-size: 100%;"><tr><td style="color:#555; padding:1em 0 0.4em; font-size: 110%; font-weight:bold; text-shadow:0 1px 0 #fff;" colspan="2">'.$this->language->get('text_customer_reg').$this->config->get('config_name').'</td></tr>';
                $user_html_reg .= $user_html_login;
                $user_html_reg .= '</table></div>';
                $user_html_reg .= '</body></html>';
                $mail = new Mail();
    			$mail->protocol = $this->config->get('config_mail_protocol');
    			$mail->parameter = $this->config->get('config_mail_parameter');
    			$mail->hostname = $this->config->get('config_smtp_host');
    			$mail->username = $this->config->get('config_smtp_username');
    			$mail->password = $this->config->get('config_smtp_password');
    			$mail->port = $this->config->get('config_smtp_port');
    			$mail->timeout = $this->config->get('config_smtp_timeout');				
    			$mail->setTo($from);
    	  		$mail->setFrom($to);
    	  		$mail->setSender($this->config->get('config_name'));
    	  		$mail->setSubject($this->language->get('text_customer_reg').$this->config->get('config_name'));
    	  		$mail->setHtml($user_html_reg);
                $mail->send();
            }
            
            if($form_info['databaseon'] == 1) {
            if(!empty($file_data)) {
                $file_link = array();
                foreach($file_data as $file) {
                    if (is_uploaded_file($file['tmp_name']) && file_exists($file['tmp_name'])) {
                        $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8')));
                        $file_name = basename($filename);
                        move_uploaded_file($file['tmp_name'], DIR_DOWNLOAD . $file_name);
                        
                        $file_link[] = '<a target="_blank" href="'. HTTP_SERVER .'download/'. $file_name.'">'.$file_name.'</a>';
                        
                    }
                    
                }
                if(!empty($file_link)) {
                    
                    $db_data .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">&nbsp;</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.implode('&nbsp;&nbsp;&nbsp;',$file_link).'</td></tr>';
                }
                
            }
            $db_data .= '</table></div>';
            $data = array(
                'form_id' => $form_id,
                'form_data' => $db_data
            );
            
            $this->model_catalog_form->addFormData($data);
        }
            
            if($form_info['use_type'] != 2) {
                $this->redirect($this->url->link('information/form/success'));
            }
            else {
                $this->redirect($this->url->link('checkout/success'));
                
            }
        }
        
        
		$this->data['breadcrumbs'] = array();
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
		
		if ($form_info && $form_info['use_type'] != 1) {
			
			$this->document->setTitle($form_info['name']);
			
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $form_info['name'],
				'href'      => $this->url->link('information/form', 'form_id=' .  $form_id),      		
        		'separator' => $this->language->get('text_separator')
      		);		
						
			$this->document->addScript('catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js');
            $this->document->addScript('catalog/view/javascript/oforms/oforms.js');
            $this->document->addScript('catalog/view/javascript/oforms/jquery.validate.js');
            $this->document->addScript('catalog/view/javascript/oforms/additional-methods.js');
            $this->document->addScript($this->url->link('information/form/valid_lang'));
            $this->document->addScript($this->url->link('information/form/valid_js','form_id='.$form_id));
            $this->document->addScript($this->url->link('information/form/dt_lang'));
            $this->document->addStyle('catalog/view/theme/default/stylesheet/oforms.css');
            
            $this->data['heading_title'] = $form_info['name'];
            $this->data['action'] = $this->url->link('information/form&form_id='.$form_id);
            $this->data['form_id'] = 'of'.$form_info['form_id'];
            $this->data['class'] = 'form'.$form_info['prefix'];
            $this->data['items'] = $this->model_catalog_form->getItems($form_id);
            $this->data['button'] = utf8_strlen($form_info['button']) == 0 ? $this->language->get('text_button') : $form_info['button'];
            $this->data['fileon'] = $form_info['file'] == 1 ? true : false;
            $this->data['customer'] = $this->customer->isLogged() ? true : false;
            
            if(!empty($this->error)) {
                foreach($this->error as $key=>$value) {
                    if($key != 'captcha') {
                        $this->data['error']['item_error'.$key] = $value;
                    } else {
                        $this->data['captcha_error'] = $value;
                    }
                }
            }
                        
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/form.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/form.tpl';
			} else {
				$this->template = 'default/template/information/form.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
						
	  		$this->response->setOutput($this->render());
    	} else {
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('information/form', 'form_id=' . $form_id),
        		'separator' => $this->language->get('text_separator')
      		);
				
	  		$this->document->setTitle($this->language->get('text_error'));
			
      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
					
	  		$this->response->setOutput($this->render());
    	}
		
  	}
    
    
    public function success() {
		$this->language->load('information/form');

		$this->document->setTitle($this->language->get('heading_title')); 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/form'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
 		$this->response->setOutput($this->render()); 
	}
    
    private function validate($form_id) {
        $this->load->model('catalog/form');
        $form_info = $this->model_catalog_form->getForm($form_id);
        $items = $this->model_catalog_form->getItems($form_id);
        
        foreach($items as $item) {
            if($item['required'] == 1 && $item['item_type'] != 'file' && $item['item_type'] != 'capcha') {
                    if(isset($this->request->post['item-'.$item['item_id']])) {
                        
                    } else {
                        $this->error[$item['item_id']] = $this->language->get('error_required');
                    }
                        
            } 
            
            if ($item['required'] == 1 && $item['item_type'] == 'file') {
                  if($_FILES['item-'.$item['item_id']]['error'] != 0 ) {
                       $this->error[$item['item_id']] = $this->language->get('error_file');
                  }
            }
            if(!empty($_FILES)) {
            if ($item['item_type'] == 'file' && $_FILES['item-'.$item['item_id']]['error'] == 0) {
                  $ext = strtolower(substr(strrchr($_FILES['item-'.$item['item_id']]['name'],'.'), 1));
                  $valid = explode('|',$item['pattern']);
                  $exts = strtolower($valid[0]);
                  $exts = explode(',',$exts);
                  if(!in_array($ext,$exts)) {
                    $this->error[$item['item_id']] = $this->language->get('error_file_ext');
                    
                  }
                  if ($_FILES['item-'.$item['item_id']]['size'] > $valid[1]) {
                    $this->error[$item['item_id']] = $this->language->get('error_file_size');
                    
                  }
                
            }
            }
            
            
            if($item['item_type'] == 'input' or $item['item_type'] == 'textarea') {
                
                if ($item['validation'] == 'email') {
                    if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['item-'.$item['item_id']])) {
                        
                  		$this->error[$item['item_id']] = $this->language->get('error_email');
                        
                	}
                } elseif ($item['validation'] == 're') {
                    if (!preg_match('/'.$item['pattern'].'/i', $this->request->post['item-'.$item['item_id']])) {
                  		$this->error[$item['item_id']] = $this->language->get('error_value');
                	}
                } elseif ($item['validation'] == 'int') {
                    if (!preg_match('/[0-9]/i', $this->request->post['item-'.$item['item_id']])) {
                  		$this->error[$item['item_id']] = $this->language->get('error_number');
                	}
                }
            }
        
            
            
            if ($item['item_type'] == 'capcha') {
                if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
      		$this->error['captcha'] = $this->language->get('error_captcha');
    	     }
            }
        }
        
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  	  
  	}
    
	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
    
    public function valid_js() {
        $form_id = $this->request->get['form_id'];
		$this->load->model('catalog/form');
        $form_info = $this->model_catalog_form->getForm($form_id);
        $items = $this->model_catalog_form->getItems($form_id);
        
        $id_form = 'of';
        $id_form .= $form_info['form_id'];
        
        $validate_js = '$().ready(function(){';
        $validate_js .= "$('#".$id_form."').validate({";
        
        $validate_js .= 'errorPlacement: function(error, element) {';
        $validate_js .= 'if ( element.is(":text") )';
        $validate_js .= 'error.appendTo( $(element).parent().find("span.error"));';
        $validate_js .= 'else if ( element.is("select") ) ';
        $validate_js .= 'error.appendTo( $(element).parent().find("span.error"));';
        $validate_js .= 'else if ( element.is(":file") ) ';
        $validate_js .= 'error.appendTo( $(element).parent().find("span.error"));';
        $validate_js .= 'else if ( element.is("textarea") ) ';
        $validate_js .= 'error.appendTo( $(element).parent().find("span.error"));';
        $validate_js .= 'else if ( element.is(":checkbox") ) ';
        $validate_js .= 'error.appendTo( $(element).closest("li.lisub").find("span.error"));';
        $validate_js .= 'else if ( element.is(":radio") ) ';
        $validate_js .= 'error.appendTo( $(element).closest("li.lisub").find("span.error"));';
        $validate_js .= 'else $(element).after(error);';
        $validate_js .= '},';
        
        
        $validate_js .= 'rules: {';
        $item_array = array();
        foreach($items as $item){
            if ($item['item_type'] == 'html') {
                
            } else {
                $rules = array();
                if($item['required'] == 1) {
                    $rules[] = 'required: true';
                }
                if($item['validation'] == 'email') {
                    $rules[] = 'email: true';
                } elseif ($item['validation'] == 're') {
                    $rules[] = 'pattern: /'.$item['pattern'].'/';
                } elseif ($item['validation'] == 'int') {
                    $rules[] = 'number: true';
                }
                $count_rules = count($rules);
                if($count_rules > 0) {
                    if($count_rules > 1) {
                        $rule = implode(',',$rules);
                    } else {
                        $rule = $rules[0];
                    }
                } else {
                    $rule = '';
                }
            }
        if($item['item_type'] == 'checkbox' or $item['item_type'] == 'multiselect') {
        $item_rules = '"item-'.$item['item_id'].'[]": {';      
        } elseif($item['item_type'] == 'capcha') {
        $item_rules = 'captcha: {';      
        } else {
        $item_rules = '"item-'.$item['item_id'].'": {';  
        }
        
        $item_rules .= $rule;
        $item_rules .= '}';  
        $item_array[] = $item_rules;    
            
        }

        $count_ir = count($item_array);
        
        if($count_ir > 0) {
            if($count_ir > 1) {
                $validate_js .= implode(',',$item_array);
            } else {
                $validate_js .= $item_array[0];
            }
        }
        
        $validate_js .= '}});});';
        echo $validate_js;
        
    }
    
    public function dt_lang() {
        $this->language->load('information/form');
        $dtl = 'jQuery(document).ready(function(){';
        $dtl .= '$( ".of-datepicker" ).datepicker({';
        $dtl .= "prevText: '".$this->language->get('text_prevtext')."',";
	    $dtl .= "nextText: '".$this->language->get('text_nexttext')."',";
	    $dtl .= "currentText: '".$this->language->get('text_currenttext')."',";
	    $dtl .= 'monthNames: '.$this->language->get('text_monthnames').',';
	    $dtl .= 'monthNamesShort: '.$this->language->get('text_monthnamesshort').',';
	    $dtl .= 'dayNames: '.$this->language->get('text_daynames').',';
	    $dtl .= 'dayNamesShort: '.$this->language->get('text_daynamesshort').',';
	    $dtl .= 'dayNamesMin: '.$this->language->get('text_daynamesmin').',';
	    $dtl .= "weekHeader: '".$this->language->get('text_weekheader')."',";
        $dtl .= 'firstDay: 1,';
	    $dtl .= 'isRTL: false,';
	    $dtl .= 'showMonthAfterYear: false';
        $dtl .= '});';
        $dtl .= '$( ".of-timepicker" ).timepicker({';
        $dtl .= "timeOnlyTitle: '".$this->language->get('text_timeonlytitle')."',";
	    $dtl .= "timeText: '".$this->language->get('text_timetext')."',";
	    $dtl .= "hourText: '".$this->language->get('text_hourtext')."',";
	    $dtl .= "minuteText: '".$this->language->get('text_minutetext')."',";
	    $dtl .= "secondText: '".$this->language->get('text_secondtext')."',";
	    $dtl .= "currentText: '".$this->language->get('text_currenttext2')."',";
	    $dtl .= "closeText: '".$this->language->get('text_closetext')."'";
        $dtl .= '});';
        $dtl .= '$( ".of-datetimepicker" ).datetimepicker({';
        $dtl .= "prevText: '".$this->language->get('text_prevtext')."',";
	    $dtl .= "nextText: '".$this->language->get('text_nexttext')."',";
	    $dtl .= "currentText: '".$this->language->get('text_currenttext')."',";
	    $dtl .= 'monthNames: '.$this->language->get('text_monthnames').',';
	    $dtl .= 'monthNamesShort: '.$this->language->get('text_monthnamesshort').',';
	    $dtl .= 'dayNames: '.$this->language->get('text_daynames').',';
	    $dtl .= 'dayNamesShort: '.$this->language->get('text_daynamesshort').',';
	    $dtl .= 'dayNamesMin: '.$this->language->get('text_daynamesmin').',';
	    $dtl .= "weekHeader: '".$this->language->get('text_weekheader')."',";
        $dtl .= 'firstDay: 1,';
	    $dtl .= 'isRTL: false,';
	    $dtl .= 'showMonthAfterYear: false,';
        $dtl .= "timeOnlyTitle: '".$this->language->get('text_timeonlytitle')."',";
	    $dtl .= "timeText: '".$this->language->get('text_timetext')."',";
	    $dtl .= "hourText: '".$this->language->get('text_hourtext')."',";
	    $dtl .= "minuteText: '".$this->language->get('text_minutetext')."',";
	    $dtl .= "secondText: '".$this->language->get('text_secondtext')."',";
	    $dtl .= "currentText: '".$this->language->get('text_currenttext2')."',";
	    $dtl .= "closeText: '".$this->language->get('text_closetext')."'";
        $dtl .= '});';
        $dtl .= '});';
        echo $dtl;
    }
    
    public function valid_lang() {
        $this->language->load('information/form');
        $vl = 'jQuery.extend(jQuery.validator.messages, {';
        $vl .= 'required: "'.$this->language->get('error_required').'",';
        $vl .= 'pattern: "'.$this->language->get('error_value').'",';
        $vl .= 'email: "'.$this->language->get('error_email').'",';
        $vl .= 'number: "'.$this->language->get('error_number').'"';
        
        $vl .= '});';
        
        echo $vl;
    }
    
    public function valid_js_module() {
        $form_id = $this->request->get['form_id'];
        $fb = $this->request->get['fb'];
        $this->language->load('information/form');
		$this->load->model('catalog/form');
        $form_info = $this->model_catalog_form->getForm($form_id);
        $items = $this->model_catalog_form->getItems($form_id);
        
        $id_form = 'of';
        $id_form .= $form_info['form_id'];
        
        $validate_js = '$().ready(function(){';
        $validate_js .= "$('#".$id_form."').validate({";
        
        $validate_js .= 'errorPlacement: function(error, element) {';
        $validate_js .= 'if ( element.is(":text") )';
        $validate_js .= 'error.appendTo( $(element).parent().find("span.error"));';
        $validate_js .= 'else if ( element.is("select") ) ';
        $validate_js .= 'error.appendTo( $(element).parent().find("span.error"));';
        $validate_js .= 'else if ( element.is(":file") ) ';
        $validate_js .= 'error.appendTo( $(element).parent().find("span.error"));';
        $validate_js .= 'else if ( element.is("textarea") ) ';
        $validate_js .= 'error.appendTo( $(element).parent().find("span.error"));';
        $validate_js .= 'else if ( element.is(":checkbox") ) ';
        $validate_js .= 'error.appendTo( $(element).closest("li.lisub").find("span.error"));';
        $validate_js .= 'else if ( element.is(":radio") ) ';
        $validate_js .= 'error.appendTo( $(element).closest("li.lisub").find("span.error"));';
        $validate_js .= 'else $(element).after(error);';
        $validate_js .= '},';
        
        
        $validate_js .= 'rules: {';
        $item_array = array();
        foreach($items as $item){
            if ($item['item_type'] == 'html') {
                
            } else {
                $rules = array();
                if($item['required'] == 1) {
                    $rules[] = 'required: true';
                }
                if($item['validation'] == 'email') {
                    $rules[] = 'email: true';
                } elseif ($item['validation'] == 're') {
                    $rules[] = 'pattern: /'.$item['pattern'].'/';
                } elseif ($item['validation'] == 'int') {
                    $rules[] = 'number: true';
                }
                $count_rules = count($rules);
                if($count_rules > 0) {
                    if($count_rules > 1) {
                        $rule = implode(',',$rules);
                    } else {
                        $rule = $rules[0];
                    }
                } else {
                    $rule = '';
                }
            }
        if($item['item_type'] == 'checkbox' or $item['item_type'] == 'multiselect') {
        $item_rules = '"item-'.$item['item_id'].'[]": {';      
        } elseif($item['item_type'] == 'capcha') {
        $item_rules = 'captcha: {';      
        } else {
        $item_rules = '"item-'.$item['item_id'].'": {';  
        }
        
        $item_rules .= $rule;
        $item_rules .= '}';  
        $item_array[] = $item_rules;    
            
        }

        $count_ir = count($item_array);
        
        if($count_ir > 0) {
            if($count_ir > 1) {
                $validate_js .= implode(',',$item_array);
            } else {
                $validate_js .= $item_array[0];
            }
        }
        
        $validate_js .= '}';
        if($form_info['use_type'] != 2) {
        
        $action = html_entity_decode($this->url->link('information/form/ajax_send&form_id='.$form_id));
        $validate_js .= ', submitHandler: function(form) {';
        $validate_js .= '$(form).ajaxSubmit({';
        $validate_js .= 'url:"'.$action.'",';
        $validate_js .= "target:'#sid-".$form_id."',";
        $validate_js .= 'type:"post",';
        $validate_js .= 'beforeSubmit:  function(){';
        $validate_js .= "$('#of".$form_id." .hide-form').show();";
        $validate_js .= '},';
        $validate_js .= 'success: function(){';
        $validate_js .= "$('#of".$form_id." .hide-form').hide();";
        if($fb == 1) {
            $validate_js .= "$('#mas-".$form_id."').fancybox().trigger('click');";
        }
        $validate_js .= '}});}';
        
        }
        
        $validate_js .= '});});';
        echo $validate_js;
        
    }
    
    public function fancybox() {
        $form_id = $this->request->get['form_id'];
        $this->load->model('catalog/form');
        $form_info = $this->model_catalog_form->getForm($form_id);
        $class = 'form'.$form_info['prefix'];
        $fancybox = '$(document).ready(function() {';
        $fancybox .= '$("#'.$class.'").fancybox(';
        $fancybox .= '); });';
        
        echo $fancybox;
    }
  
/*888888888888888888888888888888888888888888888888888888888888888888888888888888888*/  
    public function ajax_send() {
        $this->language->load('information/form');
        if (isset($this->request->get['form_id'])) {
			$form_id = $this->request->get['form_id'];
		} else {
			$form_id = 0;
		}
        if (($this->request->server['REQUEST_METHOD'] == 'POST')  && $this->validate($form_id) ) {
            $form_info = $this->model_catalog_form->getForm($form_id);
            $form_data = $_POST;
            $form_name  = $form_info['name'];
            $form_data = $_POST;
            $product_html = '';
            if (isset($this->request->post['product']['id']) && $form_info['use_type'] == 1 ) { 
                $product = '<a href="'.$this->request->post['product']['url'].'" target="_blank">'.$this->request->post['product']['name'].'</a>';
             $product_html = '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_product').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$product.'</td></tr>';
             
             $product_html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_price').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$this->request->post['product']['price'].'</td></tr>';
             
             if(isset($this->request->post['product']['special'])) {
              $product_html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_special').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$this->request->post['product']['special'].'</td></tr>';  
             }
             
            $form_name .= ' '.$this->request->post['product']['name'];    
            }
            
            $html = '<div style="background:#f0f0f0; border-top:1px solid #777; box-shadow:0 -2px 2px #999; -webkit-box-shadow:0 -2px 2px #999;">';
            $html .= '<table cellpadding="0" cellspacing="0" style="width:auto; margin: 0.2em 2em 2em; font-size: 100%;"><tr><td style="color:#555; padding:1em 0 0.4em; font-size: 110%; font-weight:bold; text-shadow:0 1px 0 #fff;" colspan="2">'.$form_name.'</td></tr>';
        
        $from = $this->config->get('config_email');
        $sender = $this->config->get('config_name');
        $user_email = false;
        $user_name = false;
        $letter = false;
        
        foreach($form_data as $key => $val) {
            
            $if = str_replace('item-','',$key);
            
            if($key != 'captcha' && $key != 'product'){
            $item_info = $this->model_catalog_form->getItem($if);
            
            if($item_info['setfrom'] == 1) {
                $from = $val;
                $user_email = $val;
                if($item_info['letter'] == 1) {
                    $letter = true;
                }
            }
            if ($item_info['setsender'] == 1) {
                $sender = $val;
                $user_name = $val;
            }
            if ($item_info['item_type'] == 'checkbox' or $item_info['item_type'] == 'multiselect') {
                $val = implode(', ',$val);
                $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$item_info['label'].'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$val.'</td></tr>';
            } else {
               $html .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$item_info['label'].'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$val.'</td></tr>';
                
                }
            }
            
        }
        
        $html .= $product_html;
        $user_html = $html;
        $db_data = $html;
        $html .= '</table></div>';
        if(!empty($_FILES)) {
            $file_data = $_FILES;
        }
        
        
        
        $html_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title></head><body style="margin:0; padding:0; font-family: Verdana, Arial; font-size: 13px; color:#555;">';
        
        $html_head .= $html;
        $html = $html_head;
        $html .= '</body></html>';
        
        
        if (utf8_strlen($form_info['email']) !=0) {
            $to = $form_info['email'];
        } else {
            $to = $this->config->get('config_email');
        }
        
            $mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($to);
	  		$mail->setFrom($from);
	  		$mail->setSender($sender);
	  		$mail->setSubject(html_entity_decode(sprintf($form_name, ENT_QUOTES, 'UTF-8')));
	  		$mail->setHtml($html);
            
            if(!empty($file_data)) {
              
            
              foreach($file_data as $file) {
                $mail->addAttachment($file['tmp_name'],$file['name']);
              }
            
           }
            
      		$mail->send();
            $user_data = array();
            $user_html_login = '';
            if($form_info['newsletteron'] == 1 && $user_email && $user_name && !$this->customer->isLogged()) {
                $this->load->model('account/customer');
                $user_by_email = $this->model_account_customer->getCustomerByEmail($user_email);
                if(empty($user_by_email)){
                    $customer_group = $this->model_catalog_form->getCustomerGroup($form_id);
                    $customer_group_id = $customer_group['customer_group_id'];
                    $data = array(
                        'firstname' => $user_name,
                        'email' => $user_email,
                        'customer_group_id' => $customer_group_id,
                        'useron' => $form_info['useron'],
                        'password' => uniqid()
                    
                    );
                    $this->model_catalog_form->addCustomer($data);
                    if($form_info['useron'] == 1){
                        $user_data = $data;
                        $user_html_login .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">&nbsp;</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$this->language->get('text_customer_acaunt').'</td></tr>';
                        $user_html_login .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_customer_login').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$user_data['email'].'</td></tr>';
                        $user_html_login .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_customer_pass').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.$user_data['password'].'</td></tr>';
                        $user_html_login .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">'.$this->language->get('text_customer_log').'</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;"><a href="'.$this->url->link('account/login').'">'.$this->url->link('account/login').'</a></td></tr>';
                        
                    }
                    
                }
            }
            
            
            if($letter && !empty($user_data)){
                $user_html .= $user_html_login;
                $user_html .= '</table></div>';
                $html_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title></head><body style="margin:0; padding:0; font-family: Verdana, Arial; font-size: 13px; color:#555;">';
                $html_head .= $user_html;
                $user_html = $html_head;
                $user_html .= '</body></html>';
                $mail = new Mail();
    			$mail->protocol = $this->config->get('config_mail_protocol');
    			$mail->parameter = $this->config->get('config_mail_parameter');
    			$mail->hostname = $this->config->get('config_smtp_host');
    			$mail->username = $this->config->get('config_smtp_username');
    			$mail->password = $this->config->get('config_smtp_password');
    			$mail->port = $this->config->get('config_smtp_port');
    			$mail->timeout = $this->config->get('config_smtp_timeout');				
    			$mail->setTo('selovs@yandex.ru');
    	  		$mail->setFrom($to);
    	  		$mail->setSender($this->config->get('config_name'));
    	  		$mail->setSubject($this->language->get('text_customer').$form_name);
    	  		$mail->setHtml($user_html);
                
                if(!empty($file_data)) {
                
                    foreach($file_data as $file) {
                        $mail->addAttachment($file['tmp_name'],$file['name']);
                    }
                }
                
          		$mail->send();
                
            } elseif($letter && empty($user_data)) {
                $html_head = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title></head><body style="margin:0; padding:0; font-family: Verdana, Arial; font-size: 13px; color:#555;">';
                $html_head .= $user_html;
                $user_html = $html_head;
                $user_html .= '</table></div>';
                $user_html .= '</body></html>';
                $mail = new Mail();
    			$mail->protocol = $this->config->get('config_mail_protocol');
    			$mail->parameter = $this->config->get('config_mail_parameter');
    			$mail->hostname = $this->config->get('config_smtp_host');
    			$mail->username = $this->config->get('config_smtp_username');
    			$mail->password = $this->config->get('config_smtp_password');
    			$mail->port = $this->config->get('config_smtp_port');
    			$mail->timeout = $this->config->get('config_smtp_timeout');				
    			$mail->setTo($from);
    	  		$mail->setFrom($to);
    	  		$mail->setSender($this->config->get('config_name'));
    	  		$mail->setSubject($this->language->get('text_customer').$form_name);
    	  		$mail->setHtml($user_html);
                
                if(!empty($file_data)) {
                
                    foreach($file_data as $file) {
                        $mail->addAttachment($file['tmp_name'],$file['name']);
                    }
                }
                
          		$mail->send();
            } elseif(!$letter && !empty($user_data)) {
                $user_html_reg = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title></head><body style="margin:0; padding:0; font-family: Verdana, Arial; font-size: 13px; color:#555;">';
                $user_html_reg .= '<div style="background:#f0f0f0; border-top:1px solid #777; box-shadow:0 -2px 2px #999; -webkit-box-shadow:0 -2px 2px #999;">';
                $user_html_reg .= '<table cellpadding="0" cellspacing="0" style="width:auto; margin: 0.2em 2em 2em; font-size: 100%;"><tr><td style="color:#555; padding:1em 0 0.4em; font-size: 110%; font-weight:bold; text-shadow:0 1px 0 #fff;" colspan="2">'.$this->language->get('text_customer_reg').$this->config->get('config_name').'</td></tr>';
                $user_html_reg .= $user_html_login;
                $user_html_reg .= '</table></div>';
                $user_html_reg .= '</body></html>';
                $mail = new Mail();
    			$mail->protocol = $this->config->get('config_mail_protocol');
    			$mail->parameter = $this->config->get('config_mail_parameter');
    			$mail->hostname = $this->config->get('config_smtp_host');
    			$mail->username = $this->config->get('config_smtp_username');
    			$mail->password = $this->config->get('config_smtp_password');
    			$mail->port = $this->config->get('config_smtp_port');
    			$mail->timeout = $this->config->get('config_smtp_timeout');				
    			$mail->setTo($from);
    	  		$mail->setFrom($to);
    	  		$mail->setSender($this->config->get('config_name'));
    	  		$mail->setSubject($this->language->get('text_customer_reg').$this->config->get('config_name'));
    	  		$mail->setHtml($user_html_reg);
                $mail->send();
            }
        
        if($form_info['databaseon'] == 1) {
            
            if(!empty($file_data)) {
                $file_link = array();
                foreach($file_data as $file) {
                    if (is_uploaded_file($file['tmp_name']) && file_exists($file['tmp_name'])) {
                        $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8')));
                        $file_name = basename($filename);
                        move_uploaded_file($file['tmp_name'], DIR_DOWNLOAD . $file_name);
                        
                        $file_link[] = '<a target="_blank" href="'. HTTP_SERVER .'download/'. $file_name.'">'.$file_name.'</a>';
                        
                    }
                    
                }
                if(!empty($file_link)) {
                    
                    $db_data .= '<tr><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-right:2em; color:#888; width:1%;">&nbsp;</td><td style="padding: 0.3em 1em; border-bottom:1px dotted #ddd; padding-left:0; color:#333;">'.implode('&nbsp;&nbsp;&nbsp;',$file_link).'</td></tr>';
                }
                
            }
            $db_data .= '</table></div>';
            $data = array(
                'form_id' => $form_id,
                'form_data' => $db_data
            );
            
            $this->model_catalog_form->addFormData($data);
        }
            
        echo $this->language->get('text_message');
    	} else {
    	   echo $this->language->get('error_error');   
          
    	}
        
        
    }
    
    public function ajax_form(){
        $json = array();
        if($this->request->server['REQUEST_METHOD'] == 'POST'){
            if($this->request->post['form_id']){
                $this->load->language('module/form');
        		$this->load->model('catalog/form');
                $form_id = $this->request->post['form_id'];
                $form_info = $this->model_catalog_form->getForm($form_id);
                
                $this->data['view_form'] = true;
                if($form_info['use_type'] == 2 && !$this->cart->hasProducts() ) {
                    $this->data['view_form'] = false;
                    
                } 
                
                
                    $this->data['link_form'] = false;
                    $this->data['heading_title'] = $form_info['name'];
                    $this->data['action'] = $this->url->link('information/form&form_id='.$form_id);
                    $this->data['form_id'] = 'of'.$form_info['form_id'];
                    $this->data['class'] = 'form'.$form_info['prefix'];
                    $this->data['items'] = $this->model_catalog_form->getItems($form_id);
                    $this->data['button'] = utf8_strlen($form_info['button']) == 0 ? $this->language->get('text_button') : $form_info['button'];
                    $this->data['hiddens'] = array();
                    $this->data['fileon'] = $form_info['file'] == 1 ? true : false;
                    $this->data['sid'] = 'sid-'.$form_info['form_id'];
                    $this->data['customer'] = $this->customer->isLogged() ? true : false;
                    
                    
                    $div_start = '';
                    $div_finish = '';
                    $this->data['linkmas'] = '';
                    
                    $this->data['modal'] = false;
                    $this->document->addScript($this->url->link('information/form/valid_js_module','form_id='.$form_id.'&fb=0'));
                    
                    
                    $this->data['div_start'] = $div_start;
                    $this->data['div_finish'] = $div_finish;
                    
                    
                    if (isset($this->request->post['product_id']) && $form_info['use_type'] == 1 ) {
        			$product_id = $this->request->post['product_id'];
                    $this->load->model('catalog/product');
                    $product_info = $this->model_catalog_product->getProduct($product_id);
                    
                        
                    $product_prise = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    
                    $this->data['heading_title'] .= ' '.$product_info['name'];
                    $product_url = $this->url->link('product/product&product_id='.$product_id);
                    $this->data['hiddens'][] = '<input type="hidden" name="product[name]" value="'.$product_info['name'].'" />';
                    $this->data['hiddens'][] = '<input type="hidden" name="product[price]" value="'.$product_prise.'" />';
                    $this->data['hiddens'][] = '<input type="hidden" name="product[id]" value="'.$product_id.'" />';
                    $this->data['hiddens'][] = '<input type="hidden" name="product[url]" value="'.$product_url.'" />';
                    if ((float)$product_info['special']) {
        				$product_special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    $this->data['hiddens'][] = '<input type="hidden" name="product[special]" value="'.$product_special.'" />';    
        			} 
                    
            		
                    }
                    
                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/form.tpl')) {
            			$this->template = $this->config->get('config_template') . '/template/module/form.tpl';
            		} else {
            			$this->template = 'default/template/module/form.tpl';
            		}
            		
            		
                    $json['form'] = $this->render();
               
            
            $this->response->setOutput(json_encode($json));
            
            } else {
                $json['error'] = 'non';
                
            }
        }
    }
	
}
?>