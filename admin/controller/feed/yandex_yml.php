<?php
class ControllerFeedYandexYml extends Controller {
	protected $error = array();
	
	protected $allowedCurrencies = array('RUR', 'RUB', 'USD', 'EUR', 'BYR', 'KZT', 'UAH');
	
	protected $CONFIG_PREFIX = 'yandex_yml_';
	
	protected function preparePostData() {
		if (isset($this->request->post['yandex_yml_categories'])) {
			$this->request->post['yandex_yml_categories'] = implode(',', $this->request->post['yandex_yml_categories']);
		}
		if (isset($this->request->post['yandex_yml_manufacturers'])) {
			$this->request->post['yandex_yml_manufacturers'] = implode(',', $this->request->post['yandex_yml_manufacturers']);
		}
		if (isset($this->request->post['yandex_yml_categ_mapping'])) {
			$this->request->post['yandex_yml_categ_mapping'] = serialize($this->request->post['yandex_yml_categ_mapping']);
		}
		if (isset($this->request->post['yandex_yml_categ_delivery_cost'])) {
			$this->request->post['yandex_yml_categ_delivery_cost'] = serialize($this->request->post['yandex_yml_categ_delivery_cost']);
		}
		if (isset($this->request->post['yandex_yml_categ_cpa'])) {
			$this->request->post['yandex_yml_categ_cpa'] = serialize($this->request->post['yandex_yml_categ_cpa']);
		}
		if (isset($this->request->post['yandex_yml_categ_sales_notes'])) {
			$this->request->post['yandex_yml_categ_sales_notes'] = serialize($this->request->post['yandex_yml_categ_sales_notes']);
		}
		if (isset($this->request->post['yandex_yml_blacklist'])) {
			$this->request->post['yandex_yml_blacklist'] = implode(',', $this->request->post['yandex_yml_blacklist']);
		}
		if (isset($this->request->post['yandex_yml_pricefrom'])) {
			$this->request->post['yandex_yml_pricefrom'] = floatval($this->request->post['yandex_yml_pricefrom']);
		}
		if (isset($this->request->post['yandex_yml_attributes'])) {
			$this->request->post['yandex_yml_attributes'] = implode(',', $this->request->post['yandex_yml_attributes']);
		}
		if (isset($this->request->post['yandex_yml_color_options'])) {
			$this->request->post['yandex_yml_color_options'] = implode(',', $this->request->post['yandex_yml_color_options']);
		}
		if (isset($this->request->post['yandex_yml_size_options'])) {
			$this->request->post['yandex_yml_size_options'] = implode(',', $this->request->post['yandex_yml_size_options']);
		}
		if (isset($this->request->post['yandex_yml_size_units'])) {
			$this->request->post['yandex_yml_size_units'] = serialize($this->request->post['yandex_yml_size_units']);
		}
	}

	protected function setLanguageData() {
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_feed'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('feed/yandex_yml', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_categories'] = $this->language->get('tab_categories');
		$this->data['tab_attributes'] = $this->language->get('tab_attributes');
		$this->data['tab_tailor'] = $this->language->get('tab_tailor');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_blacklist'] = $this->language->get('text_blacklist');
		$this->data['text_whitelist'] = $this->language->get('text_whitelist');

		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_data_feed'] = $this->language->get('entry_data_feed');
		//$this->data['entry_shopname'] = $this->language->get('entry_shopname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_datamodel'] = $this->language->get('entry_datamodel');
		
		$this->data['entry_name_field'] = $this->language->get('entry_name_field');
		$this->data['entry_model_field'] = $this->language->get('entry_model_field');
		$this->data['entry_vendorcode_field'] = $this->language->get('entry_vendorcode_field');
		$this->data['entry_typeprefix_field'] = $this->language->get('entry_typeprefix_field');
		$this->data['entry_barcode_field'] = $this->language->get('entry_barcode_field');
		
