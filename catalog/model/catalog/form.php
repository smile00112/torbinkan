<?php
class ModelCatalogForm extends Model {
	public function getForm($form_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "form i LEFT JOIN " . DB_PREFIX . "form_description id ON (i.form_id = id.form_id) WHERE i.form_id = '" . (int)$form_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i.status = '1'");
	
		return $query->row;
	}
    
    public function getItems($form_id) {
       $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "form_item fi LEFT JOIN " . DB_PREFIX . "form_item_description fid ON (fi.item_id = fid.item_id) WHERE fi.form_id = '" . (int)$form_id . "' AND fid.language_id = '" . (int)$this->config->get('config_language_id') . "' AND fi.status = '1' ORDER BY fi.sort_order ASC");
	
		return $query->rows; 
    }
    
    public function getItem($item_id) {
       $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "form_item fi LEFT JOIN " . DB_PREFIX . "form_item_description fid ON (fi.item_id = fid.item_id) WHERE fi.item_id = '" . (int)$item_id . "' AND fid.language_id = '" . (int)$this->config->get('config_language_id') . "' AND fi.status = '1' ORDER BY fi.sort_order ASC");
	
		return $query->row; 
    }
	
    public function addFormData($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "form_data SET form_id = '" . (int)$data['form_id'] . "', form_data = '" . $this->db->escape($data['form_data']) . "', not_view = '0', date_added = NOW()");
    }
    
    public function getCustomerGroup($form_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.description = '" . $form_id . "'");
		
		return $query->row;
	}
    
	public function addCustomer($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', approved = '".(int)$data['useron']."', lastname = '', email = '" . $this->db->escape($data['email']) . "', telephone = '', fax = '', newsletter = '', customer_group_id = '" . (int)$data['customer_group_id'] . "', password = '".$this->db->escape(md5($data['password']))."', status = '".(int)$data['useron']."', date_added = NOW()");
      	
	}	
}
?>