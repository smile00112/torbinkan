<?php
class ControllerPaymentCashplusplus extends Controller {
	protected function index() {
  $this->language->load('payment/cash_plusplus');
		
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
      if ($this->config->get('cash_plusplus_instruction_attach')){
      $this->data['text_instruction'] = $this->language->get('text_instruction');
      $this->data['bank'] = nl2br(htmlspecialchars_decode($this->config->get('cash_plusplus_instruction_' . $this->config->get('config_language_id'))));
      }
      $this->data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cash_plusplus.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/cash_plusplus.tpl';
		} else {
            $this->template = 'default/template/payment/cash_plusplus.tpl';
        }
		
		$this->render();		 
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    $this->language->load('payment/cash_plusplus');
    $cash_plusplus_url = $order_info['store_url'] . 'index.php?route=account/cash_plusplus/view&sum=' . substr($order_info['total'], 0, -5) . '&plat=' . $order_info['firstname'] . '+' . $order_info['lastname'] . '&onum=' . $order_info['order_id'] ;
    $cash_plusplus_url = str_replace(" ", "+", $cash_plusplus_url);
    $cash_plusplus_url = '<a href="' . $cash_plusplus_url . '" >' . $cash_plusplus_url . '</a>';
    $comment  =  $this->language->get('text_link') . "\n";
    $comment .= html_entity_decode($cash_plusplus_url, ENT_QUOTES, 'UTF-8') . "\n\n";
    if ($this->config->get('cash_plusplus_instruction_attach')){
    $comment .= $this->language->get('text_instruction') . "\n\n";
	$comment .= $this->config->get('cash_plusplus_instruction_' . $this->config->get('config_language_id'))  . "\n\n";
    $comment = htmlspecialchars_decode($comment);
    }
		if ($this->config->get('cash_plusplus_email_attach')){
        $inn      = nl2br($this->config->get('cash_plusplus_inn_' . $this->config->get('config_language_id')));
        $bank     = nl2br($this->config->get('cash_plusplus_bank_' . $this->config->get('config_language_id')));
        $ur       = nl2br($this->config->get('cash_plusplus_ur_' . $this->config->get('config_language_id')));
        $rs       = nl2br($this->config->get('cash_plusplus_rs_' . $this->config->get('config_language_id')));
        $bankuser = nl2br($this->config->get('cash_plusplus_bankuser_' . $this->config->get('config_language_id')));
        $bik      = nl2br($this->config->get('cash_plusplus_bik_' . $this->config->get('config_language_id')));
        $ks       = nl2br($this->config->get('cash_plusplus_ks_' . $this->config->get('config_language_id')));
        $kpp      = nl2br($this->config->get('cash_plusplus_kpp_' . $this->config->get('config_language_id')));
        $tel      = nl2br($this->config->get('cash_plusplus_tel_' . $this->config->get('config_language_id')));
        $date     = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
        $ccomment = htmlspecialchars_decode($this->config->get('cash_plusplus_comment_' . $this->config->get('config_language_id')));
        $ccomment = str_replace("&nbsp;", " ", $ccomment);

                    if ($ccomment){
                      $ccomment = '<span style="font-family:arial !important;">' . $ccomment . '</span>';
                    }
                    else{
                      $ccomment='';
                    }
        
        if ($kpp){
        $kpp      = 'КПП ' . $kpp;
        }
        else{
        $kpp  = '';
        }

        $logo    = nl2br($this->config->get('cash_plusplus_logo'));
        if ($logo){
          $logo     ='
          <p>
            <span>
              <IMG class="logo" src="image/' . $logo . '"/>
            </span>
          </p>';
        }
        else{
          $logo = '';
        }
        $image    = nl2br($this->config->get('cash_plusplus_image'));
        if ($image){
          $image     ='
            <div>
              <IMG src="image/' . $image . '"/>
            </div>';
        }
        else{
          $image = '';
        }

        $text     = nl2br($this->config->get('cash_plusplus_etext_' . $this->config->get('config_language_id')));
        
        $prod='';
        $i=0;

        if($this->config->get('cash_plusplus_copey')){ $valu = $this->currency->format(0, $order_info['currency_code'], $order_info['currency_value']);
        $valu = preg_replace('/[^a-zа-яё.\s]+/iu', '', $valu);}

        foreach ($this->cart->getProducts() as $product) { 
         $i += 1;
         $prod1 = '<tr>
            <td class="center"><p>' . $i . '</p></td>
            <td><p>' . $product['name'] . ' ' ;
            if ($product['option']){
              foreach ($product['option'] as $option) {
                  $opti = '<br/><span class="smal"><small>- ' . $option['name'] .' : ' . $option['option_value'] . '</small></span>';
                  }
            }
            if($this->config->get('cash_plusplus_copey')){
                  $product['total'] = number_format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), 2, '.', '') . $valu;
                  $product['price'] = number_format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), 2, '.', ''). $valu;
                }
                else{
         $product['total'] = $this->currency->format(($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']), $order_info['currency_code'], $order_info['currency_value']);
         $product['price'] = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $order_info['currency_code'], $order_info['currency_value']);
                }
         $prod2 = '</p></td>
            <td class="center"><p>шт.</p></td>
            <td class="textr"><p>' . $product['quantity'] . '</p></td>
            <td class="textr"><p>' . $product['price'] . '</p></td>
            <td class="textr"><p>' . $product['total'] . '</p></td>
          </tr>';
          if ($product['option']){
          $prod .= $prod1 . $opti . $prod2;
          }
          else{
          $prod .= $prod1 . $prod2;
          }
        }

			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();
			 
			$this->load->model('setting/extension');
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}
			
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $total_data);

          $tot='';
          foreach ($total_data as $total){
            if($this->config->get('cash_plusplus_copey')){
          $total['text'] = number_format(preg_replace('/[^0-9.]+/iu', '', $total['text']), 2, '.', '') . $valu;}
            $totme = '
            <tr>
                <td colspan="5" class="textr b0"><p>' . strip_tags($total['title']) . '</p></td>
                <td class="textr"><p>' . $total['text'] . '</p></td>
              </tr>';
              $tot .= $totme;
        }

        if ($this->config->get('cash_plusplus_nds')){
        $nds= '<p>' . $this->language->get('text_nds') . '</p>';
        }
        else{
          $nds= '';
        }

        $this->load->model('payment/cash_plusplus');
        $numbers = $this->model_payment_cash_plusplus->numbers(ltrim(preg_replace('/[^0-9.]/', '', str_replace(" ","", $this->currency->format($order_info['total']))), " ."));
        $numbers = $this->language->get('text_itogo')  . $numbers;

        //Счет

                    $invoice = $this->config->get('cash_plusplus_invoi');
                    if ($invoice == 'invoi_zakaz'){
                        $invoi = $order_info['order_id'];
                    }
                    if ($invoice == 'invoi_zakazd'){
                         $invoi = $order_info['order_id'] . ' от ' . date($this->language->get('date_format_short'), strtotime($order_info['date_modified'])) . 'г.';
                    }
                    if ($invoice == 'invoi_zakazdc'){
                         $invoi = $order_info['order_id'] . ' от ' . date($this->language->get('date_format_short'), strtotime($order_info['date_added'])) . 'г.';
                    }
                    if ($invoice == 'invoi_noinvoice'){
                        if ($order_info['invoice_no'] != 0){
                            $invoi = $order_info['invoice_prefix'] . $order_info['invoice_no'];
                        }
                        else {
                            $invoi = $order_info['order_id'];
                        }
                    }
                    if ($invoice == 'invoi_noinvoiced'){
                        if ($order_info['invoice_no'] != 0){
                            $invoi = $order_info['invoice_prefix'] . $order_info['invoice_no'] . ' от ' . date($this->language->get('date_format_short'), strtotime($order_info['date_modified'])). 'г.';
                        }
                        else {
                            $invoi = $order_info['order_id'];
                        }
                    }
                    if ($invoice == 'invoi_noinvoicedc'){
                        if ($order_info['invoice_no'] != 0){
                            $invoi = $order_info['invoice_prefix'] . $order_info['invoice_no'] . ' от ' . date($this->language->get('date_format_short'), strtotime($order_info['date_added'])). 'г.';
                        }
                        else {
                            $invoi = $order_info['order_id'];
                        }
                    }

        //Плательщик

                    $chelpay = $this->config->get('cash_plusplus_chelpay');
                    if ($chelpay == 'chelpay_fio'){
                         $addrp = $order_info['payment_firstname'] . " " . $order_info['payment_lastname'] . ", " . $order_info['payment_city'] . ", " . $order_info['payment_address_1'] . " " . $order_info['payment_address_2'];
                    }
                    if ($chelpay == 'chelpay_company'){
                        if ($order_info['payment_company']){
                         $addrp = $order_info['payment_company'] . ", " . $order_info['payment_city'] . ", " . $order_info['payment_address_1'] . " " . $order_info['payment_address_2'];
                        }
                        else{
                          $addrp = $order_info['payment_firstname'] . " " . $order_info['payment_lastname'] . ", " . $order_info['payment_city'] . ", " . $order_info['payment_address_1'] . " " . $order_info['payment_address_2'];
                        }
                    }
                    if ($chelpay == 'chelpay_company_fio'){
                        if($order_info['payment_company']){
                           $addrp = $order_info['payment_company'] . ", " . $order_info['payment_firstname'] . " " . $order_info['payment_lastname'] . ", " . $order_info['payment_city'] . ", " . $order_info['payment_address_1'] . " " . $order_info['payment_address_2'];
                        }
                         else{
                          $addrp = $order_info['payment_firstname'] . " " . $order_info['payment_lastname'] . ", " . $order_info['payment_city'] . ", " . $order_info['payment_address_1'] . " " . $order_info['payment_address_2'];
                        }
                    }
                    if ($chelpay == 'chelpay_fio_company'){
                      if($order_info['payment_company']){
                         $addrp = $order_info['payment_firstname'] . " " . $order_info['payment_lastname'] . ", " . $order_info['payment_company'] . ", " . $order_info['payment_city'] . ", " . $order_info['payment_address_1'] . " " . $order_info['payment_address_2'];
                       }
                       else{
                        $addrp = $order_info['payment_firstname'] . " " . $order_info['payment_lastname'] . ", " . $order_info['payment_city'] . ", " . $order_info['payment_address_1'] . " " . $order_info['payment_address_2'];
                       }
                    }

                    if ($chelpay == 'chelpay_custom'){
                      $customs = explode(',', (str_replace(" ","",$this->config->get('cash_plusplus_custom'))));
                      $customz = "";
                        foreach ($customs as $custom) {
                          if (substr_count($custom, "custom_") ){
                        $this->load->model('tool/simplecustom');
                        $customx = $this->model_tool_simplecustom->getOrderField($order_info['order_id'], $custom);
                        if ($customx){
                        $customz .= ' ' . $customx;
                        }
                        if($customx === ''){
                          $customx = $this->model_tool_simplecustom->getPaymentAddressField($order_info['order_id'], $custom);
                          if ($customx){
                          $customz .= ' ' . $customx;
                          }
                        }
                      }
                      else{
                        if ($custom == 'address'){
                          $custom_other = $order_info['payment_address_1'] . ' ' . $order_info['payment_address_2'];
                        }
                        if ($custom == 'firstname_x'){
                            $custom_other = $order_info['payment_firstname'];
                        }
                        if ($custom == 'lastname_x'){
                            $custom_other = $order_info['payment_lastname'];
                        }
                        if ($custom == 'firstname_lastname'){
                            $custom_other = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
                        }
                        if ($custom == 'city'){
                          $custom_other = $order_info['payment_city'];
                        }
                        if ($custom == 'company'){
                          $custom_other = $order_info['payment_company'];
                        }
                        if ($custom == 'postcode'){
                          $custom_other = $order_info['payment_postcode'];
                        }
                        if ($custom == 'zone'){
                          $custom_other = $order_info['payment_zone'];
                        }
                        if ($custom == 'zap'){
                          $custom_other = ', ';
                        }
                        else{
                          if (isset($order_info[$custom])) {
                        $custom_other = $order_info[$custom];
                          }
                        }
                        if (isset($custom_other)){
                        if ($custom !== 'zap'){
                        $customz .= ' ' . $custom_other;
                        }
                        else{
                          $customz .= $custom_other;
                        }
                        }
                      }
                        
                      }
                      $addrp = preg_replace('~,  ,+~', ',', (trim(preg_replace('~, ,+~', ',', $customz), ', ')));
                       
                       }
                    

                    //Грузополучатель
                    $gruzpay = $this->config->get('cash_plusplus_gruzpay');
                    if ($gruzpay == 'gruzpay_fio'){
                         $addrg = $order_info['shipping_firstname'] . " " . $order_info['shipping_lastname'] . ", " . $order_info['shipping_city'] . ", " . $order_info['shipping_address_1'] . " " . $order_info['shipping_address_2'];
                    }
                    if ($gruzpay == 'gruzpay_company'){
                        if ($order_info['shipping_company']){
                         $addrg = $order_info['shipping_company'] . ", " . $order_info['shipping_city'] . ", " . $order_info['shipping_address_1'] . " " . $order_info['shipping_address_2'];
                        }
                        else{
                          $addrg = $order_info['shipping_firstname'] . " " . $order_info['shipping_lastname'] . ", " . $order_info['shipping_city'] . ", " . $order_info['shipping_address_1'] . " " . $order_info['shipping_address_2'];
                        }
                    }
                    if ($gruzpay == 'gruzpay_company_fio'){
                        if($order_info['shipping_company']){
                           $addrg = $order_info['shipping_company'] . ", " . $order_info['shipping_firstname'] . " " . $order_info['shipping_lastname'] . ", " . $order_info['shipping_city'] . ", " . $order_info['shipping_address_1'] . " " . $order_info['shipping_address_2'];
                        }
                         else{
                          $addrg = $order_info['shipping_firstname'] . " " . $order_info['shipping_lastname'] . ", " . $order_info['shipping_city'] . ", " . $order_info['shipping_address_1'] . " " . $order_info['shipping_address_2'];
                        }
                    }
                    if ($gruzpay == 'gruzpay_fio_company'){
                      if($order_info['shipping_company']){
                         $addrg = $order_info['shipping_firstname'] . " " . $order_info['shipping_lastname'] . ", " . $order_info['shipping_company'] . ", " . $order_info['shipping_city'] . ", " . $order_info['shipping_address_1'] . " " . $order_info['shipping_address_2'];
                       }
                       else{
                        $addrg = $order_info['shipping_firstname'] . " " . $order_info['shipping_lastname'] . ", " . $order_info['shipping_city'] . ", " . $order_info['shipping_address_1'] . " " . $order_info['shipping_address_2'];
                       }
                    }

                    if ($gruzpay == 'gruzpay_custom'){
                         $customs = explode(',', (str_replace(" ","",$this->config->get('cash_plusplus_custom_2'))));
                         $customz = "";
                              foreach ($customs as $custom) {
                                   if (substr_count($custom, "custom_") ){
                              $this->load->model('tool/simplecustom');
                              $customx = $this->model_tool_simplecustom->getOrderField($order_info['order_id'], $custom);
                              if ($customx){
                              $customz .= ' ' . $customx;
                              }
                              if($customx === ''){
                                   $customx = $this->model_tool_simplecustom->getShippingAddressField($order_info['order_id'], $custom);
                                   if ($customx){
                                   $customz .= ' ' . $customx;
                                   }
                              }
                         }
                         else{
                              if ($custom == 'address'){
                                   $custom_other = $order_info['shipping_address_1'] . ' ' . $order_info['shipping_address_2'];
                              }
                              if ($custom == 'firstname_x'){
                                        $custom_other = $order_info['shipping_firstname'];
                              }
                              if ($custom == 'lastname_x'){
                                        $custom_other = $order_info['shipping_lastname'];
                              }
                              if ($custom == 'firstname_lastname'){
                                        $custom_other = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'];
                              }
                              if ($custom == 'city'){
                                   $custom_other = $order_info['shipping_city'];
                              }
                              if ($custom == 'company'){
                                   $custom_other = $order_info['shipping_company'];
                              }
                              if ($custom == 'postcode'){
                                   $custom_other = $order_info['shipping_postcode'];
                              }
                              if ($custom == 'zone'){
                                   $custom_other = $order_info['shipping_zone'];
                              }
                              if ($custom == 'zap'){
                                   $custom_other = ', ';
                              }
                              else{
                          if (isset($order_info[$custom])) {
                        $custom_other = $order_info[$custom];
                          }
                        }
                        if (isset($custom_other)){
                        if ($custom !== 'zap'){
                        $customz .= ' ' . $custom_other;
                        }
                        else{
                          $customz .= $custom_other;
                        }
                        }
                      }
                        
                      }
                      $addrg = preg_replace('~,  ,+~', ',', (trim(preg_replace('~, ,+~', ',', $customz), ', ')));
                       
                       }

        $html = '
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
    html{
      margin:10px 20px;
    }
    body{
      max-width: 800px;
    }
    h2 {
      font-weight: bold;
      font-size: 10pt;
    }
    .s1 {
      font-weight: normal;
      font-size: 12pt;
    }
    .s2 {
      font-weight: normal;
      font-size: 10pt;
    }
    h1 {
      font-weight: bold;
      font-size: 18pt;
      text-align: center;
    }
    p {
      font-weight: normal;
      font-size: 10pt;
    }
    a {
      color: #00F;
      font-weight: normal;
      text-decoration: underline;
      font-size: 12pt;
    }
    .logo, .head-s{
      float: left;
    }
    .clear{
      clear: both;
    }
    .center{
      text-align: center;
    }
    .center p{
      text-align: center;
    }
    .bold{
      font-weight: bold;
    }
    table{
      clear: both;
      width:100%;
    }
    table td{
      border: 1px solid;
      }
    td p{
      text-align: bottom;
      margin:4px 8px;
    }
    .textr p{
      text-align: right;
    }
    .b0{
      border: 0;
    }
    .bold p{
      font-weight: bold;
    }
    .etext{
    font-size:10px;
    }
    .smal{
    font-size:10px;
    }
