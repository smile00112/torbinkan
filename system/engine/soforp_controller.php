<?php

class SoforpController extends Controller {
    protected $_moduleName = "SOFORP Controller";
    protected $debug = false;

    protected function debug( $message ){
        if( $this->debug ) {
            $this->log(" DEBUG " . $message);
        }
    }

    protected function log( $message ){
        file_put_contents(DIR_LOGS . $this->config->get("config_error_filename") , date("Y-m-d H:i:s - ") . $this->_moduleName . " " . $message . "\r\n", FILE_APPEND );
    }

    protected function initLanguage($module) {
        $this->data = array_merge( $this->data, $this->language->load($module) );
    }

    protected function initBreadcrumbs($items) {
        $newItems = array_merge( array(array("common/home","text_home")), $items);

        $this->data['breadcrumbs'] = array();

        foreach( $newItems as $item ){
            if( isset($this->session->data['token']) ) {
                $this->data['breadcrumbs'][] = array(
                    'href'      => $this->url->link($item[0], 'token=' . $this->session->data['token'], 'SSL'),
                    'text'      => $this->language->get($item[1]),
                    'separator' => (count($this->data['breadcrumbs']) ==0 ? FALSE : ' :: ')
                );
            } else {
                $this->data['breadcrumbs'][] = array(
                    'href'      => $this->url->link($item[0], 'SSL'),
                    'text'      => $this->language->get($item[1]),
                    'separator' => (count($this->data['breadcrumbs']) ==0 ? FALSE : ' :: ')
                );
            }
        }
    }

    protected function initParams($items) {

        foreach( $items as $item ){
            if (isset($this->request->post[ $item[0] ])) {
                $this->data[ $item[0] ] = $this->request->post[ $item[0] ];
            } else if ($this->config->has( $item[0] )) {
                $this->data[ $item[0] ] = $this->config->get( $item[0] );
            } else if(isset($item[1])){
                $this->data[$item[0]] = $item[1]; // default value
            }
        }
    }
    protected function initConfigParams($items) {
        foreach( $items as $item ){
            if( !is_array($item))
                $item = array($item);
            $param_name = $item[0];
            $config_name = isset($item[1]) ? $item[1] : $item[0];

            $this->data[$param_name] = $this->config->get($config_name);
        }
    }

    protected function initSessionParams($items) {
        foreach( $items as $item ){
            if( !is_array($item))
                $item = array($item);
            $param_name = $item[0];
            $session_name = isset($item[1]) ? $item[1] : $item[0];
            $default_value = isset($item[2]) ? $item[2] : '';

            if (isset($this->session->data[$session_name])) {
                $this->data[$param_name] = $this->session->data[$session_name];
            } else {
                $this->data[$param_name] = $default_value;
            }
        }
    }

    protected function addThemeStyle($file){
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/' . $file)) {
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/' . $file );
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $file);
        }
    }


    protected function renderTemplateOnly($file) {
        if( defined('HTTP_CATALOG') ) {
            $this->template = $file;
        } else {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $file )) {
                $this->template = $this->config->get('config_template') . '/template/' . $file;
            } else {
                $this->template = 'default/template/' . $file;
            }
        }

        $children = $this->children;
        $this->children = array();

        $result = $this->render();

        $this->children = $children;

        return $result;
    }

    protected function renderTemplate($file, $children = array()){
        if( defined('HTTP_CATALOG') ) {
            $this->template = $file;
        } else {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $file )) {
                $this->template = $this->config->get('config_template') . '/template/' . $file;
            } else {
                $this->template = 'default/template/' . $file;
            }
        }

        $this->children = $children;

        return $this->render();
    }

    protected function outputTemplate($file, $children = array()){
        if( defined('HTTP_CATALOG') ) {
            $this->template = $file;
        } else {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $file )) {
                $this->template = $this->config->get('config_template') . '/template/' . $file;
            } else {
                $this->template = 'default/template/' . $file;
            }
        }

        $this->children = $children;

        $this->response->setOutput($this->render());
    }

    protected function outputJson($data){
        $this->response->setOutput(json_encode($data));
    }

}