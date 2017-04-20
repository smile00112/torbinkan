<?php
$this->language->load('module/mattimeotheme');
$button_quick = $this->language->get('entry_quickview');
?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($products) { ?>
  <div class="product-filter">
   
    
    <div class="limit"><?php echo $text_limit; ?>
      <select onchange="location = this.value;" class="select1">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort">
      <select onchange="location = this.value;" class="select1">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div> 
  </div>
 
  <div class="product-grid catalog-item-cards">
    <?php foreach ($products as $product) { ?>
      <div class="catalog-item-card">
	  <div class="catalog-item-info">
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
		<div class="article_rating"><div class="article">Артикул:	<?php echo $product['model']; ?></div>
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
		
		<button data-type="v-category" data-active="<?php echo $this->config->get( 'product_quantity_v_category' ); ?>"  onclick="addToCart('<?php echo $product['product_id']; ?>');" class="btn_buy" ><i class="fa fa-shopping-cart"></i>Купить</button></div>
      </div></div>
    <?php } ?>

  </div>
  
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?></div>
    <div class="cont_bottom"></div>
  <?php echo $content_bottom; ?>
<script type="text/javascript"><!--

view = $.totalStorage('display');

if (view) {
	display(view);
} else {
	display('grid');
}
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

<?php echo $footer; ?>