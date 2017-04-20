<?php
class ControllerModuleTotalImportPro
 extends Controller {

	public function index() {
		$this->response->redirect($this->url->link('tool/total_import', 'token=' . $this->session->data['token'], 'SSL'));
	}
}