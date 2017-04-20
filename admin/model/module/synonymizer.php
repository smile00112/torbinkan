<?php
class ModelModuleSynonymizer extends Model {


	
	public function getTemplate($category_id){
		$template_syn = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_synonymizer WHERE category_id = '" . (int)$category_id . "'");
		foreach ($query->rows as $result) {
			$template_syn[$result['language_id']] = array(
				'description'      => html_entity_decode($result['template'], ENT_QUOTES, 'UTF-8')
			);
		}
		
		return $template_syn;
	}
	
  
	public function createFields() {
	$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_synonymizer` (
		  `category_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `template` text NOT NULL,
		  PRIMARY KEY (`category_id`,`language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	$this->db->query("
			ALTER TABLE  `". DB_PREFIX ."product_description` ADD  `description_syn` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `description` ;
	");
# ------------------model------------------------------------------
$model=DIR_CATALOG.'model/catalog/product.php';
$content = file_get_contents($model);
$content = str_replace('$query->row[\'description\'],','$query->row[\'description\'],
# SynonymizeR start
				\'description_syn\'      => $query->row[\'description_syn\'],
# SynonymizeR end',$content);
$fp = fopen($model, 'w+');
fwrite($fp, $content);
fclose($fp);

$admin_model=DIR_APPLICATION.'model/catalog/product.php';
$content = file_get_contents($admin_model);
$content = str_replace('if (isset($data[\'product_store\'])) {','# SynonymizeR start
foreach ($data[\'product_description\'] as $language_id => $value) {
$this->db->query("UPDATE " . DB_PREFIX . "product_description SET description_syn = \'" . $this->db->escape($value[\'description_syn\']) . "\' WHERE product_id = \'" . (int)$product_id . "\' AND language_id = \'" . (int)$language_id . "\'");
}
# SynonymizeR end
if (isset($data[\'product_store\'])) {',$content);

$content = str_replace('$result[\'description\'],','$result[\'description\'],
# SynonymizeR start
				\'description_syn\' => $result[\'description_syn\'],
# SynonymizeR end',$content);
$fp = fopen($admin_model, 'w+');
fwrite($fp, $content);
fclose($fp);
# ------------------controller------------------------------------------
$controller=DIR_CATALOG.'controller/product/product.php';
$content = file_get_contents($controller);
$content = str_replace('$this->data[\'description\']','# SynonymizeR start
$this->data[\'description_syn\'] = html_entity_decode($product_info[\'description_syn\'], ENT_QUOTES, \'UTF-8\');
# SynonymizeR end
$this->data[\'description\']',$content);
$fp = fopen($controller, 'w+');
fwrite($fp, $content);
fclose($fp);



# ------------------template------------------------------------------
if (file_exists(DIR_CATALOG . 'view/theme/'. $this->config->get('config_template') . '/template/product/product.tpl')) {
		$template=DIR_CATALOG.'view/theme/'.$this->config->get('config_template') . '/template/product/product.tpl';
		} else {
		$template=DIR_CATALOG.'view/theme/default/template/product/product.tpl';		
		}
$content = file_get_contents($template);
$content = str_replace('<?php echo $description; ?>', '<?php echo $description; ?>
<!-- SynonymizeR start -->
<?php echo $description_syn; ?>
<?php $syn_price = \'\'; if ($price) { $syn_price = (!$special) ? $price : $special; } ?> <script type="text/javascript">$(document).ready(function() { $(\'span.synprice\').each(function(){ $(this).text(\'<?php echo $syn_price; ?>\');});}); </script>
<!-- SynonymizeR end -->', $content);

$fp = fopen($template, 'w+');
fwrite($fp, $content);
fclose($fp);

$admin_template=DIR_TEMPLATE.'catalog/product_form.tpl';
$content = file_get_contents($admin_template);
$content = str_replace('<?php echo $entry_tag; ?>', '<!-- SynonymizeR start -->
<?php echo \'SynonymizeR\'; ?></td><td><textarea name="product_description[<?php echo $language[\'language_id\']; ?>][description_syn]" id="description_syn<?php echo $language[\'language_id\']; ?>"><?php echo isset($product_description[$language[\'language_id\']]) ? $product_description[$language[\'language_id\']][\'description_syn\'] : \'\'; ?></textarea></td></tr><tr><td>
<!-- SynonymizeR end -->
<?php echo $entry_tag; ?>', $content);
$content = str_replace('<?php echo $footer; ?>','<!-- SynonymizeR start -->
<script type="text/javascript" src="view/javascript/synonymizer/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace(\'description_syn<?php echo $language[\'language_id\']; ?>\', {
	filebrowserBrowseUrl: \'index.php?route=common/filemanager&token=<?php echo $token; ?>\',
	filebrowserImageBrowseUrl: \'index.php?route=common/filemanager&token=<?php echo $token; ?>\',
	filebrowserFlashBrowseUrl: \'index.php?route=common/filemanager&token=<?php echo $token; ?>\',
	filebrowserUploadUrl: \'index.php?route=common/filemanager&token=<?php echo $token; ?>\',
	filebrowserImageUploadUrl: \'index.php?route=common/filemanager&token=<?php echo $token; ?>\',
	filebrowserFlashUploadUrl: \'index.php?route=common/filemanager&token=<?php echo $token; ?>\'
});
<?php } ?>
//--></script>
<!-- SynonymizeR end -->
<?php echo $footer; ?>',$content);
$fp = fopen($admin_template, 'w+');
fwrite($fp, $content);
fclose($fp);
#print_r($admin_template);exit;
	}

	public function deleteFields() {
	$this->db->query("
			ALTER TABLE `". DB_PREFIX ."product_description` DROP `description_syn` ;
	");
    
$model=DIR_CATALOG.'model/catalog/product.php';
$content = file_get_contents($model);
preg_match_all('!(#|\<\!--)\s?synonymizer start.+?synonymizer end\s+?(--\>|)!is', $content, $matches);
foreach ($matches[0] as $match) {
$content = str_replace($match, '', $content);
}
$fp = fopen($model, 'w+');
fwrite($fp, $content);
fclose($fp);

$admin_model=DIR_APPLICATION.'model/catalog/product.php';
$content = file_get_contents($admin_model);
preg_match_all('!(#|\<\!--)\s?synonymizer start.+?synonymizer end\s+?(--\>|)!is', $content, $matches);
foreach ($matches[0] as $match) {
$content = str_replace($match, '', $content);
}
$fp = fopen($admin_model, 'w+');
fwrite($fp, $content);
fclose($fp);

$controller=DIR_CATALOG.'controller/product/product.php';
$content = file_get_contents($controller);
preg_match_all('!(#|\<\!--)\s?synonymizer start.+?synonymizer end\s+?(--\>|)!is', $content, $matches);
foreach ($matches[0] as $match) {
$content = str_replace($match, '', $content);
}
$fp = fopen($controller, 'w+');
fwrite($fp, $content);
fclose($fp);

$admin_controller=DIR_APPLICATION.'controller/catalog/product.php';
$content = file_get_contents($admin_controller);
preg_match_all('!(#|\<\!--)\s?synonymizer start.+?synonymizer end\s+?(--\>|)!is', $content, $matches);
foreach ($matches[0] as $match) {
$content = str_replace($match, '', $content);
}
$fp = fopen($admin_controller, 'w+');
fwrite($fp, $content);
fclose($fp);

if (file_exists(DIR_CATALOG . 'view/theme/'. $this->config->get('config_template') . '/template/product/product.tpl')) {
		$template=DIR_CATALOG.'view/theme/'.$this->config->get('config_template') . '/template/product/product.tpl';
		} else {
		$template=DIR_CATALOG.'view/theme/default/template/product/product.tpl';		
		}
$content = file_get_contents($template);
$content = str_replace('<?php echo $description_syn; ?>', '', $content);

preg_match_all('!(#|\<\!--)\s?synonymizer start.+?synonymizer end\s+?(--\>|)!is', $content, $matches);
foreach ($matches[0] as $match) {
$content = str_replace($match, '', $content);
}


$fp = fopen($template, 'w+');
fwrite($fp, $content);
fclose($fp);

$admin_template=DIR_TEMPLATE.'catalog/product_form.tpl';
$content = file_get_contents($admin_template);
preg_match_all('!(#|\<\!--)\s?synonymizer start.+?synonymizer end\s+?(--\>|)!is', $content, $matches);
foreach ($matches[0] as $match) {
$content = str_replace($match, '', $content);
}
$fp = fopen($admin_template, 'w+');
fwrite($fp, $content);
fclose($fp);

	}



	public function saveTemplate($data) {

foreach ($data['descriptions'] as $language_id => $value) {
	#save templates to synonymizer when generate
	$this->db->query("INSERT INTO " . DB_PREFIX . "product_synonymizer (category_id,language_id,template) VALUES (" .$data['category_id']. "," .$language_id. ",'" . $this->db->escape($value) . "') ON DUPLICATE KEY UPDATE template='" . $this->db->escape($value) . "'");
}
	}

	public function getProductsByCatId($category_id) {
		$query = array();
		$seo_h1_fix = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_description LIKE 'seo_h1'");
		if ($seo_h1_fix->num_rows) {$h1="pd.seo_h1";} else {$h1="pd.name";}
		$main_cat_fix = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_to_category LIKE 'main_category'");
		if ($main_cat_fix->num_rows) {$m_c=" AND p2c.main_category = 1";} else {$m_c="";}
		$query = $this->db->query("SELECT p2c.product_id, p.model, p.price, pd.language_id, pd.name, " .$h1." AS seo_h1, cd.name AS cat_name, m.name AS brand_name FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p2c.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (p2c.category_id = cd.category_id) AND (pd.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (m.manufacturer_id = p.manufacturer_id) WHERE p2c.category_id = " . $category_id.$m_c);
		return $query->rows;
	}

	public function generateProductsSyn($data,$type) {

$this->saveTemplate($data);
$products = $this->getProductsByCatId($data['category_id']);

foreach ($products as $product) {	
$value=$data['descriptions'][$product['language_id']];

$patterns = array('%NAME%','%H1%','%BRAND%','%CATEGORY%','%MODEL%','%PRICE%');
$replace = array ($product['name'],$product['seo_h1'],$product['brand_name'],$product['cat_name'],$product['model'],'<span class="synprice">'.$this->currency->format($product['price']).'</span>');
$value = str_replace($patterns,$replace,$value);
#echo 'token replace'; print_r($value);exit;
		while($rands0 != $value = preg_replace_callback('/\{  ([^\{\}]*)  \}/xs', function ($m) {$ar = explode("|", $m[1]);return $ar[array_rand($ar, 1)];}, $rands0 = $value));

$product_attributes = $this->getProductAttributes($product['product_id'],$product['language_id']);

preg_match_all('#\[(.+?)__(.+?)\]#uxs', $value, $atts_patterns);

foreach ($atts_patterns[0] as $key => $att_pattern) {
	$clear = 0;
	foreach ($product_attributes as $product_attribute)
	{
		if ($atts_patterns[1][$key] == $product_attribute['group_name'] && $atts_patterns[2][$key] == $product_attribute['name']) {
			$value = str_replace($att_pattern,$product_attribute['text'],$value);
		} else {
			$clear = 1;
		}
		
	}
	if ($clear === 1) {
		$value = str_replace($att_pattern,'',$value);
	}
}

		#write product descriptions to base
	 	$this->db->query("UPDATE " . DB_PREFIX . "product_description pd LEFT JOIN " . DB_PREFIX . "product p ON (pd.product_id = p.product_id) SET pd.description". ($type=='rewrite' ? '' : '_syn') . " = '" . $this->db->escape($value) . "', p.date_modified = '" . date('Y-m-d H') . ":00:00' WHERE pd.product_id = " . (int)$product['product_id'] . " AND pd.language_id = " . $product['language_id'] . "");

}


	}

	
	public function getProductAttributes($product_id,$language_id) {
		$product_attribute_group_data = array();
		
		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$language_id . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
		
		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();
			
			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$language_id . "' AND pa.language_id = '" . (int)$language_id . "' ORDER BY a.sort_order, ad.name");
			
			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_group_data[] = array(
			#		'language_id' => $language_id,
					'attribute_group_id' => $product_attribute_group['attribute_group_id'],
					'group_name'               => $product_attribute_group['name'],
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']		 	
				);
			}
	
		}
		
		return $product_attribute_group_data;
	}

	
	public function getAllAttributes() {
			
		$attributes_data = array();
	$attributes_data = $this->db->query("SELECT agd.name AS g_name, ad.name AS a_name FROM " . DB_PREFIX . "attribute_description ad RIGHT JOIN " . DB_PREFIX . "attribute a ON (ad.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (agd.attribute_group_id = a.attribute_group_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $attributes_data->rows;
	}

	public function getCategories($parent_id = 0) {
		
			$category_data = array();
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cd.name ASC");
		
			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['category_id'],
					'name'        => $this->getPath($result['category_id'], $this->config->get('config_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
			
				$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}	
	
		
		return $category_data;
	}
	
	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . ' > ' . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}

#конец
}
?>