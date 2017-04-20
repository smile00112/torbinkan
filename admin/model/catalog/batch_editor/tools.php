<?php
class ModelCatalogBatchEditorTools extends Model {
	public function Autocomplete($products, $autocomplete, $action) {
		$products = implode (',', $products);
		
		$language_id = (int) $this->config->get('config_language_id');
		
		$fields = $values = $apply = $dublicate = $tags = $tags_start = $tags_end = array ();
		
		if (empty ($autocomplete['separator'])) {
			$autocomplete['separator'] = ', ';
		}
		
		if (isset ($autocomplete['tpl']['attributes'])) {
			$query = $this->db->query('SELECT pa.product_id AS product_id, ad.name AS attribute FROM ' . DB_PREFIX . 'attribute_description ad LEFT JOIN ' . DB_PREFIX . 'product_attribute pa ON (ad.attribute_id = pa.attribute_id AND ad.language_id = "' . $language_id . '" AND pa.language_id = "' . $language_id . '") WHERE pa.product_id IN (' . $products . ')')->rows;
			
			foreach ($query as $data) {
				$attributes[$data['product_id']][] = $data['attribute'];
			}
			
			unset ($autocomplete['tpl']['attributes']);
		}
		
		if (isset ($autocomplete['app']['tag']) && VERSION < '1.5.4') {
			$tag_status = TRUE;
			
			unset ($autocomplete['app']['tag']);
		} else {
			$tag_status = FALSE;
		}
		
		if ($tag_status) {
			$separator = trim ($autocomplete['separator']);
			
			if ($autocomplete['text_start']) {
				$tags_start = explode ($separator, $autocomplete['text_start']);
			}
			
			if ($autocomplete['text_end']) {
				$tags_end = explode ($separator, $autocomplete['text_end']);
			}
		}
		
		$text_start = $text_end = $text_start_tag = $text_end_tag = '';
		
		if ($autocomplete['text_start']) {
			$text_start = $autocomplete['text_start'] . $autocomplete['separator'];
			$text_start_tag = $autocomplete['text_start'] . ',';
		}
		
		if ($autocomplete['text_end']) {
			$text_end = $autocomplete['separator'] . $autocomplete['text_end'];
			$text_end_tag = ',' . $autocomplete['text_end'];
		}
		
		foreach ($autocomplete['tpl'] as $field=>$value) {
			if ($field == 'name') {
				$fields[] = 'pd.' . $field . ' AS ' . $field;
			} else {
				$fields[] = 'p.' . $field . ' AS ' . $field;
			}
		}
		
		if ($fields) {
			$sql = 'SELECT p.product_id, ' . implode (', ', $fields) . ' FROM ' . DB_PREFIX . 'product p ';
		} else {
			$sql = 'SELECT p.product_id FROM ' . DB_PREFIX . 'product p ';
		}
		
		if (isset ($autocomplete['tpl']['name'])) {
			$sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (pd.product_id = p.product_id AND pd.language_id = "' . $language_id . '") ';
		}
		
		$sql .= 'WHERE p.product_id IN (' . $products . ') ';
		
		$data = $this->db->query($sql)->rows;
		
		$i = 0;
		
		foreach ($data as $key=>$value) {
			if (isset ($attributes[$data[$key]['product_id']])) {
				$data[$key]['attributes'] = implode ($autocomplete['separator'], $attributes[$value['product_id']]);
			}
			
			if ($tag_status) {
				if ($tags_start) {
					foreach ($tags_start as $tag) {
						$tags[] = '("' . $data[$key]['product_id'] . '", "' . $language_id . '", "' . $this->db->escape(trim ($tag)) . '")';
					}
				}
				
				if (isset ($attributes[$data[$key]['product_id']])) {
					foreach ($attributes[$data[$key]['product_id']] as $attribute) {
						$tags[] = '("' . $data[$key]['product_id'] . '", "' . $language_id . '", "' . $this->db->escape(trim ($attribute)) . '")';
					}
				}
				
				foreach ($autocomplete['tpl'] as $field=>$value) {
					$tag = trim ($data[$key][$field]);
					
					if ($tag) {
						$tags[] = '("' . $data[$key]['product_id'] . '", "' . $language_id . '", "' . $this->db->escape($tag) . '")';
					}
				}
				
				if ($tags_end) {
					foreach ($tags_end as $tag) {
						$tags[] = '("' . $data[$key]['product_id'] . '", "' . $language_id . '", "' . $this->db->escape(trim ($tag)) . '")';
					}
				}
			}
			
			$values[$i] = '("' . $data[$key]['product_id'] . '", "' . $language_id . '"';
			
			unset ($data[$key]['product_id']);
			
			foreach ($autocomplete['tpl'] as $field=>$value) {
				if (empty ($data[$key][$field])) {
					unset ($data[$key][$field]);
				}
			}
			
			foreach ($autocomplete['app'] as $field=>$value) {
				if ($field == 'tag') {
					$values[$i] .= ', "' . $this->db->escape($text_start_tag . implode (',', $data[$key]) . $text_end_tag) . '"';
				} else {
					$values[$i] .= ', "' . $this->db->escape($text_start . implode ($autocomplete['separator'], $data[$key]) . $text_end) . '"';
				}
			}
			
			$values[$i] .= ')';
			
			$i++;
		}
		
		foreach ($autocomplete['app'] as $field=>$value) {
			$apply[] = $field;
			
			if ($action == 'upd') {
				$dublicate[] = $field . ' = VALUES(' . $field . ')';
			} else {
				$dublicate[] = $field . ' = IF((' . $field . ' = ""), VALUES(' . $field . '), CONCAT_WS("' . $autocomplete['separator'] . '", ' . $field . ', VALUES(' . $field . ')))';
			}
		}
		
		if ($values && $apply) {
			$this->db->query('INSERT INTO ' . DB_PREFIX . 'product_description (product_id, language_id, ' . implode (', ', $apply) . ') VALUES ' . implode (', ', $values) . ' ON DUPLICATE KEY UPDATE ' . implode (', ', $dublicate));
		}
		
		if ($tag_status && $tags) {
			if ($action == 'upd') {
				$this->db->query('DELETE FROM ' . DB_PREFIX . 'product_tag WHERE product_id IN (' . $products . ') AND language_id = ' . $language_id);
			}
			
			$this->db->query('INSERT INTO ' . DB_PREFIX . 'product_tag (product_id, language_id, tag) VALUES ' . implode (',', $tags));
		}
		
		$this->db->query('UPDATE ' . DB_PREFIX . 'product SET date_modified = NOW() WHERE product_id IN (' . $products . ')');
	}
}
?>