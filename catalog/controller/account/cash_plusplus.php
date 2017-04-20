<?php
class ControllerAccountCashplusplus extends Controller {
    private $error = array();
    public function index() {
        $this->load->language('account/cash_plusplus');
        $this->load->model('account/cash_plusplus');
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');
            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }
        if (isset($_GET['sum']) & isset($_GET['onum']) & isset($_GET['plat'])) {
            
            $address = $this->model_account_cash_plusplus->getOrderAdress($_GET['onum']);
            if (isset($address['firstname']) & isset($address['lastname'])) {
                $platp = $address['firstname'] . " " . $address['lastname'];
                if ($_GET['plat'] == $platp ) {
                    $inn      = nl2br($this->config->get('cash_plusplus_inn_' . $this->config->get('config_language_id')));
                    $bank     = nl2br($this->config->get('cash_plusplus_bank_' . $this->config->get('config_language_id')));
                    $rs       = nl2br($this->config->get('cash_plusplus_rs_' . $this->config->get('config_language_id')));
                    $bankuser = nl2br($this->config->get('cash_plusplus_bankuser_' . $this->config->get('config_language_id')));
                    $bik      = nl2br($this->config->get('cash_plusplus_bik_' . $this->config->get('config_language_id')));
                    $ks       = nl2br($this->config->get('cash_plusplus_ks_' . $this->config->get('config_language_id')));
                    $ur       = nl2br($this->config->get('cash_plusplus_ur_' . $this->config->get('config_language_id')));
                    $kpp      = nl2br($this->config->get('cash_plusplus_kpp_' . $this->config->get('config_language_id')));
                    $tel      = nl2br($this->config->get('cash_plusplus_tel_' . $this->config->get('config_language_id')));  
                    $products = $this->model_account_cash_plusplus->getOrderProducts($this->request->get['onum']);
                    $order_info = $this->model_account_cash_plusplus->getOrderAdress($this->request->get['onum']);
                    $comment = htmlspecialchars_decode($this->config->get('cash_plusplus_comment_' . $this->config->get('config_language_id')));
                    $comment = str_replace("&nbsp;", " ", $comment);

                    if ($comment){
                    	$comment = '<span style="font-family:arial !important;">' . $comment . '</span>';
                    }
                    else{
                    	$comment='';
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

        if($this->config->get('cash_plusplus_copey')){ $valu = $this->currency->format(0, $address['currency_code'], $address['currency_value']);
        $valu = preg_replace('/[^a-zа-яё.\s]+/iu', '', $valu);}

        foreach ($products as $product) { 
            $i += 1;
         $prod1 = '<tr>
            <td class="center"><p>' . $i . '</p></td>
            <td><p>' . $product['name'] . ' ' ;
            $options = $this->model_account_cash_plusplus->getOrderOptions($this->request->get['onum'], $product['order_product_id']);
            if ($options){
              foreach ($options as $option) {
                  $opti = '<br/><span class="smal"><small>- ' . $option['name'] .' : ' . $option['value'] . '</small></span>';
                  }
            }
            if($this->config->get('cash_plusplus_copey')){
                  $product['total'] = number_format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), 2, '.', '') . $valu;
                  $product['price'] = number_format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), 2, '.', ''). $valu;

               }
               else{
         $product['total'] = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']);
         $product['price'] = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
                }
         $prod2 = '</p></td>
            <td class="center"><p>шт.</p></td>
            <td class="textr"><p>' . $product['quantity'] . '</p></td>
            <td class="textr"><p>' . $product['price'] . '</p></td>
            <td class="textr"><p>' . $product['total'] . '</p></td>
          </tr>';
          if ($options){
          $prod .= $prod1 . $opti . $prod2;
          }
          else{
          $prod .= $prod1 . $prod2;
          }
        }