</style>
  <p class="center etext">
    ' . $text . '
  </p>
' . $logo . '
  <div class="head-s">
    <h2>' . $bank . '</h2>
    <h2>' . $ur . '</h2>
    <h2>' . $tel . '</h2>
  </div>
  <p class="clear center bold">Образец заполнения платежного поручения</p>
  <table border="1" cellspacing="0" cellpadding="2px" style="border-collapse:collapse">
    <tr>
      <td>
        <p class="s2">ИНН ' . $inn . '</p>
      </td>
      <td>
        <p class="s2">' . $kpp . '</p>
      </td>
      <td  rowspan="2" valign="bottom">
        <p class="s2">Сч.№</p>
      </td>
      <td rowspan="2" valign="bottom">
        <p class="s2">' . $rs . '</p>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="s2">Получатель</p>
        <p class="s2">' . $bank . '</p>
      </td>

    </tr>
    <tr>
      <td colspan="2">
        <p class="s2">Банк получателя</p>
        <p class="s2">' . $bankuser . '</p>
      </td>
      <td>
        <p class="s2">БИК</p>
        <p class="s2">Сч.№</p>
      </td>
      <td>
        <p class="s2">' . $bik . '</p>
        <p class="s2">' . $ks . '</p>
      </td>
    </tr>
  </table>
  <h1>СЧЕТ № ' . $invoi . '</h1>
  <p>
    Плательщик: ' . $addrp . '
    <br/>
    Грузополучатель: ' . $addrg . '
  </p>
  <table border="0" cellspacing="0" cellpadding="2px" style="border-collapse:collapse">
    <thead>
      <tr>
        <th>№</th>
        <th>Наименование товара</th>
        <th>Единица</th>
        <th>Количество</th>
        <th>Цена</th>
        <th>Сумма</th>
      </tr>
    </thead>
    <tbody>
      ' . $prod . '
      ' . $tot . '
    </tbody>
  </table>
  <p>Всего наименований ' . $i . ', на сумму ' . number_format(floatval(ltrim(preg_replace('/[^0-9.]/', '', str_replace(" ","", $this->currency->format($order_info['total']))), " .")), 2, '.', ' ') . ' руб.<br/>
  <b>' . $numbers . '</b></p>
 ' . $nds . '
 ' . $ccomment . '
 ' . $image . '
';
      $drot = 'system/extra/dompdf/dompdf_config.inc.php';
      require_once($drot);
      $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->render();
      $output = $dompdf->output();
      $file_location = 'download/bank_payment_' . $order_info['order_id'] . '.pdf';
      file_put_contents($file_location,$output);
		$attachment = $file_location;
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('cash_plusplus_order_status_id'), $comment, true, $attachment);
    unlink($file_location);
	}
	else{
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('cash_plusplus_order_status_id'), $comment, true);
	}

}
}
?>