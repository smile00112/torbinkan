<?php

class ControllerInformationArticles extends Controller {

	public function index() {
	
    	$this->language->load('information/articles');
	
		$this->load->model('catalog/articles');
	
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		if (isset($this->request->get['articles_id'])) {
			$articles_id = $this->request->get['articles_id'];
		} else {
			$articles_id = 0;
		}
	
		$articles_info = $this->model_catalog_articles->getArticlesStory($articles_id);
	
		if ($articles_info) {
	  	
			$this->document->addStyle('catalog/view/theme/default/stylesheet/articles.css');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
		
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		
			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('information/articles'),
				'text'      => $this->language->get('heading_title'),
				'separator' => $this->language->get('text_separator')
			);
		
			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('information/articles', 'articles_id=' . $this->request->get['articles_id']),
				'text'      => $articles_info['title'],
				'separator' => $this->language->get('text_separator')
			);
			
			$this->document->setTitle($articles_info['title']);
			$this->document->setDescription($articles_info['meta_description']);
			$this->document->setKeywords($articles_info['meta_keyword']);
			$this->document->addLink($this->url->link('information/articles', 'articles_id=' . $this->request->get['articles_id']), 'canonical');
		
     		$this->data['articles_info'] = $articles_info;
		
     		$this->data['heading_title'] = $articles_info['title'];
     		
			$this->data['description'] = html_entity_decode($articles_info['description']);
			
     		$this->data['meta_keyword'] = html_entity_decode($articles_info['meta_keyword']);
			
			$this->data['viewed'] = sprintf($this->language->get('text_viewed'), $articles_info['viewed']);
		
			$this->data['addthis'] = $this->config->get('articles_articlespage_addthis');
		
			$this->data['min_height'] = $this->config->get('articles_thumb_height');
		
			$this->load->model('tool/image');
		
			if ($articles_info['image']) { $this->data['image'] = TRUE; } else { $this->data['image'] = FALSE; }
		
			$this->data['thumb'] = $this->model_tool_image->resize($articles_info['image'], $this->config->get('articles_thumb_width'), $this->config->get('articles_thumb_height'));
			$this->data['popup'] = $this->model_tool_image->resize($articles_info['image'], $this->config->get('articles_popup_width'), $this->config->get('articles_popup_height'));
		
     		$this->data['button_articles'] = $this->language->get('button_articles');
			$this->data['button_continue'] = $this->language->get('button_continue');
		
			$this->data['articles'] = $this->url->link('information/articles');
			$this->data['continue'] = $this->url->link('common/home');
			
			$this->data['referred'] = $_SERVER['HTTP_REFERER'];
			$this->data['refreshed'] = 'http://' . $_SERVER['HTTP_HOST'] . '' . $_SERVER['REQUEST_URI'];
			
			if ($this->data['referred'] != $this->data['refreshed']) {
				$this->model_catalog_articles->updateViewed($this->request->get['articles_id']);
			}
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/articles.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/articles.tpl';
			} else {
				$this->template = 'default/template/information/articles.tpl';
			}
		
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
		
			$this->response->setOutput($this->render());
		
	  	} else {
		
	  		$articles_data = $this->model_catalog_articles->getArticles();
		
	  		if ($articles_data) {
			
				$this->document->setTitle($this->language->get('heading_title'));
			
				$this->data['breadcrumbs'][] = array(
					'href'      => $this->url->link('information/articles'),
					'text'      => $this->language->get('heading_title'),
					'separator' => $this->language->get('text_separator')
				);
			
				$this->data['heading_title'] = $this->language->get('heading_title');
			
				$this->document->addStyle('catalog/view/javascript/jquery/panels/main.css');
				$this->document->addScript('catalog/view/javascript/jquery/panels/utils.js');
			
				$this->data['text_more'] = $this->language->get('text_more');
				$this->data['text_posted'] = $this->language->get('text_posted');
				
				$chars = $this->config->get('articles_headline_chars');
			
				foreach ($articles_data as $result) {
					$this->data['articles_data'][] = array(
						'id'  				=> $result['articles_id'],
						'title'        		=> $result['title'],
						'description'  	=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $chars),
						'href'         		=> $this->url->link('information/articles', 'articles_id=' . $result['articles_id']),
						'posted'   		=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
					);
				}
			
				$this->data['button_continue'] = $this->language->get('button_continue');
			
				$this->data['continue'] = $this->url->link('common/home');
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/articles.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/information/articles.tpl';
				} else {
					$this->template = 'default/template/information/articles.tpl';
				}
			
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
			
				$this->response->setOutput($this->render());
			
	    	} else {
			
		  		$this->document->setTitle($this->language->get('text_error'));
			
	     		$this->document->breadcrumbs[] = array(
	        		'href'      => $this->url->link('information/articles'),
	        		'text'      => $this->language->get('text_error'),
	        		'separator' => $this->language->get('text_separator')
	     		);
			
				$this->data['heading_title'] = $this->language->get('text_error');
			
				$this->data['text_error'] = $this->language->get('text_error');
			
				$this->data['button_continue'] = $this->language->get('button_continue');
			
				$this->data['continue'] = $this->url->link('common/home');
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
			
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
			
				$this->response->setOutput($this->render());
		  	}
		}
	}
}
?>
