<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roboter
 * Date: 10.01.13
 * Time: 13:57
 * To change this template use File | Settings | File Templates.
 */

class ControllerDesignTemplate extends Controller
{
	private $error = array ();



	public function update_()
	{
		$this->load->model('design/template');
		$this->model_design_template->createTemplateTable();
	}

	public function insert()
	{
		$this->load->language('design/template');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/template');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_design_template->addtemplate($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort']))
			{
				$url .= '&sort='.$this->request->get['sort'];
			}

			if (isset($this->request->get['order']))
			{
				$url .= '&order='.$this->request->get['order'];
			}

			if (isset($this->request->get['page']))
			{
				$url .= '&page='.$this->request->get['page'];
			}

			$this->redirect($this->url->link('design/template', 'token='.$this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update()
	{
		$this->load->language('design/template');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/template');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_design_template->editTemplate($this->request->get['template_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort']))
			{
				$url .= '&sort='.$this->request->get['sort'];
			}

			if (isset($this->request->get['order']))
			{
				$url .= '&order='.$this->request->get['order'];
			}

			if (isset($this->request->get['page']))
			{
				$url .= '&page='.$this->request->get['page'];
			}

			$this->redirect($this->url->link('design/template', 'token='.$this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('design/template');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/template');

		if (isset($this->request->post['selected']) && $this->validateDelete())
		{
			foreach ($this->request->post['selected'] as $template_id)
			{
				$this->model_design_template->deleteTemplate($template_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort']))
			{
				$url .= '&sort='.$this->request->get['sort'];
			}

			if (isset($this->request->get['order']))
			{
				$url .= '&order='.$this->request->get['order'];
			}

			if (isset($this->request->get['page']))
			{
				$url .= '&page='.$this->request->get['page'];
			}

			$this->redirect($this->url->link('design/template', 'token='.$this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}


	public function index()
	{
		$this->update_();
		$this->load->language('design/template');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/template');

		$this->getList();
	}

	private function getList()
	{
		if (isset($this->request->get['sort']))
		{
			$sort = $this->request->get['sort'];
		}
		else
		{
			$sort = 'name';
		}

		if (isset($this->request->get['order']))
		{
			$order = $this->request->get['order'];
		}
		else
		{
			$order = 'ASC';
		}

		if (isset($this->request->get['page']))
		{
			$page = $this->request->get['page'];
		}
		else
		{
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort']))
		{
			$url .= '&sort='.$this->request->get['sort'];
		}

		if (isset($this->request->get['order']))
		{
			$url .= '&order='.$this->request->get['order'];
		}

		if (isset($this->request->get['page']))
		{
			$url .= '&page='.$this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array ();

		$this->data['breadcrumbs'][] = array (
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array (
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/template', 'token='.$this->session->data['token'].$url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] =
			$this->url->link('design/template/insert', 'token='.$this->session->data['token'].$url, 'SSL');
		$this->data['delete'] =
			$this->url->link('design/template/delete', 'token='.$this->session->data['token'].$url, 'SSL');

		$this->data['templates'] = array ();

		$data = array (
			'sort' => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$template_total = $this->model_design_template->getTotalTemplates();

		$results = $this->model_design_template->getTemplates($data);

		foreach ($results as $result)
		{
			$action = array ();

			$action[] = array (
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('design/template/update',
					'token='.$this->session->data['token'].'&template_id='.$result['template_id'].$url,
					'SSL')
			);

			$this->data['templates'][] = array (
				'template_id' => $result['template_id'],
				'name' => $result['name'],
				'filename' => $result['filename'],
				'selected' => isset($this->request->post['selected']) &&
					in_array($result['template_id'], $this->request->post['selected']),
				'action' => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning']))
		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success']))
		{
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		}
		else
		{
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC')
		{
			$url .= '&order=DESC';
		}
		else
		{
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page']))
		{
			$url .= '&page='.$this->request->get['page'];
		}

		$this->data['sort_name'] =
			$this->url->link('design/template', 'token='.$this->session->data['token'].'&sort=name'.$url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort']))
		{
			$url .= '&sort='.$this->request->get['sort'];
		}

		if (isset($this->request->get['order']))
		{
			$url .= '&order='.$this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $template_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url =
			$this->url->link('design/template', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'design/template_list.tpl';
		$this->children = array (
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm()
	{
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_file'] = $this->language->get('entry_file');
		$this->data['entry_store'] = $this->language->get('entry_store');


		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning']))
		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}
		if (isset($this->error['name']))
		{
			$this->data['error_name'] = $this->error['name'];
		}
		else
		{
			$this->data['error_name'] = '';
		}

		if (isset($this->error['filename']))
		{
			$this->data['error_file'] = $this->error['filenmae'];
		}
		else
		{
			$this->data['error_file'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort']))
		{
			$url .= '&sort='.$this->request->get['sort'];
		}

		if (isset($this->request->get['order']))
		{
			$url .= '&order='.$this->request->get['order'];
		}

		if (isset($this->request->get['page']))
		{
			$url .= '&page='.$this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array ();

		$this->data['breadcrumbs'][] = array (
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array (
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/template', 'token='.$this->session->data['token'].$url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['template_id']))
		{
			$this->data['action'] =
				$this->url->link('design/template/insert', 'token='.$this->session->data['token'].$url, 'SSL');
		}
		else
		{
			$this->data['action'] =
				$this->url->link('design/template/update',
					'token='.$this->session->data['token'].'&template_id='.$this->request->get['template_id'].$url,
					'SSL');
		}

		$this->data['cancel'] = $this->url->link('design/template', 'token='.$this->session->data['token'].$url, 'SSL');

		if (isset($this->request->get['template_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST'))
		{
			$template_info = $this->model_design_template->getTemplate($this->request->get['template_id']);
		}

		if (isset($this->request->post['name']))
		{
			$this->data['name'] = $this->request->post['name'];
		}
		elseif (!empty($template_info))
		{
			$this->data['name'] = $template_info['name'];
		}
		else
		{
			$this->data['name'] = '';
		}
		if (isset($this->request->post['filename']))
		{
			$this->data['filename'] = $this->request->post['filenmae'];
		}
		elseif (!empty($template_info))
		{
			$this->data['filename'] = $template_info['filename'];
		}
		else
		{
			$this->data['filename'] = '';
		}

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();


		$this->template = 'design/template_form.tpl';
		$this->children = array (
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'design/template'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64))
		{
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['filename']) < 3) || (utf8_strlen($this->request->post['filename']) > 64))
		{
			$this->error['filename'] = $this->language->get('error_file');
		}

		if (!$this->error)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	private function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'design/template'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');

		foreach ($this->request->post['selected'] as $template_id)
		{
			if ($this->config->get('config_template_id') == $template_id)
			{
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByTemplateId($template_id);

			if ($store_total)
			{
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$product_total = $this->model_catalog_product->getTotalProductsByTemplateId($template_id);

			if ($product_total)
			{
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}

			$category_total = $this->model_catalog_category->getTotalCategoriesByTemplateId($template_id);

			if ($category_total)
			{
				$this->error['warning'] = sprintf($this->language->get('error_category'), $category_total);
			}

			$information_total = $this->model_catalog_information->getTotalInformationsByTemplateId($template_id);

			if ($information_total)
			{
				$this->error['warning'] = sprintf($this->language->get('error_information'), $information_total);
			}
		}

		if (!$this->error)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}