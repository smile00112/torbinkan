<?php
/*
 * OpenCart
 * Модуль для подключения платежной системы IntellectMoney
 *
Last Changed Rev: 13322
Last Changed Date: 2011-12-19 09:14:49 +0400 (Mon, 19 Dec 2011)
 */
?>
<?php 
class ModelPaymentIntellectmoney extends Model {
  	public function getMethod($address) {
		$this->load->language('payment/intellectmoney');
		
		if ($this->config->get('intellectmoney_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('intellectmoney_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$this->config->get('intellectmoney_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
      		  	$status = TRUE;
      		} else {
     	  		$status = FALSE;
			}	
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'intellectmoney',
        		'code'         => 'intellectmoney',				
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('intellectmoney_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>