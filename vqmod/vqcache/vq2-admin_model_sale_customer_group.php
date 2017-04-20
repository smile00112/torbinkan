<?php
class ModelSaleCustomerGroup extends Model {
	public function addCustomerGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', company_id_display = '" . (int)$data['company_id_display'] . "', company_id_required = '" . (int)$data['company_id_required'] . "', tax_id_display = '" . (int)$data['tax_id_display'] . "', tax_id_required = '" . (int)$data['tax_id_required'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
	
		$customer_group_id = $this->db->getLastId();

        /* SOFORP Customer Group Downloads - begin */
        foreach ($data['customer_group_ext_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_ext_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
        }

        if (isset($data['customer_group_download'])) {
            foreach ($data['customer_group_download'] as $download_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_to_download SET customer_group_id = '" . (int)$customer_group_id . "', download_id = '" . (int)$download_id . "'");
            }
        }
        /* SOFORP Customer Group Downloads - end */
            
		
		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}	
	}
	
	public function editCustomerGroup($customer_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', company_id_display = '" . (int)$data['company_id_display'] . "', company_id_required = '" . (int)$data['company_id_required'] . "', tax_id_display = '" . (int)$data['tax_id_display'] . "', tax_id_required = '" . (int)$data['tax_id_required'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE customer_group_id = '" . (int)$customer_group_id . "'");

        /* SOFORP Customer Group Downloads - begin */
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_ext_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");

        foreach ($data['customer_group_ext_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_ext_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_to_download WHERE customer_group_id = '" . (int)$customer_group_id . "'");

        if (isset($data['customer_group_download'])) {
            foreach ($data['customer_group_download'] as $download_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_to_download SET customer_group_id = '" . (int)$customer_group_id . "', download_id = '" . (int)$download_id . "'");
            }
        }
        /* SOFORP Customer Group Downloads - end */
            
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}
	
	public function deleteCustomerGroup($customer_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE customer_group_id = '" . (int)$customer_group_id . "'");

        /* SOFORP Customer Group Downloads - begin */
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_ext_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_to_download WHERE customer_group_id = '" . (int)$customer_group_id . "'");
        /* SOFORP Customer Group Downloads - end */
            
	}
	
	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getCustomerGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cgd.name";	
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
	}
	
	public function getCustomerGroupDescriptions($customer_group_id) {
		$customer_group_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
				
		foreach ($query->rows as $result) {
			$customer_group_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}
		
		return $customer_group_data;
	}
		

    /* SOFORP Customer Group Downloads - begin */
    public function installDownloads() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_group_to_download` ( `customer_group_id` int(11) NOT NULL, `download_id` int(11) NOT NULL, PRIMARY KEY (`customer_group_id`,`download_id`) ) DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_group_ext_description` (`customer_group_id` int(11) NOT NULL, `language_id` int(11) NOT NULL, `description` text NOT NULL, PRIMARY KEY (`customer_group_id`,`language_id`) ) DEFAULT CHARSET=utf8;");
    }

    public function getCustomerGroupExtDescriptions($customer_group_id) {
        $customer_group_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_ext_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");

        foreach ($query->rows as $result) {
            $customer_group_data[$result['language_id']] = array(
                'description' => $result['description']
            );
        }

        return $customer_group_data;
    }

    public function getCustomerGroupDownloads($customer_group_id) {
        $customer_group_download_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_to_download WHERE customer_group_id = '" . (int)$customer_group_id . "'");

        foreach ($query->rows as $result) {
            $customer_group_download_data[] = $result['download_id'];
        }

        return $customer_group_download_data;
    }
    /* SOFORP Customer Group Downloads - end */
            
	public function getTotalCustomerGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group");
		
		return $query->row['total'];
	}
}
?>