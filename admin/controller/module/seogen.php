<?php
class ControllerModuleSeogen extends Controller {

	private $error = array();

	public function index() {

		$this->data += $this->load->language('module/seogen');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/setting');
		
		$this->data['token'] = $this->session->data['token'];

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['seogen_status'] = isset($this->request->post['seogen_status']) ? 1 : 0;

			if (!isset($this->request->post['seogen']['seogen_overwrite'])){
				$this->request->post['seogen']['seogen_overwrite'] = 0;
			}
			$this->model_setting_setting->editSetting('seogen', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/seogen', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('module/seogen', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['generate'] = $this->url->link('module/seogen/generate', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('catalog/category');

		// function from 1.5.4.1
		$categories = $this->model_catalog_category_getAllCategories();

		$this->data['categories'] = $this->getAllCategories($categories);

		if(isset($this->request->post['seogen'])) {
			$this->data['seogen'] = $this->request->post['seogen'];
		} elseif($this->config->get('seogen')) {
			$this->data['seogen'] = $this->config->get('seogen');
		}

		if (!isset($this->data['seogen']['only_categories'])){
			$this->data['seogen']['only_categories'] = array();
		}

		$default_tags = $this->getDefaultTags();
		foreach($default_tags['seogen'] as $k => $v) {
			if(!isset($this->data['seogen'][$k])) {
				$this->data['seogen'][$k] = $v;
			}
		}

		if(!$this->data['seogen']['main_category_exists']){
			$language = $this->load->language('module/seogen_oc');
			$this->data = array_merge($this->data, $language);
		}

		$this->data['seogen_status'] = $this->config->get('seogen_status');
		if(isset($this->request->post['seogen_status'])) {
			$this->data['seogen_status'] = $this->request->post['seogen_status'];
		}

		$this->template = 'module/seogen.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getDefaultTags(){
		$seogen = array('seogen_status' => 1,
				'seogen' => array(
						'seogen_overwrite' => 1,
						'categories_template' => $this->language->get('text_categories_tags'),
						'categories_description_template' => $this->language->get('text_categories_description_tags'),
						'categories_meta_description_limit' => 160,
						'products_template' => $this->language->get('text_products_tags'),
						'products_model_template' => $this->language->get('text_products_model_tags'),
						'products_description_template' => $this->language->get('text_products_description_tags'),
						'products_description_small_template' => $this->language->get('text_products_description_small'),
						'products_meta_description_limit' => 160,
						'products_img_alt_template' => $this->language->get('text_products_img_alt'),
						'products_img_title_template' => $this->language->get('text_products_img_title'),
						'manufacturers_template' => $this->language->get('text_manufacturers_tags'),
						'manufacturers_description_template' => $this->language->get('text_manufacturers_description_tags'),
						'informations_template' => $this->language->get('text_informations_tags'),
				));
		$query = $this->db->query("DESC `" . DB_PREFIX . "category_description`");
		foreach($query->rows as $row) {
			if($row['Field'] == "seo_title") {
				$seogen['seogen']['categories_title_template'] = $this->language->get('text_categories_title_tags');
			} elseif($row['Field'] == "seo_h1") {
				$seogen['seogen']['categories_h1_template'] = $this->language->get('text_categories_h1_tags');
			} elseif($row['Field'] == "meta_description") {
				$seogen['seogen']['categories_meta_keyword_template'] = $this->language->get('text_categories_meta_keyword_tags');
			} elseif($row['Field'] == "meta_keyword") {
				$seogen['seogen']['categories_meta_description_template'] = $this->language->get('text_categories_meta_description_tags');
			}
		}

		$query = $this->db->query("DESC `" . DB_PREFIX . "product_description`");
		foreach($query->rows as $row) {
			if($row['Field'] == "seo_title") {
				$seogen['seogen']['products_title_template'] = $this->language->get('text_products_title_tags');
			} elseif($row['Field'] == "seo_h1") {
				$seogen['seogen']['products_h1_template'] = $this->language->get('text_products_h1_tags');
			} elseif($row['Field'] == "meta_description") {
				$seogen['seogen']['products_meta_keyword_template'] = $this->language->get('text_products_meta_keyword_tags');
			} elseif($row['Field'] == "meta_keyword") {
				$seogen['seogen']['products_meta_description_template'] = $this->language->get('text_products_meta_description_tags');
			}
		}

		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "manufacturer_description'");
		if($query->num_rows) {
			$query = $this->db->query("DESC `" . DB_PREFIX . "manufacturer_description`");
			foreach($query->rows as $row) {
				if($row['Field'] == "seo_title") {
					$seogen['seogen']['manufacturers_title_template'] = $this->language->get('text_manufacturers_title_tags');
				} elseif($row['Field'] == "seo_h1") {
					$seogen['seogen']['manufacturers_h1_template'] = $this->language->get('text_manufacturers_h1_tags');
				} elseif($row['Field'] == "meta_description") {
					$seogen['seogen']['manufacturers_meta_keyword_template'] = $this->language->get('text_manufacturers_meta_keyword_tags');
				} elseif($row['Field'] == "meta_keyword") {
					$seogen['seogen']['manufacturers_meta_description_template'] = $this->language->get('text_manufacturers_meta_description_tags');
				}
			}
		}

		$query = $this->db->query("DESC `" . DB_PREFIX . "information_description`");
		foreach($query->rows as $row) {
			if($row['Field'] == "seo_title") {
				$seogen['seogen']['informations_title_template'] = $this->language->get('text_informations_title_tags');
			} elseif($row['Field'] == "seo_h1") {
				$seogen['seogen']['informations_h1_template'] = $this->language->get('text_informations_h1_tags');
			} elseif($row['Field'] == "meta_description") {
				$seogen['seogen']['informations_meta_keyword_template'] = $this->language->get('text_informations_meta_keyword_tags');
			} elseif($row['Field'] == "meta_keyword") {
				$seogen['seogen']['informations_meta_description_template'] = $this->language->get('text_informations_meta_description_tags');
			}
		}
		$query = $this->db->query("DESC `" . DB_PREFIX . "product_to_category`");
		foreach($query->rows as $row) {
			if($row['Field'] == "main_category") {
				$seogen['seogen']['main_category_exists'] = true;
			}
		}
		if (!isset($seogen['seogen']['main_category_exists'])){
			$seogen['seogen']['main_category_exists'] = false;
		}
		return $seogen;
	}
	public function install() {
		$this->load->language('module/seogen');
		$this->load->model('setting/setting');
		$seogen = $this->getDefaultTags();
		$this->model_setting_setting->editSetting('seogen', $seogen);
	}

