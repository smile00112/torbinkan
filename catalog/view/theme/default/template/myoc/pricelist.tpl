<?php echo $header; ?>
<?php if(isset($error_customer_group)) { ?>
<div class="attention"><?php echo $error_customer_group; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<h1><?php echo $heading_title; ?></h1>
	<?php if (!isset($error_customer_group)) { ?>
	<div class="filter-control">
		<?php if($cat_filter) { ?>
		<div class="fleft">
			<strong><?php echo $text_category; ?></strong> <select name="category_id" onchange="location = this.value;">
				<?php foreach($categories as $category){ ?>
				<option value="<?php echo $category['href']; ?>"<?php if($category['category_id'] == $catid) { ?> selected="selected"<?php } ?>><?php echo $category['path']; ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } ?>
		<div class="fright">
			<strong><?php echo $text_limit; ?></strong> <select name="limit" onchange="location = this.value;">
				<?php foreach($limits as $limit_value) { ?>
				<option value="<?php echo $limit_value['href']; ?>"<?php if($limit == $limit_value['value']) { ?> selected="selected"<?php } ?>><?php echo $limit_value['value']; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="print-control">
		<div class="fright">
			<?php if($print) { ?>
			<a href="<?php echo $print; ?>" class="button" target="_blank"><span><?php echo $text_print; ?></span></a>
			<?php } ?>
			<?php if($pdf) { ?>
			<a href="<?php echo $pdf; ?>" class="button" target="_blank"><span><?php echo $text_pdf; ?></span></a>
			<?php } ?>
		</div>
	</div>
	<div class="pricelist">
		<table>
			<thead>
				<tr>
					<?php foreach ($columns as $column) { ?>
					<?php if($column['for_pricelist']) { ?>
					<th class="<?php echo $column['type']; ?>">
						<?php if($column['type'] == 'checkbox') { ?>
						<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
						<?php } else { ?>
						<?php if($column['sortable']) { ?>
						<a href="<?php echo $column['sortable']; ?>"><?php echo $column['name']; ?></a>
						<?php } else { ?>
						<?php echo $column['name']; ?>
						<?php } ?>
						<?php } ?>
					</th>
					<?php } ?>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php if(!empty($products)) { ?>
				<?php foreach($products as $product_id => $product) { ?>
				<tr id="product<?php echo $product_id; ?>-info " class="backcolor">
					<?php foreach ($columns as $column) { ?>
					<?php if($column['for_pricelist']) { ?>
					<td class="<?php echo $column['type']; ?>">
						<?php if($column['type'] == 'checkbox') { ?>
						<input type="checkbox" name="selected[]" value="<?php echo $product_id; ?>" />
						<?php } elseif($column['type'] == 'num') { ?>
						<?php echo $product['num']; ?>.
						<?php } elseif($column['type'] == 'image') { ?>
						<?php if($product['popup']) { ?>
						<a href="<?php echo $product['popup']; ?>" class="fancybox colorbox" rel="fancybox"><?php } ?>
						<img src="<?php echo $product['image']; ?>" alt="no_image" title="<?php echo $product['name']; ?>" />
						<?php if($product['popup']) { ?></a><?php } ?>
						<?php } elseif($column['type'] == 'name') { ?>
							<?php if($column['barcode'] == '0') { ?>
							<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
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
						<span class="price-old"><?php echo $product['price']; ?></span><br />
						<span class="price-new"><?php echo $product['special']; ?></span>
						<?php } ?>
						<?php if ($product['discounts'] && !$product['special']) { ?>
						<br />
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



                        <!--
						<?php echo $text_qty; ?> <input type="text" id="qty-<?php echo $product_id; ?>" style="width:40px;" name="quantity" size="2" value="<?php echo $product['minimum']; ?>" />
						<input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" /><br />
						<input type="button" value="<?php echo $button_cart; ?>" id="addtocart-<?php echo $product_id; ?>" class="addtocart-button button" />

                        -->
                        <input type="button" value="Купить" onclick="location.href='<?php echo $product['href']; ?>';" class="addtocart-button button" />
						<?php if($add_wishlist || $add_compare) { ?>
 						<div class="or">&nbsp;&nbsp;<?php echo $text_or; ?>&nbsp;&nbsp;</div>
						<span>
							<?php if($add_wishlist) { ?>
							<a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>
							<br />
							<?php } ?>
							<?php if($add_compare) { ?>
							<a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a>
							<?php } ?>
						</span>
						<?php } ?>
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
					<?php } //end if($column['for_pricelist']) ?>

					<?php } //end foreach($columns) ?>
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


				<?php } //end foreach($products) ?>
			<?php } else { ?>
				<tr><td colspan="<?php echo count($columns); ?>" class="tcenter"><?php echo $text_empty; ?></td></tr>
			<?php } ?>
			<tbody>
		</table>
		<div class="print-control">

			<div class="fright">
				<?php if($print) { ?>
				<a href="<?php echo $print; ?>" class="button" target="_blank"><span><?php echo $text_print; ?></span></a>
				<?php } ?>
				<?php if($pdf) { ?>
				<a href="<?php echo $pdf; ?>" class="button" target="_blank"><span><?php echo $text_pdf; ?></span></a>
				<?php } ?>
			</div>
		</div>
		<div class="pagination"><?php echo $pagination; ?></div>
	</div>
	<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
	<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
	<script type="text/javascript"><!--
	$(".but-multicart").click(function () { //batch add to cart
		$(":input[name^=selected]:checked").each(function () {
			var product_id = $(this).val();
			add_to_cart(product_id);
		});

		return false;
	});

	$(".addtocart-button").click(function () { //single add to cart
		var product_id = $(this).attr("id").substring($(this).attr("id").indexOf("-")+1);
		add_to_cart(product_id);

		return false;
	});

	function add_to_cart(product_id) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: $('#product' + product_id + '-info input[type=\'text\'], #product' + product_id + '-info input[type=\'hidden\'], #product' + product_id + '-info input[type=\'radio\']:checked, #product' + product_id + '-info input[type=\'checkbox\']:checked, #product' + product_id + '-info select, #product' + product_id + '-info textarea'),
			dataType: 'json',
			success: function(json) {
				$('.success, .warning, .attention, .information').remove();

				<?php if($show_option) { ?>
				if (json['error']) {
					if (json['error']['option']) {
						$('#product' + product_id + '-info .error').remove();

						for (i in json['error']['option']) {
							$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
						}
					} else {
						for (i in json['error']) {
							$('#option-' + i).after('<span class="error">' + json['error'][i] + '</span>');
						}
					}
				}
				<?php } else { ?>
				if (json['redirect']) {
					location = json['redirect'];
				}
				<?php } ?>

				if (json['success']) {
					$('#product' + product_id + '-info .error').remove();

					$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					$('.success').fadeIn('slow');

					$('#cart-total, #cart_total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				} 
			}
		});
	}

	if(typeof $.fancybox == 'function') {
		$('.fancybox').fancybox({cyclic: true});
	}
	if(typeof $.colorbox == 'function') {
		$('.colorbox').colorbox({
			overlayClose: true,
			opacity: 0.5
		});
	}

	$(document).ready(function() {
		if ($.browser.msie && $.browser.version == 6) {
			$('.date, .datetime, .time').bgIframe();
		}

		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});
		$('.time').timepicker({timeFormat: 'h:m'});
	});
	//--></script>	
	<?php if(!empty($products)) { ?>
	<?php foreach($products as $product_id => $product) { ?>
		<?php if($product['options']) { ?>
		<?php foreach ($product['options'] as $option) { ?>
			<?php if ($option['type'] == 'file') { ?>
			<script type="text/javascript"><!--
			new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
				action: 'index.php?route=product/product/upload',
				name: 'file',
				autoSubmit: true,
				responseType: 'json',
				onSubmit: function(file, extension) {
					$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
					$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);
				},
				onComplete: function(file, json) {
					$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);
					
					$('.error').remove();
					
					if (json['success']) {
						alert(json['success']);
						
						$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);
					}
					
					if (json['error']) {
						$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
					}
					
					$('.loading').remove();	
				}
			});
			//--></script>
			<?php } ?>
		<?php } ?>
		<?php } ?>
	<?php } ?>
	<?php } ?>
	<?php } else { ?>
	<div class="content"><?php echo $text_empty; ?></div>
	<div class="buttons">
		<div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
	</div>
	<?php } ?>
	
</div>
<?php echo $footer; ?>