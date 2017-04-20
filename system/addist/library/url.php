<?php
class AddistUrl
{
	private $domain;
	private $ssl;

	public function __construct($domain, $ssl = '')
    {
		$this->domain = $domain;
		$this->ssl = $ssl;
	}
    
	public function link($route, $args = '', $secure = false, $no_store = false)
    {
		if (!$secure)
        {
			$url = $this->domain;
		}
        else
        {
			$url = $this->ssl;
		}

		$url .= 'index.php?route=' . $route;
        
        //STORE_ID
        if (!preg_match("#&store_id=(\d+)#",$args) && STORE_ID != 0)
        {
            $args .=  '&store_id='.STORE_ID;
        }
        
        //DEBUG
        global $config;
        if (isset($_GET['debug']) || $config->get('addist_installer_debug'))
        {
            $args .=  '&XDEBUG_PROFILE=1';
        }
        
		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
		}
        
		return $url;
	}
}
?>