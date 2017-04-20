<?php
class ModelSalespecialsubscribers extends Model {
	private function check_db(){
	   
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "specsubscribe (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`email_id` varchar(225) NOT NULL,
			`name` varchar(225) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `Index_2` (`email_id`)
		) ENGINE=mysql  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");

	}
	
	public function addEmail($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "specsubscribe SET email_id='".$data['email_id']."',name='".$data['email_name']."'");
	}
	
	public function editEmail($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "specsubscribe SET email_id='".$data['email_id']."',name='".$data['email_name']."' WHERE id = '" . (int)$id . "'");
	}
	
	public function deleteEmail($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "specsubscribe WHERE id = '" . (int)$id . "'");
	}
	
	public function getEmail($id) {
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "specsubscribe WHERE id = '" . (int)$id . "'");

		return $query->row;
	}
	
	public function getEmails($data,$start,$limit) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "specsubscribe  LIMIT $start,$limit";

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalEmails($data) {
		
		$this->check_db();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "specsubscribe";

		$query = $this->db->query($sql);
		
		return $query->num_rows;
	}
	
	public function checkmail($data,$id=FALSE) {
	  
	   if($id)
		$sql = "SELECT * FROM " . DB_PREFIX . "specsubscribe WHERE email_id='".$data."' AND id!='".$id."'";
	   else	
		$sql = "SELECT * FROM " . DB_PREFIX . "specsubscribe WHERE email_id='".$data."'";

		$query = $this->db->query($sql);
		
		return $query->num_rows;
	}

}
?>