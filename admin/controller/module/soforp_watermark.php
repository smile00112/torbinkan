<?php

require_once( DIR_SYSTEM . "/engine/soforp_controller.php");


class ControllerModuleSoforpWatermark extends SoforpController {
	private $error = array();

    function getWatermarkDirectories($dir, &$result) {
        $items = glob($dir . '/*', GLOB_ONLYDIR);

        if (!empty($items)) {
            foreach ($items as $entry) {
                if ($entry == '.' || $entry == '..')
                    continue;

                $result[] = array(
                    'text'  => substr($entry, strlen(DIR_IMAGE . 'data/')),
                    'value' => $entry
                );

                $this->getWatermarkDirectories($entry, $result);
            }
        }
    }

	public function index() {
		$this->initLanguage('module/soforp_watermark');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('soforp_watermark', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('module/soforp_watermark', 'token=' . $this->session->data['token'], 'SSL'));
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

        $this->initBreadcrumbs(array(
            array("extension/module","text_module"),
            array("module/soforp_watermark","heading_title")
        ));

		$this->data['action'] = $this->url->link('module/soforp_watermark', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['token'] = $this->session->data['token'];

        $this->data['image_directories'] = array();
        $this->getWatermarkDirectories(DIR_IMAGE . 'data', $this->data['image_directories']);

        $this->initParams(array(
            array( "soforp_watermark_status", 1 ),
            array( "soforp_watermark_debug", 0 ),
            array( "soforp_watermark_exclude", array() ),
            array( "soforp_watermark_size", 0),
            array( "soforp_watermark_min_height", 100),
            array( "soforp_watermark_min_width", 100),
            array( "soforp_watermark_position", "middle"),
            array( "soforp_watermark_offset_x", 0),
            array( "soforp_watermark_offset_y", 0),
        ));

        $this->load->model("tool/image");
        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        if ( $this->config->get('soforp_watermark_image') && file_exists(DIR_IMAGE . $this->config->get('soforp_watermark_image')) && is_file(DIR_IMAGE . $this->config->get('soforp_watermark_image'))) {
            $this->data['soforp_watermark_image_thumb'] = $this->model_tool_image->resize($this->config->get('soforp_watermark_image'), 100, 100);
            $this->data['soforp_watermark_image'] = $this->config->get('soforp_watermark_image');
        } else {
            $this->data['soforp_watermark_image_thumb'] = $this->data['no_image'];
            $this->data['soforp_watermark_image'] = "";
        }

		$this->template = 'module/soforp_watermark.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/soforp_watermark')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>