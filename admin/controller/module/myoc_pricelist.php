<?php
class ControllerModuleMyocPricelist extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/product');
        $this->load->language('module/myoc_pricelist');

        $this->document->setTitle($this->language->get('common_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('myoc_pricelist', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->get['exit'])) {
				$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->redirect($this->url->link('module/myoc_pricelist', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

        $this->data['heading_title'] = $this->language->get('common_title');

		$this->data['tab_pricelist'] = $this->language->get('tab_pricelist');
		$this->data['tab_layout'] = $this->language->get('tab_layout');

		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_login'] = $this->language->get('entry_login');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$this->data['entry_filter_by_category'] = $this->language->get('entry_filter_by_category');
		$this->data['entry_page'] = $this->language->get('entry_page');
		$this->data['entry_image_dimension'] = $this->language->get('entry_image_dimension');
		$this->data['entry_barcode_dimension'] = $this->language->get('entry_barcode_dimension');
		$this->data['entry_barcode_zoom'] = $this->language->get('entry_barcode_zoom');
		$this->data['entry_barcode_fontsize'] = $this->language->get('entry_barcode_fontsize');
		$this->data['entry_multicart'] = $this->language->get('entry_multicart');
		$this->data['entry_print'] = $this->language->get('entry_print');
		$this->data['entry_print_paging'] = $this->language->get('entry_print_paging');
		$this->data['entry_pdf'] = $this->language->get('entry_pdf');
		$this->data['entry_pdf_paging'] = $this->language->get('entry_pdf_paging');
		$this->data['entry_pdf_orientation'] = $this->language->get('entry_pdf_orientation');
		$this->data['entry_description_length'] = $this->language->get('entry_description_length');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_add_wishlist'] = $this->language->get('entry_add_wishlist');
		$this->data['entry_add_compare'] = $this->language->get('entry_add_compare');
		$this->data['entry_link_header'] = $this->language->get('entry_link_header');
		$this->data['entry_link_footer'] = $this->language->get('entry_link_footer');
		$this->data['entry_filter_stock'] = $this->language->get('entry_filter_stock');
		$this->data['entry_filter_special'] = $this->language->get('entry_filter_special');
		$this->data['entry_filter_discount'] = $this->language->get('entry_filter_discount');
		$this->data['entry_design'] = $this->language->get('entry_design');
		$this->data['entry_layout_override'] = $this->language->get('entry_layout_override');
		$this->data['entry_pricelist'] = $this->language->get('entry_pricelist');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_portrait'] = $this->language->get('text_portrait');
		$this->data['text_landscape'] = $this->language->get('text_landscape');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_column_name'] = $this->language->get('text_column_name');
		$this->data['text_sort_order'] = $this->language->get('text_sort_order');
		$this->data['text_checkbox'] = $this->language->get('text_checkbox');
		$this->data['text_num'] = $this->language->get('text_num');
		$this->data['text_product_info'] = $this->language->get('text_product_info');
		$this->data['text_image'] = $this->language->get('text_image');
		$this->data['text_name'] = $this->language->get('text_name');
		$this->data['text_description'] = $this->language->get('text_description');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_sku'] = $this->language->get('text_sku');
		$this->data['text_upc'] = $this->language->get('text_upc');
		$this->data['text_ean'] = $this->language->get('text_ean');
		$this->data['text_jan'] = $this->language->get('text_jan');
		$this->data['text_isbn'] = $this->language->get('text_isbn');
		$this->data['text_mpn'] = $this->language->get('text_mpn');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_quantity'] = $this->language->get('text_quantity');
		$this->data['text_stock_status'] = $this->language->get('text_stock_status');
		$this->data['text_minimum'] = $this->language->get('text_minimum');
		$this->data['text_rating'] = $this->language->get('text_rating');
		$this->data['text_dimension'] = $this->language->get('text_dimension');
		$this->data['text_weight'] = $this->language->get('text_weight');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_action'] = $this->language->get('text_action');
		$this->data['text_blank'] = $this->language->get('text_blank');
		$this->data['text_attributes'] = $this->language->get('text_attributes');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_bc_codabar'] = $this->language->get('text_bc_codabar');
		$this->data['text_bc_code11'] = $this->language->get('text_bc_code11');
		$this->data['text_bc_code39'] = $this->language->get('text_bc_code39');
		$this->data['text_bc_code93'] = $this->language->get('text_bc_code93');
		$this->data['text_bc_code128'] = $this->language->get('text_bc_code128');
		$this->data['text_bc_ean8'] = $this->language->get('text_bc_ean8');
		$this->data['text_bc_ean13'] = $this->language->get('text_bc_ean13');
		$this->data['text_bc_std25'] = $this->language->get('text_bc_std25');
		$this->data['text_bc_int25'] = $this->language->get('text_bc_int25');
		$this->data['text_bc_msi'] = $this->language->get('text_bc_msi');
		$this->data['text_bc_datamatrix'] = $this->language->get('text_bc_datamatrix');

		$this->data['column_column'] = $this->language->get('column_column');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sortable'] = $this->language->get('column_sortable');
		$this->data['column_barcode'] = $this->language->get('column_barcode');
		$this->data['column_pricelist'] = $this->language->get('column_pricelist');
		$this->data['column_print'] = $this->language->get('column_print');
		$this->data['column_pdf'] = $this->language->get('column_pdf');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_save_exit'] = $this->language->get('button_save_exit');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_column'] = $this->language->get('button_add_column');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_add_pricelist'] = $this->language->get('button_add_pricelist');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['myoc_copyright'] = $this->language->get('myoc_copyright');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

        $this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('common_title'),
			'href'      => $this->url->link('module/myoc_pricelist', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

        $this->data['action'] = $this->url->link('module/myoc_pricelist', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_exit'] = $this->url->link('module/myoc_pricelist', 'token=' . $this->session->data['token'] . '&exit=1', 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];

		$this->data['modules'] = array();
		
		if (isset($this->request->post['myoc_pricelist_module'])) {
			$this->data['modules'] = $this->request->post['myoc_pricelist_module'];
		} elseif ($this->config->get('myoc_pricelist_module')) { 
			$this->data['modules'] = $this->config->get('myoc_pricelist_module');
		}
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		if(isset($this->request->post['myocwpl_data'])) {
			$myocwpl_data = $this->request->post['myocwpl_data'];
		} else {
			$myocwpl_data = $this->config->get('myocwpl_data');
		}
		$this->data['pricelists'] = array();
		$this->data['next_pricelist_id'] = 1;
		if($myocwpl_data) {
			$this->data['pricelists'] = $myocwpl_data;
			$pricelist_ids = array();
			foreach ($myocwpl_data as $wpl) {
				$pricelist_ids[] = $wpl['pricelist_id'];
			}
			sort($pricelist_ids);
			$this->data['next_pricelist_id'] += array_pop($pricelist_ids);
		}

        $this->load->model('localisation/language');
        $this->data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('sale/customer_group');
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        $this->load->model('setting/store');
        $this->data['stores'] = $this->model_setting_store->getStores();
        
        $this->load->model('catalog/manufacturer');
        $this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
        
        $this->load->model('catalog/category');
        $this->data['categories'] = $this->model_catalog_category->getCategories(0);

		$this->load->model('catalog/product');
		$this->data['products'] = $this->model_catalog_product->getProducts();

		$this->load->model('catalog/attribute');
		$this->data['attributes'] = $this->model_catalog_attribute->getAttributes();

        $this->template = 'myoc/pricelist.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }

    private function validate() {
		if (!$this->user->hasPermission('modify', 'module/myoc_pricelist')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if(isset($this->request->post['myocwpl_data'])) {
			foreach ($this->request->post['myocwpl_data'] as $pricelist_row => $wpl_data) {
				foreach ($wpl_data['name'] as $language_id => $value) {
					if ((strlen($value) < 1) || (strlen($value) > 255)) {
						$this->error['name'][$pricelist_row][$language_id] = $this->language->get('error_name');
					}
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function install() {
		$this->load->model('design/layout');
		$this->load->model('setting/store');
		$layouts = $this->model_design_layout->getLayouts();

		$layout_name = 'Price List';
		foreach($layouts as $layout){
			if($layout['name'] == $layout_name){
				$this->model_design_layout->deleteLayout($layout['layout_id']);
				break;
			}
		}
		$layout_data = array();
		$layout_data['name'] = $layout_name;
		$layout_data['layout_route'][0] = array(
			'store_id'=> '0',
			'route'	  => 'product/pricelist'
		);

		$stores = $this->model_setting_store->getStores();
		foreach ($stores as $store) {
			
			$layout_data['layout_route'][] = array(
				'store_id'=> $store['store_id'],
				'route'	  => 'product/pricelist'
			);
		}
		$this->model_design_layout->addLayout($layout_data);
	}

	public function uninstall() {
		$this->load->model('design/layout');
		$layouts = $this->model_design_layout->getLayouts();

		$layout_name = 'Price List';
		foreach($layouts as $layout){
			if($layout['name'] == $layout_name){
				$this->model_design_layout->deleteLayout($layout['layout_id']);
				break;
			}
		}
	}
}
?>