<?php
class ModelCatalogForm extends Model {
	public function addForm($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "form SET status = '" . (int)$data['status'] . "', prefix = '" . $this->db->escape($data['prefix']) . "', email = '" . $this->db->escape($data['email']) ."', file = '" . (int)$data['file'] ."', use_type = '" . (int)$data['use_type'] ."', databaseon = '" . (int)$data['databaseon'] ."', newsletteron = '" . (int)$data['newsletteron'] ."', useron = '" . (int)$data['useron'] ."'");
        
        $form_id = $this->db->getLastId(); 
        
        foreach ($data['form_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "form_description SET form_id = '" . (int)$form_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', button = '" . $this->db->escape($value['button']) . "'");
		}
        
        foreach ($data['items'] as $item) {
            $letter = 0;
            if($item['setfrom'] == 1 && $item['letter'] == 1) {
                $letter = 1;
            } else {
                $letter = 0;
            }
            $this->db->query("INSERT INTO " . DB_PREFIX . "form_item SET form_id = '" . (int)$form_id . "', status = '" . (int)$item['status'] . "', required = '" . (int)$item['required']. "', sort_order = '" . (int)$item['sort_order']. "', validation = '". $this->db->escape($item['validation']) . "', item_type = '". $this->db->escape($item['item_type']) . "', setfrom = '" . (int)$item['setfrom']. "', setsender = '" . (int)$item['setsender']. "', letter = '" . (int)$letter. "'");
        
            $item_id = $this->db->getLastId(); 
            
            foreach ($item['description'] as $language_id => $value) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "form_item_description SET item_id = '" . (int)$item_id . "', language_id = '" . (int)$language_id . "', label = '" . $this->db->escape($value['label']) . "', pattern = '" . $this->db->escape($value['pattern']) . "', value = '" . $this->db->escape($value['value']) . "'");
    		}
        }
        
        if ($data['newsletteron'] == 1){
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '0', company_id_display = '0', company_id_required = '0', tax_id_display = '0', tax_id_required = '0', sort_order = '0'");
        $customer_group_id = $this->db->getLastId();
        foreach ($data['form_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . (int)$form_id . "'");
		}
        }
		
		$this->cache->delete('form');

        
		$this->cache->delete('seo_pro');
        
      
	}
	
	public function editForm($form_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "form SET status = '" . (int)$data['status'] . "', prefix = '" . $this->db->escape($data['prefix']) . "', email = '" . $this->db->escape($data['email']) ."', file = '" . (int)$data['file'] ."', use_type = '" . (int)$data['use_type'] ."', databaseon = '" . (int)$data['databaseon'] ."', newsletteron = '" . (int)$data['newsletteron'] ."', useron = '" . (int)$data['useron'] ."' WHERE form_id = '" . (int)$form_id . "'");
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "form_description WHERE form_id = '" . (int)$form_id . "'");
        
        foreach ($data['form_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "form_description SET form_id = '" . (int)$form_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', button = '" . $this->db->escape($value['button']) . "'");
		}
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "form_item WHERE form_id = '" . (int)$form_id . "'");
        
        foreach ($data['items'] as $item) {
            $letter = 0;
            if($item['setfrom'] == 1 && $item['letter'] == 1) {
                $letter = 1;
            } else {
                $letter = 0;
            }
            $this->db->query("INSERT INTO " . DB_PREFIX . "form_item SET form_id = '" . (int)$form_id . "', status = '" . (int)$item['status'] . "', required = '" . (int)$item['required']. "', sort_order = '" . (int)$item['sort_order']. "', validation = '". $this->db->escape($item['validation']) . "', item_type = '". $this->db->escape($item['item_type']) . "', setfrom = '" . (int)$item['setfrom']. "', setsender = '" . (int)$item['setsender']. "', letter = '" . (int)$letter. "'");
        
            $item_id = $this->db->getLastId();
            
            if(isset($item['item_id'])) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "form_item_description WHERE item_id = '" . (int)$item['item_id'] . "'"); 
            }
            
            foreach ($item['description'] as $language_id => $value) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "form_item_description SET item_id = '" . (int)$item_id . "', language_id = '" . (int)$language_id . "', label = '" . $this->db->escape($value['label']) . "', pattern = '" . $this->db->escape($value['pattern']) . "', value = '" . $this->db->escape($value['value']) . "'");
    		}
        }
        if ($data['newsletteron'] == 1){
            $customer_group = $this->getCustomerGroup($form_id);
            if(empty($customer_group)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '0', company_id_display = '0', company_id_required = '0', tax_id_display = '0', tax_id_required = '0', sort_order = '0'");
                $customer_group_id = $this->db->getLastId();
                foreach ($data['form_description'] as $language_id => $value) {
        			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . (int)$form_id . "'");
        		}    
            } else {
                $customer_group_id = $customer_group['customer_group_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE description = '" . $form_id . "'");
                foreach ($data['form_description'] as $language_id => $value) {
        			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . (int)$form_id . "'");
        		}  
            }
        }
        
		$this->cache->delete('form');

        
		$this->cache->delete('seo_pro');
        
      
	}
	
	public function deleteForm($form_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "form WHERE form_id = '" . (int)$form_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "form_description WHERE form_id = '" . (int)$form_id . "'");
        
        $items = $this->getFormItems($form_id);
        foreach ($items as $item) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "form_item_description WHERE item_id = '" . (int)$item['item_id'] . "'");
        }
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "form_item WHERE form_id = '" . (int)$form_id . "'");
        $this->cache->delete('form');

        
		$this->cache->delete('seo_pro');
        
      
	}	
	
	public function getForm ($form_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form WHERE form_id = '" . (int)$form_id . "'");
		
		return $query->row;
	}
    public function getFormDescription($form_id) {
        $form_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form_description WHERE form_id = '" . (int)$form_id . "'");
        foreach ($query->rows as $result) {
			$form_description_data[$result['language_id']] = array(
				'name'        => $result['name'],
                'button'        => $result['button']
			);
		}
		
		return $form_description_data;
        
    }
	
	public function getForms($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "form i LEFT JOIN " . DB_PREFIX . "form_description id ON (i.form_id = id.form_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
				'id.name',
				'i.sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY id.name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
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
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		} else {
			
            $form_data = $this->cache->get('form.' . (int)$this->config->get('config_language_id'));
		
			if (!$form_data) {
				
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form i LEFT JOIN " . DB_PREFIX . "form_description id ON (i.form_id = id.form_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.name");
	
				$form_data = $query->rows;
			    $this->cache->set('form.' . (int)$this->config->get('config_language_id'), $form_data);
			}
		 
			return $form_data;
		}
	}
    
    public function getTotalForms() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "form");
		
		return $query->row['total'];
	}
	/*FormItem*/
	
	public function getFormItems ($form_id) {
        $items = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form_item WHERE form_id = '" . (int)$form_id . "' ORDER BY sort_order");
        $items = $query->rows;
        foreach($query->rows as $key=>$result) {
            $description = $this->getFormitemDescription($result['item_id']);
            $items[$key]['description'] = $description;
        }
        return $items;
    }
    
    public function getFormitemDescription($form_item_id) {
        $form_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form_item_description WHERE item_id = '" . (int)$form_item_id . "'");

		foreach ($query->rows as $result) {
			$formitem_description_data[$result['language_id']] = array(
				'label'        => $result['label'],
                'pattern'        => $result['pattern'],
                'value'        => $result['value']
			);
		}
		
		return $formitem_description_data;
        
    }
	// CustomerGroup
    
    public function getCustomerGroup($form_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.description = '" . $form_id . "'");
		
		return $query->row;
	}
    
    // FormData
    
    public function getFormsDbOn($data = array()) {
		if ($data) {
			
            $sql = "SELECT * FROM " . DB_PREFIX . "form i LEFT JOIN " . DB_PREFIX . "form_description id ON (i.form_id = id.form_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i.databaseon = '1'";
			
			$sort_data = array(
				'id.name',
				'i.sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY id.name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
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
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		} else {
			
            $form_data = $this->cache->get('formdata.' . (int)$this->config->get('config_language_id'));
		
			if (!$form_data) {
				
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form i LEFT JOIN " . DB_PREFIX . "form_description id ON (i.form_id = id.form_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i.databaseon = '1' ORDER BY id.name");
	
				$form_data = $query->rows;
			
				
                $this->cache->set('formdata.' . (int)$this->config->get('config_language_id'), $form_data);
			}
		 
			return $form_data;
		}
	}
    
    public function getTotalFormsDbOn() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "form WHERE databaseon = '1'");
		
		return $query->row['total'];
	}
    
    public function getNewDataForm($form_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "form_data WHERE form_id = '" . (int)$form_id . "' AND not_view = '0'");
		
		return $query->row['total'];
    }
    
    public function getTotalDataForm($form_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "form_data WHERE form_id = '" . (int)$form_id . "'");
		
		return $query->row['total'];
    }
    
    public function getFormDatas($form_id,$data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "form_data WHERE form_id = '" . (int)$form_id . "'";
			
			$sort_data = array(
				'form_data_id',
				'not_view'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY form_data_id";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
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
			
			$query = $this->db->query($sql);
		
			return $query->rows;
        } else {
			
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form_data WHERE form_id = '" . (int)$form_id . "' ORDER BY form_data_id DESK");
        
        	return $query->rows;
		}
        
    }
    
    public function getFormData($form_data_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form_data WHERE form_data_id = '" . (int)$form_data_id . "'");
        return $query->row;
    }
    
    public function getFormName($form_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form i LEFT JOIN " . DB_PREFIX . "form_description id ON (i.form_id = id.form_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND id.form_id = '" .(int)$form_id. "'");
        
        return $query->row;
    }
    
    public function updateView($form_data_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "form_data SET not_view = '1' WHERE form_data_id = '" . (int)$form_data_id . "'");
    }
    
    public function deleteFormData($form_data_id){
        $this->db->query("DELETE FROM " . DB_PREFIX . "form_data WHERE form_data_id = '" . (int)$form_data_id . "'");
    }
    
	   
}
?>