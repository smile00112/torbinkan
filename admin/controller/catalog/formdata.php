<?php 
class ControllerCatalogFormdata extends Controller { 
	private $error = array();
    public function index() {
		$this->load->language('catalog/form');
		$this->document->setTitle($this->language->get('heading_title_data'));
		$this->load->model('catalog/form');
    	$this->getFormList();
  	}
    
    public function delete() {
        $this->load->language('catalog/form');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/form');
    	if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $form_data_id) {
				$this->model_catalog_form->deleteFormData($form_data_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_data');
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
			$this->redirect($this->url->link('catalog/formdata/viewform', 'token=' . $this->session->data['token'].'&form_id=' . $this->request->post['form_id'].$url, 'SSL'));
    	}
    	$this->getFormList();
    }
    
    private function getFormList() {
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
       		'text'      => $this->language->get('heading_title_data'),
			'href'      => $this->url->link('catalog/formdata', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		$this->data['forms'] = array();
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$form_total = $this->model_catalog_form->getTotalFormsDbOn();
		$results = $this->model_catalog_form->getFormsDbOn($data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('button_view'),
				'href' => $this->url->link('catalog/formdata/viewform', 'token=' . $this->session->data['token'] . '&form_id=' . $result['form_id'] . $url, 'SSL')
			);
			$this->data['forms'][] = array(
				'form_id' => $result['form_id'],
				'name'            => $result['name'],
                'total' => $this->model_catalog_form->getTotalDataForm($result['form_id']),
				'status'     	  => $this->model_catalog_form->getNewDataForm($result['form_id']) > 0 ? $this->language->get('text_new') : $this->language->get('text_notnew'),
				'action'          => $action
			);
		}
		$this->data['heading_title'] = $this->language->get('heading_title_data');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');	
        $this->data['column_total'] = $this->language->get('column_total');		
	
        
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
		$this->data['sort_name'] = $this->url->link('catalog/formdata', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/formdata', 'token=' . $this->session->data['token'] . '&sort=not_view' . $url, 'SSL');
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
		$pagination->url = $this->url->link('catalog/formdata', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'catalog/formdata_formlist.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->response->setOutput($this->render());
	}
    
    public function viewform() {
        $this->load->language('catalog/form');
        $this->load->model('catalog/form');
        $this->document->setTitle($this->language->get('text_form_datas'));
        $form_id = $this->request->get['form_id'];
        if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'form_data_id';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
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
       		'text'      => $this->language->get('heading_title_data'),
			'href'      => $this->url->link('catalog/formdata', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
        $this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_form_datas'),
			'href'      => $this->url->link('catalog/formdata/viewform', 'token=' . $this->session->data['token'].'&form_id='.$form_id, 'SSL'),
      		'separator' => ' :: '
   		);
        $this->data['back'] = $this->url->link('catalog/formdata', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/formdata/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $formdata_total = $this->model_catalog_form->getTotalDataForm($form_id);
        
        $data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
        $this->data['form_datas'] = array();
        $this->data['form_id'] = $form_id;
        $results = $this->model_catalog_form->getFormDatas($form_id, $data);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
                'text' => $this->language->get('button_view'),
				'href' => $this->url->link('catalog/formdata/viewdata', 'token=' . $this->session->data['token'] . '&form_data_id=' . $result['form_data_id'] , 'SSL')
			);
			$this->data['form_datas'][] = array(
                'form_data_id'    => $result['form_data_id'],
				'date_added'      => $result['date_added'],
				'status'     	  => $result['not_view'] == 0 ? $this->language->get('text_newdata') : $this->language->get('text_notnewdata'),
				'selected'        => isset($this->request->post['selected']) && in_array($result['form_data_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}
        $this->data['heading_title'] = $this->language->get('text_form_datas');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
	    $this->data['button_delete'] = $this->language->get('button_delete');
        $this->data['button_back'] = $this->language->get('button_back');
        
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
		$this->data['sort_name'] = $this->url->link('catalog/formdata/viewform', 'token=' . $this->session->data['token'] . '&sort=form_data_id&form_id=' . $form_id.$url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/formdata/viewform', 'token=' . $this->session->data['token'] . '&sort=not_view&form_id=' . $form_id.$url, 'SSL');
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $formdata_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/formform', 'token=' . $this->session->data['token']. '&form_id=' . $form_id . $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'catalog/formdata_formdatas.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->response->setOutput($this->render());
    }
    
    public function viewdata(){
        $this->load->language('catalog/form');
        $this->load->model('catalog/form');
        $this->document->setTitle($this->language->get('heading_title_data'));
        $form_data_id = $this->request->get['form_data_id'];
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
        $this->data['heading_title'] = $this->language->get('heading_title_data');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_form'] = $this->language->get('column_form');
		$this->data['button_delete'] = $this->language->get('button_delete');
        $this->data['button_back'] = $this->language->get('button_back');
        $this->data['form_data'] = $this->model_catalog_form->getFormData($form_data_id);
        if($this->data['form_data']['not_view'] == 0) {
            $this->update_view($form_data_id);
        }
        $this->data['form_id'] = $this->data['form_data']['form_id'];
        $this->data['form'] = $this->url->link('catalog/form/update', 'token=' . $this->session->data['token'].'&form_id='.$this->data['form_data']['form_id'], 'SSL');
        $this->data['form_name'] = $this->model_catalog_form->getFormName($this->data['form_data']['form_id']);
        
        $this->data['back'] = $this->url->link('catalog/formdata/viewform', 'token=' . $this->session->data['token'].'&form_id='.$this->data['form_data']['form_id'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/formdata/delete', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_data'),
			'href'      => $this->url->link('catalog/formdata', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
        $this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_form_datas'),
			'href'      => $this->url->link('catalog/formdata/viewform', 'token=' . $this->session->data['token'].'&form_id='.$this->data['form_id'], 'SSL'),
      		'separator' => ' :: '
   		);
        $this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_form_data'),
			'href'      => $this->url->link('catalog/formdata/viewdata', 'token=' . $this->session->data['token'].'&form_data_id='.$form_data_id, 'SSL'),
      		'separator' => ' :: '
   		);
        
        $this->template = 'catalog/formdata_formdata.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->response->setOutput($this->render());
    }
    
    public function update_view ($form_data_id) {
        $this->model_catalog_form->updateView($form_data_id);
    }
    
    private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/formdata')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
}