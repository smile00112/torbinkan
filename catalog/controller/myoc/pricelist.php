<?php
class ControllerMyocPricelist extends Controller {
	public function index() {
	    $this->language->load('myoc/pricelist');

		if (isset($this->request->get['pricelist_id'])) {
			$pricelist_id = $this->request->get['pricelist_id'];
		} else {
			$pricelist_id = 0;
		}

		$myocwpl_data = $this->config->get('myocwpl_data');
		$pricelist = false;
		if($myocwpl_data)
		{
			foreach ($myocwpl_data as $wpl) {
				if($wpl['pricelist_id'] == $pricelist_id && $wpl['status'] && isset($wpl['store']) && in_array($this->config->get('config_store_id'), $wpl['store']))
				{
					$pricelist = $wpl;
					break;
				}
			}
		}

		if($pricelist) {
			if($pricelist['login'] && !$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('product/pricelist', '&pricelist_id=' . $pricelist['pricelist_id']);

				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}

			$this->document->setTitle($this->language->get('heading_title'));

			if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/myoc/pricelist.css')) {
				$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/myoc/pricelist.css');
			} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/myoc/pricelist.css');
			}

			if(file_exists('catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css')) {
				$this->document->addStyle('catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css');
				$this->document->addScript('catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js');
			}
			if(file_exists('catalog/view/javascript/jquery/colorbox/colorbox.css')) {
				$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
				$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			}

			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}

			$this->data['base'] = $server;
			$this->data['description'] = $this->document->getDescription();
			$this->data['keywords'] = $this->document->getKeywords();
			$this->data['links'] = $this->document->getLinks();	 
			$this->data['styles'] = $this->document->getStyles();
			$this->data['scripts'] = $this->document->getScripts();
			$this->data['lang'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
			$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
			$this->data['name'] = $this->config->get('config_name');
	
			if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
				$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
			} else {
				$this->data['icon'] = '';
			}
			
			if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
				$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
			} else {
				$this->data['logo'] = '';
			}	

			$this->data['store_url'] = $this->data['base'];
			$this->data['store_title'] = $this->config->get('config_title');
			$this->data['store_address'] = nl2br($this->config->get('config_address'));
			$this->data['store_email'] = $this->config->get('config_email');
			$this->data['store_telephone'] = $this->config->get('config_telephone');

			$this->data['title'] = $this->document->getTitle();

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('product/pricelist', '&pricelist_id=' . $pricelist['pricelist_id']),
				'separator' => $this->language->get('text_separator')
			);

			$this->data['heading_title'] = isset($pricelist['name'][$this->config->get('config_language_id')]) ? $pricelist['name'][$this->config->get('config_language_id')] : $this->language->get('heading_title');

			$this->data['text_empty'] = $this->language->get('text_empty');

			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['continue'] = $this->url->link('common/home', '');

			if($pricelist['login'] && $this->customer->isLogged() && (!isset($pricelist['customer_group']) || !in_array($this->customer->getCustomerGroupId(), $pricelist['customer_group']))) {
				$this->data['error_customer_group'] = $this->language->get('error_customer_group');
			} else {
				$this->load->model('myoc/pricelist');
				$this->load->model('catalog/product');
				$this->load->model('catalog/category');
				$this->load->model('tool/image');

				$default_limits = empty($pricelist['page']) ? array(15,25,50,75,100) : explode(",", $pricelist['page']);

				if (isset($this->request->get['catid'])) {
					$catid = $this->request->get['catid'];
				} else {
					$catid = 0;
				}

				if (isset($this->request->get['sort'])) {
					$sort = $this->request->get['sort'];
				} else {
					$sort = 'name';
				}

				if (isset($this->request->get['order'])) {
					$order = $this->request->get['order'];
				} else {
					$order = 'ASC';
				}

				if (isset($this->request->get['limit'])) {
					$limit = $this->request->get['limit'];
				} else {
					$limit = $default_limits[0];
				}

				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}

				if (isset($this->request->get['output'])) {
					$output = $this->request->get['output'];
				} else {
					$output = '';
				}

				//languages
				$this->data['text_limit'] = $this->language->get('text_limit');
				$this->data['text_category'] = $this->language->get('text_category');
				$this->data['text_print'] = $this->language->get('text_print');
				$this->data['text_discount'] = $this->language->get('text_discount');
				$this->data['text_pdf'] = $this->language->get('text_pdf');
				$this->data['text_qty'] = $this->language->get('text_qty');
				$this->data['text_select'] = $this->language->get('text_select');
				$this->data['text_or'] = $this->language->get('text_or');

				$this->data['button_upload'] = $this->language->get('button_upload');
				$this->data['button_cart'] = $this->language->get('button_cart');
				$this->data['button_wishlist'] = $this->language->get('button_wishlist');
				$this->data['button_compare'] = $this->language->get('button_compare');

				//settings
				$this->data['cat_filter'] = $pricelist['cat_filter'];
				$this->data['multicart'] = $pricelist['multicart'];
				$this->data['add_wishlist'] = $pricelist['add_wishlist'];
				$this->data['add_compare'] = $pricelist['add_compare'];
				$this->data['show_option'] = $pricelist['option'];

				$url = ''; //column header sort href

				$url .= '&pricelist_id=' . $pricelist['pricelist_id'];

				if ($order == 'ASC') {
					$url .= '&order=DESC';
				} else {
					$url .= '&order=ASC';
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				if (isset($this->request->get['catid'])) {
					$url .= '&catid=' . $this->request->get['catid'];
				}

				$this->data['columns'] = array();
				if(isset($pricelist['columns']) && $pricelist['columns']) {
					foreach ($pricelist['columns'] as $column) {
						if ($column['column'] == 'price' && $this->config->get('config_customer_price') && !$this->customer->isLogged()) {
							continue;
						}
						$this->data['columns'][] = array(
							'type' => $column['column'],
							'name' => $column['name'][$this->config->get('config_language_id')],
							'sortable' => isset($column['sortable']) ? $this->url->link('product/pricelist', '&sort=' . $column['column'] . $url) : false,
							'barcode' => $column['barcode'],
							'for_pricelist' => isset($column['for_pricelist']) ? true : false,
							'for_print' => isset($column['for_print']) ? true : false,
							'for_pdf' => isset($column['for_pdf']) ? true : false,
						);
					}
				}

				$url = ''; //limits

				$url .= '&pricelist_id=' . $pricelist['pricelist_id'];

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['catid'])) {
					$url .= '&catid=' . $this->request->get['catid'];
				}

				$this->data['limits'] = array();

				foreach($default_limits as $default_limit) {
					$this->data['limits'][] = array(
						'value' => $default_limit,
						'href'  => $this->url->link('product/pricelist', $url . '&limit='. $default_limit),
					);
				}

				$url = ''; //categories

				$url .= '&pricelist_id=' . $pricelist['pricelist_id'];

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$this->data['categories'] = array();
				$this->data['categories'][] = array(
					'category_id' => 0,
					'path' => $this->language->get('text_all_products'),
					'href' => $this->url->link('product/pricelist', $url),
				);

				$url = ''; //redirect, pagination href

				$url .= '&pricelist_id=' . $pricelist['pricelist_id'];

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				if (isset($this->request->get['catid'])) {
					$url .= '&catid=' . $this->request->get['catid'];
				}

				$this->data['catid'] = $catid;

				$product_ids = array(0);
				if(isset($pricelist['product']) && !empty($pricelist['product']))
				{
					$product_ids = explode(",", $pricelist['product']);
				}
				if(isset($pricelist['category']))
				{
					foreach ($pricelist['category'] as $category_id) {
						$filter_category_data = array(
							'filter_category_id'  => $category_id,
							'filter_sub_category' => false	
						);
						$category_products = $this->model_catalog_product->getProducts($filter_category_data);
						foreach ($category_products as $category_product) {
							if(!in_array($category_product['product_id'], $product_ids))
							{
								$product_ids[] = $category_product['product_id'];
							}
						}
					}
				}

				if(isset($pricelist['manufacturer']))
				{
					foreach ($pricelist['manufacturer'] as $manufacturer_id) {
						$filter_manufacturer_data = array(
							'filter_manufacturer_id'  => $manufacturer_id,
						);
						$manufacturer_products = $this->model_catalog_product->getProducts($filter_manufacturer_data);
						foreach ($manufacturer_products as $manufacturer_product) {
							if(!in_array($manufacturer_product['product_id'], $product_ids))
							{
								$product_ids[] = $manufacturer_product['product_id'];
							}
						}
					}
				}

				$data = array(
					'filter_category_id' => '',
					'filter_product_ids' => implode(",", $product_ids),
					'filter_stock' => $pricelist['filter_stock'],
					'filter_special' => $pricelist['filter_special'],
					'filter_discount' => $pricelist['filter_discount'],
				);

				if($pricelist['cat_filter'])
				{
					$product_categories_array = array();
					$categories = $this->getCategories(0);
					$all_products = $this->model_myoc_pricelist->getProducts($data);

					foreach($all_products as $product_id) {
						if($this->model_catalog_product->getProduct($product_id)) {
							$product_categories = $this->model_catalog_product->getCategories($product_id);
							if($product_categories) {
								foreach ($product_categories as $product_category) {
									$product_categories_array[] = $product_category['category_id'];
								}
							}
						}
					}
					foreach ($categories as $category) {
						if((isset($pricelist['category']) && in_array($category['category_id'], $pricelist['category'])) || in_array($category['category_id'], $product_categories_array)) {
							$this->data['categories'][] = array(
								'category_id' => $category['category_id'],
								'path' => $this->getPath($category['category_id']),
								'href' => $this->url->link('product/pricelist', $url . '&catid=' . $category['category_id']),
							);
						}
					}
				}

				$data = array_merge($data, array(
					'filter_category_id' => $catid,
					'sort' => $sort,
					'order' => $order,
				));

				$product_total = $this->model_myoc_pricelist->getTotalProducts($data);

				if(!($output == 'print' && $pricelist['print_paging'] == 0) && !($output == 'pdf' && $pricelist['pdf_paging'] == 0)) {
					$data = array_merge($data, array(
						'start' => ($page - 1) * $limit,
						'limit' => $limit,
					));
				}

				$products = $this->model_myoc_pricelist->getProducts($data);

				$this->data['products'] = array();

				$count = 1;
				$start = ($page - 1) * $limit;
				if($start < 0)
				{
					$start = 0;
				}
				foreach($products as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);
					if(!$product_info) {
						$product_total--;
						continue;
					}
					if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
						$product_info['price'] = false;
					}

					$discounts = array();

					if($pricelist['discount']) {
						$discount_query = $this->model_catalog_product->getProductDiscounts($product_info['product_id']);
						
						if($product_info['price']) { 
							foreach ($discount_query as $discount) {
								$discounts[] = array(
									'quantity' => $discount['quantity'],
									'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
								);
							}
						}
					}

					$options = array();
                    $pricelist['option'] = 1;
					if($pricelist['option']) {
						foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {
							if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
								$option_value_data = array();
								
								foreach ($option['option_value'] as $option_value) {
									if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
										if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
											$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
										} else {
											$price = false;
										}

										$option_value_data[] = array(
											'product_option_value_id' => $option_value['product_option_value_id'],
											'option_value_id'         => $option_value['option_value_id'],
											'name'                    => $product_info['name'].' '.$option_value['name'],
                                            'size'                    => $option_value['name'], 
                                            'amount_option'           => $option_value['amount_option'],
                                            'unit_option'             => $option_value['unit_option'],
                                            'model_option'            => $option_value['model_option'],
											'image'                   => isset($option_value['image']) ? $this->model_tool_image->resize($option_value['image'], 50, 50) : false,
											'price'                   => $price,
											'price_prefix'            => $option_value['price_prefix']
										);
									}
								}
								
								$options[] = array(
									'product_option_id' => $option['product_option_id'],
									'option_id'         => $option['option_id'],
									'name'              => $option['name'],
									'type'              => $option['type'],
									'option_value'      => $option_value_data,
									'required'          => $option['required']
								);					
							} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
								$options[] = array(
									'product_option_id' => $option['product_option_id'],
									'option_id'         => $option['option_id'],
									'name'              => $option['name'],
									'type'              => $option['type'],
									'option_value'      => $option['option_value'],
									'required'          => $option['required']
								);						
							}
						}
					}						

					$this->data['products'][$product_info['product_id']] = array(
						'num' =>  $start + $count,
						'image' => $product_info['image'] ? $this->model_tool_image->resize($product_info['image'], $pricelist['image_width'], $pricelist['image_height']) : $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'popup' => $product_info['image'] ? $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) : false,
						'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
						'name' => $product_info['name'],
						'description' => nl2br($this->word_trim(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), $pricelist['description_length'], TRUE)),
						'model' => $product_info['model'],
						'sku' => $product_info['sku'] == "" ? false : $product_info['sku'],
						'upc' => (!isset($product_info['upc']) || $product_info['upc'] == "") ? false : $product_info['upc'],
						'ean' => (!isset($product_info['ean']) || $product_info['ean'] == "") ? false : $product_info['ean'],
						'jan' => (!isset($product_info['jan']) || $product_info['jan'] == "") ? false : $product_info['jan'],
						'isbn' => (!isset($product_info['isbn']) || $product_info['isbn'] == "") ? false : $product_info['isbn'],
						'mpn' => (!isset($product_info['mpn']) || $product_info['mpn'] == "") ? false : $product_info['mpn'],
						'manufacturer' => $product_info['manufacturer'],
						'rating' => $product_info['rating'],
						'rating_img' => file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/image/stars-' . $product_info['rating'] . '.png') ? 'catalog/view/theme/' . $this->config->get('config_template') . '/image/stars-' . $product_info['rating'] . '.png' : 'catalog/view/theme/default/image/stars-' . $product_info['rating'] . '.png',
						'price' => $product_info['price'] ? $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : false,
						'special' => $product_info['special'] ? $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'))) : false,
						'discounts' => $discounts,
						'options' => $options,
						'quantity' => ($product_info['quantity'] < 0) ? 0 : $product_info['quantity'],
						'stock_status' => ($product_info['quantity'] <= 0) ? $product_info['stock_status'] : $this->language->get('text_instock'),
						'minimum' => $product_info['minimum'],
						'length' => isset($product_info['length_class_id']) ? $this->length->format($product_info['length'], $product_info['length_class_id']) : (float)$product_info['length'].$product_info['length_class'],
						'width' => isset($product_info['length_class_id']) ? $this->length->format($product_info['width'], $product_info['length_class_id']) : (float)$product_info['width'].$product_info['length_class'],
						'height' => isset($product_info['length_class_id']) ? $this->length->format($product_info['height'], $product_info['length_class_id']) : (float)$product_info['height'].$product_info['length_class'],
						'weight' => isset($product_info['weight_class_id']) ? $this->weight->format($product_info['weight'], $product_info['weight_class_id']) : (float)$product_info['weight'].$product_info['weight_class'],
						'date_added' => date('j M Y', strtotime($product_info['date_added'])),
						'attribute_groups' => $this->model_catalog_product->getProductAttributes($product_id),
					);
					$count++;
				}

				if((($page - 1) * $limit) > $product_total) {
					$this->redirect($this->url->link('product/pricelist', $url));
				}

				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('product/pricelist', $url . '&page={page}');

				$this->data['pagination'] = $pagination->render();

				//print button
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
				$this->data['limit'] = $limit;

				$this->data['barcode_width'] = $pricelist['barcode_width'];
				$this->data['barcode_height'] = $pricelist['barcode_height'];
				$this->data['barcode_zoom'] = $pricelist['barcode_zoom'];
				$this->data['barcode_fontsize'] = $pricelist['barcode_fontsize'];

				$this->data['print'] = $pricelist['print'] ? $this->url->link('product/pricelist', $url . '&output=print') : false;
				$this->data['pdf'] = $pricelist['pdf'] ? $this->url->link('product/pricelist', $url . '&output=pdf') : false;

				$this->data['output'] = (!empty($output)) ? $output : 'print';
			}

			$tpl = 'pricelist.tpl';

			if($output == 'print') {
				$tpl = 'pricelist_print.tpl';
			} elseif ($output == 'pdf') {
				if(!headers_sent() && class_exists('mPDF')) {
					$template = new Template();

					$template->data = $this->data;
					$template->model_myoc_pricelist = $this->model_myoc_pricelist;

					$format = 'A4' . ($pricelist['pdf_orientation'] == 'L' ? '-L' : '');

					$mpdf = new mPDF('', $format);

					$mpdf->SetHTMLFooter('<table class="tbl"><tr><td class="tleft">' . $this->data['pdf'] . '</td><td class="tright">{PAGENO}</td></tr></table>');

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/myoc/pricelist_print.tpl')) {
						$mpdf->WriteHTML($template->fetch($this->config->get('config_template') . '/template/myoc/pricelist_print.tpl'));
					} else {
						$mpdf->WriteHTML($template->fetch('default/template/myoc/pricelist_print.tpl'));
					}

					$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->data['heading_title'], ENT_QUOTES, 'UTF-8')));

					$mpdf->Output($filename . '.pdf', 'D');
				} else {
					$tpl = 'pricelist_print.tpl';
				}
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/myoc/' . $tpl)) {
				$this->template = $this->config->get('config_template') . '/template/myoc/' . $tpl;
			} else {
				$this->template = 'default/template/myoc/' . $tpl;
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
				'href'      => $this->url->link('product/pricelist', '&pricelist_id=' . $pricelist_id),
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

    private function getCategories($parent_id) {
        $this->load->model('catalog/category');
        $categories = array();
        $categories_info = $this->model_catalog_category->getCategories($parent_id);
        if($categories_info) {
	        foreach ($categories_info as $category) {
	        	$categories[] = $category;
	        	$categories = array_merge($categories, $this->getCategories($category['category_id']));
			}
		}
		return $categories;
	}

	private function getPath($category_id) {
		$this->load->model('catalog/category');
		$category_info = $this->model_catalog_category->getCategory($category_id);
		if(!$category_info) {
			return false;
		}
		if ($category_info['parent_id']) {
			return $this->getPath($category_info['parent_id']) . $this->language->get('text_separator') . $category_info['name'];
		}
		return $category_info['name'];
	}

    private function word_trim($string, $count, $ellipsis = FALSE) {
        $words = explode(' ', $string);
        if (count($words) > $count){
            array_splice($words, $count);
            $string = implode(' ', $words);
            if (is_string($ellipsis)){
                $string .= $ellipsis;
            } elseif ($ellipsis){
                $string .= '&hellip;';
            }
        }
        return $string;
    }
}
?>