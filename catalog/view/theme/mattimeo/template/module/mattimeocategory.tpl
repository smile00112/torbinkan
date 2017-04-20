<?php if(!empty($tabs)){ ?>
<?php
$this->language->load('module/category');
$button_compare = $this->language->get('button_compare');
$button_wishlist = $this->language->get('button_wishlist');
$button_cart = $this->language->get('button_cart');
$this->language->load('module/mattimeotheme');
$button_quick = $this->language->get('entry_quickview');
?>
<?php if ($category_width == '1') { ?></div></div><?php } ?>

<div class="verticaltab featur_categ"> 

  <div <?php if ($category_width == '1') { ?>class="container" <?php } ?>>
  <div class="select_category">
  <a><?php echo $text_show; ?></a>
    <ul class="vtabs" id="featurcateg<?php echo $module; ?>">
       <?php $number = (count($tabs)); ?>
		<?php foreach ($tabs as $numTab => $tab) { ?>
          <?php  if ($tab['name']) { ?>
		<li class="number<?php echo $number; ?>"><a href="#mattimeocategory-<?php echo $numTab; ?><?php echo $module; ?>"><?php echo $tab['name']; ?></a></li>
         <?php } ?>
        
		<?php } ?>
	 </ul>
  </div>
  </div>
    
	<?php foreach ($tabs as $numTab => $tab) { ?>
     <?php  if ($tab['name']) { ?>

    
	<div id="mattimeocategory-<?php echo $numTab; ?><?php echo $module; ?>" >
     
		 <div class="box-product-category" 
          <?php  if ($tab['image'] ) { ?>
         style="background-image:url(<?php echo $tab['image']; ?>);"
           <?php } ?>	
         >
       <div <?php if ($category_width == '1') { ?>class="container" <?php } ?>>
         <div class="name_categ"><a href="<?php echo $tab['href']; ?>"><?php echo $tab['name']; ?></a></div> 
         
        <?php  if (( ($tab['children']) && ($tab['subcateg'] == '1' )) || ($tab['title'])) { ?> 
          <div class="about_category">
          
            <?php  if ($tab['title'])  { ?>
             <div class="description_categ"><?php echo html_entity_decode($tab['title']); ?></div> 
             <?php } ?>
             
          <!--Sucategory--> 
         <?php if (($tab['children']) && ($tab['subcateg'] == '1' )) { ?>
          <div class="subcategory">
             <?php foreach ($tab['children'] as $child) { ?>
              <a href="<?php echo $child['href']; ?>">
              <div class="block1">
               <?php if ($child['image']) { ?><img src="<?php echo $child['image']; ?>" alt="<?php echo $child['name']; ?>"> <?php } ?>
               <?php echo $child['name']; ?>
             </div>
             </a>
            <?php } ?>
            </div>
           <?php } ?> 
           <!--end Subcategory-->   
           
           </div>
        <?php } ?> 
        
            
          
         <?php if((!empty($tab['products'])) && ($tab['showproducts'] == '1')){ ?>
         
         <!--Latest products-->
         <div class="latestprod">
         <!-- <div class="box-heading"><?php echo $heading_latest; ?></div>-->
         <div class="owl-addblock<?php echo $module; ?> owl-carousel">
          
		   <?php foreach ($tab['products'] as $product) { ?>
         
				<div class="block2">
                    <div class="image">
                    <?php if ($product['thumb']) { ?>
                    
					<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"></a>
					<?php } ?>
                    
                 	<div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                    <div class="description"><?php echo $product['description']; ?></div>    

                    </div>
                  
               
				</div>
                <?php } ?>
                
               </div>
               </div> 
                  
       
<script type="text/javascript">
 $(document).ready(function() {
	var widthimg2 = <?php echo $widthimg; ?> + 20;
	
	 <?php if ($this->config->get('gen_responsive') == '1') { ?>
	  enquire.register("only screen and (min-width: 790px)", {
	  match : function() {
		   $('.right_desc2').css('margin-left',widthimg2);
			  }
	 }).register("only screen and (max-width: 789px)", {
	   match : function() {
		  $('.right_desc2').css('margin-left',0);
			  }
		    }); 

	<?php } else { ?>
	      $('.right_desc2').css('margin-left',widthimg2);
	 <?php } ?>
	   	   
 });
</script>  
         
         <!--end Latest Products-->
         <?php } ?>
                
                
        </div>
        
         </div>  <!--end box-product-category-->
         </div>

          <?php } ?>   
	    <?php } ?>

<script type="text/javascript">	
$('#featurcateg<?php echo $module; ?>.vtabs a').tabs();

</script>
<script type="text/javascript">	
                $(document).ready(function(){
				 $(".owl-addblock<?php echo $module; ?>").owlCarousel({
                 navigation : true,
                 pagination : false,
				items 	 : <?php echo $limit_v; ?> ,
				<?php if ($this->config->get('gen_responsive') == '1') { ?>
				itemsDesktop 			: [1199,2],
		        itemsDesktopSmall 		: [979,1],
		        itemsTablet 			: [768,1],
				 <?php } else { ?>
				 itemsDesktop : false,
				 itemsDesktopSmall: false,
				 itemsTablet :false,
				 itemsMobile: false,
				<?php } ?>
			    });
	
                 });                                                                      
               </script>

</div>

<?php if ($category_width == '1') { ?><div class="main"><div class="container"><?php } ?>

<?php } ?>


