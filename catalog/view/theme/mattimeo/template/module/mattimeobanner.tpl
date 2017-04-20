<?php  if(!empty($tabs)){ ?>


<div id="matban_box<?php echo $module; ?>" class="matban_box
                  <?php if ($dinamic == '1') { ?>maska_text<?php }  
                  elseif ($dinamic == '2') { ?>beforetext<?php } 
                  elseif ($dinamic == '3') { ?>slider_banner<?php } 
                  elseif ($dinamic == '4') { ?>double slider_banner<?php } ?>" >



<?php if ($dinamic == '1') { ?>
 <script type="text/javascript">	
                $(document).ready(function(){
				$('.container #matban_box<?php echo $module; ?>.maska_text .stylebanner .maska').css('width',<?php echo $image_width; ?>);	
				$('.container #matban_box<?php echo $module; ?>.maska_text .stylebanner .maska').css('height',<?php echo $image_height; ?>);	
				<?php if ($this->config->get('gen_responsive') == '1') { ?>
	             enquire.register("only screen and (max-width: 1170px)", {
			  match : function() {
                  $('#matban_box<?php echo $module; ?>.maska_text .stylebanner .maska').css('width','100%');	
				$('#matban_box<?php echo $module; ?>.maska_text .stylebanner .maska').css('height','99%'); 

			  }
		    });
	            <?php } ?>	
				
				});                                                                      
</script>

<?php } ?>
 
    <?php if (($dinamic == '3') || ($dinamic == '4')) { ?> <div class="owl-addbanner owl-carousel mattSlider">
    <?php } else { ?>
    <div class="box_maska">
    <?php } ?>

     
	<?php foreach ($tabs as $numTab => $tab) { ?>
    
    <div class="stylebanner  <?php if (($dinamic == '1') || ($dinamic == '2')) { ?> count-<?php echo count($tabs); ?><?php } ?>" id="mattimeobanner-<?php echo $numTab; ?><?php echo $module; ?>" >
    
    <?php if ($tab['href']) { ?><a href="<?php echo $tab['href']; ?>"> <?php } ?>
        
        
        <div class="one">
         <div>
         <div class="maska"></div>
         <img src="<?php echo $tab['image']; ?>" 
         <?php if (isset($tab['headingtext'][$lang])){ ?>
         alt="<?php echo $tab['headingtext'][$lang]; ?>" 
         title="<?php echo $tab['headingtext'][$lang]; ?>"
         <?php } ?>>
         </div> 
        </div>

        
        <?php if ( (isset($tab['title'][$lang])) && $tab['title'][$lang] !='' ) { ?>
        <div class="two"><div>
          <?php if (isset($tab['headingtext'][$lang]))  { ?>
           <div class="threeheading"> <?php echo $tab['headingtext'][$lang]; ?></div>
           <?php } ?> 
         <div class="textbanner2"><?php echo html_entity_decode($tab['title'][$lang]); ?></div>
        </div></div>
        <?php } ?>
       
        
     <?php if ($tab['href']) { ?></a> <?php } ?>
           
     </div>
     <?php } ?>
     
  
     
      </div>
	
   
<script type="text/javascript">	
                $(document).ready(function(){
				$('.double:even').addClass('doubleleft');	
				 $("#matban_box<?php echo $module; ?>.slider_banner .owl-addbanner").owlCarousel({
                 navigation : true,
                 pagination : false,
				 singleItem : true,
				 	 <?php if ($this->config->get('slider_pauseTime') != '') { ?>
			     autoPlay: <?php echo $this->config->get('slider_pauseTime'); ?>,
			         <?php } else {?>
				 autoPlay: 8000,	 
					 <?php } ?>
				<?php if ($this->config->get('slider_animSpeed') != '') { ?>
			      slideSpeed: <?php echo $this->config->get('slider_animSpeed'); ?>,
			    <?php } ?>
				 items 	 : 1 
			    });
	
                 });                                                                      
               </script>
</div>
<?php } ?>




