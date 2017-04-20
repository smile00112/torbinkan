<?php echo $header; ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
 <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div> 
 
  <div class="product-info">
  <h1><?php echo $heading_title; ?></h1> 
    <?php if ($thumb || $images) { ?>
    <div class="left">
     <?php if ($thumb) { ?>
      <div class="image">
       <a title="<?php echo $heading_title; ?>"  <?php if ($this->config->get('product_zoom') !== '1') { ?> href="<?php echo $popup; ?>" class="colorbox" <?php } ?>>
       <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>"
        <?php if ($this->config->get('product_zoom') == '1') { ?>  id="main-image" data-zoom-image="<?php echo $popup; ?>"  <?php } ?> >
        
       </a></div>
      <?php } ?>
      
     <?php $i=1; if ($images) { ?>
					
						<div class="image-additional owl-carousel" id="add-gallery">
							<?php if (($thumb) && (isset($smallimg)) && ($this->config->get('product_zoom') == '1')) { ?>
							<div data-index="0">
								<a title="<?php echo $heading_title; ?>" data-image="<?php echo $thumb; ?>" data-zoom-image="<?php echo $popup; ?>">
                                <img src="<?php echo $smallimg; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" ></a>
							</div>
							<?php } ?>
                            
                            
							<?php foreach ($images as $image) { ?>
                            <?php if (isset($image['thumb1'])) { ?>
							<div data-index="<?php echo $i; ?>">
								<a title="<?php echo $heading_title; ?>" 
                                 <?php if ($this->config->get('product_zoom') == '1') { ?>
                                data-image="<?php echo $image['thumb1']; ?>" data-zoom-image="<?php echo $image['popup']; ?>"
                                 <?php } else { ?>
                                href="<?php echo $image['popup']; ?>" class="colorbox"
                                <?php } ?>>
                                <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" ></a>
							</div>
                            <?php } ?>
							<?php $i++; } ?>
                            
						</div>
					
				<?php } ?>
				<div id="options"></div>
     </div>
    <?php } ?> 
  
 
    
    <div class="right">
    
     
     <div class="general_info">  

      
     
    
    
      <div class="description">
        <?php if ($manufacturer) { ?>
        
             <?php if (isset($manufacturers_img)) { ?>
            <?php echo ($manufacturers_img) ? '<span> <a href="'.$manufacturers.'"><img src="'.$manufacturers_img.'"  title="'.$manufacturer.'" /></a></span>' : '' ;?>
             <?php } else { ?>
             <span><?php echo $text_manufacturer; ?></span>
             <?php } ?>
        <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a><br />
        <?php } ?>
        <span><?php echo $text_model; ?></span> <?php echo $model; ?><br />
        <?php if ($reward) { ?>
        <span><?php echo $text_reward; ?></span> <?php echo $reward; ?><br />
        <?php } ?>
        <span><?php echo $text_stock; ?></span> <?php echo $stock; ?></div>
        
        
       <?php if ($review_status) { ?>
      <div class="review">
        <div><img src="catalog/view/theme/mattimeo/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;
                <a onclick="$('a[href=\'#tab-review\']').trigger('click'); $('html, body').animate({scrollTop: $('#tabs').offset().top}, 800);"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click'); $('html, body').animate({scrollTop: $('#tabs').offset().top}, 800);"><?php echo $text_write; ?></a></div>

      </div>
      <?php } ?> 
      </div>  
        <div class="share">
		<!-- AddThis Button BEGIN -->
			<div class="share42init" data-image="<?php echo $thumb; ?>"></div>
			<script type="text/javascript" src="catalog/view/javascript/jquery/share42/share42.js"></script> 
		<!-- AddThis Button END --> 
        </div>

       <div class="cart">
        
         <table class="gty"><tr><td><input style="display:none" type="text" name="quantity" id="htop" size="2" value="<?php echo $minimum; ?>" /></td><td>
          <input type="hidden" name="product_id" size="4" value="<?php echo $product_id; ?>" /></td>
         </tr></table> 

        
        <?php if ($minimum > 1) { ?>
        <div class="minimum"><?php echo $text_minimum; ?></div>
        <?php } ?>
      </div>
         <?php echo $mini_description; ?>
     
  <!--Custom product information-->            
    <?php 
    $cusom_p = $this->registry->get('cusom_p');
    if (($this->config->get('status_product') == '1') && (isset($cusom_p)) ){ ?> 
    <div class="product_custom"><?php echo $cusom_p; ?></div> 
    <?php } ?> 
     
   <!--end Custom product information--> 

  </div>  
  </div>

  <div id="tabs" class="htabs">
  <?php if ($options) { ?><a href="#tab-zakaz">Таблица заказа</a><?php } ?>
  <a href="#tab-description"><?php echo $tab_description; ?></a>
    <?php if ($attribute_groups) { ?>
    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
    <?php } ?>
    <?php if ($review_status) { ?>
    <a href="#tab-review"><?php echo $tab_review; ?></a>
    <?php } ?>
     <?php 
      $cusom_p_tab1 = $this->registry->get('cusom_p_tab1');
     if  (($this->config->get('status_product_tab') == '1') && (isset($cusom_p_tab1)) ){ ?> 
    <a href="#tab-custom"><?php echo $cusom_p_tab1; ?></a>
    <?php } ?>
  </div>
  <?php if ($options) { ?>
  <div id="tab-zakaz" class="tab-content">
      <table class="options">
	  <tr class="titles"><td>Артикул</td><td>Размер</td><td>Ед. измерения</td><td>Кол-во шт. в уп.</td><td>Цена</td><td>Кол-во</td><td>В корзину</td></tr>
        <?php foreach ($options as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
        <tbody id="option-<?php echo $option['product_option_id']; ?>" class="option">
			
            <?php foreach ($option['option_value'] as $option_value) { ?>
			<tr class="box_item_option">
			
			<td><b><?php echo $option_value['model_option']; ?></b></td>
            
            <td class="name_option"><?php echo $option_value['name']; ?></td>
			<td><?php echo $option_value['unit_option']; ?></td>
			<td><?php echo $option_value['amount_option']; ?></td>
            <td class="price_option">
            <?php if ($option_value['points'] > 0) { ?>
            <?php print_r($product);?>
            Акция!<bR>
            <font color="red"><?php echo $option_value['price']; ?></font>
            <?php } else { ?>
            <?php echo $option_value['price']; ?>
            <?php } ?>
            </td>
			<td class="quantity">
			<span class="quantity_<?php echo $option_value['product_option_value_id']; ?>">
			<input type="text" name="quantity" size="2" value="1" />
			</td>
			<td >
			<?php if ($option_value['quantity'] > 0) { ?>
			<a onclick="buy_in_product(<?php echo $option['product_option_id']; ?>, <?php echo $option_value['product_option_value_id']; ?>); return false;" class="button" title="Добавить в корзину"><span><?php echo $button_cart; ?></span></a>
			<?php } else { ?>
			<a class="outstock_option" title="Нет в наличии"><span>Нет в наличии</span></a>
			<?php } ?>
			</td>
			</tr>
            <?php } ?>
			<input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
          <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
        </tbody>
        <?php } ?>
        <?php } ?>
      </table>
  </div>
  <?php } ?>
  <div id="tab-description" class="tab-content"><?php echo $description; ?>
