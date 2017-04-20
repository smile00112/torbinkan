<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<title>Счет. Заказ № <?php echo $order_id ?></title>
<style type="text/css">
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
</head>
<body>
  <p class="center etext">
    <?php echo $text ; ?>
  </p>
<?php
if ($logo){ ?>
          <p>
            <span>
              <IMG class="logo" style="margin-right:10px;" src="image/<?php echo $logo; ?>"/>
            </span>
          </p>
<?php } ?>
  <div class="head-s">
    <h2><?php echo $bank; ?></h2>
    <h2><?php echo $ur; ?></h2>
    <h2><?php echo $tel; ?></h2>
  </div>
  <p class="clear center bold">Образец заполнения платежного поручения</p>
  <table border="1" cellspacing="0" cellpadding="2px" style="border-collapse:collapse">
    <tr>
      <td>
        <p class="s2">ИНН <?php echo $inn; ?></p>
      </td>
      <td>
        <p class="s2"><? if ($kpp) { ?> КПП <?php  echo $kpp; } ?></p>
      </td>
      <td  rowspan="2" valign="bottom">
        <p class="s2">Сч.№</p>
      </td>
      <td rowspan="2" valign="bottom">
        <p class="s2"><?php echo $rs; ?></p>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <p class="s2">Получатель</p>
        <p class="s2"><?php echo $bank; ?></p>
      </td>

    </tr>
    <tr>
      <td colspan="2">
        <p class="s2">Банк получателя</p>
        <p class="s2"><?php echo $bankuser; ?></p>
      </td>
      <td>
        <p class="s2">БИК</p>
        <p class="s2">Сч.№</p>
      </td>
      <td>
        <p class="s2"><?php echo $bik; ?></p>
        <p class="s2"><?php echo $ks; ?></p>
      </td>
    </tr>
  </table>
  <h1>СЧЕТ № <?php echo $invoi; ?></h1>
  <p>
    Плательщик: <?php echo $addrp; ?>
    <br/>
    Грузополучатель: <?php echo $addrg; ?>
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
    <?php if($this->config->get('cash_plusplus_copey')){ $valu = $this->currency->format(0, $address['currency_code'], $address['currency_value']);
    $valu = preg_replace('/[^a-zа-яё.\s]+/iu', '', $valu);} ?>
      <?php foreach ($products as $product) { ?>
        <tr>
            <td class="center"><p><?php $i+=1 ; echo $i; ?></p></td>
            <td><p><?php echo $product['name'];
                $options = $this->model_account_cash_plusplus->getOrderOptions($this->request->get['onum'], $product['order_product_id']);
                if ($options){
              foreach ($options as $option) { ?>
                  <br/><span class="smal"><small>- <?php echo $option['name']; ?> : <?php echo $option['value']; ?> </small></span>
                <?php  }
                }
                if($this->config->get('cash_plusplus_copey')){
                 $product['total'] = number_format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), 2, '.', '') . $valu;
                  $product['price'] = number_format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), 2, '.', ''). $valu;

               }
                else{ 
         $product['total'] = $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $address['currency_code'], $address['currency_value']);
         $product['price'] = $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $address['currency_code'], $address['currency_value']);
                }
                ?>
        </p></td>
            <td class="center"><p>шт.</p></td>
            <td class="textr"><p><?php echo $product['quantity']; ?></p></td>
            <td class="textr"><p><?php echo $product['price']; ?></p></td>
            <td class="textr"><p><?php echo $product['total']; ?></p></td>
          </tr>
        <?php } 
        foreach ($totals as $total){ 
        if($this->config->get('cash_plusplus_copey')){
          $total['text'] = number_format(preg_replace('/[^0-9.]+/iu', '', $total['text']), 2, '.', '') . $valu;
        } ?>
          <tr>
                <td colspan="5" class="textr b0"><p><?php echo $total['title']; ?></p></td>
                <td class="textr"><p><?php echo $total['text']; ?></p></td>
              </tr>
        <?php } ?>
    </tbody>
  </table>
  <p>Всего наименований <?php echo $i ; ?>, на сумму <?php echo number_format(floatval(ltrim(preg_replace('/[^0-9.]/', '', str_replace(" ","", $this->currency->format($address['total']))), " .")), 2, '.', ' '); ?> руб.<br/>
  <b><?php echo $numbers ?></b></p>
  <?php if ($this->config->get('cash_plusplus_nds')) { ?>
  <p><?php echo $nds ?></p>
  <?php } ?>
  <?php if ($comment){
  echo $comment;
  } ?>
<?php if ($image){ ?>
            <div>
              <IMG src="image/<?php echo $image;  ?>"/>
            </div>
       <?php } ?>
</body>
</html>