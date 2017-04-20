<?php
           $wayPath = 'catalog/view/theme/mattimeo/template/';
?>

<?php echo $header; ?>
<?php if (isset($topcontent)) { ?>
<?php echo $topcontent; ?>
<?php } ?>
<?php if ($this->config->get('site_position') !== '1') { ?>
<?php echo $content_top; ?>
<?php } ?>


<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div id="content">

<?php if ($this->config->get('site_position') == '1') { ?>
<?php echo $content_top; ?>
<?php } ?>


<?php if ($this->config->get('site_position') !== '1') { ?>
  <?php echo $content_bottom; ?>
<?php } ?>

<?php if ($this->config->get('site_position') == '1') { ?>

  <div class="cont_bottom"></div>
  <?php echo $content_bottom; ?>

<?php } ?>
</div>



<?php if ($this->config->get('slider_position') == '2') { ?>
<script type="text/javascript">

 <?php if ($this->config->get('gen_responsive') != '1') { ?>
 
 $(document).ready(function() {
	
	 var heightheader = $('#header').innerHeight() - $('#header .header_topbox').innerHeight() + 51;
	 $('.full_container .mattSlider').css('top',-heightheader);
	 $('.full_container .mattSlider').css('margin-bottom',-heightheader);
	 $('#all_header').removeClass("color").addClass("nocolor");
	 });
	 

 <?php } else { ?>
 
  if ($(window).width() >= '990'){
     var heightheader = $('#header').innerHeight() - $('#header .header_topbox').innerHeight() + 51;
	 $('.full_container .mattSlider').css('top',-heightheader);
	 $('.full_container .mattSlider').css('margin-bottom',-heightheader);
	 $('#all_header').removeClass("color").addClass("nocolor");
  };

	 $(window).resize(function (){	
	   if ($(window).width() >= '990'){
	     var heightheader = $('#header').innerHeight() - $('#header .header_topbox').innerHeight() + 51;
		 $('#all_header').removeClass("color").addClass("nocolor");
	  } else {
		 var heightheader = 0; 
		 $('#all_header').removeClass("nocolor").addClass("color");
		 
	  };
		
		  $('.full_container .mattSlider').css('top',-heightheader);
	      $('.full_container .mattSlider').css('margin-bottom',-heightheader);
	      

		    }); 		
	
	
			   
    <?php } ?>
	   	   

</script>
 <?php } ?>
 
 <?php if ($this->config->get('comptext_status') == '1')  {
 include $wayPath ."/common/m_custombox.php"; 
 } ?>

<?php echo $footer; ?>