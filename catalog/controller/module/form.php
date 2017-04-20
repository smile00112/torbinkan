<?php  
class ControllerModuleForm extends Controller {
    private $error = array();
	protected function index($setting) {
		$this->load->language('module/form');
		$this->load->model('catalog/form');
        $form_id = $setting['form_id'];
        $form_info = $this->model_catalog_form->getForm($form_id);
        
        $this->data['view_form'] = true;
        if($form_info['use_type'] == 2 && !$this->cart->hasProducts() ) {
            $this->data['view_form'] = false;
            
        } 
        
        if($setting['button'] == 0) {
            $this->data['link_form'] = false;
            
            $this->document->addScript('catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js');
            $this->document->addScript('catalog/view/javascript/oforms/oforms_module.js');
            $this->document->addScript('catalog/view/javascript/oforms/jquery.validate.js');
            $this->document->addScript('catalog/view/javascript/oforms/additional-methods.js');
            $this->document->addScript('catalog/view/javascript/oforms/jquery.form.js');
            $this->document->addScript($this->url->link('information/form/valid_lang'));
            $this->document->addScript($this->url->link('information/form/dt_lang'));
            
            $this->document->addStyle('catalog/view/theme/default/stylesheet/oforms.css');
            
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
            if($setting['modal_form'] == 1){
                $this->document->addScript('catalog/view/javascript/jquery/fancybox/jquery.mousewheel-3.0.4.pack.js');
                $this->document->addScript('catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.js');
                $this->document->addScript($this->url->link('information/form/fancybox','form_id='.$form_id));
                $this->document->addStyle('catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css');
                $this->document->addScript($this->url->link('information/form/valid_js_module','form_id='.$form_id.'&fb=1'));
            
                
                $div_start .= '<div style="display: none;">';
                $div_finish .= '</div>';
                $this->data['modal'] = true;
                
                $this->data['form_link'] = '#of'.$form_id;
                $this->data['form_class'] = $this->data['class'];
                
                
                $this->data['linkmas'] .= '<a id="mas-'.$form_id.'" href="#sid-'.$form_id.'">Open</a>';
            } else {
                $this->data['modal'] = false;
                $this->document->addScript($this->url->link('information/form/valid_js_module','form_id='.$form_id.'&fb=0'));
            }
            
            $this->data['div_start'] = $div_start;
            $this->data['div_finish'] = $div_finish;
            
            
            if (isset($this->request->get['product_id']) && $form_info['use_type'] == 1 ) {
			$product_id = $this->request->get['product_id'];
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
            
            
        } else {
            $this->data['heading_title'] = $form_info['name'];
            $this->data['link_form'] = true;
            $this->data['form_name'] = $form_info['name'];
            $this->data['form_link'] = $this->url->link('information/form&form_id='.$form_id);
            
            
        }

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/form.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/form.tpl';
		} else {
			$this->template = 'default/template/module/form.tpl';
		}
		
		$this->render();
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
            if ($item['item_type'] == 'file' && $_FILES['item-'.$item['item_id']]['error'] == 0) {
                  if (utf8_strlen($item['pattern']) !=0 ){
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
    
    
}
?>