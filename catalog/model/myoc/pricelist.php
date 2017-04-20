<?php
class ModelMyocPricelist extends Model {
	public function getProducts($data = array()) {		
		$sql = $this->getProductsSQL($data);

		$sort_data = array(
			'name',
			'description',
			'model',
			'sku',
			'upc',
			'ean',
			'jan',
			'isbn',
			'mpn',
			'manufacturer',
			'price',
			'quantity',
			'stock_status',
			'minimum',
			'rating',
			'dimension',
			'weight',
			'date_added',
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'name') {
				$sql .= " ORDER BY LCASE(pd." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'description') {
				$sql .= " ORDER BY LCASE(pd." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'model') {
				$sql .= " ORDER BY LCASE(p." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'sku') {
				$sql .= " ORDER BY LCASE(p." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'upc') {
				$sql .= " ORDER BY LCASE(p." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'ean') {
				$sql .= " ORDER BY LCASE(p." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'jan') {
				$sql .= " ORDER BY LCASE(p." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'isbn') {
				$sql .= " ORDER BY LCASE(p." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'mpn') {
				$sql .= " ORDER BY LCASE(p." . $data['sort'] . ")";
			} elseif ($data['sort'] == 'manufacturer') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} elseif ($data['sort'] == 'quantity') {
				$sql .= " ORDER BY p." . $data['sort'];
			} elseif ($data['sort'] == 'stock_status') {
				$sql .= " ORDER BY p.quantity";
			} elseif ($data['sort'] == 'minimum') {
				$sql .= " ORDER BY p." . $data['sort'];
			} elseif ($data['sort'] == 'rating') {
				$sql .= " ORDER BY " . $data['sort'];
			} elseif ($data['sort'] == 'dimension') {
				$sql .= " ORDER BY (p.length * p.width * p.height)";
			} elseif ($data['sort'] == 'weight') {
				$sql .= " ORDER BY p." . $data['sort'];
			} elseif ($data['sort'] == 'date_added') {
				$sql .= " ORDER BY p." . $data['sort'];
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} elseif(isset($data['sort']) && substr($data['sort'], 0, 4) == 'attr') {
			$sql .= " ORDER BY LCASE(pattrib.text)";
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$product_data = array();

		$query = $this->db->query($sql);
	
		foreach ($query->rows as $result) {
			$product_data[] = $result['product_id'];
		}

		return $product_data;
	}

	public function getTotalProducts($data = array()) {
		$sql = $this->getProductsSQL($data);

		$query = $this->db->query($sql);
		
		return $query->num_rows;
	}

	private function getProductsSQL($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		if(isset($data['sort']) && substr($data['sort'], 0, 4) == 'attr') {
			$attribute_id = substr($data['sort'], 4);
		} else {
			$attribute_id = 0;
		}

		$sql = "SELECT p.product_id, (SELECT m.name FROM " . DB_PREFIX . "manufacturer m WHERE m.manufacturer_id = p.manufacturer_id) AS manufacturer, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special"; 
		
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}
		
			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_attribute pattrib ON (p.product_id = pattrib.product_id AND pattrib.attribute_id = '" . (int)$attribute_id . "' AND pattrib.language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	
		
			if (!empty($data['filter_filter'])) {
				$implode = array();
				
				$filters = explode(',', $data['filter_filter']);
				
				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}
				
				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}
		
		if (isset($data['filter_stock']) && $data['filter_stock'] == 0) {
			$sql .= " AND ( (p.subtract = 1 AND p.quantity > 0) || p.subtract = 0)";
		}

		if (isset($data['filter_product_ids'])) {
			$sql .= " AND p.product_id IN (" . $data['filter_product_ids'] . ")";
		}
		
		$sql .= " GROUP BY p.product_id";

		if (isset($data['filter_special']) && $data['filter_special'] == 1 && isset($data['filter_discount']) && $data['filter_discount'] == 1) {
			$sql .= " HAVING (special IS NOT NULL || discount IS NOT NULL)";
		}
	
		if (isset($data['filter_special']) && $data['filter_special'] == 1 && isset($data['filter_discount']) && $data['filter_discount'] == 0) {
			$sql .= " HAVING special IS NOT NULL";
		}

		if (isset($data['filter_special']) && $data['filter_special'] == 0 && isset($data['filter_discount']) && $data['filter_discount'] == 1) {
			$sql .= " HAVING discount IS NOT NULL";
		}

		return $sql;
	}

	public function getBarcode($args = array('type' => 'std25', 'code' => '', 'width' => 200, 'height' => 60, 'fontsize' => 12, 'zoom' => 2)) {
		$width = $args['width'];
		$height = $args['height'];
		$angle = 0;
		$font = DIR_SYSTEM . 'font/arial.ttf';
		$fontSize = $args['fontsize'];
		$marge = 2;
		$im = imagecreatetruecolor($width, $height + $fontSize*1.4 + $marge);
		$black = ImageColorAllocate($im,0x00,0x00,0x00);
		$white = ImageColorAllocate($im,0xff,0xff,0xff);
		imagefilledrectangle($im, 0, 0, $width, $height + $fontSize*1.4 + $marge, $white);
		$data = Barcode::gd($im, $black, $width/2, $height/2, $angle, $args['type'], $args['code'], $args['zoom'], $height);

		if (isset($font)) {
			$box = imagettfbbox($fontSize, 0, $font, $data['hri']);
			$len = $box[2] - $box[0];
			Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
			imagettftext($im, $fontSize, $angle, ($width/2) + $xt, (($height)/2) + $yt, $black, $font, $data['hri']);
		}

		//header('Content-type: image/png');
		ob_start();
		imagepng($im);
		$output = ob_get_contents();
		ob_end_clean();
		return base64_encode($output);
	}
}
?>