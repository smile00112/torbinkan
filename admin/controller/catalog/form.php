<?php    
class ControllerCatalogForm extends Controller { 
	private $error = array();
  	public function index() {
		$this->load->language('catalog/form');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/form');
    	$this->getList();
  	}
    
    public function insert() {
		$this->load->language('catalog/form');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/form');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_form->addform($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/form', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    	$this->getForm();
  	}
    
    public function update() {
		$this->load->language('catalog/form');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/form');
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_form->editform($this->request->get['form_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/form', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    	$this->getForm();
  	}
  	public function delete() {
		$this->load->language('catalog/form');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/form');
    	if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $form_id) {
				$this->model_catalog_form->deleteForm($form_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/form', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
    	$this->getList();

  	} 
    
    private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.name';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/form', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		$this->data['insert'] = $this->url->link('catalog/form/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/form/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['forms'] = array();
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$form_total = $this->model_catalog_form->getTotalForms();
		$results = $this->model_catalog_form->getForms($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/form/update', 'token=' . $this->session->data['token'] . '&form_id=' . $result['form_id'] . $url, 'SSL')
			);
			$this->data['forms'][] = array(
				'form_id' => $result['form_id'],
				'name'            => $result['name'],
				'status'     	  => $result['status'] == 1 ? $this->language->get('entry_on') : $this->language->get('entry_off'),
				'selected'        => isset($this->request->post['selected']) && in_array($result['form_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['sort_name'] = $this->url->link('catalog/form', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/form', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $form_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/form', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'catalog/form_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->response->setOutput($this->render());
	}
    
    
    private function getForm() {
        $this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
    	$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_prefix'] = $this->language->get('entry_prefix');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['tab_data'] = $this->language->get('tab_data');
        $this->data['tab_items'] = $this->language->get('tab_items');
        $this->data['button_add_item'] = $this->language->get('button_add_item');
		$this->data['entry_button'] = $this->language->get('entry_button');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_file'] = $this->language->get('entry_file');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_on'] = $this->language->get('entry_on');
        $this->data['entry_off'] = $this->language->get('entry_off');
        $this->data['entry_use'] = $this->language->get('entry_use');
        $this->data['entry_fastorder'] = $this->language->get('entry_fastorder');
        $this->data['entry_opdercart'] = $this->language->get('entry_opdercart');
        $this->data['entry_database'] = $this->language->get('entry_database');
        $this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $this->data['entry_item_name'] = $this->language->get('entry_item_name');
        $this->data['entry_item_type'] = $this->language->get('entry_item_type');
        $this->data['entry_item_type8'] = $this->language->get('entry_item_type8');
        $this->data['entry_item_type1'] = $this->language->get('entry_item_type1');
        $this->data['entry_item_type2'] = $this->language->get('entry_item_type2');
        $this->data['entry_item_type3'] = $this->language->get('entry_item_type3');
        $this->data['entry_item_type4'] = $this->language->get('entry_item_type4');
        $this->data['entry_item_type5'] = $this->language->get('entry_item_type5');
        $this->data['entry_item_type6'] = $this->language->get('entry_item_type6');
        $this->data['entry_item_type7'] = $this->language->get('entry_item_type7');
        $this->data['entry_item_type9'] = $this->language->get('entry_item_type9');
        $this->data['entry_item_type10'] = $this->language->get('entry_item_type10');
        $this->data['entry_item_type11'] = $this->language->get('entry_item_type11');
        $this->data['entry_item_type12'] = $this->language->get('entry_item_type12');
        $this->data['entry_sort'] = $this->language->get('entry_sort');
        $this->data['entry_required'] = $this->language->get('entry_required');
        $this->data['entry_validation'] = $this->language->get('entry_validation');
        $this->data['entry_validation1'] = $this->language->get('entry_validation1');
        $this->data['entry_validation2'] = $this->language->get('entry_validation2');
        $this->data['entry_validation3'] = $this->language->get('entry_validation3');
        $this->data['entry_validation4'] = $this->language->get('entry_validation4');
        $this->data['entry_pattern'] = $this->language->get('entry_pattern');
        $this->data['entry_value'] = $this->language->get('entry_value');
        $this->data['entry_setfrom'] = $this->language->get('entry_setfrom');
        $this->data['entry_setsender'] = $this->language->get('entry_setsender');
        $this->data['entry_letter'] = $this->language->get('entry_letter');
        $this->data['entry_useron'] = $this->language->get('entry_useron');
         
       if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/form', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
        $this->data['breadcrumbs'][] = array(
       		'text'      => !isset($this->request->get['form_id']) ? $this->language->get('text_form_add') : $this->language->get('text_form_edit'),
			'href'      => !isset($this->request->get['form_id']) ? $this->url->link('catalog/form/insert', 'token=' . $this->session->data['token'], 'SSL') : $this->url->link('catalog/form/update', 'token=' . $this->session->data['token'].'&form_id='.$this->request->get['form_id'], 'SSL'),
      		'separator' => ' :: '
   		);
        
        if (!isset($this->request->get['form_id'])) {
            $this->data['heading_title'] = $this->language->get('text_form_add');
        } else {
            $this->data['heading_title'] = $this->language->get('text_form_edit');
        }
        
		if (!isset($this->request->get['form_id'])) {
			$this->data['action'] = $this->url->link('catalog/form/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/form/update', 'token=' . $this->session->data['token'] . '&form_id=' . $this->request->get['form_id'] . $url, 'SSL');
		}
		$this->data['cancel'] = $this->url->link('catalog/form', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];
    	if (isset($this->request->get['form_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$form_info = $this->model_catalog_form->getform($this->request->get['form_id']);
    	}
    	if (isset($this->request->post['prefix'])) {
      		$this->data['prefix'] = $this->request->post['prefix'];
    	} elseif (isset($form_info)) {
			$this->data['prefix'] = $form_info['prefix'];
		} else {	
      		$this->data['prefix'] = '';
    	}
		if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (isset($form_info)) {
			$this->data['status'] = $form_info['status'];
		} else {
      		$this->data['status'] = '';
    	}
        if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (isset($form_info)) {
			$this->data['email'] = $form_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
        if (isset($this->request->post['newsletteron'])) {
      		$this->data['newsletteron'] = $this->request->post['newsletteron'];
    	} elseif (isset($form_info)) {
			$this->data['newsletteron'] = $form_info['newsletteron'];
		} else {
      		$this->data['newsletteron'] = 0;
    	}
        if (isset($this->request->post['useron'])) {
      		$this->data['useron'] = $this->request->post['useron'];
    	} elseif (isset($form_info)) {
			$this->data['useron'] = $form_info['useron'];
		} else {
      		$this->data['useron'] = 0;
    	}
        if (isset($this->request->post['databaseon'])) {
      		$this->data['databaseon'] = $this->request->post['databaseon'];
    	} elseif (isset($form_info)) {
			$this->data['databaseon'] = $form_info['databaseon'];
		} else {
      		$this->data['databaseon'] = 0;
    	}
        if (isset($this->request->post['file'])) {
      		$this->data['fileon'] = $this->request->post['file'];
    	} elseif (isset($form_info)) {
			$this->data['fileon'] = $form_info['file'];
		} else {
      		$this->data['fileon'] = 0;
    	}
        if (isset($this->request->post['use_type'])) {
      		$this->data['use_type'] = $this->request->post['use_type'];
    	} elseif (isset($form_info)) {
			$this->data['use_type'] = $form_info['use_type'];
		} else {
      		$this->data['use_type'] = 0;
    	}
        if (isset($this->request->post['form_description'])) {
			$this->data['form_description'] = $this->request->post['form_description'];
		} elseif (isset($this->request->get['form_id'])) {
			$this->data['form_description'] = $this->model_catalog_form->getFormDescription($this->request->get['form_id']);
		} else {
			$this->data['form_description'] = array();
		}
        $this->data['items'] = array();
        if(isset($this->request->post['items'])) {
            $this->data['items'] = $this->request->post['items'];
        } elseif (isset($this->request->get['form_id'])) {
            $this->data['items'] = $this->model_catalog_form->getFormItems($this->request->get['form_id']);
        }
        foreach($this->data['items'] as $key=>$value){
            if (isset($this->error['label'][$key])) {
			$this->data['error_label'][$key] = $this->error['label'][$key];
		      } else {
			$this->data['error_label'][$key] = '';
		      }
        }
        $this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
        $this->template = 'catalog/form_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->response->setOutput($this->render());
	}
	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/form')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
        foreach ($this->request->post['form_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
        foreach ($this->request->post['items'] as $item=>$item_value) {
            foreach($item_value['description'] as $language_id => $value) {
               if ((utf8_strlen($value['label']) < 1) || (utf8_strlen($value['label']) > 255)) {
				$this->error['label'][$item][$language_id] = $this->language->get('error_name');
			    } 
            }
        }
        
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
    
}