<!-- SynonymizeR start -->
<?php echo $description_syn; ?>
<?php $syn_price = ''; if ($price) { $syn_price = (!$special) ? $price : $special; } ?> <script type="text/javascript">$(document).ready(function() { $('span.synprice').each(function(){ $(this).text('<?php echo $syn_price; ?>');});}); </script>
<!-- SynonymizeR end --></div>
  <?php if ($attribute_groups) { ?>
  <div id="tab-attribute" class="tab-content">
    <table class="attribute">
      <?php foreach ($attribute_groups as $attribute_group) { ?>
      <thead>
        <tr>
          <td colspan="2"><?php echo $attribute_group['name']; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
        <tr>
          <td><?php echo $attribute['name']; ?></td>
          <td><?php echo $attribute['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php } ?>
    </table>
  </div>
  <?php } ?>
  <?php if ($review_status) { ?>
  <div id="tab-review" class="tab-content">
    <div id="review"></div>
    <h2 id="review-title"><?php echo $text_write; ?></h2>
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="" />
    <br />
    <br />
    <b><?php echo $entry_review; ?></b>
    <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" />
    &nbsp;<span><?php echo $entry_good; ?></span><br />
    <br />
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="" />
    <br /><br />
    <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
    <br />
    <div class="buttons">
      <div class="left"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
    </div>
  </div>
  <?php } ?>
 
 <?php 
$cusom_p_tab2 = $this->registry->get('cusom_p_tab2'); 
 if (($this->config->get('status_product_tab') == '1') && (isset($cusom_p_tab2)) ){ ?>
    <div id="tab-custom" class="tab-content"><?php echo $cusom_p_tab2; ?></div>
    <?php } ?>
	<?php if ($products) { ?>
	<div class="box">
	  <div class="box-heading">Сопутствующие товары</div>
	    <div class="box-content">
  <div class="box-product">
	 <section  class="list_carousel content_carou" id="featured_scroll2">
	 <?php foreach ($products as $product) { ?>
               <div class="itemcolumns">
               <div>
                 <div class="img_but">
                 <?php if ($product['thumb']) { ?>

                  <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" ></a></div>
             
      
                <?php } ?>
               </div>
               <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
			   
                 <div class="description"><?php echo $product['description']; ?></div>  
				
        </div>
      </div>
      <?php } ?>
	   </section>
	    </div> </div> </div>
		 <?php } ?>
  <?php echo $content_bottom; ?>
  
  </div>
  
<script type="text/javascript"><!--
$(document).ready(function() {
	
	 <?php if ($this->config->get('product_zoom') == '1') { ?>
	   // additional images
	   $('.image-additional div a').click(function(){
		$('.image-additional div').removeClass('active');
		$(this).parent().addClass('active');
		$('.product-info .image img').attr('src', $(this).attr('data-image'));
	});
	$('.image-additional div:first').addClass('active');
	  
		// zoom
		
		$("#main-image").elevateZoom({
		gallery:'add-gallery',  
		galleryActiveClass: 'active',
		zoomType: "inner",
		cursor: "pointer"
		});	
		
		//popup
		$('.left .image a').magnificPopup({
			items: [
			
				<?php if ($thumb) { ?>
				{src: '<?php echo $popup; ?>'},
				<?php } ?>
				<?php if ($images) { ?>
				<?php foreach ($images as $image) { ?>
				{src: '<?php echo $image['popup']; ?>'},
				<?php } ?>
			<?php } ?>
			],
			gallery: { enabled: true, preload: [0,2] },
			type: 'image',
			mainClass: 'mfp-fade',
		   <?php if ($images) { ?>
			callbacks: {
				open: function() {
					var activeIndex = parseInt($('.image-additional div.active').attr('data-index'));
					var magnificPopup = $.magnificPopup.instance;
					magnificPopup.goTo(activeIndex);
				}
			}
			<?php } ?>
		});	
		
		<?php } else { ?>
		
		 //Colorbox
        $('.colorbox').colorbox({
		maxWidth:'95%', 
		maxHeight:'95%',
		overlayClose: true,
		opacity: 0.5,
		current: "{current} of {total}",
		rel: "colorbox"
	    });

  <?php if ($this->config->get('gen_responsive') == '1') { ?>
      // Colorbox resize function 
      var resizeTimer;
      function resizeColorBox()
      {
       if (resizeTimer) clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (jQuery('#cboxOverlay').is(':visible')) {
                      jQuery.colorbox.load(true);
            }
        }, 300);
     }
 
    // Resize Colorbox when resizing window or changing mobile device orientation
    jQuery(window).resize(resizeColorBox);
    window.addEventListener("orientationchange", resizeColorBox, false);
	
	    <?php } ?>	
		<?php } ?>
		
		
		 $('.image-additional').owlCarousel({
                 navigation : true,
                 pagination : false,
	             items 	 : 3,
                  itemsDesktop : false,
				 itemsDesktopSmall: false,
				 itemsTablet :false,
				 itemsMobile: false,
      });

//Related
$('#related_scroll').carouFredSel({
				    prev: '.related_scroll .prev',
					next: '.related_scroll .next',
					scroll: 1,
			        swipe:{onTouch: true},
			        auto: false,
			        responsive: true,
			         items: {
		         	width: 280,
			       visible: {
		        	min: 1,
			        max: 5
				}
				}
		});				
	


});
//--></script> 

