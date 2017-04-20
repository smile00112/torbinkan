<div class="box">
<?php
$this->language->load('module/category');
$button_compare = $this->language->get('button_compare');
$button_wishlist = $this->language->get('button_wishlist');
$this->language->load('module/mattimeotheme');
$button_quick = $this->language->get('entry_quickview');
?>
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content" id="latest">
  	 <div class="catalog-item-cards">
  
<?php if ( (isset($position)) && ($position == 'column_left' || $position == 'column_right')){ ?> 
 
       <?php if ($this->config->get('showmore2_latest') == '1')  { ?>
       <!--Carousel products--> 
         <section  class="list_carousel" id="latest_scroll">
         <div class="carou">
        <?php } ?> 
         
      
 <?php } else { ?>
 
      <?php if ($this->config->get('showmore_latest') == '1')  { ?>
       <!--Carousel products--> 
         <section  class="list_carousel content_carou" id="latest_scroll">
         <div class="carou">
         
       
         
       <?php } ?>
       
 <?php } ?>
 
        
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
<span class="catalog-item-price"><?php echo $product['price']; ?><span class="unit">за шт</span></span>
<?php } else { ?>
<span class="catalog-item-price-old"><?php echo $product['price']; ?></span>
<span class="catalog-item-price-percent">Экономия <?php echo $product['saving']; ?></span>
<span class="catalog-item-price"><?php echo $product['special']; ?><span class="unit">за шт</span></span>
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
      
      
      
 <?php if ( (isset($position)) && ($position == 'column_left' || $position == 'column_right')){ ?> 
 
       <?php if ($this->config->get('showmore2_latest') == '1')  { ?> 
        <!--Carousel products--> 
       </div>
       <div class="clearfix"></div>
				<a id="prev4" class="prev" href="#"></a>
				<a id="next4" class="next" href="#"></a>
        </section>
       <!--end Carousel products--> 
        <?php } ?> 
          
       <?php } else { ?> 
       
     <?php if ($this->config->get('showmore_latest') == '1')  { ?>
     <!--Carousel products--> 
     </div>
     <div class="clearfix"></div>
				<a class="prev" href="#"></a>
				<a class="next" href="#"></a>
     </section>
     <!--end Carousel products--> 
     
      
     <?php } ?>  
     <?php } ?>
     
             
         <script type="text/javascript"> 
		  $(document).ready(function(){
		$('#latest_scroll .carou').carouFredSel({
			prev: '#latest_scroll .prev',
			next: '#latest_scroll .next',
			scroll: 1,
			swipe:{onTouch: true},
			auto: false,
			responsive: true,
			items: {
			width: 290,
			visible: {
			min: 1,
			max: 5
					}
				}
		});
		});
	</script>
     
   </div>
   </div>    
         
         
  </div>  
         