        $totals = $this->model_account_cash_plusplus->getOrderTotals($this->request->get['onum']);
          $tot='';
          foreach ($totals as $total){
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

        $numbers = $this->model_account_cash_plusplus->numbers(ltrim(preg_replace('/[^0-9.]/', '', str_replace(" ","", $this->currency->format($address['total']))), " ."));
        $numbers = $this->language->get('text_itogo')  . $numbers;

        //Счет

                    $invoice = $this->config->get('cash_plusplus_invoi');
                    if ($invoice == 'invoi_zakaz'){
                        $invoi = $_GET['onum'];
                    }
                    if ($invoice == 'invoi_zakazd'){
                         $invoi = $_GET['onum'] . ' от ' . date($this->language->get('date_format_short'), strtotime($address['date_modified'])) . 'г.';
                    }
                    if ($invoice == 'invoi_zakazdc'){
                         $invoi = $_GET['onum'] . ' от ' . date($this->language->get('date_format_short'), strtotime($address['date_added'])) . 'г.';
                    }
                    if ($invoice == 'invoi_noinvoice'){
                        if ($address['invoice_no'] != 0){
                            $invoi = $address['invoice_prefix'] . $address['invoice_no'];
                        }
                        else {
                            $invoi = $_GET['onum'];
                        }
                    }
                    if ($invoice == 'invoi_noinvoiced'){
                        if ($address['invoice_no'] != 0){
                            $invoi = $address['invoice_prefix'] . $address['invoice_no'] . ' от ' . date($this->language->get('date_format_short'), strtotime($address['date_modified'])). 'г.';
                        }
                        else {
                            $invoi = $_GET['onum'];
                        }
                    }
                    if ($invoice == 'invoi_noinvoicedc'){
                        if ($address['invoice_no'] != 0){
                            $invoi = $address['invoice_prefix'] . $address['invoice_no'] . ' от ' . date($this->language->get('date_format_short'), strtotime($address['date_added'])). 'г.';
                        }
                        else {
                            $invoi = $_GET['onum'];
                        }
                    }


                    //Плательщик

                    $chelpay = $this->config->get('cash_plusplus_chelpay');
                    if ($chelpay == 'chelpay_fio'){
                         $addrp = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                    }
                    if ($chelpay == 'chelpay_company'){
                        if ($address['payment_company']){
                         $addrp = $address['payment_company'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                        }
                        else{
                          $addrp = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                        }
                    }
                    if ($chelpay == 'chelpay_company_fio'){
                        if($address['payment_company']){
                           $addrp = $address['payment_company'] . ", " . $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                        }
                         else{
                          $addrp = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                        }
                    }
                    if ($chelpay == 'chelpay_fio_company'){
                      if($address['payment_company']){
                         $addrp = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_company'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                       }
                       else{
                        $addrp = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                       }
                    }

                    if ($chelpay == 'chelpay_custom'){
                      $customs = explode(',', (str_replace(" ","",$this->config->get('cash_plusplus_custom'))));
                      $customz = "";
                        foreach ($customs as $custom) {
                          if (substr_count($custom, "custom_") ){
                        $this->load->model('tool/simplecustom');
                        $customx = $this->model_tool_simplecustom->getOrderField($address['order_id'], $custom);
                        if ($customx){
                        $customz .= ' ' . $customx;
                        }
                        if($customx === ''){
                          $customx = $this->model_tool_simplecustom->getPaymentAddressField($address['order_id'], $custom);
                          if ($customx){
                          $customz .= ' ' . $customx;
                          }
                        }
                      }
                      else{
                        if ($custom == 'address'){
                          $custom_other = $address['payment_address_1'] . ' ' . $address['payment_address_2'];
                        }
                        if ($custom == 'firstname_x'){
                            $custom_other = $address['payment_firstname'];
                        }
                        if ($custom == 'lastname_x'){
                            $custom_other = $address['payment_lastname'];
                        }
                        if ($custom == 'firstname_lastname'){
                            $custom_other = $address['payment_firstname'] . ' ' . $address['payment_lastname'];
                        }
                        if ($custom == 'city'){
                          $custom_other = $address['payment_city'];
                        }
                        if ($custom == 'company'){
                          $custom_other = $address['payment_company'];
                        }
                        if ($custom == 'postcode'){
                          $custom_other = $address['payment_postcode'];
                        }
                        if ($custom == 'zone'){
                          $custom_other = $address['payment_zone'];
                        }
                        if ($custom == 'zap'){
                          $custom_other = ', ';
                        }
                        else{
                          if (isset($address[$custom])) {
                        $custom_other = $address[$custom];
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
                         $addrg = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                    }
                    if ($gruzpay == 'gruzpay_company'){
                        if ($address['shipping_company']){
                         $addrg = $address['shipping_company'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                        }
                        else{
                          $addrg = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                        }
                    }
                    if ($gruzpay == 'gruzpay_company_fio'){
                        if($address['shipping_company']){
                           $addrg = $address['shipping_company'] . ", " . $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                        }
                         else{
                          $addrg = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                        }
                    }
                    if ($gruzpay == 'gruzpay_fio_company'){
                      if($address['shipping_company']){
                         $addrg = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_company'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                       }
                       else{
                        $addrg = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                       }
                    }

                    if ($gruzpay == 'gruzpay_custom'){
                      $customs = explode(',', (str_replace(" ","",$this->config->get('cash_plusplus_custom_2'))));
                      $customz = "";
                        foreach ($customs as $custom) {
                          if (substr_count($custom, "custom_") ){
                        $this->load->model('tool/simplecustom');
                        $customx = $this->model_tool_simplecustom->getOrderField($address['order_id'], $custom);
                        if ($customx){
                        $customz .= ' ' . $customx;
                        }
                        if($customx === ''){
                          $customx = $this->model_tool_simplecustom->getShippingAddressField($address['order_id'], $custom);
                          if ($customx){
                          $customz .= ' ' . $customx;
                          }
                        }
                      }
                      else{
                        if ($custom == 'address'){
                          $custom_other = $address['shipping_address_1'] . ' ' . $address['shipping_address_2'];
                        }
                        if ($custom == 'firstname_x'){
                            $custom_other = $address['shipping_firstname'];
                        }
                        if ($custom == 'lastname_x'){
                            $custom_other = $address['shipping_lastname'];
                        }
                        if ($custom == 'firstname_lastname'){
                            $custom_other = $address['shipping_firstname'] . ' ' . $address['shipping_lastname'];
                        }
                        if ($custom == 'city'){
                          $custom_other = $address['shipping_city'];
                        }
                        if ($custom == 'company'){
                          $custom_other = $address['shipping_company'];
                        }
                        if ($custom == 'postcode'){
                          $custom_other = $address['shipping_postcode'];
                        }
                        if ($custom == 'zone'){
                          $custom_other = $address['shipping_zone'];
                        }
                        if ($custom == 'zap'){
                          $custom_other = ', ';
                        }
                        else{
                          if (isset($address[$custom])) {
                        $custom_other = $address[$custom];
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
 <p>Всего наименований ' . $i . ', на сумму ' . number_format(floatval(ltrim(preg_replace('/[^0-9.]/', '', str_replace(" ","", $this->currency->format($address['total']))), " .")), 2, '.', ' ') . ' руб.<br/>
  <b>' . $numbers . '</b></p>
 ' . $nds . '
 ' . $comment . '
 ' . $image . '
';
                    require_once("system/extra/dompdf/dompdf_config.inc.php");
                    $dompdf = new DOMPDF();
                    $dompdf->load_html($html);
                    $dompdf->render();
                    $dompdf->stream("zakaz_" . $_GET['onum'] . ".pdf");
                } else {
                    echo "No data";
                }
            } else {
                echo "No Data";
            }
        } else {
            echo "No Data";
        }
    }
    public function view() {
        
        $this->load->language('account/cash_plusplus');
        $this->load->model('account/cash_plusplus');
        if (isset($_GET['sum']) & isset($_GET['onum']) & isset($_GET['plat'])) {
            $address = $this->model_account_cash_plusplus->getOrderAdress($_GET['onum']);
            if (isset($address['firstname']) & isset($address['lastname'])) {
                $platp = $address['firstname'] . " " . $address['lastname'];
                if ($_GET['plat'] == $platp & ($_GET['sum'] == (intval(str_replace(" ","", $address['total']) + 1)) || $_GET['sum'] == intval(str_replace(" ","", $address['total']))) & $_GET['onum'] === $address['order_id']) {
                    $this->data['order_id'] = $_GET['onum'];
                    $this->data['inn']      = nl2br($this->config->get('cash_plusplus_inn_' . $this->config->get('config_language_id')));
                    $this->data['bank']     = nl2br($this->config->get('cash_plusplus_bank_' . $this->config->get('config_language_id')));
                    $this->data['rs']       = nl2br($this->config->get('cash_plusplus_rs_' . $this->config->get('config_language_id')));
                    $this->data['bankuser'] = nl2br($this->config->get('cash_plusplus_bankuser_' . $this->config->get('config_language_id')));
                    $this->data['bik']      = nl2br($this->config->get('cash_plusplus_bik_' . $this->config->get('config_language_id')));
                    $this->data['ks']       = nl2br($this->config->get('cash_plusplus_ks_' . $this->config->get('config_language_id')));
                    $this->data['amount']   = $_GET['sum'];
                    $this->data['name']     = $_GET['plat'];
                    $this->data['ur']       = nl2br($this->config->get('cash_plusplus_ur_' . $this->config->get('config_language_id')));
                    $this->data['kpp']      = nl2br($this->config->get('cash_plusplus_kpp_' . $this->config->get('config_language_id')));
                    $this->data['tel']      = nl2br($this->config->get('cash_plusplus_tel_' . $this->config->get('config_language_id')));  
                    $this->data['logo']     = nl2br($this->config->get('cash_plusplus_logo'));
                    $this->data['products'] = $this->model_account_cash_plusplus->getOrderProducts($this->request->get['onum']);
                    $this->data['date']     = date($this->language->get('date_format_short'), strtotime($address['date_modified']));
                    $this->data['image']    = nl2br($this->config->get('cash_plusplus_image'));
                    $this->data['text']     = nl2br($this->config->get('cash_plusplus_etext_' . $this->config->get('config_language_id')));
                    $this->data['address']  = $address;
                    $this->data['totals']   = $this->model_account_cash_plusplus->getOrderTotals($this->request->get['onum']);
                    $this->data['i']        = 0;
                    $this->data['nds']      = $this->language->get('text_nds');
                    $numbers = $this->model_account_cash_plusplus->numbers(ltrim(preg_replace('/[^0-9.]/', '', str_replace(" ","", $this->currency->format($address['total']))), " ."));
                    $this->data['numbers'] = $this->language->get('text_itogo')  . $numbers;
                    $this->data['comment'] = htmlspecialchars_decode($this->config->get('cash_plusplus_comment_' . $this->config->get('config_language_id')));

                    //Счет

                    $invoice = $this->config->get('cash_plusplus_invoi');
                    if ($invoice == 'invoi_zakaz'){
                        $this->data['invoi'] = $_GET['onum'];
                    }
                    if ($invoice == 'invoi_zakazd'){
                         $this->data['invoi'] = $_GET['onum'] . ' от ' . date($this->language->get('date_format_short'), strtotime($address['date_modified'])) . 'г.';
                    }
                    if ($invoice == 'invoi_zakazdc'){
                         $this->data['invoi'] = $_GET['onum'] . ' от ' . date($this->language->get('date_format_short'), strtotime($address['date_added'])) . 'г.';
                    }
                    if ($invoice == 'invoi_noinvoice'){
                        if ($address['invoice_no'] != 0){
                            $this->data['invoi'] = $address['invoice_prefix'] . $address['invoice_no'];
                        }
                        else {
                            $this->data['invoi'] = $_GET['onum'];
                        }
                    }
                    if ($invoice == 'invoi_noinvoiced'){
                        if ($address['invoice_no'] != 0){
                            $this->data['invoi'] = $address['invoice_prefix'] . $address['invoice_no'] . ' от ' . date($this->language->get('date_format_short'), strtotime($address['date_modified'])). 'г.';
                        }
                        else {
                            $this->data['$invoi'] = $_GET['onum'];
                        }
                    }
                    if ($invoice == 'invoi_noinvoicedc'){
                        if ($address['invoice_no'] != 0){
                            $this->data['invoi'] = $address['invoice_prefix'] . $address['invoice_no'] . ' от ' . date($this->language->get('date_format_short'), strtotime($address['date_added'])). 'г.';
                        }
                        else {
                            $this->data['invoi'] = $_GET['onum'];
                        }
                    }
                    
                    //Плательщик

                    $chelpay = $this->config->get('cash_plusplus_chelpay');
                    if ($chelpay == 'chelpay_fio'){
                         $this->data['addrp'] = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                    }
                    if ($chelpay == 'chelpay_company'){
                        if ($address['payment_company']){
                         $this->data['addrp'] = $address['payment_company'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                        }
                        else{
                          $this->data['addrp'] = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                        }
                    }
                    if ($chelpay == 'chelpay_company_fio'){
                        if($address['payment_company']){
                           $this->data['addrp'] = $address['payment_company'] . ", " . $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                        }
                         else{
                          $this->data['addrp'] = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                        }
                    }
                    if ($chelpay == 'chelpay_fio_company'){
                      if($address['payment_company']){
                         $this->data['addrp'] = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_company'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                       }
                       else{
                        $this->data['addrp'] = $address['payment_firstname'] . " " . $address['payment_lastname'] . ", " . $address['payment_city'] . ", " . $address['payment_address_1'] . " " . $address['payment_address_2'];
                       }
                    }
                    
                    if ($chelpay == 'chelpay_custom'){
                      $customs = explode(',', (str_replace(" ","",$this->config->get('cash_plusplus_custom'))));
                      $customz = "";
                        foreach ($customs as $custom) {
                          if (substr_count($custom, "custom_") ){
                        $this->load->model('tool/simplecustom');
                        $customx = $this->model_tool_simplecustom->getOrderField($address['order_id'], $custom);
                        if ($customx){
                        $customz .= ' ' . $customx;
                        }
                        if($customx === ''){
                          $customx = $this->model_tool_simplecustom->getPaymentAddressField($address['order_id'], $custom);
                          if ($customx){
                          $customz .= ' ' . $customx;
                          }
                        }
                      }
                      else{
                        if ($custom == 'address'){
                          $custom_other = $address['payment_address_1'] . ' ' . $address['payment_address_2'];
                        }
                        if ($custom == 'firstname_x'){
                            $custom_other = $address['payment_firstname'];
                        }
                        if ($custom == 'lastname_x'){
                            $custom_other = $address['payment_lastname'];
                        }
                        if ($custom == 'firstname_lastname'){
                            $custom_other = $address['payment_firstname'] . ' ' . $address['payment_lastname'];
                        }
                        if ($custom == 'city'){
                          $custom_other = $address['payment_city'];
                        }
                        if ($custom == 'company'){
                          $custom_other = $address['payment_company'];
                        }
                        if ($custom == 'postcode'){
                          $custom_other = $address['payment_postcode'];
                        }
                        if ($custom == 'zone'){
                          $custom_other = $address['payment_zone'];
                        }
                        if ($custom == 'zap'){
                          $custom_other = ', ';
                        }
                        else{
                          if (isset($address[$custom])) {
                        $custom_other = $address[$custom];
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
                      $this->data['addrp'] = preg_replace('~,  ,+~', ',', (trim(preg_replace('~, ,+~', ',', $customz), ', ')));
                       
                       }
                    //Грузополучатель
                    $gruzpay = $this->config->get('cash_plusplus_gruzpay');
                    if ($gruzpay == 'gruzpay_fio'){
                         $this->data['addrg'] = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                    }
                    if ($gruzpay == 'gruzpay_company'){
                        if ($address['shipping_company']){
                         $this->data['addrg'] = $address['shipping_company'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                        }
                        else{
                          $this->data['addrg'] = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                        }
                    }
                    if ($gruzpay == 'gruzpay_company_fio'){
                        if($address['shipping_company']){
                           $this->data['addrg'] = $address['shipping_company'] . ", " . $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                        }
                         else{
                          $this->data['addrg'] = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                        }
                    }
                    if ($gruzpay == 'gruzpay_fio_company'){
                      if($address['shipping_company']){
                         $this->data['addrg'] = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_company'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                       }
                       else{
                        $this->data['addrg'] = $address['shipping_firstname'] . " " . $address['shipping_lastname'] . ", " . $address['shipping_city'] . ", " . $address['shipping_address_1'] . " " . $address['shipping_address_2'];
                       }
                    }

                    if ($gruzpay == 'gruzpay_custom'){
                      $customs = explode(',', (str_replace(" ","",$this->config->get('cash_plusplus_custom_2'))));
                      $customz = "";
                        foreach ($customs as $custom) {
                          if (substr_count($custom, "custom_") ){
                          $this->load->model('tool/simplecustom');
                        $customx = $this->model_tool_simplecustom->getOrderField($address['order_id'], $custom);
                        if ($customx){
                        $customz .= ' ' . $customx;
                        }
                        if($customx === ''){
                          $customx = $this->model_tool_simplecustom->getShippingAddressField($address['order_id'], $custom);
                          if ($customx){
                          $customz .= ' ' . $customx;
                          }
                        }
                      }
                      else{
                        if ($custom == 'address'){
                          $custom_other = $address['shipping_address_1'] . ' ' . $address['shipping_address_2'];
                        }
                        if ($custom == 'firstname_x'){
                            $custom_other = $address['shipping_firstname'];
                        }
                        if ($custom == 'lastname_x'){
                            $custom_other = $address['shipping_lastname'];
                        }
                        if ($custom == 'firstname_lastname'){
                            $custom_other = $address['shipping_firstname'] . ' ' . $address['shipping_lastname'];
                        }
                        if ($custom == 'city'){
                          $custom_other = $address['shipping_city'];
                        }
                        if ($custom == 'company'){
                          $custom_other = $address['shipping_company'];
                        }
                        if ($custom == 'postcode'){
                          $custom_other = $address['shipping_postcode'];
                        }
                        if ($custom == 'zone'){
                          $custom_other = $address['shipping_zone'];
                        }
                        if ($custom == 'zap'){
                          $custom_other = ', ';
                        }
                        else{
                          if (isset($address[$custom])) {
                        $custom_other = $address[$custom];
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
                      $this->data['addrg'] = preg_replace('~,  ,+~', ',', (trim(preg_replace('~, ,+~', ',', $customz), ', ')));
                       
                       }

                    
                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/cash_plusplus.tpl')) {
                        
                        $this->template = $this->config->get('config_template') . '/template/account/cash_plusplus.tpl';
                    } else {
                        
                        $this->template = 'default/template/account/cash_plusplus.tpl';
                    }
                    
                    
                    
                    $this->response->setOutput($this->render());
                } else {
                    echo "No data";
                }
            } else {
                echo "No Data";
            }
        } else {
            echo "No Data";
        }
    }
}