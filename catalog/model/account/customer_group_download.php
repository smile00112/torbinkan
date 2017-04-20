<?php
class ModelAccountCustomerGroupDownload extends Model {
	public function getDownload($download_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group_to_download` cgd LEFT JOIN `" . DB_PREFIX . "customer` c ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN `" . DB_PREFIX . "download` d ON (d.download_id = cgd.download_id) WHERE c.customer_id = '" . (int)$this->customer->getId(). "' AND d.download_id = '" . (int)$download_id . "' AND d.remaining > 0");

		return $query->row;
	}
	
	public function getDownloads($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}	
		
		$query = $this->db->query("SELECT d.download_id, dd.name, d.filename, d.mask, d.remaining FROM `" . DB_PREFIX . "customer_group_to_download` cgd LEFT JOIN `" . DB_PREFIX . "customer` c ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN `" . DB_PREFIX . "download` d ON (d.download_id = cgd.download_id)  LEFT JOIN `" . DB_PREFIX . "download_description` dd ON (d.download_id = dd.download_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND d.remaining > 0 ORDER BY cgd.customer_group_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

    public function getExtDescription() {
        $query = $this->db->query("SELECT cged.description FROM `" . DB_PREFIX . "customer_group_ext_description` cged LEFT JOIN `" . DB_PREFIX . "customer` c ON (cged.customer_group_id = c.customer_group_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND cged.language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1");
        if( !$query )
            return "";
        return $query->row["description"];
    }

	public function updateRemaining($download_id) {
        // У нас нет отдельного хранилища под каждого клиента, поэтому просто не уменьшаем число скачиваний.
		//$this->db->query("UPDATE `" . DB_PREFIX . "customer_group_to_download` SET remaining = (remaining - 1) WHERE customer_group_download_id = '" . (int)$customer_group_download_id . "'");
	}
	
	public function getTotalDownloads() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_group_to_download` cgd LEFT JOIN `" . DB_PREFIX . "customer` c ON (cgd.customer_group_id = c.customer_group_id) LEFT JOIN `" . DB_PREFIX . "download` d ON (d.download_id = cgd.download_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND d.remaining > 0");
		
		return $query->row['total'];
	}	
}
?>
