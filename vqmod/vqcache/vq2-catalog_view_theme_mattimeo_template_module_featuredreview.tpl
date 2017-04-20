<?php
$this->language->load('module/category');
$button_compare = $this->language->get('button_compare');
$button_wishlist = $this->language->get('button_wishlist');
$this->language->load('module/mattimeotheme');
$button_quick = $this->language->get('entry_quickview');
?>
<div class="box prod_review_box">
  <?php if($customtitle) { ?>
            <div class="box-heading"><?php echo $customtitle; ?></div>
        <?php } ?>
  <div class="box-content verticaltab ">

  
     <div class="popular_scroll" id="popular_scroll">

     
      <?php foreach ($products as $product) { ?>
      
      <div class="block2">
      
       <div class="image">
          <?php if ($product['thumb']) { ?>
           <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
           <?php } ?>
           
           <?php if ((isset($product['saving']))&& $product['special'] ) { ?>
            <div  class="savemoney">- <?php echo $product['saving']; ?>%</div>
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
      

      
        <div class="center_desc3">
          <div class="center_desc3_right">
             <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
            <?php if ($product['price']) { ?>
            <div class="price">
            <?php if (!$product['special']) { ?>
            <?php echo $product['price']; ?>
            <?php } else { ?>
            <span class="price-old"><?php echo $product['price']; ?></span><br /> <span class="price-new"><?php echo $product['special']; ?></span>
            <?php } ?>
          </div>
          <?php } ?>
           <div class="description"><?php echo $product['description']; ?></div>
        
           <div class="cart"><?php if ($product['quantity'] <= 0 ) { ?>
			
			   <?php if ($product['stock'] == $button_out_of_stock ) { ?>
			   <input type="button" value="<?php echo $product['stock']; ?>" class="button nostock" />
			   <?php }  else  {?>
			    <input type="button" value="<?php echo $product['stock']; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
			   <?php } ?>
			   
			<?php } else { ?>
			<input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
			<?php } ?></div>
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
          </div> 
          
           <!--right description-->
        <div class="featured_review">  
          <?php if ($product['reviews2']) { ?>
          <div class="owl-rev"> 
          <?php foreach ($product['reviews2'] as $review) { ?>
           <div>
          <div class="authorreview"><?php echo $review['author']; ?></div>
         <div class="textreview"> <a href="<?php echo $product['href']; ?>"><?php echo  utf8_substr(strip_tags(html_entity_decode($review['text'], ENT_QUOTES, 'UTF-8')), 0, 145) . '..' ?> </a></div>
         </div>
          <?php } ?> 
          </div>
          <?php } else { ?>
           <?php echo $no_reviews; ?>
         <?php } ?>
         </div> 
         <!--end right description--> 
           
         </div>
         
     
     </div>

      <?php } ?>
      
    </div>

    
  </div>


<script type="text/javascript">
$(document).ready(function(){
   $("#popular_scroll").owlCarousel({
	 navigation : true,
     pagination : false, 
     singleItem : true,

	   
   });

    $(".owl-rev").owlCarousel({
	navigation : false,
     pagination : true,
	 singleItem : true,
	});
});
</script>
<script type="text/javascript">
 $(document).ready(function() {
	var widthimg3 = <?php echo $widthimg; ?> + 20;
	
	 <?php if ($this->config->get('gen_responsive') == '1') { ?>
	  enquire.register("only screen and (min-width: 790px)", {
	  match : function() {
		   $('.center_desc3').css('margin-left',widthimg3);
		   $('#column-left .center_desc3').css('margin-left',0);
		   $('#column-right .center_desc3').css('margin-left',0);
			  }
	 }).register("only screen and (max-width: 789px)", {
	   match : function() {
		  $('.center_desc3').css('margin-left',0);
			  }
		    }); 

	<?php } else { ?>
	      $('.center_desc3').css('margin-left',widthimg3);
		   $('#column-left .center_desc3').css('margin-left',0);
		   $('#column-right .center_desc3').css('margin-left',0);
	 <?php } ?>
	   	   
 });
</script>
</div> 