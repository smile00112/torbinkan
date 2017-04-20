<?php  
class ControllerModuleNewslettersubscribe extends Controller {
  	private $error = array();
	
	public function index(){
	   $this->loadmodule();
	   $this->load->model('account/newslettersubscribe');
	   $this->model_account_newslettersubscribe->check_db();
	}
	
	public function subscribe(){
	
		if($this->config->get('newslettersubscribe_thickbox')){
			$prefix_eval = "";
		}else{
			$prefix_eval = "";
		}
	  
		$this->language->load('module/newslettersubscribe');
	  
		$this->load->model('account/newslettersubscribe');
	  
		if(isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'],FILTER_VALIDATE_EMAIL)){
           
			if($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)){
			   
			    $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post,1);
				
				echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('subscribe').'");$("'.$prefix_eval.' #subscribe'.$this->data['module'].'")[0].reset();');
			   
			}else if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){
			 
				$this->model_account_newslettersubscribe->subscribe($this->request->post);
				echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('subscribe').'");$("'.$prefix_eval.' #subscribe'.$this->data['module'].'")[0].reset();');
			 
				$email_subscriber = $this->request->post['subscribe_email'];
				
				
								if($this->config->get('newslettersubscribe_mail_status')){
					$subject = $this->language->get('mail_subject');	
				
					$message = '<table width="60%" cellpadding="2"  cellspacing="1" border="0"> 
						<tr>
							<td> <b>Новая подписка на новости</b> </td>
							<td>  </td>
						</tr>
				  	    <tr>
							<td> Email </td>
							<td> '.$this->request->post['subscribe_email'].' </td>
						</tr>
				  	    ';
					
					$message .= '</table>';
	 
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->hostname = $this->config->get('config_smtp_host');
					$mail->username = $this->config->get('config_smtp_username');
					$mail->password = $this->config->get('config_smtp_password');
					$mail->port = $this->config->get('config_smtp_port');
					$mail->timeout = $this->config->get('config_smtp_timeout');				
					$mail->setTo('info@interkrep.ru');
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($this->config->get('config_name'));
					$mail->setSubject($subject);
					$mail->setHtml($message);
					$mail->send();
				}
				
				if($this->config->get('newslettersubscribe_mail_status')){
					$subject = $this->language->get('mail_subject');	
				
					$message = '<table width="60%" cellpadding="2"  cellspacing="1" border="0"> 
						<tr>
							<td> <b>Вы успешно подписались на новостную рассылку компании ИнтерКреп!</b> </td>
							<td>  </td>
						</tr>
				  	   
				  	    ';
					
					$message .= '</table>';
	 
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->hostname = $this->config->get('config_smtp_host');
					$mail->username = $this->config->get('config_smtp_username');
					$mail->password = $this->config->get('config_smtp_password');
					$mail->port = $this->config->get('config_smtp_port');
					$mail->timeout = $this->config->get('config_smtp_timeout');				
					$mail->setTo($email_subscriber);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($this->config->get('config_name'));
					$mail->setSubject($subject);
					$mail->setHtml($message);
					$mail->send();
				}
			 
			}else{
				echo('$("'.$prefix_eval.' #subscribe_result").html("<span class=\"error\">'.$this->language->get('alreadyexist').'</span>");$("'.$prefix_eval.' #subscribe'.$this->data['module'].'")[0].reset();');	 
			}
		   
		}else{
			echo('$("'.$prefix_eval.' #subscribe_result").html("<span class=\"error\">'.$this->language->get('error_invalid').'</span>")');
		}
	}

	public function unsubscribe(){
	  
		if($this->config->get('newslettersubscribe_thickbox')){
			$prefix_eval = "#TB_ajaxContent ";
		}else{
			$prefix_eval = "";
		}
	  
		$this->language->load('module/newslettersubscribe');
	  
		$this->load->model('account/newslettersubscribe');
	  
		if(isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'],FILTER_VALIDATE_EMAIL)){
            
		    if($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)){
			   
			    $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post,0);
				
				echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('unsubscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			   
			}else if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){
			 
				echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('notexist').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			 
			}else{
			   
				if($this->config->get('option_unsubscribe')) {
					$this->model_account_newslettersubscribe->unsubscribe($this->request->post);
					echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('unsubscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
				}
			}
		   
		}else{
			echo('$("'.$prefix_eval.' #subscribe_result").html("<span class=\"error\">'.$this->language->get('error_invalid').'</span>")');
		}
	}

	protected function loadmodule() {
		static $module = 0;
		
		$this->language->load('module/newslettersubscribe');

      	$this->data['heading_title'] = $this->language->get('heading_title');	
      	$this->data['entry_name'] = $this->language->get('entry_name');	
      	$this->data['entry_email'] = $this->language->get('entry_email');	
      	$this->data['entry_button'] = $this->language->get('entry_button');		
      	$this->data['entry_unbutton'] = $this->language->get('entry_unbutton');		
      	$this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');	
		$this->data['text_subscribe'] = $this->language->get('text_subscribe');	
		
		$this->id = 'newslettersubscribe';

		$this->data['module'] = $module++;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribe.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribe.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribe.tpl';
		}
		
		$this->render();
	}
}
?>