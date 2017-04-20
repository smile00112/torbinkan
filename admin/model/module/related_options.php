<?php
class ModelModuleRelatedOptions extends Model {
	
	public function getLanguageId($lang) {
		$query = $this->db->query('SELECT `language_id` FROM `' . DB_PREFIX . 'language` WHERE `code` = "'.$lang.'"');
		return $query->row['language_id'];
	}
	
	// возвращает количество из заказа в остаток (при удалении заказа)
	public function set_ro_quantity_back($product_id, $product_options, $quantity) {
		
		if (!$this->installed() || !$product_options || !is_array($product_options)) {
			return;
		}
		
		$query = $this->db->query("SELECT subtract FROM `".DB_PREFIX."product` WHERE `product_id` = ".(int)$product_id." " );
		if ($query->num_rows && $query->row['subtract'] && $this->get_product_related_options_use((int)$product_id)) {
			
			$options = array();
			foreach ($product_options as $product_option) {
				$options[$product_option['product_option_id']] = $product_option['product_option_value_id'];
			}
			
			$ro = $this->get_related_options_set_by_poids($product_id, $options);
			if ($ro && is_array($ro)) {
				$this->db->query("UPDATE `".DB_PREFIX."relatedoptions` SET quantity=(quantity+".(int)$quantity.") WHERE `relatedoptions_id` = ".(int)$ro['relatedoptions_id']." " );
			}
			
		}
		
	}
	
	
	public function get_char_id($relatedoptions_id) {
		
		$query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'relatedoptions_to_char` WHERE `relatedoptions_id` = "'.$relatedoptions_id.'"');
		if ($query->num_rows) {
			return $query->row['char_id'];
		}
		return FALSE;
	}
	
	// находит набор связанных опций для массива значений опций типа product_option_id => product_option_value_id
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
																		AND PO.product_option_id IN ( ".$str_opts.")
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
	
	
	public function get_product_variant($product_id) {
		
		if (!$this->installed()) return 0;
		
		$query = $this->db->query("	SELECT VP.relatedoptions_variant_id
																FROM 	`".DB_PREFIX."relatedoptions_variant_product` VP
																WHERE	VP.product_id = ".$product_id."
																");
		
		if ($query->num_rows) {
			return $query->row['relatedoptions_variant_id'];
		} else {
			return 0;
		}
		
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
	
	
	public function set_product_variant($product_id, $ro_variant_id, $ro_use) {
		
		$query = $this->db->query("	DELETE
																FROM 	`".DB_PREFIX."relatedoptions_variant_product` 
																WHERE	product_id = ".$product_id."
																");
		
		$query = $this->db->query("	INSERT INTO `".DB_PREFIX."relatedoptions_variant_product` SET product_id = ".$product_id.", relatedoptions_use = ".(int)$ro_use.", relatedoptions_variant_id = ".$ro_variant_id." ");
		
	}
	

	
	public function get_compatible_options(&$opts_order=array()) {
		
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
			$opts_order[] = $row['option_id'];
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
																	FROM `".DB_PREFIX."relatedoptions_variant_option` VO, `".DB_PREFIX."option` O
																	WHERE relatedoptions_variant_id = ".$relatedoptions_variant_id." AND VO.option_id = O.option_id
																	ORDER BY O.sort_order
																	");
			foreach ($query->rows as $row) {
				$options[] = $row['option_id'];
			}
		}
		
		return $options;
		
	}
	
	// возвращает массив всех вариантов связанных опций и привязанных к ним опций
	public function get_variants_options($add_all = false) {
		
		$lang_id = $this->getLanguageId($this->config->get('config_language'));
		
		$vopts = array();
		
		
		
		if ($this->installed()) {
			
			if ($add_all) {
				$comp_opts_order = array();
				$comp_opts = $this->get_compatible_options($comp_opts_order);
				$vopts[0] = array('options'=>$comp_opts, 'options_order'=>$comp_opts_order);
			}
			
			
			$query = $this->db->query("	SELECT V.relatedoptions_variant_name, V.relatedoptions_variant_id
																	FROM `".DB_PREFIX."relatedoptions_variant` V
																	ORDER BY relatedoptions_variant_name
																	");
			foreach ($query->rows as $row) {
				$vopts[$row['relatedoptions_variant_id']] = array('options'=>array(), 'name'=> $row['relatedoptions_variant_name'], 'options_order'=>array());
			}
			
			$query = $this->db->query("	SELECT VO.relatedoptions_variant_id, VO.option_id, OD.name
																	FROM `".DB_PREFIX."relatedoptions_variant_option` VO, `".DB_PREFIX."option_description` OD, `".DB_PREFIX."option` O
																	WHERE OD.option_id = VO.option_id
																		AND O.option_id = VO.option_id
																		AND OD.language_id = ".$lang_id."
																	ORDER BY O.sort_order	
																	");
			
			
			foreach ($query->rows as $row) {
				$vopts[$row['relatedoptions_variant_id']]['options'][$row['option_id']] = $row['name'];
				$vopts[$row['relatedoptions_variant_id']]['options_order'][] = $row['option_id'];
			}
			
		}	
			
		return $vopts;
		
	}
	
	public function set_variants_options($vo, $clear_others=TRUE) {
		
		if ($clear_others) {
			$query = $this->db->query("	DELETE FROM `".DB_PREFIX."relatedoptions_variant_option` ");
		}
		$str_vo_id = "";
		
		$updated_vo = array();
		
		if (is_array($vo)) {
			
			foreach ($vo as $vo_key => $vo_arr) {
				
				if (is_array($vo_arr)) {
					
					$vo_id = (isset($vo_arr['id'])) ? $vo_arr['id'] : ""; 
					$vo_name = "";
					if (isset($vo_arr['name'])) {
						$vo_name = $vo_arr['name'];
					} else {
						if (isset($vo_arr['options']) && is_array($vo_arr['options'])) {
							$lang_id = $this->getLanguageId($this->config->get('config_language'));
							$options_in = implode(",",array_values($vo_arr['options']));
							$query = $this->db->query("	SELECT * FROM `".DB_PREFIX."option_description` WHERE language_id = ".$lang_id." AND option_id IN (".$options_in.") ");
							if ($query->num_rows) {
								foreach ($query->rows as $row) {
									$vo_name .= ", ".$row['name'];
								}
								$vo_name = substr($vo_name, 2);
							}
						}
					}
					
					
					if (!empty($vo_id)) {
						$query = $this->db->query("	UPDATE `".DB_PREFIX."relatedoptions_variant` SET relatedoptions_variant_name='".$this->db->escape($vo_name)."' WHERE relatedoptions_variant_id = ".$vo_id." ");
					} else {
						$query = $this->db->query("	INSERT INTO `".DB_PREFIX."relatedoptions_variant` SET relatedoptions_variant_name='".$this->db->escape($vo_name)."' ");
						$vo_id = $this->db->getLastId();
					}
					$str_vo_id .= ",".$vo_id;
					$updated_vo[] = $vo_id; 
					
					if (isset($vo_arr['options'])) {
						$vo_opts = $vo_arr['options'];
						if (is_array($vo_opts)) {
							foreach ($vo_opts as $opts_key => $option_id) {
								$query = $this->db->query("	INSERT INTO `".DB_PREFIX."relatedoptions_variant_option` SET relatedoptions_variant_id=".$vo_id.", option_id = ".$option_id." ");
							}
						}
					}	
					
				}
				
			}
			
		}
		
		if ($clear_others) {
			//if ($str_vo_id!="") {
			//	$str_vo_id = substr($str_vo_id, 1);
				$query = $this->db->query("	DELETE FROM `".DB_PREFIX."relatedoptions_variant` WHERE NOT relatedoptions_variant_id IN (0".$str_vo_id.") ");
				$query = $this->db->query("	DELETE FROM `".DB_PREFIX."relatedoptions_variant_product` WHERE NOT relatedoptions_variant_id IN (0".$str_vo_id.") ");
			//} else {
			//	$query = $this->db->query("	DELETE FROM `".DB_PREFIX."relatedoptions_variant` ");
			//	$query = $this->db->query("	DELETE FROM `".DB_PREFIX."relatedoptions_variant_product` ");
			//}
		}
		
		return $updated_vo;
		
	}
	
	public function get_related_options($product_id) {
		
		
		if (!$this->installed()) {
			
			return array();
		}
		
		$query1 = $this->db->query("SELECT RO.*
																FROM 	`" . DB_PREFIX . "relatedoptions` RO		
																WHERE RO.product_id = " . (int)$product_id . "
																ORDER BY RO.relatedoptions_id
																");
		
		$query2 = $this->db->query("SELECT RO.*, ROO.*
																FROM 	`" . DB_PREFIX . "relatedoptions` RO
																		, `" . DB_PREFIX . "relatedoptions_option` ROO
																		, `" . DB_PREFIX . "option` O
																WHERE RO.product_id = " . (int)$product_id . "
																	AND RO.relatedoptions_id = ROO.relatedoptions_id
																	AND O.option_id = ROO.option_id
																ORDER BY RO.relatedoptions_id, O.sort_order 
																");
		
		$query_d = $this->db->query("SELECT RD.*
																	FROM 	`" . DB_PREFIX . "relatedoptions` RO
																			, `" . DB_PREFIX . "relatedoptions_discount` RD
																	WHERE RO.product_id = " . (int)$product_id . "
																		AND RO.relatedoptions_id = RD.relatedoptions_id
																	ORDER BY RO.relatedoptions_id 
																	");
		
		
		$rop_array = array();
		$rop_cnt = 0;
		$ro_ids = array();
		foreach ($query1->rows as $row1) {
			
			$rop_array[$rop_cnt] = array('relatedoptions_id' => $row1['relatedoptions_id']
																	 , 'quantity' => $row1['quantity']
																	 , 'price' => $row1['price']
																	 , 'model' => $row1['model']
																	 , 'defaultselect' => $row1['defaultselect']
																	 , 'defaultselectpriority' => $row1['defaultselectpriority']
																	 , 'options'=> array()
																	 );
			
			foreach ($query2->rows as $row2) {
				if ($row1['relatedoptions_id'] == $row2['relatedoptions_id']) {
					$rop_array[$rop_cnt]['options'][$row2['option_id']] = $row2['option_value_id'];
				}
			}
			
			foreach ($query_d->rows as $row_d) {
				if ($row1['relatedoptions_id'] == $row_d['relatedoptions_id']) {
					$rop_array[$rop_cnt]['discounts'][] = array('quantity'=>$row_d['quantity'], 'price'=>$row_d['price'], 'priority'=>$row_d['priority'], 'customer_group_id'=>$row_d['customer_group_id']);
				}
			}
			
			$rop_cnt++;
			
		}

		
		return $rop_array;
		
	}
	
	
	public function get_option_types() {
		return "'select', 'radio', 'image'";
	}
	
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
	
	// ищет подходящий вариант связанных опций для комплекта наборов опций товара, если не находит - создает новый
	private function search_ro_variant($data) {
		
		if (isset($data['relatedoptions']) && (is_array($data['relatedoptions']))) {
			
			$all_options = array();
			foreach ($data['relatedoptions'] as $relatedoptions) {
			
				if (isset($relatedoptions['options']) && is_array($relatedoptions['options'])) {
					$options = array_keys($relatedoptions['options']);
					foreach ($options as $option_id) {
						if (!in_array($option_id, $all_options)) {
							$all_options[] = $option_id;
						}
					}
				}
			
			}
			
			
			if (count($all_options)>0) {
				
				sort($all_options);
				
				$variants = $this->get_variants_options();
				
				foreach ($variants as $variant_id => $variant) {
					
					$vo_options = array_keys($variant['options']);
					sort($vo_options);
					if ($vo_options == $all_options) {
						return $variant_id;
					}
					
				}
			}
			
			// не нашли подходящий вариант - создадим
			$vo = array();
			$vo[] = array('options' => $all_options);
			$vo_added = $this->set_variants_options($vo, FALSE);
			if (is_array($vo_added) && count($vo_added) != 0) {
				return reset($vo_added);
			}
			
		}
		
		return 0;
	}
	

	public function editRelatedOptions($product_id, $data) {
		
		if (!$this->installed() || (int)$product_id == 0) {
			return;
		}
		
		if ($data === 0) {
			// удаление
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_discount WHERE relatedoptions_id IN ( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE product_id = " . (int)$product_id . ")");
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions WHERE product_id = " . (int)$product_id . "");
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_option WHERE product_id = " . (int)$product_id . "");
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_variant_product WHERE product_id = " . (int)$product_id . "");
			
			return;
		}
		
		
		$related_options_use 			= (isset($data['related_options_use']))?($data['related_options_use']):(0);
		
		// для 1с
		if ($related_options_use && isset($data['relatedoptions']) && is_array($data['relatedoptions']) && count($data['relatedoptions'])>0 && isset($data['related_options_variant_search']) && $data['related_options_variant_search'] ) {
			$data['related_options_variant'] = $this->search_ro_variant($data);
		}
		
		// скидки
		if (isset($data['related_options_discount']) && $data['related_options_discount'] ) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_discount WHERE relatedoptions_id IN ( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE product_id = " . (int)$product_id . ")");
		}
		
		$this->set_product_variant($product_id, (isset($data['related_options_variant']))?($data['related_options_variant']):(0), $related_options_use);
			
			
		if (isset($data['related_options_variant']))	{
			
			$mod_settings = $this->config->get('related_options');
			
			//$this->set_product_variant($product_id, $data['related_options_variant'], $data['related_options_use']);
			
			
			// получим существующие связанные опции
			$query = $this->db->query("SELECT relatedoptions_id FROM " . DB_PREFIX . "relatedoptions WHERE product_id = " . (int)$product_id . "");
			$rop_array = array();
			foreach ($query->rows as $row) {
				$rop_array[] = $row['relatedoptions_id'];
			}
			
			$ropupd_array = array();
			$quantity_total = 0;
			
			// для подсчета общего количества по опциям
			$product_options = array();
			
			$options = $this->get_product_variant_options($product_id);
			
			
			if ( isset($data['relatedoptions']) && (is_array($data['relatedoptions']))  ) {
			
				
			
				if (count($options) != 0) {
					
					
					// удалим связи опций и связаных опций
					$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_option WHERE product_id = " . (int)$product_id . "");
				
					
					foreach ($data['relatedoptions'] as $relatedoption) {
						
						if (!isset($relatedoption['model'])) $relatedoption['model'] = "";
						if (!isset($relatedoption['price'])) $relatedoption['price'] = 0;
						if (!isset($relatedoption['defaultselect'])) $relatedoption['defaultselect'] = 0;
						if (!isset($relatedoption['defaultselectpriority'])) $relatedoption['defaultselectpriority'] = 0;
						
						$relatedoptions_id = '';
						// если такая связанная опция по id уже есть - оставим ее, иначе добавим новую
						if ( isset($relatedoption['relatedoptions_id']) && !empty($relatedoption['relatedoptions_id']) ) {
							$query = $this->db->query("SELECT relatedoptions_id FROM " . DB_PREFIX . "relatedoptions
																				WHERE product_id = " . (int)$product_id . "
																					AND relatedoptions_id = " . (int)$relatedoption['relatedoptions_id'] . "
																				");
							
							if ($query->num_rows) {
								$relatedoptions_id = (int)$relatedoption['relatedoptions_id'];
								$ropupd_array[] = $relatedoptions_id;
							}
						}
						
						if ($relatedoptions_id == '') {
							$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions
																SET product_id = " . (int)$product_id . "
																		,quantity = ".(int)$relatedoption['quantity']."
																		,model = '".$this->db->escape((string)$relatedoption['model'])."'
																		,price = ".(float)$relatedoption['price']."
																		,defaultselect = ".(int)$relatedoption['defaultselect']."
																		,defaultselectpriority = ".(float)$relatedoption['defaultselectpriority']."
																		");
							$relatedoptions_id = $this->db->getLastId();
						} else {
							$this->db->query("UPDATE ".DB_PREFIX."relatedoptions
																	SET quantity = ".(int)$relatedoption['quantity']."
																			,model = '".$this->db->escape((string)$relatedoption['model'])."'
																			,price = ".(float)$relatedoption['price']."
																			,defaultselect = ".(int)$relatedoption['defaultselect']."
																			,defaultselectpriority = ".(float)$relatedoption['defaultselectpriority']."
																WHERE relatedoptions_id = ".$relatedoptions_id." ");
						}
						
						if (isset($relatedoption['char_id'])) { // для 1с
							$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_to_char WHERE relatedoptions_id = " . $relatedoptions_id . " ");
							$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions_to_char SET relatedoptions_id = " . $relatedoptions_id . ", char_id = '".$this->db->escape($relatedoption['char_id'])."' ");
						}
						
						if ( isset($relatedoption['options']) && is_array($relatedoption['options']) ) {
							foreach ($relatedoption['options'] as $option_id => $option_value_id) {
								
								if (in_array($option_id, $options)) {
									
									$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions_option
																	 SET product_id = " . (int)$product_id . "
																	 , relatedoptions_id = " . (int)$relatedoptions_id . "
																	 , option_id = " . (int)$option_id . "
																	 , option_value_id = " . (int)$option_value_id . "
																	 ");
									
									// суммируем количества по опциям
									if ( !isset($product_options[$option_id])) {
										$product_options[$option_id] = array();
									}
									if ( !isset($product_options[$option_id][$option_value_id])) {
										$product_options[$option_id][$option_value_id] = 0;
									}
									$product_options[$option_id][$option_value_id] += (int)$relatedoption['quantity'];
								}
							}
						}
						
						if (isset($relatedoption['discounts']) && is_array($relatedoption['discounts'])) {
							foreach ($relatedoption['discounts'] as $ro_discount) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions_discount
																		SET relatedoptions_id = " . (int)$relatedoptions_id . "
																			, customer_group_id = " . (int)$ro_discount['customer_group_id'] . "
																			, quantity 					= " . (int)$ro_discount['quantity'] . "
																			, priority 					= " . (int)$ro_discount['priority'] . "
																			, price 						= " . (float)$ro_discount['price'] . "
																			");
							}
						}
						
						$quantity_total += (int)$relatedoption['quantity'];
						
					}
					
				}
			}
			
			$str_del = '';
			foreach ($rop_array as $relatedoptions_id) {
				if ( !in_array($relatedoptions_id, $ropupd_array )) {
					$str_del .= (($str_del=='')?(''):(',')).$relatedoptions_id;
				}
			}
			
			if ($str_del != '') {
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions
													WHERE product_id = " . (int)$product_id . "
														AND relatedoptions_id IN (".$str_del.")
												 ");
				
				// для 1с
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_to_char
													WHERE relatedoptions_id IN (".$str_del.")
												 ");
			}
			
			// обновляем опции и количество, только если для товара включены связанные опции
			
			if ($related_options_use) {
				
				
				// обновление количества товара
				if ( $mod_settings && isset($mod_settings['update_quantity']) && $mod_settings['update_quantity'] ) {
					$this->db->query("UPDATE ".DB_PREFIX."product SET quantity = ".$quantity_total." WHERE product_id = ".$product_id." ");
				}
				
				// обновление опций
				if ( $mod_settings && isset($mod_settings['update_options']) && $mod_settings['update_options'] ) {
					
					
					$product_subtract = 0;
					$query = $this->db->query("SELECT subtract FROM " . DB_PREFIX . "product WHERE product_id = ".$product_id);
					if ($query->num_rows) {
						$product_subtract = $query->row['subtract'];
					}
					
					
					$product_options_saved = array();
					$product_options_values_saved = array();
					
					// обновление по опциям
					foreach ($product_options as $option_id => $option_values) {
						
						if ( isset($product_options_saved[$option_id]))  {
							$product_option_id = $product_options_saved[$option_id];	

						} else {
							
							$query = $this->db->query("SELECT product_option_id, required FROM " . DB_PREFIX . "product_option
																				WHERE product_id = " . (int)$product_id . " AND option_id = ".$option_id."
																				");
							if ($query->num_rows) {
								$product_option_id = $query->row['product_option_id'];
								if ($query->row['required'] == 0) {
									$this->db->query("UPDATE " . DB_PREFIX . "product_option SET required = 1 WHERE product_option_id = " . $product_option_id . " ");
								}
								
							} else {
								$query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_option
																				SET product_id = " . (int)$product_id . ", option_id = ".$option_id.", required = 1
																				");
								$product_option_id = $this->db->getLastId();
							}
							$product_options_saved[$option_id] = $product_option_id;
						
						}
						
						if (!isset($product_options_values_saved[$product_option_id])) {
							$product_options_values_saved[$product_option_id] = array();
						}
						
						foreach ($option_values as $option_value_id => $option_value_quantity) {
							
							
							$query = $this->db->query("SELECT product_option_value_id FROM " . DB_PREFIX . "product_option_value
																					WHERE product_option_id = " . $product_option_id . "
																						AND option_value_id = ".$option_value_id."
																					");
							if ($query->num_rows) {
								
								$product_option_value_id = $query->row['product_option_value_id'];
								
								$this->db->query("UPDATE " . DB_PREFIX . "product_option_value
																	SET quantity = ".$option_value_quantity.", subtract = ".$product_subtract."
																	WHERE product_option_value_id = ".$product_option_value_id."	
																	");
								
							} else {
								
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value
																	SET product_id = " . (int)$product_id . ", option_id = ".$option_id."
																		, option_value_id = ".$option_value_id.", quantity = ".$option_value_quantity."
																		, product_option_id = ".$product_option_id.", subtract = ".$product_subtract."
																	");
								$product_option_value_id = $this->db->getLastId();
								
							}
							
							$product_options_values_saved[$product_option_id][] = $product_option_value_id;
							
						}
					}
					
					$sql_add = join(",", $product_options_saved);
					if ($sql_add != "") {
						$sql_add = "AND NOT product_option_id IN (".$sql_add.")";
					}
					
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_option
														WHERE product_id = " . (int)$product_id . "
															AND option_id IN (".join(",",$options).")
															".$sql_add."
															");
					
					$sql_add = "";
					foreach ($product_options_values_saved as $product_option_id => $values) {
						if (count($values)!=0) {
							$sql_add .= ",".join(",",$values);
						}
					}
					if ($sql_add != "") {
						$sql_add = "AND NOT product_option_value_id IN (".substr($sql_add,1).")";
					}
					
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value
														WHERE product_id = " . (int)$product_id . "
															AND option_id IN (".join(",",$options).")
															".$sql_add."
															");
					
				}
			}
		}
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
	
	public function current_version() {
		return "1.67";
	}
	
	
	public function install()
	{
		$this->uninstall();
    
    $this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `' . DB_PREFIX . 'relatedoptions` (
            `relatedoptions_id` int(11) NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            `quantity` int(4) NOT NULL,
            PRIMARY KEY (`relatedoptions_id`),
            FOREIGN KEY (product_id) REFERENCES '. DB_PREFIX .'product(product_id) ON DELETE CASCADE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
    
    $this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `' . DB_PREFIX . 'relatedoptions_option` (
            `relatedoptions_id` int(11) NOT NULL,
            `product_id` int(11) NOT NULL,
            `option_id` int(11) NOT NULL,
            `option_value_id` int(11) NOT NULL,
            FOREIGN KEY (`relatedoptions_id`) 	REFERENCES `'. DB_PREFIX .'relatedoptions`(`relatedoptions_id`) ON DELETE CASCADE,
            FOREIGN KEY (`option_value_id`) 	REFERENCES `'. DB_PREFIX .'option_value`(`option_value_id`) ON DELETE CASCADE,
            FOREIGN KEY (`option_id`) 			REFERENCES `'. DB_PREFIX .'option`(`option_id`) 			ON DELETE CASCADE,
            FOREIGN KEY (`product_id`) 			REFERENCES `'. DB_PREFIX .'product`(`product_id`) 			ON DELETE CASCADE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$this->install_additional_tables();
    
	}
	
	public function install_additional_tables() {
		
		$this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `' . DB_PREFIX . 'relatedoptions_variant` (
            `relatedoptions_variant_id` int(11) NOT NULL AUTO_INCREMENT,
            `relatedoptions_variant_name` char(255) NOT NULL,
            PRIMARY KEY (`relatedoptions_variant_id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `' . DB_PREFIX . 'relatedoptions_variant_option` (
            `relatedoptions_variant_id` int(11) NOT NULL,
            `option_id` int(11) NOT NULL,
            FOREIGN KEY (`option_id`) 			REFERENCES `'. DB_PREFIX .'option`(`option_id`) 			ON DELETE CASCADE,
						FOREIGN KEY (`relatedoptions_variant_id`) 			REFERENCES `'. DB_PREFIX .'relatedoptions_variant`(`relatedoptions_variant_id`) 			ON DELETE CASCADE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `' . DB_PREFIX . 'relatedoptions_variant_product` (
            `relatedoptions_variant_id` int(11) NOT NULL,
            `product_id` int(11) NOT NULL,
						`relatedoptions_use` tinyint(1) NOT NULL,
            FOREIGN KEY (`product_id`) 			REFERENCES `'. DB_PREFIX .'product`(`product_id`) 			ON DELETE CASCADE,
						FOREIGN KEY (`relatedoptions_variant_id`) 			REFERENCES `'. DB_PREFIX .'relatedoptions_variant`(`relatedoptions_variant_id`) 			ON DELETE CASCADE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$this->db->query(
				'CREATE TABLE IF NOT EXISTS
					`' . DB_PREFIX . 'relatedoptions_to_char` (
						`relatedoptions_id` int(11) NOT NULL,
						`char_id` varchar(255) NOT NULL,
						KEY (`relatedoptions_id`),
						KEY `char_id` (`char_id`),
						FOREIGN KEY (relatedoptions_id) REFERENCES '. DB_PREFIX .'relatedoptions(relatedoptions_id) ON DELETE CASCADE
					) ENGINE=MyISAM DEFAULT CHARSET=utf8'
		);
		
		$this->db->query(
				'CREATE TABLE IF NOT EXISTS
					`' . DB_PREFIX . 'relatedoptions_discount` (
						`relatedoptions_id` int(11) NOT NULL,
						`customer_group_id` int(11) NOT NULL,
						`quantity` int(4) NOT NULL,
						`priority` int(5) NOT NULL,
						`price` decimal(15,4) NOT NULL,
						KEY (`relatedoptions_id`),
						KEY (`customer_group_id`),
						FOREIGN KEY (relatedoptions_id) REFERENCES '. DB_PREFIX .'relatedoptions(relatedoptions_id) ON DELETE CASCADE,
						FOREIGN KEY (customer_group_id) REFERENCES '. DB_PREFIX .'customer_group(customer_group_id) ON DELETE CASCADE
					) ENGINE=MyISAM DEFAULT CHARSET=utf8'
		);
		
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "relatedoptions_variant_product` WHERE field='relatedoptions_use' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions_variant_product` ADD COLUMN `relatedoptions_use` tinyint(1) NOT NULL " );
			
			$this->db->query("UPDATE `" . DB_PREFIX . "relatedoptions_variant_product` SET relatedoptions_use = 1 WHERE product_id IN (SELECT DISTINCT product_id FROM `" . DB_PREFIX . "relatedoptions`)");
		}
		
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "relatedoptions` WHERE field='price' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions` ADD COLUMN `price` decimal(15,4) NOT NULL " );
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "relatedoptions` WHERE field='model' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions` ADD COLUMN `model` varchar(64) NOT NULL " );
		}
		
		// 1.6
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "relatedoptions` WHERE field='defaultselect' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions` ADD COLUMN `defaultselect` tinyint(11) NOT NULL " );
		}
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "relatedoptions` WHERE field='defaultselectpriority' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions` ADD COLUMN `defaultselectpriority` int(11) NOT NULL " );
		}
		
	}

	public function uninstall()
	{
		$this->db->query("DROP TABLE IF EXISTS 
			`" . DB_PREFIX . "relatedoptions`,
			`" . DB_PREFIX . "relatedoptions_to_char`,
			`" . DB_PREFIX . "relatedoptions_variant`,
			`" . DB_PREFIX . "relatedoptions_variant_option`,
			`" . DB_PREFIX . "relatedoptions_variant_product`,
			`" . DB_PREFIX . "relatedoptions_discount`,
			`" . DB_PREFIX . "relatedoptions_option`;");
	}

 
	
}
?>