		$this->data['datamodels'] = $this->language->get('datamodels');
		$this->data['entry_delivery_cost'] = $this->language->get('entry_delivery_cost');
		
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_manufacturers'] = $this->language->get('entry_manufacturers');
		$this->data['entry_blacklist_type'] = $this->language->get('entry_blacklist_type');
		$this->data['entry_blacklist'] = $this->language->get('entry_blacklist');
		$this->data['entry_whitelist'] = $this->language->get('entry_whitelist');
		$this->data['entry_pricefrom'] = $this->language->get('entry_pricefrom');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		$this->data['entry_oldprice'] = $this->language->get('entry_oldprice');
		$this->data['entry_unavailable'] = $this->language->get('entry_unavailable');
		$this->data['entry_in_stock'] = $this->language->get('entry_in_stock');
		$this->data['entry_out_of_stock'] = $this->language->get('entry_out_of_stock');

		$this->data['entry_pickup'] = $this->language->get('entry_pickup');
		$this->data['entry_sales_notes'] = $this->language->get('entry_sales_notes');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_numpictures'] = $this->language->get('entry_numpictures');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_cron_run'] = $this->language->get('entry_cron_run');
		$this->data['entry_export_url'] = $this->language->get('entry_export_url');

		//++++ Для вкладки аттрибутов ++++
		$this->data['tab_attributes_description'] = str_replace('%attr_url%', $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL'), $this->language->get('tab_attributes_description'));
		$this->data['entry_attributes'] = $this->language->get('entry_attributes');
		$this->data['entry_adult'] = $this->language->get('entry_adult');
		$this->data['entry_manufacturer_warranty'] = $this->language->get('entry_manufacturer_warranty');
		$this->data['entry_country_of_origin'] = $this->language->get('entry_country_of_origin');
		$this->data['entry_product_rel'] = $this->language->get('entry_product_rel');
		$this->data['entry_product_accessory'] = $this->language->get('entry_product_accessory');
		$this->data['entry_attr_vs_description'] = $this->language->get('entry_attr_vs_description');

		//++++ Для магазинов одежды ++++
		$this->data['entry_color_option'] = $this->language->get('entry_color_option');
		$this->data['entry_size_option'] = $this->language->get('entry_size_option');
		$this->data['entry_size_unit'] = $this->language->get('entry_size_unit');
		$this->data['entry_optioned_name'] = $this->language->get('entry_optioned_name');
		$this->data['optioned_name_no'] = $this->language->get('optioned_name_no');
		$this->data['optioned_name_short'] = $this->language->get('optioned_name_short');
		$this->data['optioned_name_long'] = $this->language->get('optioned_name_long');
		
		$this->data['size_units_orig'] = array(
			'RU' => 'Россия (СНГ)',
			'EU' => 'Европа',
			'UK' => 'Великобритания',
			'US' => 'США',
			'INT' => 'Международная');
		$this->data['size_units_type'] = array(
			'INCH' => 'Дюймы',
			'Height' => 'Рост в сантиметрах',
			'Months' => 'Возраст в месяцах',
			'Years' => 'Возраст в годах',
			'Round' => 'Окружность в сантиметрах');
			
		$this->data['oc_fields'] = array(
			'name' => 'Название товара',
			'model' => 'Модель',
			'sku' => 'Артикул (SKU, код производителя)',
			'upc' => 'UPC',
		);
		if (version_compare(VERSION, '1.5.3.1') >= 0) {
			$this->data['oc_fields']['seo_h1'] = 'HTML-тег H1';
			$this->data['oc_fields']['seo_title'] = 'HTML-тег Title';
		}
		if (version_compare(VERSION, '1.5.4.1') >= 0) {
			$this->data['oc_fields']['ean'] = 'EAN';
			$this->data['oc_fields']['jan'] = 'JAN';
			$this->data['oc_fields']['isbn'] = 'ISBN';
			$this->data['oc_fields']['mpn'] = 'MPN';
		}
	}

	protected function setFormData() {
		if (isset($this->request->post['yandex_yml_status'])) {
			$this->data['yandex_yml_status'] = $this->request->post['yandex_yml_status'];
		} else {
			$this->data['yandex_yml_status'] = $this->config->get($this->CONFIG_PREFIX.'status');
		}

		/*
		if (isset($this->request->post['yandex_yml_shopname'])) {
			$this->data['yandex_yml_shopname'] = $this->request->post['yandex_yml_shopname'];
		} else {
			$this->data['yandex_yml_shopname'] = $this->config->get($this->CONFIG_PREFIX.'shopname');
		}

		if (isset($this->request->post['yandex_yml_company'])) {
			$this->data['yandex_yml_company'] = $this->request->post['yandex_yml_company'];
		} else {
			$this->data['yandex_yml_company'] = $this->config->get($this->CONFIG_PREFIX.'company');
		}
		*/

		if (isset($this->request->post['yandex_yml_datamodel'])) {
			$this->data['yandex_yml_datamodel'] = $this->request->post['yandex_yml_datamodel'];
		} else {
			$this->data['yandex_yml_datamodel'] = $this->config->get($this->CONFIG_PREFIX.'datamodel');
		}
		
		if (isset($this->request->post['yandex_yml_name_field'])) {
			$this->data['yandex_yml_name_field'] = $this->request->post['yandex_yml_name_field'];
		} else {
			$this->data['yandex_yml_name_field'] = $this->config->get($this->CONFIG_PREFIX.'name_field');
		}
		if (isset($this->request->post['yandex_yml_model_field'])) {
			$this->data['yandex_yml_model_field'] = $this->request->post['yandex_yml_model_field'];
		} else {
			$this->data['yandex_yml_model_field'] = $this->config->get($this->CONFIG_PREFIX.'model_field');
		}
		if (isset($this->request->post['yandex_yml_vendorcode_field'])) {
			$this->data['yandex_yml_vendorcode_field'] = $this->request->post['yandex_yml_vendorcode_field'];
		} else {
			$this->data['yandex_yml_vendorcode_field'] = $this->config->get($this->CONFIG_PREFIX.'vendorcode_field');
		}
		if (isset($this->request->post['yandex_yml_typeprefix_field'])) {
			$this->data['yandex_yml_typeprefix_field'] = $this->request->post['yandex_yml_typeprefix_field'];
		} else {
			$this->data['yandex_yml_typeprefix_field'] = $this->config->get($this->CONFIG_PREFIX.'typeprefix_field');
		}
		if (isset($this->request->post['yandex_yml_barcode_field'])) {
			$this->data['yandex_yml_barcode_field'] = $this->request->post['yandex_yml_barcode_field'];
		} else {
			$this->data['yandex_yml_barcode_field'] = $this->config->get($this->CONFIG_PREFIX.'barcode_field');
		}
		
		
		if (isset($this->request->post['yandex_yml_delivery_cost'])) {
			$this->data['yandex_yml_delivery_cost'] = $this->request->post['yandex_yml_delivery_cost'];
		} else {
			$this->data['yandex_yml_delivery_cost'] = $this->config->get($this->CONFIG_PREFIX.'delivery_cost');
		}

		if (isset($this->request->post['yandex_yml_pricefrom'])) {
			$this->data['pricefrom'] = $this->request->post['yandex_yml_pricefrom'];
		} else {
			$this->data['pricefrom'] = $this->config->get($this->CONFIG_PREFIX.'pricefrom');
		}

		if (isset($this->request->post['yandex_yml_currency'])) {
			$this->data['yandex_yml_currency'] = $this->request->post['yandex_yml_currency'];
		} else {
			$this->data['yandex_yml_currency'] = $this->config->get($this->CONFIG_PREFIX.'currency');
		}

		if (isset($this->request->post['yandex_yml_oldprice'])) {
			$this->data['yandex_yml_oldprice'] = $this->request->post['yandex_yml_oldprice'];
		} else {
			$this->data['yandex_yml_oldprice'] = $this->config->get($this->CONFIG_PREFIX.'oldprice');
		}
		
		if (isset($this->request->post['yandex_yml_blacklist_type'])) {
			$this->data['blacklist_type'] = $this->request->post['yandex_yml_blacklist_type'];
		} else {
			$this->data['blacklist_type'] = $this->config->get($this->CONFIG_PREFIX.'blacklist_type');
		}

		if (isset($this->request->post['yandex_yml_blacklist'])) {
			$blacklist = $this->request->post['yandex_yml_blacklist'];
		} else {
			$blacklist = explode(',', $this->config->get($this->CONFIG_PREFIX.'blacklist'));
		}
		$this->load->model('catalog/product');
		
		$this->data['blacklist'] = array();
		
		foreach ($blacklist as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$this->data['blacklist'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}

		if (isset($this->request->post['yandex_yml_unavailable'])) {
			$this->data['yandex_yml_unavailable'] = $this->request->post['yandex_yml_unavailable'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'unavailable')) {
			$this->data['yandex_yml_unavailable'] = $this->config->get($this->CONFIG_PREFIX.'unavailable');
		} else {
			$this->data['yandex_yml_unavailable'] = '';
		}

		if (isset($this->request->post['yandex_yml_in_stock'])) {
			$this->data['yandex_yml_in_stock'] = $this->request->post['yandex_yml_in_stock'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'in_stock')) {
			$this->data['yandex_yml_in_stock'] = $this->config->get($this->CONFIG_PREFIX.'in_stock');
		} else {
			$this->data['yandex_yml_in_stock'] = 7;
		}

		if (isset($this->request->post['yandex_yml_out_of_stock'])) {
			$this->data['yandex_yml_out_of_stock'] = $this->request->post['yandex_yml_out_of_stock'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'in_stock')) {
			$this->data['yandex_yml_out_of_stock'] = $this->config->get($this->CONFIG_PREFIX.'out_of_stock');
		} else {
			$this->data['yandex_yml_out_of_stock'] = 5;
		}

		if (isset($this->request->post['yandex_yml_pickup'])) {
			$this->data['yandex_yml_pickup'] = $this->request->post['yandex_yml_pickup'];
		} else {
			$this->data['yandex_yml_pickup'] = $this->config->get($this->CONFIG_PREFIX.'pickup');
		}

		if (isset($this->request->post['yandex_yml_sales_notes'])) {
			$this->data['yandex_yml_sales_notes'] = $this->request->post['yandex_yml_sales_notes'];
		} else {
			$this->data['yandex_yml_sales_notes'] = $this->config->get($this->CONFIG_PREFIX.'sales_notes');
		}

		if (isset($this->request->post['yandex_yml_store'])) {
			$this->data['yandex_yml_store'] = $this->request->post['yandex_yml_store'];
		} else {
			$this->data['yandex_yml_store'] = $this->config->get($this->CONFIG_PREFIX.'store');
		}
		
		if (isset($this->request->post['yandex_yml_numpictures'])) {
			$this->data['yandex_yml_numpictures'] = $this->request->post['yandex_yml_numpictures'];
		} else {
			$this->data['yandex_yml_numpictures'] = $this->config->get($this->CONFIG_PREFIX.'numpictures');
		}

		//++++ Для вкладки аттрибутов ++++
		if (isset($this->request->post['yandex_yml_attributes'])) {
			$this->data['yandex_yml_attributes'] = $this->request->post['yandex_yml_attributes'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'attributes') != '') {
			$this->data['yandex_yml_attributes'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'attributes'));
		} else {
			$this->data['yandex_yml_attributes'] = array();
		}
		if (isset($this->request->post['yandex_yml_adult'])) {
			$this->data['yandex_yml_adult'] = $this->request->post['yandex_yml_adult'];
		} else {
			$this->data['yandex_yml_adult'] = $this->config->get($this->CONFIG_PREFIX.'adult');
		}
		if (isset($this->request->post['yandex_yml_manufacturer_warranty'])) {
			$this->data['yandex_yml_manufacturer_warranty'] = $this->request->post['yandex_yml_manufacturer_warranty'];
		} else {
			$this->data['yandex_yml_manufacturer_warranty'] = $this->config->get($this->CONFIG_PREFIX.'manufacturer_warranty');
		}
		if (isset($this->request->post['yandex_yml_country_of_origin'])) {
			$this->data['yandex_yml_country_of_origin'] = $this->request->post['yandex_yml_country_of_origin'];
		} else {
			$this->data['yandex_yml_country_of_origin'] = $this->config->get($this->CONFIG_PREFIX.'country_of_origin');
		}
		if (isset($this->request->post['yandex_yml_attr_vs_description'])) {
			$this->data['yandex_yml_attr_vs_description'] = $this->request->post['yandex_yml_attr_vs_description'];
		} else {
			$this->data['yandex_yml_attr_vs_description'] = $this->config->get($this->CONFIG_PREFIX.'attr_vs_description');
		}

		if (isset($this->request->post['yandex_yml_product_rel'])) {
			$this->data['yandex_yml_product_rel'] = $this->request->post['yandex_yml_product_rel'];
		} else {
			$this->data['yandex_yml_product_rel'] = $this->config->get($this->CONFIG_PREFIX.'product_rel');
		}
		if (isset($this->request->post['yandex_yml_product_accessory'])) {
			$this->data['yandex_yml_product_accessory'] = $this->request->post['yandex_yml_product_accessory'];
		} else {
			$this->data['yandex_yml_product_accessory'] = $this->config->get($this->CONFIG_PREFIX.'product_accessory');
		}
		
		$this->load->model('catalog/attribute');
		$results = $this->model_catalog_attribute->getAttributes(array('sort'=>'attribute_group'));
		$this->data['attributes'] = $results;
		//---- Для вкладки аттрибутов ----

		//++++ Для магазинов одежды ++++
		$this->load->model('catalog/option');
		$results = $this->model_catalog_option->getOptions(array('sort' => 'name'));
		$this->data['options'] = $results;
		
		$this->data['tab_tailor_description'] = $this->language->get('tab_tailor_description');

		if (isset($this->request->post['yandex_yml_color_options'])) {
			$this->data['yandex_yml_color_options'] = $this->request->post['yandex_yml_color_options'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'color_options') != '') {
			$this->data['yandex_yml_color_options'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'color_options'));
		} else {
			$this->data['yandex_yml_color_options'] = array();
		}
		if (isset($this->request->post['yandex_yml_size_options'])) {
			$this->data['yandex_yml_size_options'] = $this->request->post['yandex_yml_size_options'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'size_options') != '') {
			$this->data['yandex_yml_size_options'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'size_options'));
		} else {
			$this->data['yandex_yml_size_options'] = array();
		}
		if (isset($this->request->post['yandex_yml_size_units'])) {
			$this->data['yandex_yml_size_units'] = $this->request->post['yandex_yml_size_units'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'size_units') != '') {
			$this->data['yandex_yml_size_units'] = unserialize($this->config->get($this->CONFIG_PREFIX.'size_units'));
		} else {
			$this->data['yandex_yml_size_units'] = array();
		}

		if (isset($this->request->post['yandex_yml_optioned_name'])) {
			$this->data['yandex_yml_optioned_name'] = $this->request->post['yandex_yml_optioned_name'];
		} else {
			$this->data['yandex_yml_optioned_name'] = $this->config->get($this->CONFIG_PREFIX.'optioned_name');
		}
		//---- Для магазинов одежды ----

		$this->load->model('localisation/stock_status');

		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		$this->load->model('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		$this->load->model('catalog/manufacturer');
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers(0);

		if (isset($this->request->post['yandex_yml_categories'])) {
			$this->data['yandex_yml_categories'] = $this->request->post['yandex_yml_categories'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'categories') != '') {
			$this->data['yandex_yml_categories'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'categories'));
		} else {
			$this->data['yandex_yml_categories'] = array();
		}
		if (isset($this->request->post['yandex_yml_manufacturers'])) {
			$this->data['yandex_yml_manufacturers'] = $this->request->post['yandex_yml_manufacturers'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'manufacturers') != '') {
			$this->data['yandex_yml_manufacturers'] = explode(',', $this->config->get($this->CONFIG_PREFIX.'manufacturers'));
		} else {
			$this->data['yandex_yml_manufacturers'] = array();
		}
		if (isset($this->request->post['yandex_yml_categ_mapping'])) {
			$this->data['yandex_yml_categ_mapping'] = $this->request->post['yandex_yml_categ_mapping'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'categ_mapping') != '') {
			$this->data['yandex_yml_categ_mapping'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_mapping'));
		} else {
			$this->data['yandex_yml_categ_mapping'] = array();
		}
		if (isset($this->request->post['yandex_yml_categ_delivery_cost'])) {
			$this->data['yandex_yml_categ_delivery_cost'] = $this->request->post['yandex_yml_categ_delivery_cost'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'categ_delivery_cost') != '') {
			$this->data['yandex_yml_categ_delivery_cost'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_delivery_cost'));
		} else {
			$this->data['yandex_yml_categ_delivery_cost'] = array();
		}
		if (isset($this->request->post['yandex_yml_categ_cpa'])) {
			$this->data['yandex_yml_categ_cpa'] = $this->request->post['yandex_yml_categ_cpa'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'categ_cpa') != '') {
			$this->data['yandex_yml_categ_cpa'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_cpa'));
		} else {
			$this->data['yandex_yml_categ_cpa'] = array();
		}
		if (isset($this->request->post['yandex_yml_categ_sales_notes'])) {
			$this->data['yandex_yml_categ_sales_notes'] = $this->request->post['yandex_yml_categ_sales_notes'];
		} elseif ($this->config->get($this->CONFIG_PREFIX.'categ_sales_notes') != '') {
			$this->data['yandex_yml_categ_sales_notes'] = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_sales_notes'));
		} else {
			$this->data['yandex_yml_categ_sales_notes'] = array();
		}
		
		$this->load->model('localisation/currency');
		$currencies = $this->model_localisation_currency->getCurrencies();
		$allowed_currencies = array_flip($this->allowedCurrencies);
		$this->data['currencies'] = array_intersect_key($currencies, $allowed_currencies);
	}
	
	public function index() {
		$this->load->language('feed/yandex_yml');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate($this->request->post))) {
			$this->preparePostData();

			$this->model_setting_setting->editSetting('yandex_yml', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->setLanguageData();
		$this->data['token'] = $this->session->data['token'];
		$this->data['cron_path'] = 'php '.realpath(DIR_CATALOG.'../export/yandex_yml.php');

		$this->data['export_url'] = HTTP_CATALOG.'export/yandex_yml.xml';

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['action'] = $this->url->link('feed/yandex_yml', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/yandex_yml';
		
		$this->setFormData();
		
		$this->template = 'feed/yandex_yml.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete() {
		$text = $this->request->get['text'];
		$parts = explode('/', $text);
		foreach ($parts as $key=>$val) {
			$parts[$key] = trim($val);
		}
		if (count($parts) < 2) {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1) AS text FROM oc_yandex_category WHERE level1 LIKE '".$this->db->escape($parts[0])."%' AND level2='' AND level3=''");
		}
		elseif (count($parts) < 3) {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1,'/',level2) AS text FROM oc_yandex_category WHERE level1='".$this->db->escape($parts[0])."' AND level2 LIKE '".$this->db->escape($parts[1])."%' AND level3=''");
		}
		elseif (count($parts) < 4) {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1,'/',level2,'/',level3) AS text FROM oc_yandex_category WHERE level1='".$this->db->escape($parts[0])."' AND level2='".$this->db->escape($parts[1])."' AND level3 LIKE '".$this->db->escape($parts[2])."%'");
		}
		elseif (count($parts) < 5) {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1,'/',level2,'/',level3,'/',level4) AS text FROM oc_yandex_category WHERE level1='".$this->db->escape($parts[0])."' AND level2='".$this->db->escape($parts[1])."' AND level3='".$this->db->escape($parts[2])."' AND level4 LIKE '".$this->db->escape($parts[3])."%'");
		}
		else {
			$q = $this->db->query("SELECT DISTINCT CONCAT(level1,'/',level2,'/',level3,'/',level4,'/',level5) AS text FROM oc_yandex_category WHERE level1='".$this->db->escape($parts[0])."' AND level2='".$this->db->escape($parts[1])."' AND level3='".$this->db->escape($parts[2])."' AND level4='".$this->db->escape($parts[3])."' AND level5 LIKE '".$this->db->escape($parts[4])."%'");
		}
		$ret = array();
		foreach ($q->rows as $row) {
			$ret[] = $row['text'];
		}
		echo json_encode($ret);
	}
	
	protected function validate($data) {
		if (!$this->user->hasPermission('modify', 'feed/yandex_yml')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		/*
		elseif (!empty(array_intersect($data['yandex_yml_size_options'], $data['yandex_yml_color_options']))) {
			$this->error['warning'] = $this->language->get('error_intersects_options');
		}
		*/

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
