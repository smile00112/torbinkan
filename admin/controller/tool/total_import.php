<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.1.x From HostJars opencart.hostjars.com #
#####################################################################################

class ControllerToolTotalImport extends Controller {
	private $error = array();
	private $total_items_added = 0;
	private $total_items_updated = 0;
	private $total_items_missed = 0;	//wrong number of fields in CSV row
	private $total_items_ready = 0;		//in hj_import db ready for store import
	private $existing_prods = array();
	private $run_time = 0;
	private $help_1 = 'http://helpdesk.hostjars.com/entries/22048213-step-1-fetch-feed';
	private $help_2 = 'http://helpdesk.hostjars.com/entries/22050567-step-2-global-settings';
	private $help_3 = 'http://helpdesk.hostjars.com/entries/22050607-step-3-operations';
	private $help_4 = 'http://helpdesk.hostjars.com/entries/22032281-step-4-field-mapping';
	private $help_5 = 'http://helpdesk.hostjars.com/entries/22032291-step-5-import';
	
	/*
	* Function index
	*
	* Entry point for admin interface, acts as a contents page for other import steps.
	*
	* @author 	HostJars
	* @date	28/11/2011
	* @param (none)
	* @return (none)
	*/
	public function index() {

		// SPECIFY REQUIRED LANGUAGE TEXT
		$this->language_info = array(
			//none
		);

		//Perform functions for every single page
		$this->common();

		$pages = array(
			'step1' => 'Fetch Feed',
			'step2' => 'Global Settings',
			'step3' => 'Adjust Import Data',
			'step4' => 'Field Mapping',
			'step5' => 'Import',
		);
		$helpdesk = array(
			'step1' => $this->help_1,
			'step2' => $this->help_2,
			'step3' => $this->help_3,
			'step4' => $this->help_4,
			'step5' => $this->help_5,
		);
		foreach ($pages as $page=>$title) {
			$this->data['pages'][$page] = array(
				'link'   => $this->url->link('tool/total_import/' . $page, 'token=' . $this->session->data['token'], 'SSL'),
				'title'  => $title,
				'button' => str_replace('step', 'Step ', $page),
				'helpdesk' => $helpdesk[$page]
			);
		}
		
		$this->data['ajax_action'] = $this->url->link('tool/total_import/delete_profile&token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('tool/total_import');
		$this->data['saved_settings'] = $this->model_tool_total_import->getSavedSettingNames();
		if ($this->model_tool_total_import->checkUpdates() > 0) {
			$this->session->data['attention'] = 'Attention: Updates have been released to Total Import PRO at <a href="http://opencart.hostjars.com">HostJars</a>!';
		}
		
		
		//load settings profile
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_tool_total_import->loadSettings($this->request->post['settings_groupname']);
			$this->session->data['success'] = 'Settings Successfully Loaded: ' . $this->request->post['settings_groupname'];
			$this->redirect($this->url->link('tool/total_import', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->response->setOutput($this->render());
	}

	/*
	 * Function step1
	 *
	 * Responsible for rendering the Step 1: Fetch Feed admin view, and receiving posted data on submit.
	 *
	 * @author 	HostJars
	 * @date	28/11/2011
	 * @param (none)
	 * @return (none)
	 */
	public function step1() {

		$this->validate(1);

		if (defined('CLI_INITIATED')) {
			$this->load->model('tool/total_import');
			$this->load->model('setting/setting');
			if (PROFILE_NAME != 'default') {
				$this->model_tool_total_import->loadSettings(PROFILE_NAME);
			}
			$settings = $this->model_setting_setting->getSetting('import_step1');
			foreach ($settings as $key => $value) {
				$settings[$key] = $value ? unserialize($value) : $value;
			}
			$filename = $this->model_tool_total_import->fetchFeed($settings, isset($settings['unzip_feed']));
			if ($filename) {
				$this->model_tool_total_import->importFile($filename, $settings);
			}
			$this->step3();
			return;

		}

		// SPECIFY REQUIRED LANGUAGE TEXT
		$this->language_info = array(
			'entry_import_file',			'entry_unzip_feed',
			'entry_import_url',				'entry_feed_format',
			'entry_import_filepath',		'entry_feed_source',
			'entry_xml_product_tag',		'entry_delimiter',
			'entry_import_ftp',				'entry_ftp_server',
			'entry_ftp_user',				'entry_ftp_pass',
			'entry_ftp_path',				'button_fetch',
			'entry_first_row_is_headings',	'entry_use_safe_headings',
			'entry_use_safe_headings_help',	'entry_use_safe_headings',
			'entry_unzip_feed',				'entry_file_encoding',
			'entry_file_encoding_help',		'entry_required',
			'entry_feed_format',			'entry_advanced',
			'entry_feed_source',			'entry_file_upload',
			'entry_file_system',
		);

		$this->load->model('setting/setting');

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate(1)) {

			$settings = $this->request->post;
			foreach ($settings as $key => $value) {
				$settings[$key] = serialize($value);
			}
			
			if(!isset($settings['has_headers'])) {
				$settings['has_headers'] = '0';
			}
			
			$this->model_setting_setting->editSetting('import_step1', $settings);

			$this->language->load('tool/total_import');
			$this->load->model('tool/total_import');
			$filename = $this->model_tool_total_import->fetchFeed($this->request->post, isset($this->request->post['unzip_feed']));
			if ($this->validateFeed($filename, $this->request->post)) {
				$import_status = $this->model_tool_total_import->importFile($filename, $this->request->post);
				if ($import_status === false) {
					$this->session->data['warning'] = $this->language->get('error_xml_format');
				} else {
					$this->session->data['success'] = sprintf($this->language->get('text_success_step1'), $import_status['total_items_ready']);
					if($this->request->post['format'] == 'csv') {
						$this->session->data['success'] .= sprintf($this->language->get('text_success_step1_csv'), $import_status['total_items_missed']);
					}
					$this->redirect($this->url->link('tool/total_import/step2', 'token=' . $this->session->data['token'], 'SSL'));
				}
			}

		}

		//Perform functions for every single page
		$this->common(1);
		$this->data['entry_max_file_size'] = sprintf($this->language->get('entry_max_file_size'), ini_get('upload_max_filesize'));
		$this->data['help_link'] = $this->help_1;
		$settings = $this->model_setting_setting->getSetting('import_step1');
		foreach ($settings as $key => $value) {
			$this->data[$key] = $value ? unserialize($value) : $value;
		}

		$this->response->setOutput($this->render());
	}

	/*
	 * Function step2
	 *
	 * Responsible for rendering the Step 2: Global Settings admin view, and receiving posted data on submit.
	 *
	 * @author 	HostJars
	 * @date	28/11/2011
	 * @param (none)
	 * @return (none)
	 */
	public function step2() {

		$this->validate(2);

		// Specify required translations
		$this->language_info = array(
			'entry_store',					'entry_subtract_stock',
			'entry_remote_images',			'entry_remote_images_warning',
			'entry_top_categories',			'entry_language',
			'entry_weight_class',			'entry_length_class',
			'entry_tax_class',				'entry_product_status',
			'entry_out_of_stock',			'entry_customer_group',
			'text_sample',					'entry_split_category',
			'entry_requires_shipping',		'entry_bottom_category_only',
			'entry_minimum_quantity',		'entry_image_subfolder',
			'entry_yes',					'entry_no',
			'entry_bottom_category_only',	'entry_all_categories'
		);

		$this->load->model('setting/setting');

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate(2)) {

			$settings = $this->request->post;
			foreach ($settings as $key=>$value) {
				$settings[$key] = serialize($value);
			}
			$this->model_setting_setting->editSetting('import_step2', $settings);
			$this->load->language('tool/total_import');
			$this->session->data['success'] = $this->language->get('text_success_step2');

			$this->redirect($this->url->link('tool/total_import/step3', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$settings = $this->model_setting_setting->getSetting('import_step2');
		foreach ($settings as $key => $value) {
			$this->data[$key] = $value ? unserialize($value) : $value;
		}

		//Perform functions for every single page
		$this->common(2);

		$this->load->model('localisation/stock_status'); //For getStockStatuses()
		$this->load->model('localisation/language'); //For getLanguages()
		$this->load->model('localisation/length_class'); //For getLengthClasses()
		$this->load->model('localisation/weight_class'); //For getWeightClasses()
		$this->load->model('localisation/tax_class'); //For getTaxClasses()
		$this->load->model('setting/store'); //For getStores()
		$this->load->model('sale/customer_group'); //For getCustomerGroups()

		$this->data['stock_status_selections'] = $this->model_localisation_stock_status->getStockStatuses();
		$this->data['language_selections'] = $this->model_localisation_language->getLanguages();
		$this->data['store_selections'] = $this->model_setting_store->getStores();
		$this->data['weight_class_selections'] = $this->model_localisation_weight_class->getWeightClasses();
		$this->data['length_class_selections'] = $this->model_localisation_length_class->getLengthClasses();
		$this->data['tax_class_selections'] = $this->model_localisation_tax_class->getTaxClasses();
		$this->data['customer_group_selections'] = $this->model_sale_customer_group->getCustomerGroups();
		$this->data['image_folder_path'] = DIR_IMAGE . 'data/';
		$this->data['help_link'] = $this->help_2;
		
		$this->response->setOutput($this->render());
	}

	/*
	 * Function step3
	 *
	 * Responsible for rendering the Step 3: Operations admin view, and receiving posted data on submit.
	 *
	 * @author 	HostJars
	 * @date	28/11/2011
	 * @param (none)
	 * @return (none)
	 */
	public function step3() {

		$this->validate(3);
		$this->load->model('tool/total_import');

		if (defined('CLI_INITIATED')) {
			$this->load->model('setting/setting');

			$settings = $this->model_setting_setting->getSetting('import_step3');
			foreach ($settings as $key => $value) {
				$settings[$key] = $value ? unserialize($value) : $value;
			}

			if (isset($settings['adjust']) && is_array($settings['adjust'])) {
				$this->model_tool_total_import->runAdjustments($settings['adjust']);
			}

			$this->step5();
			return;
		}

		// SPECIFY REQUIRED LANGUAGE TEXT
		$this->language_info = array(
			'text_operation_field_name',	'text_sample',
			'text_operation',				'text_operation_data',
			'text_adjust_help',				'button_add_operation',
			'text_select',					'text_select_operation',
			'text_operation_type',			'text_operation_desc',
			'text_more',
		);

		$this->load->model('setting/setting');
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate(3)) {
			$settings = $this->request->post;
			foreach ($settings as $key=>$value) {
				$settings[$key] = serialize($value);
			}
			$this->model_setting_setting->editSetting('import_step3', $settings);

			//Adjust product data in DB.
			if (isset($this->request->post['adjust']) && is_array($this->request->post['adjust'])) {
				$this->model_tool_total_import->runAdjustments($this->request->post['adjust']);
			}

			$this->load->language('tool/total_import');
			$this->session->data['success'] = $this->language->get('text_success_step3');
			$this->redirect($this->url->link('tool/total_import/step4', 'token=' . $this->session->data['token'], 'SSL'));

		}

		$settings = $this->model_setting_setting->getSetting('import_step3');
		foreach ($settings as $key => $value) {
			$this->data[$key] = $value ? unserialize($value) : $value;
		}

		//Perform functions for every single page
		$this->common(3);

		$this->data['feed_sample'] = $this->model_tool_total_import->getNextProduct();
		unset($this->data['feed_sample']['hj_id']);
		$this->data['fields'] = array_keys($this->data['feed_sample']);
		$this->data['help_link'] = $this->help_3;
		$this->data['operations'] = $this->model_tool_total_import->getOperations();
		$this->data['labels'] = array($this->language->get('text_most_popular'), $this->language->get('text_advanced'));

		$this->response->setOutput($this->render());
	}

	/*
	 * Function step4
	 *
	 * Responsible for rendering the Step 4: Mappings admin view, and receiving posted data on submit.
	 *
	 * @author 	HostJars
	 * @date	28/11/2011
	 * @param (none)
	 * @return (none)
	 */
	public function step4() {

		$this->validate(4);

		// SPECIFY REQUIRED LANGUAGE TEXT
		$this->language_info = array(
			'entry_field_mapping',
			'text_field_oc_title',
			'text_field_feed_title',
			'text_mapping_description',
			'text_feed_sample',
			'entry_none',
		);

		$this->load->model('setting/setting');
		$this->load->model('catalog/product');

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate(4)) {

			//unset any items in the multis that don't have values.
			foreach (array('product_option', 'product_attribute', 'product_image', 'download', 'product_special') as $field) {
				for ($j=1; $j<count($this->request->post['field_names'][$field]); $j++) {
					if (!$this->request->post['field_names'][$field][$j]) {
						unset($this->request->post['field_names'][$field][$j]);
					}
				}
			}
			for ($i=0; $i<count($this->request->post['field_names']['category']); $i++) {
				for ($j=0; $j<count($this->request->post['field_names']['category'][$i]); $j++) {
					if (!$this->request->post['field_names']['category'][$i][$j]) {
						if ($j == 0) {
							unset($this->request->post['field_names']['category'][$i]);
							break;
						}
						unset($this->request->post['field_names']['category'][$i][$j]);
					}
				}
			}
			$settings = $this->request->post;
			foreach ($settings as $key => $value) {
				$settings[$key] = serialize($value);
			}
			
			$this->model_setting_setting->editSetting('import_step4', $settings);
			$this->load->language('tool/total_import');
			$this->session->data['success'] = $this->language->get('text_success_step4');
			$this->redirect($this->url->link('tool/total_import/step5', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$settings = array_merge(
			$this->model_setting_setting->getSetting('import_step2'), // for languages
			$this->model_setting_setting->getSetting('import_step4')
		);
		foreach ($settings as $key => $value) {
			$settings[$key] = $value ? unserialize($value) : $value;
		}
		
		//Perform functions for every single page
		$this->common(4);
		$this->data['help_link'] = $this->help_4;

		$this->data['feed_sample'] = $this->model_tool_total_import->getNextProduct();
		unset($this->data['feed_sample']['hj_id']);
		$this->data['fields'] = array_keys($this->data['feed_sample']);
		//Make sure the multi fields don't contain bad data from different feed or first run or other weirdness.
		if (!isset($settings['field_names'])) {
			//placeholders for first run :(
			$this->data['field_names'] = array(
				'category'=>array(array('hostjars')),
				'download'=>array('hostjars'),
				'product_attribute'=>array('hostjars'),
				'product_option'=>array('hostjars'),
				'product_image'=>array('hostjars'),
				'product_discount'=>array('hostjars'),
				'product_special'=>array('hostjars'),
			);
		} else {
			$this->data['field_names'] = $settings['field_names'];
			foreach (array('product_attribute', 'product_option', 'product_image', 'product_discount', 'download', 'product_special') as $multi) {
				$temp_multi = array();
				for ($i=0; $i<count($this->data['field_names'][$multi]); $i++) {
					if (!empty($this->data['field_names'][$multi][$i]) && in_array($this->data['field_names'][$multi][$i], $this->data['fields'])) {
						$temp_multi[] = $this->data['field_names'][$multi][$i];
					}
				}
				$this->data['field_names'][$multi] = (empty($temp_multi)) ? array('hostjars') : $temp_multi;
			}
			$temp_multi = array();
			for ($j=0; $j<count($this->data['field_names']['category']); $j++) {
				$temp_sub = array();
				for ($i=0; $i<count($this->data['field_names']['category'][$j]); $i++) {
					if (!empty($this->data['field_names']['category'][$j][$i]) && in_array($this->data['field_names']['category'][$j][$i], $this->data['fields'])) {
						$temp_sub[] = $this->data['field_names']['category'][$j][$i];
					} else {
						break;
					}
				}
				if (!empty($temp_sub)) {
					$temp_multi[] = $temp_sub;
				}
			}
			$this->data['field_names']['category'] = (empty($temp_multi)) ? array(array('hostjars')) : $temp_multi;
		}

		// Fields to map
		$this->data['field_map'] = array(
			//general fields
			'name' => $this->language->get('text_field_name'),
			'description' => $this->language->get('text_field_description'),
			'tag' => $this->language->get('text_field_tags'),
			'meta_description' => $this->language->get('text_field_meta_desc'),
			'meta_keyword' => $this->language->get('text_field_meta_keyw'),
			
			//data fields
			'model' => $this->language->get('text_field_model'),
			'sku' => $this->language->get('text_field_sku'),
			'upc' => $this->language->get('text_field_upc'),
			'ean' => $this->language->get('text_field_ean'),
			'jan' => $this->language->get('text_field_jan'),
			'isbn' => $this->language->get('text_field_isbn'),
			'mpn' => $this->language->get('text_field_mpn'),
			'location' => $this->language->get('text_field_location'),
			'price' => $this->language->get('text_field_price'),
			'quantity' => $this->language->get('text_field_quantity'),
			'minimum' => $this->language->get('text_field_minimum_quantity'),
			'subtract' => $this->language->get('text_field_subtract_stock'),
			'shipping' => $this->language->get('text_field_requires_shipping'),
			'keyword' => $this->language->get('text_field_keyword'),
			'image' => $this->language->get('text_field_image'),
			'length' => $this->language->get('text_field_length'),
			'height' => $this->language->get('text_field_height'),
			'width' => $this->language->get('text_field_width'),
			'weight' => $this->language->get('text_field_weight'),
			'status' => $this->language->get('text_field_product_status'),
			'sort_order' => $this->language->get('text_field_sort_order'),
			
			//link fields
			'manufacturer' => $this->language->get('text_field_manufacturer'),
			'category' => array($this->language->get('text_field_category'), 'both'),	
			'download' => array($this->language->get('text_field_download'), 'vert'),
			
			//attribute fields
			'product_attribute' => array($this->language->get('text_field_attribute'), 'vert'),			// specify which way it needs to replicate, vertical, horizontal or both

			//options fields
			'product_option' => array($this->language->get('text_field_option'), 'vert'),				// specify which way it needs to replicate, vertical, horizontal or both
		
			//discount fields
			'product_discount' => array($this->language->get('text_field_discount_price'), 'vert'),
		
			//special fields
			'product_special' => array($this->language->get('text_field_special_price'), 'vert'),
			
			//product image fields
			'product_image' => array($this->language->get('text_field_additional_image'), 'vert'),		// specify which way it needs to replicate, vertical, horizontal or both
			
			//reward points fields
			'points' => $this->language->get('text_field_points'),
			'product_reward' => $this->language->get('text_field_reward'),
			
			//design fields
			//'layout' => $this->language->get('text_field_layout'),
		);
		
		$this->data['tab_field'] = array(
			'General' => array(
				'name',
				'description',
				'tag',
				'meta_description',
				'meta_keyword',
				'product_tag', //for versions earlier than 1.5.4
			),
			'Data' => array(
				'model',
				'sku',
				'upc',
				'ean',
				'jan',
				'isbn',
				'mpn',
				'location',
				'price',
				'quantity',
				'minimum',
				'subtract',
				'shipping',
				'keyword',
				'image',
				'length',
				'height',
				'width',
				'weight',
				'status',
				'sort_order',
			),
			'Links' => array(
				'manufacturer',
				'category',
				'download',
				'related_products',
			),
			'Attribute' => array(
				'product_attribute',
			),
			'Option' => array(
				'product_option',
			),
			'Discount' => array(
				'product_discount',
			),
			'Special' => array(
				'product_special',
			),
			'Image' => array(
				'product_image',
			),
			'Rewards' => array(
				'points',
				'product_reward',
			),
// 			'Design' => array(
// 				'layout',
// 			),
		);
		
		$this->data['tabs'] = array_keys($this->data['tab_field']);
	
		// Unset any fields not supporter prior to current version.
		if(version_compare($this->model_tool_total_import->getVersion(), '1.5.4', '<')) {
			$this->data['field_map']['product_tag'] = $this->language->get('text_field_tags');
			$deprecated = array('tag', 'ean', 'jan', 'isbn', 'mpn');
			foreach ($deprecated as $olditem) {
				unset($this->data['field_map'][$olditem]);
			}
		}
			
		// Fields needing multi languages:
		$this->load->model('localisation/language');
		$all_languages = $this->model_localisation_language->getLanguages();
		$this->data['languages'] = array();
		foreach ($all_languages as $lang) {
			if (in_array($lang['language_id'], $settings['language'])) {
				$this->data['languages'][] = $lang;
			}
		}
		$this->data['multi_language_fields'] = array(
			'name',
			'description',
			'meta_keyword',
			'meta_description',
			'tag',
			'product_tag',
			'product_option',
			'product_attribute',
			'category',
		);
		$this->response->setOutput($this->render());
	}

	/*
	 * Function step5
	 *
	 * Responsible for rendering the Step 5: Import admin view, and receiving posted data on submit. Fires off the actual import
	 * based on accumulated Step 1 - 5 settings.
	 *
	 * @author 	HostJars
	 * @date	28/11/2011
	 * @param (none)
	 * @return (none)
	 */
	public function step5() {

		$this->validate(5);
		$this->run_time = time();
		
		if (defined('CLI_INITIATED')) {
			$this->load->model('setting/setting');

			$settings = array_merge(
			$this->model_setting_setting->getSetting('import_step1'),
			$this->model_setting_setting->getSetting('import_step2'),
			$this->model_setting_setting->getSetting('import_step3'),
			$this->model_setting_setting->getSetting('import_step4'),
			$this->model_setting_setting->getSetting('import_step5')
			);
			foreach ($settings as $key => $value) {
				$settings[$key] = $value ? unserialize($value) : $value;
			}

			$this->import($settings);

			//finished
			return;
		}

		// SPECIFY REQUIRED LANGUAGE TEXT
		$this->language_info = array(
			'text_sample',			'text_field_model',
			'text_field_sku', 		'text_field_upc',
			'text_field_ean',		'text_field_jan',
			'text_field_isbn',		'text_field_mpn',
			'text_field_name',
			'entry_new_items',		'entry_existing_items',
			'entry_reset',			'entry_delete_diff',
			'text_save_profile',	'text_identify_existing',
			'entry_no',				'entry_add',
			'entry_skip',			'entry_update',
			'entry_ignore',			'entry_delete',
			'entry_disable',		'entry_yes',
			'entry_import_range',	'entry_to',
			'entry_from',			'entry_import_range_help',
			'entry_range',			'entry_all',
			'entry_zero_quantity',
		);

		$this->load->model('setting/setting');

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate(5)) {
			$settings = $this->request->post;
			foreach ($settings as $key=>$value) {
				$settings[$key] = serialize($value);
			}
			$this->model_setting_setting->editSetting('import_step5', $settings);
			//save new settings profile
			if (!empty($this->request->post['save_settings_name'])) {
				$this->load->model('tool/total_import');
				$this->model_tool_total_import->saveSettings($this->request->post['save_settings_name']);
			}
			$settings = array_merge(
				$this->model_setting_setting->getSetting('import_step1'),
				$this->model_setting_setting->getSetting('import_step2'),
				$this->model_setting_setting->getSetting('import_step3'),
				$this->model_setting_setting->getSetting('import_step4'),
				$settings
			);
			foreach ($settings as $key => $value) {
				$settings[$key] = $value ? unserialize($value) : $value;
			}

			$this->import($settings);
			$this->load->language('tool/total_import');
			$this->session->data['success'] = sprintf($this->language->get('text_success_step5'), $this->total_items_added, $this->total_items_updated);
			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'));

		}

		$settings = $this->model_setting_setting->getSetting('import_step5');
		foreach ($settings as $key => $value) {
			$this->data[$key] = $value ? unserialize($value) : $value;
		}
		$this->data['help_link'] = $this->help_5;

		//Perform functions for every single page
		$this->common(5);

		$this->data['feed_sample'] = $this->model_tool_total_import->getNextProduct();
		unset($this->data['feed_sample']['hj_id']);
		$this->data['fields'] = array_keys($this->data['feed_sample']);
		$this->response->setOutput($this->render());
		// mark the stop time
		$stop_time = MICROTIME(TRUE);
	}
	
	public function delete_profile() {
		$output = 'error';
		if ($this->validate()) {
			$profile_name = isset($this->request->post['profile_name']) ? $this->request->post['profile_name'] : '';
			if ($profile_name) {
				$this->load->model('tool/total_import');
				$this->model_tool_total_import->deleteSettings($profile_name);
				$output = sprintf("Success: Deleted profile '%s'", $profile_name);
			}
		}
		$this->response->setOutput($output);
	}

	/*
	* Function common
	*
	* Sets up common environment for all Total Import PRO admin pages: Breadcrumbs, language, templates, etc.
	*
	* @author 	HostJars
	* @date	28/11/2011
	* @param (string) the step calling the function
	* @return none
	*/
	private function common($step='') {

		$this->load->language('tool/total_import');
		$this->load->model('tool/total_import');

		$this->document->setTitle($this->language->get('heading_title'));

		// GET REQUIRED LANGUAGE TEXT
		$common_language = array(
			'heading_title',		'text_enabled',
			'text_disabled',		'tab_adjust',
			'tab_import',			'tab_global',
			'tab_mapping',			'tab_fetch',
			'button_import',		'button_next',
			'button_cancel',		'button_remove',
			'button_skip',			'button_save',
			'text_documentation'
		);
		$this->language_info = array_merge($common_language, $this->language_info);
		foreach ($this->language_info as $language) {
			$this->data[$language] = $this->language->get($language);
		}

		// Warning or success message

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = (isset($this->error['warning'])) ? $this->error['warning'] : '';
		}
		if (isset($this->session->data['attention'])) {
			$this->data['attention'] = $this->session->data['attention'];
			unset($this->session->data['attention']);
		} else {
			$this->data['attention'] = '';
		}
		
		$this->data['token'] = $this->session->data['token'];
		
		// BCT
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('tool/total_import', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
		);

		$this->children = array(
			'common/header',
			'common/footer',
		);


		// Render response with header and footer
		$page = ($step) ? 'tool/total_import/step' . $step : 'tool/total_import';
		
		// Form Submit Action
		$this->data['skip_url'] = $this->url->link('tool/total_import/step' . ($step+1), 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action'] = $this->url->link($page, 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('tool/total_import', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->template = ($step) ? 'tool/total_import_step' . $step . '.tpl' : 'tool/total_import.tpl';
		
	}


	/*
	* Function import
	*
	* Initiates the import, looping over the database, checking for updates, and adding/editing the product.
	*
	* @author 	HostJars
	* @date	28/11/2011
	* @param (mixed) The settings required for this import, from Step 1-5 of admin.
	* @return (none)
	*/
	private function import(&$settings) {

		$this->load->model('tool/total_import');
		$product_num = 0;

		//Empty store if this is a reset
		if ($settings['reset']) {
			$this->model_tool_total_import->emptyTables();
		}
		
		$delete_diff = !empty($settings['delete_diff']) ? $settings['delete_diff'] : 'ignore';
		
		if (!$settings['reset'] && $delete_diff != 'ignore') {
			$this->existing_prods = $this->model_tool_total_import->getExistingProducts($settings['update_field']);
		}

		//Prepare Options if necessary
		$options = $this->model_tool_total_import->getOptions($settings['field_names']['product_option']);
		foreach ($options as $name=>$values) {
			$this->createOption($name, $values, $settings);
		}
		
		//Check for partial imports
		$limit = -1;
		if(isset($settings['import_range']) && $settings['import_range'] == 'partial' && $settings['reset'] != 1) {
			if(isset($settings['import_range_start'])) {
				if($settings['import_range_start'] <= 0) {
					$product_num = 0;
				} else {
					$product_num =  $settings['import_range_start'] - 1;
				}
			}
			if(isset($settings['import_range_end'])) {
				if($settings['import_range_start'] > $settings['import_range_end']) {
					$limit = -1;
				} else {
					$limit = $settings['import_range_end'] - $product_num;
				}
			}
		}
		
	    while (($raw_prod = $this->model_tool_total_import->getNextProduct($product_num)))
	    {
	    	$product_num++;
			$this->resetDefaultValues($settings);
			
			//if we reached product import range, stop importing products
			if(($limit != -1) && (($product_num - $settings['import_range_start']) >= $limit)) {
				break;
			};
			//make sure we are not reaching php timeout
	    	$run_time = time() - $this->run_time;
	    	
			if(!defined('CLI_INITIATED') && (ini_get('max_execution_time') != 0) && ($run_time >= (ini_get('max_execution_time') - 2))) {
				$this->session->data['warning'] = sprintf($this->language->get('error_timeout_reached'), ini_get('max_execution_time'));
				$this->redirect($this->url->link('tool/total_import/step5', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
	    	//price - remove leading $ or pound or euro symbol, remove any commas.
			if (isset($settings['field_names']['price']) && isset($raw_prod[$settings['field_names']['price']])) {
				$raw_prod[$settings['field_names']['price']] = preg_replace('/^[^\d]+/', '', $raw_prod[$settings['field_names']['price']]);
				$raw_prod[$settings['field_names']['price']] = str_replace(',', '', $raw_prod[$settings['field_names']['price']]);
			}
			
			//remote images.
			if ($settings['remote_images']) {
				foreach (array_merge($settings['field_names']['product_image'], array($settings['field_names']['image'])) as $image) {
					if (!empty($raw_prod[$image])) {
						if (empty($settings['image_subfolder'])) {
							$settings['image_subfolder'] =  '/';
						}
						$raw_prod[$image] = $this->model_tool_total_import->fetchImage($raw_prod[$image], $settings['image_subfolder']);
					}
				}
			}
			
			//Allow for true/false, on/off, enable/dissable and yes/no in the below fields
			$binary_fields = array($settings['field_names']['subtract'], $settings['field_names']['shipping'], $settings['field_names']['status']);
			foreach ($binary_fields as $binary_field) {
				if(isset($raw_prod[$binary_field])){
						$raw_prod[$binary_field] = preg_match('/(^no$|^n$|false|off|disable|^0$)/is', $raw_prod[$binary_field]) ? 0 : 1;
				}
			}

			// Is this an update?
			$update_id = 0;
			if (!$settings['reset'] && isset($settings['update_field'])) {
				if (isset($raw_prod[$settings['field_names'][$settings['update_field']]])) {
					$update_value = $raw_prod[$settings['field_names'][$settings['update_field']]];
					//$update_value = ($settings['update_field'] != 'name') ? $raw_prod[$settings['field_names'][$settings['update_field']]] : $raw_prod[$settings['field_names'][$settings['update_field']][$settings['language'][0]]];
					$update_id = $this->model_tool_total_import->getProductId($settings['update_field'], $update_value);
				}
			}

			//EXISTING PRODUCT:
			if ($update_id) {
				if ($settings['existing_items'] != 'skip') {
					$product = $this->updateProduct($update_id, $raw_prod, $settings);
					$this->model_catalog_product->editProduct($update_id, $product);
					$this->total_items_updated++;
				}
			}
			//NEW PRODUCT
			else {
				if ($settings['new_items'] != 'skip') {
					$product = $this->addProduct($raw_prod, $settings);
					$this->model_catalog_product->addProduct($product);
					$this->total_items_added++;
				}
	    	}

	    	
	    	//delete from existing_prods hash, so this product doesn't get deleted post-import
	    	if (!$settings['reset'] && $delete_diff != 'ignore') {
    			$this->existing_prods[$raw_prod[$settings['field_names'][$settings['update_field']]]] = 1;
	    	}
	    	
	    }//end while
	    
	    //delete/disable items that were in the store but not in the import file
	    if (!$settings['reset'] && $delete_diff != 'ignore') {
    		foreach ($this->existing_prods as $item_to_delete => $in_file) {
    			if (!$in_file) {
					$prod_id = $this->model_tool_total_import->getProductId($settings['update_field'], $item_to_delete);
					if ($prod_id) {
						if ($delete_diff == 'delete') {
							$this->model_catalog_product->deleteProduct($prod_id);
						} elseif ($delete_diff == 'disable') { 
							$this->model_tool_total_import->disableProduct($prod_id);
						} else {
							$this->model_tool_total_import->zeroQuantityProduct($prod_id);
						}
					}
    			}
			}
	    }
	    
	}

	/*
	 * Function addProduct
	 *
	 * Creates a new product ready for adding via the catalog/product model's addProduct function.
	 *
	 * @author 	HostJars
	 * @date	28/11/2011
	 * @param (mixed $raw_prod) the raw product that we wish to add, key=>value fields as mapped in $settings to OC fields
	 * @param (mixed $settings) the settings the user has saved from Steps 1-5 in the tool governing how products are added.
	 * @return (mixed) the product ready for adding via the model catalog/product addProduct function.
	 */
	private function addProduct(&$raw_prod, &$settings) {
		$this->load->model('catalog/product'); //For addProduct()
    	$product = array();  // will contain new product to add

    	
    	//categories
    	$categories = $this->getCategories($raw_prod, $settings);
    	if (!empty($categories)) {
	    	$settings['field_names']['product_category'] = 'product_category';
	    	$raw_prod['product_category'] = array_unique($categories);
    	}
    	//downloads
    	$downloads = $this->getDownloads($raw_prod, $settings);
    	if (!empty($downloads)) {
    		$settings['field_names']['product_download'] = 'product_download';
    		$raw_prod['product_download'] = array_unique($downloads);
    	}

		//manufacturer
		if (!empty($raw_prod[$settings['field_names']['manufacturer']])) {
			$raw_prod['manufacturer_id'] = $this->getManufacturer($raw_prod[$settings['field_names']['manufacturer']], $settings);
			$settings['field_names']['manufacturer_id'] = 'manufacturer_id';
		}
		//end manufacturer

		//product attributes
		$input_attributes = array();
		foreach ($settings['field_names']['product_attribute'] as $attr) {
			if (!empty($raw_prod[$attr])) {
				$input_attributes[$attr] = $raw_prod[$attr];
			}
		}
		
		$attributes = $this->getAttributes($input_attributes, $settings);
		if (!empty($attributes)) {
			$product['product_attribute'] = $attributes;
		}

		// product options
		$options = $this->getProductOptions($raw_prod, $settings);
		if (!empty($options)) {
			$product['product_option'] = $options;
		}
		
		// loop over prod_data array adding product table data
		foreach ($this->prod_data as $field => $default_value) {
			if (isset($settings['field_names'][$field]) && isset($raw_prod[$settings['field_names'][$field]])) {
				$product[$field] = $raw_prod[$settings['field_names'][$field]];
			} else {
				$product[$field] = $default_value;
			}
		}
		// loop over desc_data array adding description table data
		foreach ($this->desc_data as $field => $default_value) {
			foreach ($settings['language'] as $language) {
				if (isset($settings['field_names'][$field][$language]) && isset($raw_prod[$settings['field_names'][$field][$language]])) {
					$product['product_description'][$language][$field] = $raw_prod[$settings['field_names'][$field][$language]];
				} else {
					$product['product_description'][$language][$field] = $default_value;
				}
			}
		}

		//Optional Import Fields:

		//SEO Keyword
		if (isset($settings['field_names']['keyword']) && isset($raw_prod[$settings['field_names']['keyword']])) {
			$product['keyword'] = $raw_prod[$settings['field_names']['keyword']];
		} elseif(isset($product['product_description'][$settings['language'][0]]['name'])) {
				//auto generate SEO Keyword
				$product['keyword'] = $this->model_tool_total_import->makeSeoKeyword($product['product_description'][$settings['language'][0]]['name']);
		}
		
		//Product Tags
		if(version_compare($this->model_tool_total_import->getVersion(), '1.5.4', '<')) {
			$product['product_tag'] = array();
			foreach ($settings['language'] as $language) {
				if (isset($settings['field_names']['product_tag'][$language]) && isset($raw_prod[$settings['field_names']['product_tag'][$language]])) {
					$product['product_tag'][$language] = $raw_prod[$settings['field_names']['product_tag'][$language]];
				}
			}
		}
		//Product Specials
		$product_special = $this->getSpecials($raw_prod, $settings);
		if (!empty($product_special)) {
			$product['product_special'] = $product_special;
		}
		
		//Product Discounts
		$product_discount = $this->getDiscounts($raw_prod, $settings);
		if (!empty($product_discount)) {
			$product['product_discount'] = $product_discount;
		}
		
		//Additional Images
		$product['product_image'] = array();
		foreach ($settings['field_names']['product_image'] as $image) {
			if (!empty($raw_prod[$image])) {
				if (defined('VERSION') && VERSION == '1.5.1.1') {
					$product['product_image'][] = $raw_prod[$image];										//OpenCart 1.5.1.1
				} else { 
					$product['product_image'][] = array('sort_order' => '', 'image' => $raw_prod[$image]);	//OpenCart 1.5.1.3
				}
			}
		}
		if (empty($product['product_image'])) {
			unset($product['product_image']);
		}

		//Product Rewards
		if (!empty($raw_prod[$settings['field_names']['product_reward']])) {
			$product['product_reward'][$settings['customer_group']] = array('points' => $raw_prod[$settings['field_names']['product_reward']]);
		}
		
		//get quantity of all options and use this for product quantity if it is set
		$quantity = '';
		if(isset($product['product_option'])) {
			foreach($product['product_option'] as $option) {
				if(isset($option['product_option_value'])) {
					foreach($option['product_option_value'] as $option_value) {
						if(isset($option_value['quantity'])) {
							$quantity += $option_value['quantity'];
						}
					}
				}
			}
		}
		
		//set the product quantity as the sum of options quantities if these existed
		if($quantity != '') {
			$product['quantity'] = $quantity;
		}

		//NEW PRODUCT
		return $product;
	}

	/*
	 * Function updateProduct
	 *
	 * Creates a new product from an existing product, ready for adding via the catalog/product model's editProduct function.
	 *
	 * @author 	HostJars
	 * @date	28/11/2011
	 * @param (mixed $update_id) the product_id of the product we wish to update.
	 * @param (mixed $raw_prod) the raw product that we wish to add, key=>value fields as mapped in $settings to OC fields.
	 * @param (mixed $settings) the settings the user has saved from Steps 1-5 in the tool governing how products are added.
	 * @return (mixed) the product ready for adding via the model catalog/product addProduct function.
	 */
	private function updateProduct($update_id, &$raw_prod, &$settings) {
		$this->load->model('catalog/product'); //For addProduct()
		$product = $this->model_catalog_product->getProduct($update_id);
		$product['product_description'] = $this->model_catalog_product->getProductDescriptions($update_id);
		$product['product_category'] = $this->model_catalog_product->getProductCategories($update_id);
		$product['product_attribute'] = $this->model_catalog_product->getProductAttributes($update_id);
		$product['product_reward'] = $this->model_catalog_product->getProductRewards($update_id);
		$product['product_option'] = $this->model_catalog_product->getProductOptions($update_id);
		$product['product_download'] = $this->model_catalog_product->getProductDownloads($update_id);
		$product['product_related'] = $this->model_catalog_product->getProductRelated($update_id);
		$product['product_layout'] = $this->model_catalog_product->getProductLayouts($update_id);
		$product['product_discount'] = $this->model_catalog_product->getProductDiscounts($update_id);
		$product['product_store'] = $this->model_catalog_product->getProductStores($update_id);
		
		if(version_compare($this->model_tool_total_import->getVersion(), '1.5.4', '<')) {
				$product['product_tag'] = $this->model_catalog_product->getProductTags($update_id);
		}

		//@todo Add discount price code here for updating products
		$product['product_image'] = $this->model_catalog_product->getProductImages($update_id);
		if (defined('VERSION') && VERSION == '1.5.1.1') {
			$images = $product['product_image'];
			$product['product_image'] = array();
			foreach ($images as $image) {
				$product['product_image'][] = $image['image'];
			}
		}
		// Additional Images from feed
		if ($settings['field_names']['product_image'][0]) {
			$product['product_image'] = array();
			foreach ($settings['field_names']['product_image'] as $image) {
				if (!empty($raw_prod[$image])) {
					if (defined('VERSION') && VERSION == '1.5.1.1') {
						$product['product_image'][] = $raw_prod[$image];	//OpenCart 1.5.1.1
					} else {
						$product['product_image'][] = array('sort_order' => '', 'image' => $raw_prod[$image]);	//OpenCart 1.5.1.3
					}
				}
			}
		}
		if (empty($product['product_image'])) {
			unset($product['product_image']);
		}

		//categories
    	$categories = $this->getCategories($raw_prod, $settings);
    	if (!empty($categories)) {
	    	$settings['field_names']['product_category'] = 'product_category';
	    	$raw_prod['product_category'] = array_unique($categories);
    	}

    	//downloads
    	$downloads = $this->getDownloads($raw_prod, $settings);
    	if (!empty($downloads)) {
    		$settings['field_names']['product_download'] = 'product_download';
    		$raw_prod['product_download'] = array_unique($downloads);
    	}
    	
    	
		//manufacturer
		if (!empty($raw_prod[$settings['field_names']['manufacturer']])) {
			$raw_prod['manufacturer_id'] = $this->getManufacturer($raw_prod[$settings['field_names']['manufacturer']], $settings);
			$settings['field_names']['manufacturer_id'] = 'manufacturer_id';
		}

		//product attributes
		$input_attributes = array();
		foreach ($settings['field_names']['product_attribute'] as $attr) {
			if (!empty($raw_prod[$attr])) {
				$input_attributes[$attr] = $raw_prod[$attr];
			}
		}
		$attributes = $this->getAttributes($input_attributes, $settings);
		if (!empty($attributes)) {
			$product['product_attribute'] = $attributes;
		} elseif ($settings['field_names']['product_attribute'][0]) {
			unset($product['product_attribute']);
		}

		// product options
		$options = $this->getProductOptions($raw_prod, $settings);
		if (!empty($options)) {
			$product['product_option'] = $options;
		}

		//Overwrite product data with imported data from csv
		// Product Data
		foreach ($this->prod_data as $field => $default_value) {
			if (isset($settings['field_names'][$field]) && isset($raw_prod[$settings['field_names'][$field]])) {
				$product[$field] = $raw_prod[$settings['field_names'][$field]];
			}
		}
		// Product Descriptions
		foreach ($this->desc_data as $field => $default_value) {
			foreach ($settings['language'] as $language) {
				if (isset($settings['field_names'][$field][$language]) && isset($raw_prod[$settings['field_names'][$field][$language]])) {
					$product['product_description'][$language][$field] = $raw_prod[$settings['field_names'][$field][$language]];
				}
			}
		}

		// SEO Keyword
		if (isset($settings['field_names']['keyword']) && isset($raw_prod[$settings['field_names']['keyword']])) {
			$product['keyword'] = $raw_prod[$settings['field_names']['keyword']];
		} elseif(isset($product['product_description'][$settings['language'][0]]['name']) && $product['keyword'] == '') {
				//auto generate SEO Keyword
				$product['keyword'] = $this->model_tool_total_import->makeSeoKeyword($product['product_description'][$settings['language'][0]]['name']);
		}
		
		// Product Tags
		if(version_compare($this->model_tool_total_import->getVersion(), '1.5.4', '<')) {
			foreach ($settings['language'] as $language) {
				if (isset($settings['field_names']['product_tag'][$language]) && isset($raw_prod[$settings['field_names']['product_tag'][$language]])) {
					$product['product_tag'][$language] = $raw_prod[$settings['field_names']['product_tag'][$language]];
				}
			}
		}
		
		//Product Specials
		$special_column= $settings['field_names']['product_special'][0]; //get first special column value
		
		if (isset($special_column)){ //product special field mapped
			//mapped and set to value
			if (!empty($raw_prod[$special_column])){
				$product_special = $this->getSpecials($raw_prod, $settings);
				if (!empty($product_special)) {
					$product['product_special'] = $product_special;
				}
			}
			//mapped and set to nothing to remove special
			else {
				$this->model_tool_total_import->deleteSpecials($update_id);
			}
		}
		else{
			//product special field not mapped, but specials exist for product in db
			$product_specials = $this->model_catalog_product->getProductSpecials($update_id);
			foreach ($product_specials as $product_special) {
				$new_special = array();
				foreach ($this->special_data as $field => $default_value) {
					$new_special[$field] = $product_special[$field];
				}
			$product['product_special'][] = $new_special;
			}
		}
		
		//Product Discounts
		$product_discount = $this->getDiscounts($raw_prod, $settings);
		if (!empty($product_discount)) {
			$product['product_discount'] = $product_discount;
		}
		
		//Product Rewards
		if (!empty($raw_prod[$settings['field_names']['product_reward']])) {
			$product['product_reward'][$settings['customer_group']] = array('points' => $raw_prod[$settings['field_names']['product_reward']]);
		}

		//get quantity of all options and use this for product quantity if it is set
		$quantity = '';
		if(isset($product['product_option'])) {
			foreach($product['product_option'] as $option) {
				if(isset($option['product_option_value'])) {
					foreach($option['product_option_value'] as $option_value) {
						if(isset($option_value['quantity'])) {
							$quantity += $option_value['quantity'];
						}
					}
				}
			}
		}
		
		//set the product quantity as the sum of options quantities if these existed
		if($quantity != '') {
			$product['quantity'] = $quantity;
		}
		
		//UPDATED PRODUCT
		return $product;
	}


	private function resetDefaultValues(&$settings) {
		//required desc data
		$this->desc_data = array(
			'name' => 'No Title',
			'description' => '',
			'meta_keyword' => '',
			'meta_description' => '',
			'tag' => '',
		);

		//required product data
		$this->prod_data = array(
			'date_available' => date('Y-m-d', time()-86400),
			'model' => '',
			'sku'	=> '',
			'upc'	=> '',
			'ean'	=> '',
			'jan'	=> '',
			'isbn'	=> '',
			'mpn'	=> '',
			'points'	=> 0,
			'location' => '',
			'manufacturer_id' => 0,
			'shipping' => $settings['requires_shipping'],
			'image' => '',
			'quantity' => 1,
			'minimum' => $settings['minimum_quantity'],
			'maximum' => 0,
			'subtract' => $settings['subtract_stock'],
			'sort_order' => 1,
			'price' => 0.00,
			'status' => $settings['product_status'],
			'tax_class_id' => $settings['tax_class'],
			'weight' => '',
			'weight_class_id' => $settings['weight_class'],
			'length' => '',
			'width' => '',
			'height' => '',
			'length_class_id' => $settings['length_class'],
			'product_category' => array(0),
			'keyword' => '',
			'stock_status_id' => $settings['out_of_stock_status'],
			'product_store' => $settings['store'],
			'layout' => array(0),
			'product_download' => array(0),
		);

		//required special price data
		$this->special_data = array(
			'customer_group_id' => $settings['customer_group'],
			'priority' => 1,
			'price' => 0,
			'date_start' => date('Y-m-d', time()-86400), //today minus one day
			'date_end' => date('Y-m-d', time()+(86400*7*52*2)), //today plus 1 year
		);

		//required discount price data
		$this->discount_data = array(
			'priority' => 1,
			'date_start' => date('Y-m-d', time()-86400),
			'date_end' => date('Y-m-d', time()+(86400*7*52*2)),
			'quantity' => 1,
			'price' => 0,
			'customer_group_id' => $settings['customer_group']
		);
		
		//required points data
		/*

		 product_reward => array(
		 	[5] => array( 'points' => NUM_POINTS ); //wholesale
		 	[8] => array( 'points' => NUM_POINTS ); //default
		 );


		 */
	}
	
	private function getDiscounts(&$raw_prod, &$settings) {
		$discounts = array();
		foreach ($settings['field_names']['product_discount'] as $discount) {
			if (isset($raw_prod[$discount])) {
				$discount_parts = (strstr($raw_prod[$discount], ':') === FALSE) ? array($raw_prod[$discount]) : explode(':', $raw_prod[$discount]);
				
				//price - remove leading $ or pound or euro symbol, remove any commas.
				if (isset($discount_parts[0])) {
					$discount_parts[0] = preg_replace('/^[^\d]+/', '', $discount_parts[0]);
					$discount_parts[0] = str_replace(',', '', $discount_parts[0]);
				}
				
				$discounts[] = array(
					'price' => $discount_parts[0],
					'quantity' => isset($discount_parts[1]) ? $discount_parts[1] : 1,
					'customer_group_id' => isset($discount_parts[2]) ? (!is_numeric($discount_parts[2])) ? (int) $settings['customer_group_ids'][strtolower($discount_parts[2])] : (int)$discount_parts[2] : $settings['customer_group'],
					'priority' => isset($discount_parts[3]) ? (int)$discount_parts[3] : 1,
					'date_start' => isset($discount_parts[4]) ? date('Y-m-d', strtotime($discount_parts[4])) : date('Y-m-d', time()-86400),
					'date_end' => isset($discount_parts[5]) ? date('Y-m-d', strtotime($discount_parts[5])) : date('Y-m-d', time()+(4492800*2)),
				);
			}
		}
		return $discounts;
	}
	
	private function getSpecials(&$raw_prod, &$settings) {
		$specials = array();
		foreach ($settings['field_names']['product_special'] as $special) {
			if (isset($raw_prod[$special])) {
				$special_parts = (strstr($raw_prod[$special], ':') === FALSE) ? array($raw_prod[$special]) : explode(':', $raw_prod[$special]);
				
				//price - remove leading $ or pound or euro symbol, remove any commas.
				if (isset($special_parts[0])) {
					$special_parts[0] = preg_replace('/^[^\d]+/', '', $special_parts[0]);
					$special_parts[0] = str_replace(',', '', $special_parts[0]);
				}
				if($special_parts[0] != '' && $special_parts[0] != '0.00') {
					$specials[] = array(
						'price' => $special_parts[0],
						'customer_group_id' => isset($special_parts[1]) ? (!is_numeric($special_parts[1])) ? (int) $settings['customer_group_ids'][strtolower($special_parts[1])] : (int)$special_parts[1] : $settings['customer_group'],
						'priority' => isset($special_parts[2]) ? (int)$special_parts[2] : 1,
						'date_start' => isset($special_parts[3]) ? date('Y-m-d', strtotime($special_parts[3])) : date('Y-m-d', time()-86400),
						'date_end' => isset($special_parts[4]) ? date('Y-m-d', strtotime($special_parts[4])) : date('Y-m-d', time()+(4492800*2)),
					);
				}
			}
		}
		return $specials;
	}
	
	private function getDownloads(&$raw_prod, &$settings) {
		$this->load->model('tool/total_import');
		$this->load->model('catalog/download');
		$multi_downloads = array();
		$download_list = $settings['field_names']['download'];
		foreach($download_list as $download_field) {
		$downloads = array();
			if (isset($raw_prod[$download_field])) $downloads[] = $raw_prod[$download_field];
			$temp_dow = array();
			foreach ($downloads as $dow) {
				if ($dow != '') {
					$dow_parts = (strstr($dow, ':') === FALSE) ? array($dow) : explode(':', $dow);
					
					if(isset($dow_parts[1])) {
						$dow_id = (int)$this->model_tool_total_import->getDownloadIdByName($dow_parts[1]);
					}
					else
					{
						$dow_id = (int)$this->model_tool_total_import->getDownloadIdByName($dow_parts[0]);
					}
					
					if ($dow_id == 0) {
						//doesn't exist so add it then get it's id
						$new_dow = array(
							'filename' => $dow_parts[0],
							'mask' => isset($dow_parts[2]) ? $dow_parts[2] : $dow_parts[0],
							'remaining' => isset($dow_parts[3]) ? (int)$dow_parts[3] : 20,
							'update' => isset($dow_parts[4]) ? (int)$dow_parts[4] : 1,
						);
						foreach (array_merge(array($this->config->get('config_language')), $settings['language']) as $language) {
							$new_dow['download_description'][$language] = array();
							$new_dow['download_description'][$language]['name'] = isset($dow_parts[1]) ? $dow_parts[1] : $dow_parts[0];
						}
						$new_dow['download_store'] = $settings['store'];
						$this->model_catalog_download->addDownload($new_dow);
						if(isset($dow_parts[1])) {
							$dow_id = (int)$this->model_tool_total_import->getDownloadIdByName($dow_parts[1]);
						}
						else
						{
							$dow_id = (int)$this->model_tool_total_import->getDownloadIdByName($dow_parts[0]);
						}
					}
					$temp_dow[] = $dow_id;
				}
			}
			$new_dow = $temp_dow;
			$multi_downloads = array_merge($multi_downloads, $new_dow);
		}
		return array_unique($multi_downloads);
	}

	private function getCategories(&$raw_prod, &$settings) {
		$this->load->model('tool/total_import');
		$this->load->model('catalog/category');
		$multi_categories = array();
		foreach ($settings['field_names']['category'] as $category_field) {
			$categories = array();
			if (!empty($settings['split_category'])) {
				$settings['split_category'] = str_replace('&gt;', '>', $settings['split_category']);
				$categories = explode($settings['split_category'], $raw_prod[$category_field[0]]);
			} else {
				//normal categories:
				foreach ($category_field as $cat) {
					if (isset($raw_prod[$cat])) $categories[] = $raw_prod[$cat];
				}
			}
			$parentid = 0;
			$temp_cat = array();
			foreach ($categories as $cat) {
				$cat = trim($cat);
				if ($cat != '') {
					$cat_id = (int)$this->model_tool_total_import->getCategoryId($cat, $parentid);
					if ($cat_id == 0) {
						//doesn't exist so add it then get it's id
						$new_cat = array();
						$new_cat['parent_id'] = $parentid;
						$new_cat['top'] = ($parentid) ? 0 : $settings['top_categories'];
						$new_cat['sort_order'] = 0;
						$new_cat['status'] = 1;
						$new_cat['column'] = 1;
						$seo_keyword = $this->model_tool_total_import->makeSeoKeyword($cat);
						$new_cat['keyword'] = $seo_keyword;
						$new_cat['category_description'] = array();
						foreach (array_merge(array($this->config->get('config_language')), $settings['language']) as $language) {
							$new_cat['category_description'][$language] = array();
							$new_cat['category_description'][$language]['name'] = $cat;
							$new_cat['category_description'][$language]['description'] = '';
							$new_cat['category_description'][$language]['meta_description'] = '';
							$new_cat['category_description'][$language]['meta_keyword'] = '';
						}
						$new_cat['category_store'] = $settings['store'];
						$this->model_catalog_category->addCategory($new_cat);
						$cat_id = (int)$this->model_tool_total_import->getCategoryId($cat, $parentid);
					}
					$temp_cat[] = $cat_id;
					$parentid = $cat_id;
				}
			}
			$new_cat = ($settings['bottom_category_only']) ? array($parentid) : $temp_cat;
			$multi_categories = array_merge($multi_categories, $new_cat);
		}
		return array_unique($multi_categories);
	}

	private function getManufacturer($manu, &$settings) {
		$manu_id = $this->model_tool_total_import->getManufacturerId($manu);
		if ($manu_id == 0) {
			$this->load->model('catalog/manufacturer');
			//doesn't exist so add it then get its id
			$new_manu['name'] = $manu;
			$seo_keyword = $this->model_tool_total_import->makeSeoKeyword($manu);
			$new_manu['keyword'] = $seo_keyword;
			$new_manu['sort_order'] = 1;
			$new_manu['manufacturer_store'] = $settings['store'];
			$this->model_catalog_manufacturer->addManufacturer($new_manu);
			$manu_id = $this->model_tool_total_import->getManufacturerId($new_manu['name']);
		}
		return $manu_id;
	}

	private function getAttributes($input_attributes, &$settings) {
		$this->load->model('catalog/attribute');
		$this->load->model('catalog/attribute_group');
		$attributes = array();
		foreach ($input_attributes as $attr_name=>$attr_value) {
			$fields = explode(':', $attr_value);
			if (count($fields) == 3) {
				$group = $fields[0];
				$name = $fields[1];
				$value = $fields[2];
			} else {
				$group = $attr_name;
				$name = $attr_name;
				$value = $attr_value;
			}
			$name = ucwords($name);
			//find the attribute group based on the column name in the CSV feed
			$attr_group_id = $this->model_tool_total_import->getAttributeGroupId($group);
			if ($attr_group_id == 0) {
				//it doesn't exist, let's add it
				$attr_group['sort_order'] = 1;
				foreach ($settings['language'] as $language) {
					$attr_group['attribute_group_description'][$language]['name'] = $group;
				}
				$this->model_catalog_attribute_group->addAttributeGroup($attr_group);
				$attr_group_id = $this->model_tool_total_import->getAttributeGroupId($group);
			}
			//find the attribute value based on the value in the attribute column in the CSV feed
			$attr_id = $this->model_tool_total_import->getAttributeId($name, $attr_group_id);
			if ($attr_id == 0) {
				//it doesn't exist, let's add it
				$new_attr['attribute_group_id'] = $attr_group_id;
				$new_attr['sort_order'] = 1;
				foreach ($settings['language'] as $language) {
					$new_attr['attribute_description'][$language]['name'] = $name;
				}
				$this->model_catalog_attribute->addAttribute($new_attr);
				$attr_id = $this->model_tool_total_import->getAttributeId($name, $attr_group_id);
			}
			$new_attr = array(
				'attribute_id'=>$attr_id,
			);
			foreach ($settings['language'] as $language) {
				$new_attr['product_attribute_description'][$language]['text'] = $value;
			}
			$attributes[] = $new_attr;
		}
		return $attributes;
	}

	/*
	Requires this format:

	Size
	Small:4:3.00:400|Medium:1:2.00|Large:2:4.50|....

	ie:

	Option Name
	Value:Quantity:+Price:+Weight|Value2:Quantity:+Price:+Weight|....

	*/
	private function getProductOptions(&$raw_prod, &$settings) {
		$this->load->model('catalog/option');
		$complete_option = array();
		$i = 0;
		foreach ($settings['field_names']['product_option'] as $option_field) {
			if (!empty($raw_prod[$option_field])) {
				$options = explode('|', $raw_prod[$option_field]);
				if (!empty($options)) {
					$option_id = $this->model_tool_total_import->getOptionIdByName(ucwords($option_field));
					$complete_option[$i] = array(
						'product_option_id'=>'',
						'option_id'=>$option_id,
						'name'=>ucwords($option_field),
						'type'=>'select',
						'required'=>1
					);
					$complete_option[$i]['product_option_value'] = array();
					foreach ($options as $option) {
						$option_parts = explode(':', $option);
						if($option_parts[0] != '') {
							foreach($option_parts as $key=>$value) {
								$option_parts[$key] = htmlentities ($value, ENT_QUOTES, 'UTF-8', false);
							}
							$option_value_id = $this->model_tool_total_import->getOptionValueIdByName($option_parts[0], $option_id);
							$complete_option[$i]['product_option_value'][] = array(
								'option_value_id'=>$option_value_id,
								'product_option_value_id'=>'',
								'quantity'=> isset($option_parts[1]) ? (int) $option_parts[1] : 1,
								'subtract'=>$settings['subtract_stock'],
								'price_prefix'=> (!empty($option_parts[2]) && $option_parts[2] < 0) ? '-' : '+',
								'price'=> !empty($option_parts[2]) ? $option_parts[2] : 0,
								'points_prefix'=>'+',
								'points'=>0,
								'weight_prefix'=>'+',
								'weight'=> !empty($option_parts[3]) ? $option_parts[3] : 0,
								'ob_sku' => !empty($option_parts[5]) ? $option_parts[5] : 0,
								'ob_image' => !empty($option_parts[6]) ? $option_parts[6] : 0,
								'ob_info' => !empty($option_parts[7]) ? $option_parts[7] : 0,
							);
						} else {
							$complete_option[$i] = array();
						}
					} //end foreach options
					$i++;
				}//end if !empty
			}//end if !empty
		}//end foreach
		return $complete_option;
	}

	private function createOption($option_name, $option_values, &$settings) {
		$this->load->model('catalog/option');
		if (!empty($option_values)) {
			foreach($option_values as $key=>$value) {
				$option_values[$key] = htmlentities ($value, ENT_QUOTES, 'UTF-8', false);
			}
			$option_id = $this->model_tool_total_import->getOptionIdByName(ucwords($option_name));
			$new_option = array();
			foreach ($settings['language'] as $language) {
				$new_option['option_description'][$language] = array('name'=>ucwords($option_name));
			}
			$new_option['type'] = 'select';
			$new_option['sort_order'] = 1;
			$new_option['option_value'] = array();
			$new_option['image'] = '';
			$i=0;
			if ($option_id) {
				$existing_values = $this->model_catalog_option->getOptionValueDescriptions($option_id);
			} else {
				$existing_values = array();
			}
			
			foreach ($option_values as $option_value) {
				if (strstr($option_value, '|')) {
					$value_sortorder = explode('|', $option_value);
					$option_value = $value_sortorder[0];
					$sort_order = $value_sortorder[1];
				} else {
					$sort_order = '';
				}
				$exists = false;
				foreach ($existing_values as $ex_val) {
					$name = array_pop($ex_val['option_value_description']);
					if ($name['name'] == $option_value) {
						$exists = true;
						break;
					}
				}
				if (!$exists) {
					if($option_value != '') {
						$new_option['option_value'][$i] = array(
										'option_value_id' => '',
										'sort_order' => $sort_order,
										'image' => ''
						);
						foreach ($settings['language'] as $language) {
							$new_option['option_value'][$i]['option_value_description'][$language]['name'] = $option_value;
						}
					}
					$i++;
				}
			}
			if ($option_id) {
				$new_option['option_value'] = array_merge($new_option['option_value'], $existing_values);
				$this->model_catalog_option->editOption($option_id, $new_option);
			} else {
				$this->model_catalog_option->addOption($new_option);
			}
		}
	}


	/*
	 * function validate
	 * @param (int) the step we are currently validating.
	 * @return (boolean) true if valid posted data and user
	 */
	private function validate($step='') {
		$this->load->language('tool/total_import');
		if (defined('CLI_INITIATED')) {
			return true; // no validation for CLI initiated runs
		} elseif (!$this->user->hasPermission('modify', 'tool/total_import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif ($step) {
			$this->load->model('tool/total_import');
			$this->language->load('tool/total_import');
			if ($step == '1') {
				// step 1 input validation
				$settings = $this->request->post;
				if (!empty($settings['source']) && ($settings['source'] == 'filepath')) {
					if (!file_exists($settings['feed_filepath'])) {
						$this->error['warning'] = 'Warning: The specified filepath does not exist';
					}
				}
				if (!empty($settings['source']) && ($settings['source'] == 'ftp')) {
					if (!$settings['feed_ftpserver'] || !$settings['feed_ftpuser'] || !$settings['feed_ftppass'] || !$settings['feed_ftppath']) {
						$this->error['warning'] = 'Warning: Check your ftp details are set';
					}
				}
			} else {
				if ($step == '3') {
					if(isset($this->request->post['adjust'])) {
						foreach ($this->request->post['adjust'] as $adjustment) {
							foreach ($adjustment as $value) {
								if($value == '--Select--'){
									$this->error['warning'] = '"-- Select --" is an invalid field, please pick an existing field from your feed';
								}
							}
						}
					}
				}

				// all other steps
				if (!$this->model_tool_total_import->dbReady()) {
					//if hj_import db doesn't exist redirect to step 1 (either hasn't been run or unsuccessful)
					if (isset($this->session->data['success'])) {
						unset($this->session->data['success']);
					}
					$this->session->data['warning'] = $this->language->get('error_no_db');
					$this->error['warning'] = 'true';
					$this->redirect($this->url->link('tool/total_import/step1', 'token=' . $this->session->data['token'], 'SSL'));
				}
			}
		}
		return (!$this->error);
	}


	private function validateFeed($filename, &$settings) {
		$this->load->language('tool/total_import');
		if (!$filename) {
			if(!isset($settings['source'])) {
				$this->error['warning'] = $this->language->get('error_no_source');
			} else {
				if ($settings['source'] == 'file' && $this->request->files['feed_file']['error'] !== UPLOAD_ERR_OK) {
					$this->error['warning'] = $this->model_tool_total_import->fileUploadErrorMessage($this->request->files['feed_file']['error']);
				} else {
					$this->error['warning'] = $this->language->get('error_empty');
				}
			}
		} else {
			$fp = fopen($filename, 'r');
			if ($settings['format'] == 'csv') {
				if ($settings['delimiter'] == '\t') {
					$settings['delimiter'] = "\t";
				} elseif ($settings['delimiter'] == '') {
					$settings['delimiter'] = ',';
				}
				$first_line = fgetcsv($fp, 0, $settings['delimiter']);
				if (count($first_line) < 2) { //only one item in first row (probably wrong delimiter)
					$this->error['warning'] = $this->language->get('error_wrong_delimiter');
				}
				if (feof($fp)) { //only one line in file (probably Mac CSV)
					$this->error['warning'] = $this->language->get('error_mac_csv');
				}
				if (empty($settings['safe_headers']) || !empty($settings['has_headers'])) {
					$existing = array();
					
					foreach ($first_line as $heading) {
						if ($heading === '') { //empty column heading
							$this->error['warning'] = sprintf($this->language->get('error_csv_heading'), '<strong>non-empty</strong>');
						}
						if (isset($existing[strtolower($heading)])) { //non-unique column heading (case insensitive)
							$this->error['warning'] = sprintf($this->language->get('error_csv_heading'), '<strong>unique</strong>');
						}
						$existing[strtolower($heading)] = 1;
					}
				}
			} elseif ($settings['format'] == 'xml') {
				//@todo check specified product tag exists
				if (!$settings['xml_product_tag']) {
					$this->error['warning'] = $this->language->get('error_xml_product_tag');
				}
			}
		}
		return (!$this->error);
	}

	/*
	 * function test
	 *
	 * runs all the unit tests
	 *
	 */
	public function test() {
		$this->load->model('tool/total_import');
		$this->model_tool_total_import->emptyTables();
		$this->load->model('tool/total_import_test');
		$add_tests = $this->model_tool_total_import_test->getAddTests();
		$update_tests = $this->model_tool_total_import_test->getUpdateTests();
		echo '<html><head><title>Total Import PRO Testing</title></head><body><h1>Running Tests...</h1>';
		echo '<h2>Total Tests: ' . (count($add_tests)+count($update_tests)) . '</h2>';
		foreach (array($add_tests, $update_tests) as $tests) {
			foreach ($tests as $test) {
				$this->resetDefaultValues($test['settings']);
				echo "<p>" . $test['name'] . "............";
				$result = (!$test['update_id']) ? $this->addProduct($test['input'], $test['settings']) : $this->updateProduct($test['update_id'], $test['input'], $test['settings']);

				if ($result == $test['expected']) {
					//SUCCESS!
					echo "[<span style='color:green'>OK</span>]";
				} else {
					//FAILURE!
					echo "[<span style='color:red'>FAILED</span>]";
					echo "<pre>";
					print_r($this->model_tool_total_import_test->array_rdiff($result, $test['expected']));
					echo "</pre>";
				}
				echo "</p>";
			}
		}
		echo '<h2>Tests Complete</h2></body></html>';
	}

	public function testImages() {
		$this->load->model('tool/total_import_test');
		$this->load->model('tool/total_import');
		$tests = $this->model_tool_total_import_test->getImageFetchTests();
		echo '<html><head><title>Total Import PRO Testing Images</title></head><body><h1>Running Tests...</h1>';
		echo '<h2>Total Tests: ' . count($tests) . '</h2>';
		foreach ($tests as $test) {
			echo "<p>" . $test['description'] . "............";
			$result = $this->model_tool_total_import->fetchImage($test['input']);

			if ($result == $test['expected']) {
				//SUCCESS!
				echo "[<span style='color:green'>OK</span>]";
			} else {
				//FAILURE!
				echo "[<span style='color:red'>FAILED</span>]";
				echo "<pre>";
				echo "EXPECTED: " . $test['expected'];
				echo "RESULT: $result";
				echo "</pre>";
			}
			echo "</p>";
		}
		echo '<h2>Tests Complete</h2></body></html>';
	}
	
	function getQuantities($option) {
		return $option['quantity'];
	}
	
	public function saveSettings() {
		if(isset($this->request->post['step'])) {
			$settings = $this->request->post;
			if($settings){
				foreach ($settings as $key => $value) {
					$settings[$key] = serialize($value);
				}
				
				if($this->request->post['step'] == 'import_step5') {
					//save new settings profile
					if (!empty($this->request->post['save_settings_name'])) {
						$this->load->model('tool/total_import');
						$this->model_tool_total_import->saveSettings($this->request->post['save_settings_name']);
					}
				}
				//store each setting for this module
				$this->load->model('setting/setting');
				$this->model_setting_setting->editSetting($this->request->post['step'], $settings);
				$this->load->language('tool/total_import');
				print $this->language->get('text_success');
			}
		}
	}
}
?>