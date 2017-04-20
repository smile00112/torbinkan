<?php
class ControllerAddistError extends Controller
{
    public $data = array();
    
    public function __construct($registry)
    {
        parent::__construct($registry);
        
        $this->language->load('common/addist');
    }
    
    public function index($data = array())
    {
        if (empty($data['heading_title']))
        {
    		$data['heading_title'] = $this->language->get('error_heading');
        }
        
        $this->document->setTitle($data['heading_title']);
        
        //breadcrumbs
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link(OC_VERSION == '2.0.x' ? 'common/dashboard' : 'common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
        
        //data
        $this->data = $data;
        
        //set templates
		$this->template = 'addist/error.tpl';
        
        //set output
		$this->setOutput();
	}
    
    public function setOutput()
    {
        //default data
        $this->data['token'] = $this->session->data['token'];
        
        //bootstrap for oc 1.x
        if (OC_VERSION != '2.0.x')
        {
            $this->document->addScript('view/assets/js/bootstrap.min.js');
            $this->document->addStyle('view/assets/css/bootstrap.min.css');
            
            //font awesome
            $this->document->addStyle('view/assets/css/font-awesome.min.css');
        }
        
        //fix bootstrap
        $this->document->addStyle('view/assets/css/bootstrap.fix.css');
        
        //multi-select
        $this->document->addScript('view/assets/js/bootstrap-multiselect.js');
        $this->document->addStyle('view/assets/css/bootstrap-multiselect.css');
        
        //adding addist assets
        $this->document->addScript('view/assets/js/addist.js');
        $this->document->addStyle('view/assets/css/addist.css');
        
        //columns
        if (OC_VERSION == '2.0.x')
        {
            $this->data['header'] = $this->load->controller('common/header');
            $this->data['column_left'] = $this->load->controller('common/column_left');
            $this->data['footer'] = $this->load->controller('common/footer');
        }
        else
        {
            $this->data['header'] = $this->getChild('common/header');
            $this->data['column_left'] = '';
            $this->data['footer'] = $this->getChild('common/footer');
        }
        
        //replacing jquery
        $html = $this->load_view($this->template, $this->data);
        
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $dom->loadHTML($html);
        $xp = new DOMXPath($dom);
        
        $oldNode = $xp->query('//script[contains(@src,"jquery")]')->item(0);
        if ($oldNode)
        {
            $replacement = $dom->createDocumentFragment();
            $replacement->appendXml('<script type="text/javascript" src="view/assets/js/jquery-2.1.1.min.js"></script><script type="text/javascript" src="view/assets/js/jquery-migrate-1.2.1.min.js"></script>');                
            $oldNode->parentNode->replaceChild($replacement, $oldNode);
        }
        
        $oldNode = $xp->query('//script[contains(@src,"jquery") and contains(@src,"ui") and contains(@src,".js")]')->item(0);
        if ($oldNode)
        {
            $replacement = $dom->createDocumentFragment();
            $replacement->appendXml('<script type="text/javascript" src="view/assets/js/jquery-ui.min.js"></script>');                
            $oldNode->parentNode->replaceChild($replacement, $oldNode);
        }
        
        $html = $dom->saveHTML();                        
        $html = mb_convert_encoding($html, "UTF-8", 'HTML-ENTITIES');
        $this->response->setOutput($html);
    }
    
    public function load_view($template, $data = array())
    {
		if (OC_VERSION == '2.0.x')
        {
            return $this->load->view($template,$data);
        }
        else
        {
            if (file_exists(DIR_TEMPLATE . $template))
            {
                extract($data);
                ob_start();
                require(DIR_TEMPLATE . $template);
                $output = ob_get_contents();
                ob_end_clean();
                return $output;
            }
            else
            {
                trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $template . '!');
                exit();				
            }
        }
    }
}
?>