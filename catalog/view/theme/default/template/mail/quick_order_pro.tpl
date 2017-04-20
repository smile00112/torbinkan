<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
</head>
<body style="font-size:12px;color:#000000;font-family:Arial, Helvetica, sans-serif">
<div id="container" style="width:680px">
  <p style="margin-top:0;margin-bottom:20px;font-size:12px"><?php echo $text_greeting; ?></p>
  <p style="margin-top:0;margin-bottom:20px;font-size:12px"><?php echo $text_greeting_detail; ?></p>
  <table class="list" style="border-collapse:collapse;width:100%;border-top:1px solid #DDDDDD;border-left: 1px solid #DDDDDD;margin-bottom:20px;">
    <thead>
      <tr>
        <td class="left" style="text-align:left;border-right: 1px solid #DDDDDD;border-bottom: 1px solid #DDDDDD;background-color: #EFEFEF;padding:7px;color:#222222;font-weight:bold;font-size:12px"><?php echo $text_order_detail; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left" style="text-align:left;padding:7px;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px">
		  <?php foreach ($fields as $field) { ?>
		  <b><?php echo $field['name']; ?></b> <?php echo $field['value']; ?><br />
		  <?php } ?>
		  <?php if (!empty($date_added)) { ?>
		  <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
		  <?php } ?>
		  <?php if (!empty($ip)) { ?>
          <b><?php echo $text_ip; ?></b> <?php echo $ip; ?><br />
		  <?php } ?>
		</td>
      </tr>
    </tbody>
  </table>
  <?php if ($comment) { ?>
  <table class="list" style="border-collapse:collapse;width:100%;border-top:1px solid #DDDDDD;border-left: 1px solid #DDDDDD;margin-bottom:20px">
    <thead>
      <tr>
        <td class="left" style="text-align:left;border-right: 1px solid #DDDDDD;border-bottom: 1px solid #DDDDDD;background-color:#EFEFEF;padding:7px;color:#222222;font-weight:bold;font-size:12px"><?php echo $text_instruction; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left" style="text-align:left;padding:7px;border-right: 1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px"><?php echo $comment; ?></td>
      </tr>
    </tbody>
  </table>
  <?php } ?>
  <table class="list" style="border-collapse:collapse;width:100%;border-top:1px solid #DDDDDD;border-left: 1px solid #DDDDDD;margin-bottom:20px;">
    <thead>
      <tr>
        <td class="left" style="text-align:left;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;background-color:#EFEFEF;padding:7px;color:#222222;font-weight:bold;font-size:12px"><?php echo $text_product; ?></td>
        <td class="left" style="text-align:left;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;background-color:#EFEFEF;padding:7px;color:#222222;font-weight:bold;font-size:12px"><?php echo $text_model; ?></td>
        <td class="right" style="text-align:right;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;background-color:#EFEFEF;padding:7px;color:#222222;font-weight:bold;font-size:12px"><?php echo $text_quantity; ?></td>
        <td class="right" style="text-align:right;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;background-color:#EFEFEF;padding:7px;color:#222222;font-weight:bold;font-size:12px"><?php echo $text_price; ?></td>
        <td class="right" style="text-align:right;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;background-color:#EFEFEF;padding:7px;color:#222222;font-weight:bold;font-size:12px"><?php echo $text_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="left" style="text-align:left;padding:7px;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px"><a style="text-decoration:underline;font-size: 12px" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?></td>
        <td class="left" style="text-align:left;padding:7px;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px"><?php echo $product['model']; ?></td>
        <td class="right" style="text-align:right;padding:7px;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px"><?php echo $product['quantity']; ?></td>
        <td class="right" style="text-align:right;padding:7px;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px"><?php echo $product['price']; ?></td>
        <td class="right" style="text-align:right;padding:7px;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="right" style="text-align:right;padding:7px;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px"><b><?php echo $total['title']; ?>:</b></td>
        <td class="right" style="text-align:right;padding:7px;border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;font-size:12px"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
  <p style="margin-top:0;margin-bottom:20px;font-size:12px"><?php echo $text_footer; ?></p>
</div>
</body>
</html>
