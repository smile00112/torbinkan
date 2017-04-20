<?php

class ModelCatalogArticles extends Model {

	public function addArticles($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "articles SET status = '" . (int)$data['status'] . "', date_added = now()");
	
		$articles_id = $this->db->getLastId();
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "articles SET image = '" . $this->db->escape($data['image']) . "' WHERE articles_id = '" . (int)$articles_id . "'");
		}
	
		if (isset($data['date_added'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "articles SET date_added = '" . $this->db->escape($data['date_added']) . "' WHERE articles_id = '" . (int)$articles_id . "'");
		}
	
		foreach ($data['articles_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "articles_description SET articles_id = '" . (int)$articles_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	
		if (isset($data['articles_store'])) {
			foreach ($data['articles_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "articles_to_store SET articles_id = '" . (int)$articles_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'articles_id=" . (int)$articles_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('articles');

        
		$this->cache->delete('seo_pro');
        
      
	}

	public function editArticles($articles_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "articles SET status = '" . (int)$data['status'] . "' WHERE articles_id = '" . (int)$articles_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "articles SET image = '" . $this->db->escape($data['image']) . "' WHERE articles_id = '" . (int)$articles_id . "'");
		}
		
		if (isset($data['date_added'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "articles SET date_added = '" . $this->db->escape($data['date_added']) . "' WHERE articles_id = '" . (int)$articles_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles_description WHERE articles_id = '" . (int)$articles_id . "'");
	
		foreach ($data['articles_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "articles_description SET articles_id = '" . (int)$articles_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles_to_store WHERE articles_id = '" . (int)$articles_id . "'");
	
		if (isset($data['articles_store'])) {		
			foreach ($data['articles_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "articles_to_store SET articles_id = '" . (int)$articles_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'articles_id=" . (int)$articles_id . "'");
	
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'articles_id=" . (int)$articles_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	
		$this->cache->delete('articles');

        
		$this->cache->delete('seo_pro');
        
      
	}

	public function deleteArticles($articles_id) { 
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles WHERE articles_id = '" . (int)$articles_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles_description WHERE articles_id = '" . (int)$articles_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "articles_to_store WHERE articles_id = '" . (int)$articles_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'articles_id=" . (int)$articles_id . "'");
	
		$this->cache->delete('articles');

        
		$this->cache->delete('seo_pro');
        
      
	}

	public function getArticlesStory($articles_id) { 
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'articles_id=" . (int)$articles_id . "') AS keyword FROM " . DB_PREFIX . "articles a LEFT JOIN " . DB_PREFIX . "articles_description ad ON (a.articles_id = ad.articles_id) WHERE a.articles_id = '" . (int)$articles_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	public function getArticlesDescriptions($articles_id) { 
		$articles_description_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "articles_description WHERE articles_id = '" . (int)$articles_id . "'");
	
		foreach ($query->rows as $result) {
			$articles_description_data[$result['language_id']] = array(
				'title'            			=> $result['title'],
				'meta_description' 	=> $result['meta_description'],
				'meta_keyword' 	=> $result['meta_keyword'],
				'description'      		=> $result['description']
			);
		}
	
		return $articles_description_data;
	}

	public function getArticlesStores($articles_id) { 
		$articlespage_store_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "articles_to_store WHERE articles_id = '" . (int)$articles_id . "'");
		
		foreach ($query->rows as $result) {
			$articlespage_store_data[] = $result['store_id'];
		}
	
		return $articlespage_store_data;
	}

	public function getArticles() { 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "articles a LEFT JOIN " . DB_PREFIX . "articles_description ad ON (a.articles_id = ad.articles_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.date_added");

		return $query->rows;
	}

	public function getTotalArticles() { 
		$this->checkArticles();
	
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "articles");
	
		return $query->row['total'];
	}

	public function checkArticles() { 
		$create_articles = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "articles` (`articles_id` int(11) NOT NULL auto_increment, `status` int(1) NOT NULL default '0', `image` VARCHAR(255) COLLATE utf8_general_ci default NULL, `image_size` int(1) NOT NULL default '0', `date_added` date default NULL, `viewed` int(5) NOT NULL DEFAULT '0', PRIMARY KEY (`articles_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_articles);
	
		$create_articles_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "articles_description` (`articles_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_description` VARCHAR(255) COLLATE utf8_general_ci NOT NULL, `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `meta_keyword` varchar(255) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`articles_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_articles_descriptions);
	
		$create_articles_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "articles_to_store` (`articles_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`articles_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
		$this->db->query($create_articles_to_store);
	}
}
?>