<script type="text/javascript"><!--
jQuery.fn.scroll_to_anchor = function(){
    this.stop(false , false) // - отключаем анимацию, если она уже запустилась
    jQuery('html,body').animate({scrollTop: this.offset().top},'slow');
    return this;
}
//--></script> 
<script type="text/javascript"><!--
$('#button-cart').bind('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
					}
				}
			} 
			
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/mattimeo/image/close.png" alt="" class="close" /></div>');
					
				$('.success').fadeIn('slow');
					
				$('#cart-total').html(json['total']);
				

			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
function buy_in_product (opt_id, option) {
$('input[name=\'option['+opt_id+']\']').val(option);
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('.product-info span.quantity_'+option+' input[type=\'text\'], .options input[type=\'hidden\'],  .options span.quantity_'+option+' input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
					}
				}
			} 	 
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#cart-total').html(json['total']);
				
			
			}	

		}
	});

}
//--></script>
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/mattimeo/image/loading.gif" class="loading" style="padding-left: 5px;" />');
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
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').fadeOut('slow');
		
	$('#review').load(this.href);
	
	$('#review').fadeIn('slow');
	
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/mattimeo/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
			}
			
			if (data['success']) {
				$('#review-title').after('<div class="success">' + data['success'] + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
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

<?php echo $footer; ?>