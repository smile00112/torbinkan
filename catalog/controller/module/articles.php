<?php

class ControllerModuleArticles extends Controller {

	private $_articles = 'articles';

	protected function index($setting) {
		static $module = 0;
	
		$this->language->load('module/' . $this->_articles);
	
      	$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->load->model('localisation/language');
	
		$languages = $this->model_localisation_language->getLanguages();
	
		$this->data['customtitle'] = $this->config->get($this->_articles . '_customtitle' . $this->config->get('config_language_id'));
		$this->data['header'] = $this->config->get($this->_articles . '_header');
	
		if (!$this->data['customtitle']) { $this->data['customtitle'] = $this->data['heading_title']; } 
		if (!$this->data['header']) { $this->data['customtitle'] = ''; }
	
		$this->data['icon'] = $this->config->get($this->_articles . '_icon');
		$this->data['box'] = $this->config->get($this->_articles . '_box');
	
		$this->document->addStyle('catalog/view/theme/default/stylesheet/articles.css');
	
		$this->load->model('catalog/articles');
	
		$this->data['text_more'] = $this->language->get('text_more');
		$this->data['text_posted'] = $this->language->get('text_posted');
	
		$this->data['show_headline'] = $this->config->get($this->_articles . '_headline_module');
	
		$this->data['articles_count'] = $this->model_catalog_articles->getTotalArticles();
		
		$this->data['articles_limit'] = $setting['limit'];
	
		if ($this->data['articles_count'] > $this->data['articles_limit']) { $this->data['showbutton'] = true; } else { $this->data['showbutton'] = false; }
	
		$this->data['buttonlist'] = $this->language->get('buttonlist');
	
		$this->data['articleslist'] = $this->url->link('information/articles');
		
		$this->data['numchars'] = $setting['numchars'];
		
		if (isset($this->data['numchars'])) { $chars = $this->data['numchars']; } else { $chars = 100; }
		
		$this->data['articles'] = array();
	
		$results = $this->model_catalog_articles->getArticlesShorts($setting['limit']);
	
		foreach ($results as $result) {
			$this->data['articles'][] = array(
				'title'        		=> $result['title'],
				'thumb'        		=> $this->model_tool_image->resize($result['image'] ,57, 37),
				'description'  	=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $chars),
				'href'         		=> $this->url->link('information/articles', 'articles_id=' . $result['articles_id']),
				'posted'   		=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
	
		$this->data['module'] = $module++; 
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/' . $this->_articles . '.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/' . $this->_articles . '.tpl';
		} else {
			$this->template = 'default/template/module/' . $this->_articles . '.tpl';
		}
	
		$this->render();
	}
}
?>
