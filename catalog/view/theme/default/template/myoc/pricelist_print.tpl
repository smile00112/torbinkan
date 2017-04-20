<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/myoc/pricelist_print.css" />
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php echo $google_analytics; ?>
</head>
<body>
<div id="container">
	<table class="tbl">
		<tr>
			<td class="tleft">
				<?php if ($logo) { ?>
				<div id="logo"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></div>
				<?php } ?>
				<p><?php echo $store_title; ?></p>
				<p><?php echo $store_url; ?></p>
				<?php if($cat_filter) { ?>
				<p><strong><?php echo $text_category; ?></strong>
				<?php foreach($categories as $category){ ?>
				<?php if($category['category_id'] == $catid) { ?>
				<?php echo $category['path']; ?>
				<?php break; } ?>
				<?php } ?>
				</p>
				<?php } ?>
			</td>
			<td class="tright">
				<p><?php echo $store_address; ?></p>
				<p><?php echo $store_email; ?></p>
				<p><?php echo $store_telephone; ?></p>
			</td>
		</tr>
	</table>
	<div class="pricelist">
		<table>
			<thead>
				<tr>
					<?php foreach ($columns as $column) { ?>
					<?php if($column['for_' . $output]) { ?>
					<th class="<?php echo $column['type']; ?>">
						<?php if($column['type'] == 'checkbox') { ?>
						<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
						<?php } else { ?>
						<?php echo $column['name']; ?>
						<?php } ?>
					</th>
					<?php } ?>
					<?php } ?>
				</tr>
			</thead>
		<tbody>
			<?php if(!empty($products)) { ?>
				<?php foreach($products as $product_id => $product) { ?>
				<tr id="product<?php echo $product_id; ?>-info">
					<?php foreach ($columns as $column) { ?>
					<?php if($column['for_' . $output]) { ?>
					<td class="<?php echo $column['type']; ?>">
						<?php if($column['type'] == 'checkbox') { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $product_id; ?>" />
						<?php } elseif($column['type'] == 'num') { ?>
						<?php echo $product['num']; ?>.
						<?php } elseif($column['type'] == 'image') { ?>
						<img src="<?php echo $product['image']; ?>" alt="no_image" title="<?php echo $product['name']; ?>" />
						<?php } elseif($column['type'] == 'name') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['name']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['name'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['name']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'description') { ?>
						<?php echo $product['description']; ?>
						<?php } elseif($column['type'] == 'model') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['model']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['model'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['model']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'sku') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['sku']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['sku'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['sku']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'upc') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['upc']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['upc'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['upc']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'ean') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['ean']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['ean'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['ean']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'jan') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['jan']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['jan'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['jan']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'isbn') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['isbn']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['isbn'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['isbn']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'mpn') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['mpn']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['mpn'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['mpn']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'manufacturer') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<?php echo $product['manufacturer']; ?>
							<?php } else { ?>
							<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $product['manufacturer'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $product['manufacturer']; ?>" />
							<?php } ?>
						<?php } elseif($column['type'] == 'price') { ?>
						<?php if($product['price']) { ?>
						<?php if(!$product['special']) { ?>
						<span class="price"><?php echo $product['price']; ?></span>
						<?php } else { ?>
						<span class="price-old"><del><?php echo $product['price']; ?></del></span><br />
						<span class="price-new"><?php echo $product['special']; ?></span>
						<?php } ?>
						<?php if ($product['discounts'] && !$product['special']) { ?>
						<br /><br />
						<div class="discount">
						<?php foreach ($product['discounts'] as $discount) { ?>
						<?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br /><br />
						<?php } ?>
						</div>
						<?php } ?>
						<?php } ?>
						<?php } elseif($column['type'] == 'quantity') { ?>
						<span class="<?php if(!$product['quantity']) { ?>nostock<?php } ?>"><?php echo $product['quantity']; ?></span>
						<?php } elseif($column['type'] == 'stock_status') { ?>
						<span class="<?php if(!$product['quantity']) { ?>nostock<?php } ?>"><?php echo $product['stock_status']; ?></span>
						<?php } elseif($column['type'] == 'minimum') { ?>
						<?php echo $product['minimum']; ?>
						<?php } elseif($column['type'] == 'rating') { ?>
						<img src="<?php echo $product['rating_img']; ?>" alt="<?php echo $product['rating']; ?> *" />
						<?php } elseif($column['type'] == 'dimension') { ?>
						<?php echo $product['length']; ?> x
						<?php echo $product['width']; ?> x
						<?php echo $product['height']; ?>
						<?php } elseif($column['type'] == 'weight') { ?>
						<?php echo $product['weight']; ?>
						<?php } elseif($column['type'] == 'date_added') { ?>
						<?php echo $product['date_added']; ?>
						<?php } elseif($column['type'] == 'action') { ?>

						<?php } elseif(substr($column['type'], 0, 4) == 'attr') { //end if($column['type'] == 'action') ?>
							<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
								<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
									<?php if($attribute['attribute_id'] == substr($column['type'], 4)) { ?>
										<?php if($column['barcode'] == '0') { ?>
										<?php echo $attribute['text']; ?>
										<?php } else { ?>
										<img src="data:image/png;base64,<?php echo $this->model_myoc_pricelist->getBarcode(array('type' => $column['barcode'], 'code' => $attribute['text'], 'width' => $barcode_width, 'height' => $barcode_height, 'fontsize' => $barcode_fontsize, 'zoom' => $barcode_zoom)); ?>" alt="no_barcode" title="<?php echo $attribute['text']; ?>" />
										<?php } ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</td>
					<?php } ?>
					<?php } ?>
				</tr>


<!-- options -->
						<?php if($product['options']) { ?>
						<?php foreach ($product['options']['0']['option_value'] as $option) { ?>
<tr id="product<?php echo $product['product_id']; ?>-info">
    <td class="sku"><?php echo $option['model_option']; ?></td>
    <td class="name"><?php echo $option['name']; ?></td>
    <td class="upc"><?php echo $option['size']; ?></td>
    <td class="upc"><?php echo $option['unit_option']; ?></td>
    <td class="upc"><?php echo $option['amount_option']; ?></td>
    <td class="upc"><?php echo $option['price']; ?></td>
    <td class="action">
        <!-- options -->
        <!-- end options -->
        <!--Qty:
        <input type="text" id="qty-492" style="width:40px;" name="quantity" size="2" value="1">
        <input type="hidden" name="product_id" size="2" value="492">
        <br>
        <input type="button" value="Купить" id="addtocart-492" class="addtocart-button button">-->
    </td>
</tr>

						<?php } ?>
						<?php } ?>
						<!-- end options -->

				<?php } ?>
			<?php } else { ?>
				<tr><td colspan="<?php echo count($columns); ?>" class="tcenter"><?php echo $text_empty; ?></td></tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
window.print();
</script>
</body>
</html>