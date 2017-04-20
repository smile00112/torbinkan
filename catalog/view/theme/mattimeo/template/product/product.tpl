<?php echo $header; ?>
    <!--купить в 1 клик-->
   <a onclick="one_klick_close();" class="one_klick_close" href="javascript:void(0);">
<div style="display:none;" id="absolutes"></div></a>
<div id="one_klick" class="draggable" style="display: none;">
<div class="close">
<a onclick="one_klick_close();" class="one_klick_close" href="javascript:void(0);"><img  src="catalog/view/theme/default/image/close.png"></a>
</div>
	<?php echo $column_right; ?>

</div> 
	<!--купить в 1 клик конец-->
<?php echo $column_left; ?>

<div id="content"><?php echo $content_top; ?>
 <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
 <div class="share"> 
<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter"></div>
  </div>
  <h1><?php echo $heading_title; ?></h1> 
  <div class="product-info ">
 
    <?php if ($thumb || $images) { ?>
    <div class="left column">
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
  
 
    
    <div class="right column">
    
      <div class="catalog-detail">  
     <div class="general_info column three"> 
<div class="article">Артикул: -	<?php echo $model; ?></div>
      <?php if ($manufacturer) { ?>
      <?php if (isset($manufacturers_img)) { ?>
      <?php echo ($manufacturers_img) ? '<span> <a href="'.$manufacturers.'"><img src="'.$manufacturers_img.'"  title="'.$manufacturer.'" /></a></span>' : '' ;?>
      <?php } else { ?>
      <span><?php echo $text_manufacturer; ?></span>
       <?php } ?>
        <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a><br />
        <?php } ?>
       <?php if ($review_status) { ?>
      <div class="review">
        <div>          
		<?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($rating < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
                <a onclick="$('a[href=\'#tab-review\']').trigger('click'); $('html, body').animate({scrollTop: $('#tabs').offset().top}, 800);"><?php echo $reviews; ?></a></div>
      </div>
      <?php } ?>       
		  <div class="min_desc">   <?php echo $mini_description; ?>	  </div>  
      </div>  

     
       <div class="column">
        
<div class="column four">
<div class="price_buy_detail" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
<div class="catalog-detail-price">
<?php if (!$special) { ?>
<span class="catalog-detail-item-price"><span class="smalls">Розница: </span><?php echo $price; ?></span>
<?php if ($ean > 0) { ?>
<span class="catalog-detail-item-price"><span class="smalls">Опт: </span><?php echo $opt_price; ?></span>
 <?php } ?>	
<?php } else { ?>
<span class="catalog-detail-item-price-old"><?php echo $price; ?></span>
<span class="catalog-detail-item-price-percent">Экономия <?php echo $saving; ?></span>
<span class="catalog-detail-item-price"><?php echo $special; ?></span>
 <?php } ?>																

<?php if ($quantity <= 0) { ?>
<div class="not_avl">
<i class="fa fa-times-circle"></i><span>Нет в наличии</span>
</div>	
<?php } else { ?>
<div class="avl">
<i class="fa fa-check-circle"></i><span>В наличии</span>
</div>	
 <?php } ?>			
</div>						
						
<div class="catalog-detail-buy" >
<div class="buy_more_detail">													
<div class="qnt_cont">
<a href="javascript:void(0)" class="minus" id="decrease"><span>-</span></a>
<input type="text" name="quantity" id="htop" size="2" value="<?php echo $minimum; ?>" />
<a href="javascript:void(0)" class="plus" id="increase"><span>+</span></a>
<input type="hidden" name="product_id" size="4" value="<?php echo $product_id; ?>" />
</div>
 <?php if (!$options) { ?><button id="button-cart"  name="add2basket" class="btn_buy detail" value="В корзину"><i class="fa fa-shopping-cart"></i>В корзину</button>		 <?php } ?>							
<button name="on_click" class="btn_buy boc_anch" id="one_klick_anchor" onclick="one_klick_show(); " href="javascript:void(0);" ><i class="fa fa-bolt"></i>Купить в 1 клик</button>					
</div>
</div>				
						
<div class="compare_delay">
<div class="compare"><a class="catalog-item-compare" onclick="addToCompare('<?php echo $product_id; ?>');"><span class="compare_cont"><i class="fa fa-bar-chart"></i><span class="compare_text">Сравнить</span></span></a></div>
<div class="catalog-detail-delay" >
<div class="delay"><a class="catalog-item-delay" onclick="addToWishList('<?php echo $product_id; ?>');"><span class="delay_cont"><i class="fa fa-heart-o"></i><span class="delay_text">Отложить</span></span></a></div>
</div>
</div>						
						
											
						

  <div class="payment_methods">
	<div class="h3">Способы оплаты</div>
	<ul>
	<li><a href="javascript:void(0)"><img src="image/payment/master.png" width="66" height="30"></a></li>
	<li><a href="javascript:void(0)"><img src="image/payment/visa.png" width="66" height="30"></a></li>
	<li><a href="javascript:void(0)"><img src="image/payment/qiwi.png" width="66" height="30"></a></li>
	<li><a href="javascript:void(0)"><img src="image/payment/webm.png" width="66" height="30"></a></li>
	<li><a href="javascript:void(0)"><img src="image/payment/yad.png" width="66" height="30"></a></li>
	<li><a href="javascript:void(0)"><img src="image/payment/nal.png" width="66" height="30"></a></li>
   </ul>
  </div>						
  <div class="catalog-detail-buttons">
<a rel="nofollow" target="_blank" href="/oplata/" class="btn_buy apuo pcd"><i class="fa fa-credit-card"></i><span>Все способы оплаты</span></a>
<a rel="nofollow" target="_blank" href="/dostavka/" class="btn_buy apuo pcd"><i class="fa fa-truck"></i><span>Подробнее о доставке</span></a>
  </div>
</div>
</div>
      </div>
	</div>
  </div>  
  </div>
   <div class="ndl_tabs">
<div class="section">
  <ul id="tabs" class="tabs" >
  <?php if ($options) { ?> <li><a href="#tab-zakaz"><span>Таблица заказа</span></a></li><?php } ?>
  <li><a href="#tab-description"><span><?php echo $tab_description; ?></span></a></li>
    <?php if ($attribute_groups) { ?>
   <li> <a href="#tab-attribute"><span><?php echo $tab_attribute; ?></span></a></li>
    <?php } ?>
    <?php if ($review_status) { ?>
   <li> <a href="#tab-review"><span><?php echo $tab_review; ?></span></a></li>
    <?php } ?>
	  <li> <a href="#tab-dostavka"><span>Доставка и Оплата</span></a></li>
  </ul>
    </div>
  <?php if ($options) { ?>
  <div id="tab-zakaz" class="tab-content">
      <table class="options">
	  <tr class="titles"><td>Артикул</td><td>Размер</td><td>Ед. измерения</td><td>Кол-во шт. в уп.</td><td>Розничная цена</td><?php if ($ean > 0) { ?><td>Опт. цена</td> <?php } ?><td>Кол-во</td><td>В корзину</td></tr>
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
            Акция!<br>
            <font color="red"><?php echo $option_value['price']; ?></font>
            <?php } else { ?>
            <?php echo $option_value['price']; ?>
            <?php } ?>
            </td>
			<?php if ($ean > 0) { ?>
			 <td class="price_option">
            <?php if ($option_value['points'] > 0) { ?>
            <?php print_r($product);?>
            Акция!<br>
            <font color="red"><?php echo $option_value['price_roznic'] ?></font>
            <?php } else { ?>
            <?php echo $option_value['price_roznic'] ?>
            <?php } ?>
            </td>
			 <?php } ?>
			<td class="quantity">
			<span class="quantity_<?php echo $option_value['product_option_value_id']; ?>">
			<input type="text" name="quantity" size="2" value="1" />
			</td>
			<td >
			<?php if ($option_value['quantity'] > 0) { ?>
			<a onclick="buy_in_product(<?php echo $option['product_option_id']; ?>, <?php echo $option_value['product_option_value_id']; ?>); return false;" class="btn_buy apuo" title="Добавить в корзину"><span><?php echo $button_cart; ?></span></a>
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
  <div id="tab-dostavka" class="tab-content"> Доставка и Оплата </div>
 </div>
	<?php if ($products) { ?>
	<div class="box">
	  <div class="box-heading border_2">Сопутствующие товары</div>
	 <div class="catalog-top">
	 <div class="catalog-item-cards">
	 <section  class="list_carousel content_carou" id="featured_scroll2">
	 <?php foreach ($products as $product) { ?>
      <div class="catalog-item-card">
	  <div class="catalog-item-info">
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
		<div class="article_rating"><div class="article">Артикул: -	<?php echo $product['model']; ?></div>
		<?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
		<div class="clr"></div>
		</div>
		<div class="description"><?php echo $product['description']; ?></div>

<?php if ($product['price']) { ?>		
<div class="item-price-cont">
<div class="item-price">
<?php if (!$product['special']) { ?>
<span class="catalog-item-price"><?php echo $product['price']; ?></span>
<?php } else { ?>
<span class="catalog-item-price-old"><?php echo $product['price']; ?></span>
<span class="catalog-item-price-percent">Экономия <?php echo $product['saving']; ?></span>
<span class="catalog-item-price"><?php echo $product['special']; ?></span>
 <?php } ?>												
</div>											
</div>
        <?php } ?>
        <div class="buy_more">
		<div class="available">
		<div class="avl"><i class="fa fa-check-circle"></i><span>В наличии <?php echo $product['stock']; ?></span>
		</div>
		</div>
		<div class="clr"></div>
		<div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><i class="fa fa-bar-chart"></i></a></div>
		<div class="delay"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart-o"></i></a></div>
		
		<button   data-type="v-category" data-active="<?php echo $this->config->get( 'product_quantity_v_category' ); ?>"  onclick="addToCart('<?php echo $product['product_id']; ?>');" class="btn_buy"><i class="fa fa-shopping-cart"></i>Купить</button></div>
      </div></div>
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
<script>
		function one_klick_close(){
		$('#one_klick').hide();
		$('#absolutes').hide();
		return false;
	}
	
	function one_klick_show(){
        margin_top = -$('#one_klick').height()/2;
        margin_left= -$('#one_klick').width()/2;
        $('#one_klick').css({'margin-left': margin_left, 'margin-top': margin_top });
		$('#one_klick').show();
		$('#absolutes').show();
	
		return false;
	}

</script>

<?php echo $footer; ?>