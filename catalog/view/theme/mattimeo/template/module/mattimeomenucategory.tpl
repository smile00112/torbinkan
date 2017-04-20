<?php if(!empty($tabs)){ ?>
<?php
$this->language->load('module/category');
$button_compare = $this->language->get('button_compare');
$button_wishlist = $this->language->get('button_wishlist');
$button_cart = $this->language->get('button_cart');
$heading_title = $this->language->get('heading_title');
$this->language->load('module/mattimeotheme');
$button_quick = $this->language->get('entry_quickview');
?>


<div class="box dropdown_category <?php if ( (isset($position)) && ($position == 'column_right')){ ?>position_right<?php } ?>">
    <div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-category accordeon_categ">
        <ul class="left-menu">
       <?php $number = (count($tabs)); ?>
		<?php foreach ($tabs as $numTab => $tab) { ?>
          <?php  if ($tab['name']) { ?>
		  <li class="parent"><a href="<?php echo $tab['href']; ?>" 
                      <?php  if (($tab['image'] ) ||
                      ($tab['title']) ||
                       ((!empty($tab['products'])) && ($tab['showproducts'] == '1')) ||
                       (($tab['children']) && ($tab['subcateg'] == '1' )) ) { ?> class="dropdown_arr" <?php } ?>><?php echo $tab['name']; ?><span class="arrow"></span></a>
          
                       

            <!--Drowp down-->            
           <div class="all_subcat" >
           

              <?php  if ($tab['image'] ) { ?>
              <div class="img_categ"><a href="<?php echo $tab['href']; ?>" ><img src="<?php echo $tab['image']; ?>" alt="<?php echo $tab['name']; ?>"></a></div>
             <?php } ?>	

             <?php  if ($tab['title'])  { ?>
             <div class="description_categ"><?php echo html_entity_decode($tab['title']); ?></div> 
             <?php } ?>
           
             <!--Sucategory--> 
             <?php if (($tab['children']) && ($tab['subcateg'] == '1' )) { ?>

              <div class="sub_category_child  <?php if ($tab['column'] == '0' ){ ?>two_column <?php } ?>"> 
                  
             <?php for ($i = 0; $i < count($tab['children']);) { ?>
             <ul>
              <?php $j = $i + ceil(count($tab['children']) /2 ); ?>
              <?php for (; $i < $j; $i++) { ?>
             <?php if (isset($tab['children'][$i])) { ?>
          
          
             <li>  
       
            <a href="<?php echo $tab['children'][$i]['href']; ?>">
            <?php if ($tab['children'][$i]['image']) { ?><img src="<?php echo $tab['children'][$i]['image']; ?>" alt="<?php echo $tab['children'][$i]['name']; ?>"> <?php } ?>
            <span><?php echo $tab['children'][$i]['name']; ?></span>
            </a>
            <?php if( $tab['children'][$i]['children'] ) { ?>
            <span class="dropdown_arr"></span>
            <div>
              <ul>
              <?php foreach( $tab['children'][$i]['children'] as $menu3item ) { ?>
              <li><a href="<?php echo $menu3item['href']; ?>"><?php echo $menu3item['name']; ?></a></li>
              <?php } ?>
              </ul>
            </div>
            <?php } ?>
         </li>
          <?php } ?>
          <?php } ?>
       
        </ul>
        <?php } ?> 
          </div>
             <?php } ?> 
             <!--end Subcategory-->
           
              <?php if((!empty($tab['products'])) && ($tab['showproducts'] == '1')){ ?>
         
               <!--Latest products-->
         <div class="latestprod  verticaltab">

         <div class="owl-addblock-cat<?php echo $module; ?> owl-carousel">
          
		   <?php foreach ($tab['products'] as $product) { ?>
         
				<div class="block2">
                    <div class="image">
                    <?php if ($product['thumb']) { ?>
                    
					<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"></a>
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
                    

					<div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                    <?php if ($product['price']) { ?>
					<div class="price">
					  <?php if (!$product['special']) { ?>
					  <?php echo $product['price']; ?>
					  <?php } else { ?>
					  <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                           
					  <?php } ?>
					</div>
					<?php } ?>
               
				</div>
                <?php } ?>
                
               </div>
               </div> 

         
         <!--end Latest Products-->
         
              <?php } ?>
       
           
             </div>
             <!--end Drowp down-->  
       
        
        
        </li>
         <?php } ?>
        
		<?php } ?>
	 </ul>
  </div>
  </div>
    



<script type="text/javascript">	
                $(document).ready(function(){
				 $(".owl-addblock-cat<?php echo $module; ?>").owlCarousel({
                 navigation : true,
                 pagination : false,
				 singleItem : true,
				 autoPlay: 8000,
			    });
	
                 });                                                                      
               </script>





<?php } ?>


