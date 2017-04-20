<?php
class ModelAccountpricesubscribe extends Model {

	public function checkmailid($data) {
	   $query=$this->db->query("SELECT * FROM " . DB_PREFIX . "pricesubscribe where email_id='".$data['pricesubscribe_email']."'");
	   return $query->num_rows;
	}
	
	public function checkRegisteredUser($data) {
	   $query=$this->db->query("SELECT * FROM " . DB_PREFIX . "customer where email='".$data['pricesubscribe_email']."'");
	   return $query->num_rows;
	}
	
	public function UpdateRegisterUsers($data,$status) {
	   $query=$this->db->query("UPDATE  " . DB_PREFIX . "customer SET newsletter ='".$status."' where email='".$data['pricesubscribe_email']."'");
	}

	public function subscribe($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "pricesubscribe SET email_id='".$data['pricesubscribe_email']."',name='".$data['pricesubscribe_name']."'");
	}
	
	public function unpricesubscribe($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "pricesubscribe WHERE email_id='".$data['pricesubscribe_email']."'");
	}
	
	public function check_db(){
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "pricesubscribe (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`email_id` varchar(225) NOT NULL,
			`name` varchar(225) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `Index_2` (`email_id`)
		) ENGINE=mysql  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;");
	}
}
?>