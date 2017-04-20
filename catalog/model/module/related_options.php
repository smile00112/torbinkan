<?php

class ModelModuleRelatedOptions extends Model {

  
	
	// проверяет достаточно ли количества по связанным опциям по всем товарам корзины
	public function cart_ckeckout_stock($products) {
		
		if ($this->installed()) {
			if (is_array($products)) {
				foreach ($products as &$product) {
					if ($this->get_product_related_options_use($product['product_id'])) {
						if ($product['stock']) {
							if (isset($product['option'])&&is_array($product['option'])) {
								$poids = array();
								foreach ($product['option'] as $option) {
									$poids[$option['product_option_id']] = (int)$option['product_option_value_id'];
								}
								
								if (count($poids) > 0) {
									$product['stock'] = $this->cart_stock($product['product_id'], $poids, $product['quantity']);
								}
								
							}
						}
					}
				}
				unset($product);
			}
		}
		return $products;
		
	}
	
	public function get_ro_free_quantity($roid) {
		
		$query = $this->db->query("	SELECT RO.quantity, RO.product_id
																FROM 	" . DB_PREFIX . "relatedoptions RO
																WHERE RO.relatedoptions_id = ".$roid."
																");
		$quantity = 0;
		$product_id = 0;
		if ($query->num_rows) {
			$quantity = $query->row['quantity'];
			$product_id = $query->row['product_id'];
		}
		
		if ($product_id==0 || $quantity==0 ) return 0;
		
		
		
		$products = $this->cart->getProducts();
		foreach ($products as $product) {
			if ($product['product_id'] == $product_id) {
				$options = array();
				foreach ($product['option'] as $option) {
					$options[$option['product_option_id']] = $option['product_option_value_id'];
				}
				$ro = $this->get_related_options_set_by_poids($product_id, $options);
				if ($ro !== FALSE && $ro['relatedoptions_id'] == $roid) {
					
					return MAX(0, $quantity-$product['quantity']);
				}
			}
		}
		
		return $quantity;
		
	}
	
	
	// проверяет достаточно ли количества по связанным опциям
	public function cart_stock($product_id, $options, $quantity) {
		
		
		$ro = $this->get_related_options_set_by_poids($product_id, $options);
		if ($ro === FALSE || !is_array($ro) || !isset($ro['quantity'])) {
			return FALSE;
		} else {
			return ($quantity <= $ro['quantity']);
		}

		
	}
	
	public function get_related_options_set_by_poids($product_id, $options) {
		
		if (!is_array($options) || count($options)==0 || !$this->get_product_related_options_use($product_id)) {
			return FALSE;
		}
		
		$str_opts = "";
		foreach ($options as $product_option_id => $option_value) {
			$str_opts .= ",".$product_option_id;
		}
		$str_opts = substr($str_opts, 1);
		
		
		// проверяем только опции участвующие в связанных опциях
		$pvo = $this->get_product_variant_options($product_id);
		
		
		if (count($pvo)>0 && count($options)>0) {
		
			$query = $this->db->query("	SELECT PO.product_option_id, PO.option_id
																	FROM 	" . DB_PREFIX . "product_option PO
																	WHERE PO.product_id = ".$product_id."
																		AND PO.product_option_id IN (".$str_opts.")
																		AND PO.option_id IN (".join(",",$pvo).")
																	");
			
			$sql_from = "";
			$sql_where = "";
			$sql_cnt = 0;
			foreach ($query->rows as $row) {
				
				if (in_array($row['option_id'], $pvo)) { 
					$sql_cnt++;
					
					$sql_from .= ", ".DB_PREFIX."relatedoptions_option ROO".$sql_cnt;
					$sql_from .= ", ".DB_PREFIX."product_option_value POV".$sql_cnt;
					
					// только подходящие опции
					$sql_where .= " AND ROO".$sql_cnt.".relatedoptions_id = RO.relatedoptions_id ";
					$sql_where .= " AND ROO".$sql_cnt.".option_id = ".$row['option_id']." ";
					
					// только подходящие значения
					$sql_where .= " AND ROO".$sql_cnt.".option_value_id = POV".$sql_cnt.".option_value_id";
					$sql_where .= " AND POV".$sql_cnt.".product_option_value_id = ".(int)$options[$row['product_option_id']]."";
					
					
				}
				
			}
			
			if ($sql_from!="") {
				
				$query = $this->db->query("	SELECT RO.*
																		FROM 	".DB_PREFIX."relatedoptions RO
																					".$sql_from."
																		WHERE RO.product_id = ".$product_id."
																					".$sql_where."
																		");
				if ($query->num_rows) {
					return $query->row;
				}
				
			}
		}
		
		return FALSE;
		
	}
	
	
	public function get_product_related_options_use($product_id) {
		
		if (!$this->installed()) return 0;
		
		$query = $this->db->query("	SELECT VP.relatedoptions_use
																FROM 	`".DB_PREFIX."relatedoptions_variant_product` VP
																WHERE	VP.product_id = ".$product_id."
																");
		
		if ($query->num_rows) {
			return $query->row['relatedoptions_use'];
		} else {
			return 0;
		}
		
	}
	
	
  public function update_related_options_quantity_by_order($product_id, $quantity, $options) {
      
      if (!$this->installed()) {
        return;
      }
      
      if ( count($options) > 0 ) {
        
        // только если по товару надо минусовать количество
        $query = $this->db->query("SELECT subtract FROM " . DB_PREFIX . "product WHERE product_id = ".$product_id." ");
				// и для товара используются связанные опции
        if ($query->num_rows && $query->row['subtract'] && $this->get_product_related_options_use($product_id)) {
          
          $product_options = $this->get_variant_product_options($product_id);
          
          // найдем набор связанных опций
          
          $sql_from = "";
          $sql_where = "";
          $ro_cnt = 0;
          foreach ($options as $option) {
            //if ($option['type'] == 'select' || $option['type'] == 'radio') {
            if (in_array($option['product_option_id'], $product_options)) {
              $sql_from .= ", ".DB_PREFIX . "relatedoptions_option RO".$ro_cnt.", ".DB_PREFIX . "product_option_value POV".$ro_cnt;
              $sql_where .= "
                              AND RO".$ro_cnt.".relatedoptions_id = R.relatedoptions_id
                              AND RO".$ro_cnt.".option_id = POV".$ro_cnt.".option_id
                              AND POV".$ro_cnt.".product_option_value_id = ".$option['product_option_value_id']."
                              AND POV".$ro_cnt.".option_value_id = RO".$ro_cnt.".option_value_id ";
              $ro_cnt++;
            }
          }
          
          $query = $this->db->query("SELECT R.* FROM " . DB_PREFIX . "relatedoptions R ".$sql_from."
                                      WHERE R.product_id = ".$product_id." ".$sql_where);
          
          /*$this->log->write("SELECT R.* FROM " . DB_PREFIX . "relatedoptions R ".$sql_from."
                                      WHERE R.product_id = ".$product_id." ".$sql_where);*/
          
          if ($query->num_rows) {
            //$this->log->write(" in ");
            $new_quantity = MAX(0, $query->row['quantity']-$quantity);
            $query = $this->db->query("UPDATE " . DB_PREFIX . "relatedoptions SET quantity = ".$new_quantity." WHERE relatedoptions_id = ".$query->row['relatedoptions_id']." ");
          }
          
        }
      }
      
  }
  
  
  public function get_option_types() {
		return "'select', 'radio', 'image'";
	}
  
  public function get_compatible_options() {
		
		if (!$this->installed()) {
			return array();
		}
		
		$lang_id = $this->getLanguageId($this->config->get('config_language'));
		
		$query = $this->db->query("SELECT O.option_id, OD.name FROM `".DB_PREFIX."option` O, `".DB_PREFIX."option_description` OD
															WHERE O.option_id = OD.option_id
																AND OD.language_id = ".$lang_id."
																AND O.type IN (".$this->get_option_types().")
															ORDER BY O.sort_order
															");
		
		$opts = array();
		foreach ($query->rows as $row) {
			$opts[$row['option_id']] = $row['name'];
		}
		
		return $opts;
		
	}
  
  public function get_compatible_options_values() {
		
		if (!$this->installed()) {
			return array();
		}
		
		$lang_id = $this->getLanguageId($this->config->get('config_language'));
		
		$optsv = array();
		$compatible_options = $this->get_compatible_options();
		$str_opt = "";
		foreach ($compatible_options as $option_id => $option_name) {
			$optsv[$option_id] = array('name'=>$option_name, 'values'=>array());
			$str_opt .= ",".$option_id;
		}
		if ($str_opt!="") {
			$str_opt = substr($str_opt, 1);
			$query = $this->db->query("	SELECT OV.option_id, OVD.name, OVD.option_value_id
																	FROM `".DB_PREFIX."option_value` OV, `".DB_PREFIX."option_value_description` OVD 
																	WHERE OV.option_id IN (".$str_opt.")
																		AND OVD.language_id = ".$lang_id."
																		AND OV.option_value_id = OVD.option_value_id
																	ORDER BY OV.sort_order
																	");
			foreach ($query->rows as $row) {
				$optsv[$row['option_id']]['values'][$row['option_value_id']] = $row['name'];
			}
		}
		
		return $optsv;
		
	}
  
  public function get_options_for_variant($relatedoptions_variant_id) {
		
		$options = array();
		if ($relatedoptions_variant_id == 0) {
			$copts = $this->get_compatible_options();
			$options = array_keys($copts);
		} else {
			$options = array();
			$query = $this->db->query("	SELECT VO.option_id
																	FROM `".DB_PREFIX."relatedoptions_variant_option` VO
																	WHERE relatedoptions_variant_id = ".$relatedoptions_variant_id."
																	");
			foreach ($query->rows as $row) {
				$options[] = $row['option_id'];
			}
		}
		
		return $options;
		
	}
  
  
  public function getLanguageId($lang) {
		$query = $this->db->query('SELECT `language_id` FROM `' . DB_PREFIX . 'language` WHERE `code` = "'.$lang.'"');
		return $query->row['language_id'];
	}
  
  // option_id
  public function get_product_variant_options($product_id) {
		
		$options = array();
		
		$ro_variant_id = 0;
		$query = $this->db->query("	SELECT VP.relatedoptions_variant_id
																FROM 	" . DB_PREFIX . "relatedoptions_variant_product VP
																WHERE VP.product_id = ".$product_id."
																");
		if ($query->num_rows) {
			$ro_variant_id = $query->row['relatedoptions_variant_id'];
		}
		
		$options = $this->get_options_for_variant($ro_variant_id);
		return $options;
		
	}
  
  // product_option_id
  public function get_variant_product_options($product_id) {
    
    $product_options = array();
    
    if ($this->installed() && $this->get_product_related_options_use($product_id)) {
    
      $options = $this->get_product_variant_options($product_id);
      
      if (count($options) != 0) {
        $query = $this->db->query("SELECT PO.product_option_id
                                    FROM  " . DB_PREFIX . "product_option PO
                                    WHERE PO.option_id IN (".join(",",$options).")
                                  ");
        foreach($query->rows as $row) {
          $product_options[] = $row['product_option_id'];
        }
      }
    }
    
    
    return $product_options;
    
  }
	
	public function get_default_ro_set($product_id) {
		if (!$this->installed() || !$this->get_product_related_options_use($product_id)) {
			return FALSE;
		}
		
		$ro_settings = $this->config->get('related_options');
		if ($ro_settings && is_array($ro_settings) && isset($ro_settings['select_first']) && $ro_settings['select_first']) {
		
			$query = $this->db->query("SELECT relatedoptions_id FROM " . DB_PREFIX . "relatedoptions
																	WHERE product_id = ".(int)$product_id." AND quantity > 0 AND defaultselect = 1
																	ORDER BY defaultselectpriority ASC LIMIT 1 ");
			if ($query->num_rows) {
				return $query->row['relatedoptions_id'];
			}
			
			$query = $this->db->query("SELECT relatedoptions_id FROM " . DB_PREFIX . "relatedoptions
																	WHERE product_id = ".(int)$product_id." AND quantity > 0
																	ORDER BY relatedoptions_id LIMIT 1 ");
			if ($query->num_rows) {
				return $query->row['relatedoptions_id'];
			}
		}
		
		return FALSE;
		
	}
  
  public function get_options_array($product_id) {
    
    if (!$this->installed() || !$this->get_product_related_options_use($product_id)) {
      return array();
    }
		
		$ro_settings = $this->config->get('related_options');
    
    $query = $this->db->query("SELECT RO.relatedoptions_id, PO.product_option_id, POV.product_option_value_id
                                FROM " . DB_PREFIX . "relatedoptions RO
                                    ," . DB_PREFIX . "relatedoptions_option ROO
                                    ," . DB_PREFIX . "product_option PO
                                    ," . DB_PREFIX . "product_option_value POV
                                WHERE RO.product_id = ".$product_id."
																	".
                                  ((isset($ro_settings['allow_zero_select']) && $ro_settings['allow_zero_select'])? "" : "AND RO.quantity > 0")
																	."
                                  AND PO.product_id = ".$product_id."
                                  AND POV.product_id = ".$product_id."
                                  AND RO.relatedoptions_id = ROO.relatedoptions_id
                                  AND ROO.option_id = PO.option_id
                                  AND ROO.option_value_id = POV.option_value_id
                                  ");
    
    $ro_array = array();
    foreach ($query->rows as $row) {
      
      if ( !isset($ro_array[$row['relatedoptions_id']]) ) {
        $ro_array[$row['relatedoptions_id']] = array();
      }
      
      $ro_array[$row['relatedoptions_id']][$row['product_option_id']] = $row['product_option_value_id'];
      
    }
    
    return $ro_array;
    
  }
	
	public function get_ro_prices($product_id) {
		
		if (!$this->get_product_related_options_use($product_id)) {
			return FALSE;
		}
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		$ro_prices = array();
		
		$query = $this->db->query("SELECT RO.relatedoptions_id, RO.price ro_price, ROD.price, ROD.quantity, RO.model
															FROM ".DB_PREFIX."relatedoptions RO
															LEFT JOIN ".DB_PREFIX."relatedoptions_discount ROD ON (RO.relatedoptions_id = ROD.relatedoptions_id && ROD.customer_group_id = ".(int)$customer_group_id." )
															WHERE RO.product_id = ".(int)$product_id."
																");
		foreach ($query->rows as $row) {
			if (!isset($ro_prices[$row['relatedoptions_id']])) {
				$ro_prices[$row['relatedoptions_id']] = array('price'=>$row['ro_price'], 'model'=>$row['model'], 'discounts'=>array());
			}
			if ($row['price']) {
				$ro_prices[$row['relatedoptions_id']]['discounts'][] = array('quantity'=>$row['quantity'], 'price'=>$row['price']);
			}
		}
		
		return $ro_prices;
		
	}
  
  public function installed() {
		
		$query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'relatedoptions"');
		if($query->num_rows) {
			$query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'relatedoptions_variant_product"');
			if($query->num_rows) {
				
				$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "relatedoptions_variant_product` WHERE field='relatedoptions_use' ");
				if ($query->num_rows) {
					$query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . 'relatedoptions_discount"');
					if($query->num_rows) {
						return true;
					}
				}
				
			}
		}
		
		return false;
		
	}





}








?>