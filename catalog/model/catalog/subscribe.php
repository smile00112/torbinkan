<?php
class ModelCatalogSubscribe extends Model {

	public function addSubscribe($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "subscribe SET email = '" . $data['email'] . "', status = '" . (int) $data['status'] . "'");
	}

	public function editSubscribe($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "subscribe SET status = '" . (int) $data['status'] . "' WHERE email = '" . $data['email'] . "'");
	}

	public function getSubscribers() {
		$query = $this->db->query("SELECT email FROM " . DB_PREFIX . "subscribe WHERE status = '0'");

		return $query->rows;
	}

	public function checkEmail($email) {
		$query = $this->db->query("SELECT email FROM " . DB_PREFIX . "subscribe WHERE email='" . $email . "'");

		return isset($query->row['email']) ? $query->row['email'] : 0;
	}

	public function getAuthDescription($language_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "subscribe_auth_description WHERE language_id='" . $language_id . "'");

		return isset($query->row['subscribe_authorization']) ? $query->row['subscribe_authorization'] : '';
	}

}

?>