	public function generate() {
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['name']) && $this->validate()) {
			$this->load->language('module/seogen');
			$this->load->model('module/seogen');
			$name = $this->request->post['name'];
		    if($name == 'categories') {
				$this->model_module_seogen->generateCategories($this->request->post['seogen']);
			} elseif($name == 'products') {
				$this->model_module_seogen->generateProducts($this->request->post['seogen']);
			} elseif($name == 'manufacturers') {
				$this->model_module_seogen->generateManufacturers($this->request->post['seogen']);
			} elseif($name == 'informations') {
				$this->model_module_seogen->generateInformations($this->request->post['seogen']);
			}
			$this->response->setOutput($this->language->get('text_success_generation'));
			$this->saveSettings($this->request->post['seogen']);
			$this->cache->delete("seo_pro");
		}
	}

	private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
		$output = array();

		if (array_key_exists($parent_id, $categories)) {
			if ($parent_name != '') {
				$parent_name .= $this->language->get('text_separator');
			}

			foreach ($categories[$parent_id] as $category) {
				$output[$category['category_id']] = array(
					'category_id' => $category['category_id'],
					'name'        => $parent_name . $category['name']
				);

				$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
			}
		}

		return $output;
	}

	public function model_catalog_category_getAllCategories() {
		$category_data = $this->cache->get('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));

		if (!$category_data || !is_array($category_data)) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  ORDER BY c.parent_id, c.sort_order, cd.name");

			$category_data = array();
			foreach ($query->rows as $row) {
				$category_data[$row['parent_id']][$row['category_id']] = $row;
			}

			$this->cache->set('category.all.' . $this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $category_data);
		}

		return $category_data;
	}

	private function saveSettings($data) {
		$seogen_status = $this->config->get('seogen_status');
		$seogen = $this->config->get('seogen');
		foreach($data as $key => $val) {
			if(in_array($key, array_keys($seogen))) {
				$seogen[$key] = $val;
			}
		}
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('seogen', array('seogen' => $seogen, 'seogen_status' => $seogen_status));
	}

	private function validate() {
		if(!$this->user->hasPermission('modify', 'module/seogen')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if(!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}

?>