<div data-type="m-special" data-active="<?php echo $this->config->get( 'product_quantity_m_special' ); ?>"><div class="box">
<?php
$this->language->load('module/category');
$button_compare = $this->language->get('button_compare');
$button_wishlist = $this->language->get('button_wishlist');
$this->language->load('module/mattimeotheme');
$button_quick = $this->language->get('entry_quickview');
?>
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
  <div class="box-product">
  
<?php if ( (isset($position)) && ($position == 'column_left' || $position == 'column_right')){ ?> 
 
       <?php if ($this->config->get('showmore2_specials') == '1')  { ?>
       <!--Carousel products--> 
         <section  class="list_carousel" id="special_scroll">
         <div class="carou">
        <?php } ?> 
         
      
 <?php } else { ?>
 
      <?php if ($this->config->get('showmore_specials') == '1')  { ?>
       <!--Carousel products--> 
         <section  class="list_carousel content_carou" id="special_scroll">
         <div class="carou">
         
       
         
       <?php } ?>
       
 <?php } ?>
 

       <?php foreach ($products as $product) {
if($product['options']) {
foreach ($product['options']['0']['option_value'] as $option) {
if ($option['points'] > 0) {
$old_price = $product['price']+$option['price']+$option['points'];;
$new_price = $product['price']+$option['price'];
$discount = round(100.0*($new_price/$old_price-1));
?>
               <div class="itemcolumns">
               <div>
               
                <div class="img_but">
               <?php if ($product['thumb']) { ?>
               
               <?php if  ($this->config->get('img_additional1') == '1') { ?> 
               <!--Additional images--> 
               <div class="owl-addimage owl-carousel"> <?php } ?>
               
               <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" ></a></div>
                
               <?php if ((isset($product['dop_img'])) && ($this->config->get('img_additional1') == '1') ) { ?> 
               <?php foreach ($product['dop_img'] as $img) { ?>
               <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $img;?>" alt="<?php echo $product['name']; ?>"></a></div>
               <?php } ?>
               <?php } ?>


                <?php if  ($this->config->get('img_additional1') == '1') { ?>  
                <!--end additional images--> 
                </div> <?php } ?>
                <?php } ?>

                 <div class="hover_but">
               <?php if ($this->config->get('show_wishlist') == '1')  { ?>
               <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" ></a></div>
               <?php } ?>
               <?php if ($this->config->get('show_compare') == '1')  { ?>
               <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_compare; ?>"></a></div>
               <?php } ?>
               <?php if  ((isset($product['quickview'])) && ($this->config->get('quick_view') == '1')) { ?>  
               <div class="quickviewbutton"><a class='quickview' href="<?php echo $product['quickview']; ?>" title="<?php echo $button_quick; ?>"></a></div>
               <?php } ?> 
               </div>
                </div>
        
               <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?> </a><span style="font-weight: bold;font-size: 12px;">(<?php echo $option['size'];?>, <?php echo $option['amount_option'].', '.$option['unit_option'];?>)</span></div>
               <?php if ($product['price']) { ?>
               <div class="price">
               <span class="price-old"><?php echo $product['price']+$option['price']+$option['points']; ?> р.</span> <span class="price-new"><?php echo $product['price']+$option['price']; ?> р.</span>
               <?php if (isset($option['price'])) { ?>
              <div  class="savemoney" style=" top: 55%;left: 50%;display:block;"> <?php echo round($discount); ?>%</div>
           <?php } ?>
               </div>
               <?php } ?>
              
              <div class="cart"><?php if ($product['quantity'] <= 0 ) { ?>
			
			   <?php if ($product['stock'] == $button_out_of_stock ) { ?>
			   <input type="button" value="<?php echo $product['stock']; ?>" class="button nostock" />
			   <?php }  else  {?>
			    <input type="button" value="<?php echo $product['stock']; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
			   <?php } ?>
			   
			<?php } else { ?>
			<input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
			<?php } ?></div>
              
                     <?php if ($this->config->get('config_review_status')) { ?>
      <div class="rating"><img src="catalog/view/theme/mattimeo/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
      <?php } ?>
              

        </div>
        </div>
      <?php } } } } ?>
      
      
      
 <?php if ( (isset($position)) && ($position == 'column_left' || $position == 'column_right')){ ?> 
 
       <?php if ($this->config->get('showmore2_specials') == '1')  { ?> 
        <!--Carousel products--> 
       </div>
       <div class="clearfix"></div>
				<a id="prev4" class="prev" href="#"></a>
				<a id="next4" class="next" href="#"></a>
        </section>
       <!--end Carousel products--> 
        <?php } ?> 
          
       <?php } else { ?> 
       
     <?php if ($this->config->get('showmore_specials') == '1')  { ?>
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
		$('#special_scroll .carou').carouFredSel({
			prev: '#special_scroll .prev',
			next: '#special_scroll .next',
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
         
         
  </div></div>