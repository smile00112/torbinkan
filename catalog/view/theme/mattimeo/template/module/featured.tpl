<div class="box">
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
 
       <?php if ($this->config->get('showmore2_featured') == '1')  { ?>
       <!--Carousel products--> 
         <section  class="list_carousel" id="featured_scroll">
         <div class="carou">
        <?php } ?> 
         
      
 <?php } else { ?>
 
      <?php if ($this->config->get('showmore_featured') == '1')  { ?>
       <!--Carousel products--> 
         <section  class="list_carousel content_carou" id="featured_scroll">
         <div class="carou">

       <?php } ?>
       
 <?php } ?>
 
        
         <?php foreach ($products as $product) { ?>
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

               </div>
                
               
               <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
				
              
        </div>
      </div>
      <?php } ?>
      
      
      
 <?php if ( (isset($position)) && ($position == 'column_left' || $position == 'column_right')){ ?> 
 
       <?php if ($this->config->get('showmore2_featured') == '1')  { ?> 
        <!--Carousel products--> 
       </div>
       <div class="clearfix"></div>
				<a id="prev4" class="prev" href="#"></a>
				<a id="next4" class="next" href="#"></a>
        </section>
       <!--end Carousel products--> 
        <?php } ?> 
          
       <?php } else { ?> 
       
     <?php if ($this->config->get('showmore_featured') == '1')  { ?>
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
		$('#featured_scroll .carou').carouFredSel({
			prev: '#featured_scroll .prev',
			next: '#featured_scroll .next',
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
       
   </div> </div>  
  
   
   </div>  
   

