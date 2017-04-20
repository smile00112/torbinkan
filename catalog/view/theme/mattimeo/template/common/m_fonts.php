<?php 

	if($this->config->get('body_fonts')!='' ||
	$this->config->get('title_fonts')!='' ||
	$this->config->get('menu_fonts')!='' ||
	$this->config->get('module_fonts')!='' ||
	$this->config->get('price_fonts')!='' ||
	$this->config->get('button_fonts')!='' ||
	$this->config->get('banner_fonts')!='' ||
	$this->config->get('banner_slider_fonts')!='' ||
	$this->config->get('name_fonts')!='') {
		
		$regfonts = array('Arial', 'Verdana', 'Helvetica', 'Lucida Grande', 'Trebuchet MS', 'Times New Roman', 'Tahoma', 'Georgia' );
		
		// body font
		if (in_array($this->config->get('body_fonts'), $regfonts)==false && $this->config->get('body_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('body_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 
		
		// title font
		if (in_array($this->config->get('title_fonts'), $regfonts)==false && $this->config->get('title_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('title_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 
		
		// menu font
		if (in_array($this->config->get('menu_fonts'), $regfonts)==false && $this->config->get('menu_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('menu_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 
		
		// module font
		if (in_array($this->config->get('module_fonts'), $regfonts)==false && $this->config->get('module_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('module_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 
		
		// price font
		if (in_array($this->config->get('price_fonts'), $regfonts)==false && $this->config->get('price_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('price_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 
		
		// button font
		if (in_array($this->config->get('button_fonts'), $regfonts)==false && $this->config->get('button_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('button_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 
		 // banner heading font
		if (in_array($this->config->get('banner_fonts'), $regfonts)==false && $this->config->get('banner_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('banner_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 
		// banner text font
		if (in_array($this->config->get('banner_slider_fonts'), $regfonts)==false && $this->config->get('banner_slider_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('banner_slider_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 
		
		// name font
		if (in_array($this->config->get('name_fonts'), $regfonts)==false && $this->config->get('name_fonts')!='') { ?>
            <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('name_fonts') ?>:300,400,600&subset=latin,cyrillic&v1' rel='stylesheet' type='text/css'>
        <?php } 


} 

?>

<style type="text/css">


<?php if(($this->config->get('body_fonts') != '') ||
         ($this->config->get('body_fonts_size')!='none') ||
		 ($this->config->get('body_fonts_weight')) 
  ){?> 

  body { 
    <?php if($this->config->get('body_fonts') != '') { 
     $fontpre =  $this->config->get('body_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	 if($this->config->get('body_fonts_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('body_fonts_size') ?>px;
	<?php } 
	 if($this->config->get('body_fonts_weight')) { ?>
	font-weight:bold;
	<?php } ?>
	}
	<?php } ?>

<?php if(($this->config->get('title_fonts') != '') ||
         ($this->config->get('title_fonts_size')!='none') ||
		 ($this->config->get('title_fonts_weight')) ||
		 ($this->config->get('title_fonts_transf')) 
  ){?> 	
h1, .welcome, .verticaltab .name_categ a { 
    <?php if($this->config->get('title_fonts') != '') { 
     $fontpre =  $this->config->get('title_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	 if($this->config->get('title_fonts_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('title_fonts_size') ?>px;
	<?php } 
	 if($this->config->get('title_fonts_weight')) { ?>
	font-weight:bold;
	<?php } 
	 if($this->config->get('title_fonts_transf')) { ?>
	text-transform:uppercase;
    <?php } ?>
	}
<?php } ?>

<?php if(($this->config->get('menu_fonts') != '') ||
         ($this->config->get('menu_fonts_size')!='none') ||
		 ($this->config->get('menu_fonts_weight') ||
		 ($this->config->get('menu_fonts_transf')) )
  ){?> 

.menu > ul > li > a, .menu1 .mobilemenu > li > a, .navbar .btn-navbar div , 
.menu > ul > li > div > ul > li > a  { 
    <?php if($this->config->get('menu_fonts') != '') { 
     $fontpre =  $this->config->get('menu_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	 if($this->config->get('menu_fonts_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('menu_fonts_size') ?>px;
	<?php } 
	 if($this->config->get('menu_fonts_weight')) { ?>
	font-weight:normal;
	<?php }
	if($this->config->get('menu_fonts_transf')) { ?>
	text-transform:none;
	 <?php } ?>
	}
	<?php } ?>
	
 <?php if($this->config->get('menu_fonts') != '') { 
     $fontpre =  $this->config->get('menu_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
.menu > ul > li  a, #header #welcome a, #header .links > a, #header .links > div, #header .links > div a {font-family: <?php echo $font1 ?>;}
	<?php } ?>
	
	
<?php if(($this->config->get('categ_fonts') != '') ||
         ($this->config->get('categ_fonts_size')!='none') ||
		 ($this->config->get('categ_fonts_weight')) ||
		 ($this->config->get('categ_fonts_transf')) 
  ){?> 

.box-content > ul > li > a,
.box-content .accordeon_categ > ul > li > a,
.accordeon_description .accordeon_plus    { 
    <?php if($this->config->get('categ_fonts') != '') { 
     $fontpre =  $this->config->get('categ_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	 if($this->config->get('categ_fonts_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('categ_fonts_size') ?>px;
	<?php } 
	 if($this->config->get('categ_fonts_weight')) { ?>
	font-weight:bold;
	<?php } 
	if($this->config->get('categ_fonts_transf')) { ?>
	text-transform:uppercase;
    <?php } ?>
	}
	<?php } ?>
	
<?php if(($this->config->get('module_fonts') != '')  ){
	 $fontpre =  $this->config->get('module_fonts'); $font1 = str_replace("+", " ", $fontpre);?>	
.box .box-heading, .box .box-heading a, #footer h3,
.verticaltab .select_category > a, h2, .htabs a  { 
	font-family: <?php echo $font1 ?>;
	}
	<?php } ?>		
	
<?php if(($this->config->get('module_fonts_size')!='none') ||
		 ($this->config->get('module_fonts_weight')) ||
		 ($this->config->get('module_fonts_transf')) 
  ){?>	
.box .box-heading, .box .box-heading a, #footer h3,
.verticaltab .select_category > a { 
    <?php  if($this->config->get('module_fonts_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('module_fonts_size') ?>px;
	<?php } 
	if($this->config->get('module_fonts_weight')) { ?>
	font-weight:normal;
	<?php } 
	 if($this->config->get('module_fonts_transf')) { ?>
	text-transform:none;
    <?php } ?>
	}
	<?php } ?>

	
	<?php if(($this->config->get('banner_fonts') != '') ||
	      ($this->config->get('banner_fonts_size2')!='none') ||
		 ($this->config->get('banner_fonts_weight')) ||
		 ($this->config->get('banner_fonts_transf')) 
  ){?>	
.matban_box .threeheading { 
    <?php if($this->config->get('banner_fonts') != '') { 
     $fontpre =  $this->config->get('banner_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	 if($this->config->get('banner_fonts_size2')!='none') { ?> 
	font-size: <?php echo $this->config->get('banner_fonts_size2') ?>px;
	<?php } 
	if($this->config->get('banner_fonts_weight')) { ?>
	font-weight:normal;
	<?php } 
	 if($this->config->get('banner_fonts_transf')) { ?>
	text-transform:none;
    <?php } ?>
	}
	<?php } ?>
	<?php if($this->config->get('banner_fonts_size1')!='none') { ?> 
	.matban_box.maska_text .threeheading{font-size: <?php echo $this->config->get('banner_fonts_size1') ?>px;}
	<?php } ?>
	<?php if($this->config->get('banner_fonts_size3')!='none') { ?> 
	.matban_box.slider_banner .threeheading{font-size: <?php echo $this->config->get('banner_fonts_size3') ?>px;}
	<?php } ?>
	<?php if($this->config->get('banner_fonts_size4')!='none') { ?> 
	.matban_box.slider_banner.double .threeheading{font-size: <?php echo $this->config->get('banner_fonts_size4') ?>px;}
	    <?php if ($this->config->get('gen_responsive') == '1') { ?>
	 @media only screen and (max-width: 789px) {.matban_box.slider_banner.double .threeheading { font-size:2em;}}}
	    <?php } ?>
	
	
	<?php } ?>
	
	
	<?php if(($this->config->get('banner_slider_fonts')!='none') ||
		 ($this->config->get('banner_slider_weight')) ||
		 ($this->config->get('banner_slider_transf')) 
  ){?>	
.matban_box.slider_banner .textbanner2, .matban_box.maska_text .textbanner2 { 
     <?php if($this->config->get('banner_slider_fonts') != '') { 
     $fontpre =  $this->config->get('banner_slider_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	if($this->config->get('banner_slider_weight')) { ?>
	font-weight:bold;
	<?php } 
	 if($this->config->get('banner_slider_transf')) { ?>
	text-transform:uppercase;
    <?php } ?>
	}
	<?php } ?>
	<?php if($this->config->get('banner_slider_size1')!='none') { ?> 
	.matban_box.maska_text .textbanner2{font-size: <?php echo $this->config->get('banner_slider_size1') ?>px;}
	<?php } ?>
	<?php if($this->config->get('banner_slider_size3')!='none') { ?> 
	.matban_box.slider_banner .textbanner2{font-size: <?php echo $this->config->get('banner_slider_size3') ?>px;}
	<?php } ?>
	<?php if($this->config->get('banner_slider_size4')!='none') { ?> 
	.matban_box.slider_banner.double .textbanner2{font-size: <?php echo $this->config->get('banner_slider_size4') ?>px;}
	   <?php if ($this->config->get('gen_responsive') == '1') { ?>
	     @media only screen and (max-width: 789px) {.matban_box.slider_banner.double .textbanner2 { font-size:1em;}}
	    <?php } ?>
	<?php } ?>
	
<?php if(($this->config->get('mattimeo_categ_fonts')!='none') ||
	     ($this->config->get('mattimeo_categ_size')!='none') ||
		 ($this->config->get('mattimeo_categ_weight')) ||
		 ($this->config->get('mattimeo_categ_transf')) 
  ){?>	
.verticaltab .description_categ  { 
     <?php if($this->config->get('mattimeo_categ_fonts') != '') { 
     $fontpre =  $this->config->get('mattimeo_categ_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
    if($this->config->get('mattimeo_categ_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('mattimeo_categ_size') ?>px;
	<?php } 
	if($this->config->get('mattimeo_categ_weight')) { ?>
	font-weight:bold;
	<?php } 
	 if($this->config->get('mattimeo_categ_transf')) { ?>
	text-transform:uppercase;
    <?php } ?>
	}
	<?php } ?>	
 <?php if($this->config->get('price_fonts') != '') { 
     $fontpre =  $this->config->get('price_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
.block2 .price, .box-product .price, .product-grid .price, .product-list .price, .product-info .price,
.compare-info .price    { 
   font-family: <?php echo $font1 ?>;
	}
	<?php } ?>
	
	  <?php if($this->config->get('price_fonts_weight')) { ?>
	.block2 .price, .box-product .price, .product-grid .price, .product-list .price, .product-info .price,
   .compare-info .price{
	  font-weight:normal; 
	   }
   <?php } ?>
	
	<?php if($this->config->get('price_fonts_size')!='none') { ?> 
.block2 .price, .box-product .price, .product-grid .price, .product-list .price{ 
	font-size: <?php echo $this->config->get('price_fonts_size') ?>px;
	}
	<?php } ?>
	
	<?php if($this->config->get('bigprice_fonts_size')!='none') { ?> 
	.product-info .price { 
	font-size: <?php echo $this->config->get('bigprice_fonts_size') ?>px;
	}
	<?php } ?>
	
<?php if(($this->config->get('button_fonts') != '') ||
         ($this->config->get('button_fonts_size')!='none') ||
		 ($this->config->get('button_fonts_weight') ||
		 ($this->config->get('button_fonts_transf')) )
   ){?>		
a.button, input.button, a.readmore  { 
    <?php if($this->config->get('button_fonts') != '') { 
     $fontpre =  $this->config->get('button_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	if($this->config->get('button_fonts_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('button_fonts_size') ?>px;
	<?php } 
	 if($this->config->get('button_fonts_weight')) { ?>
	font-weight:bold;
	<?php } 
	 if($this->config->get('button_fonts_transf')) { ?>
	text-transform:none;}
    <?php } ?>

	<?php } ?>	
<?php if(($this->config->get('name_fonts') != '') ||
         ($this->config->get('name_fonts_size')!='none') ||
		 ($this->config->get('name_fonts_weight')) ||
		 ($this->config->get('name_fonts_transf')) 
  ){?>		
.box-product .name a, .product-grid .name a, .product-list .name a,
.compare-info .name a, .cart-info tbody .name a, .wishlist-info tbody .name a,
.block2 .name a, .heading_news_mod a  { 
    <?php if($this->config->get('name_fonts') != '') { 
     $fontpre =  $this->config->get('name_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	 if($this->config->get('name_fonts_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('name_fonts_size') ?>px;
	<?php } 
	 if($this->config->get('name_fonts_weight')) { ?>
	font-weight:bold;
	<?php } 
	 if($this->config->get('name_fonts_transf')) { ?>
	text-transform:none;
    <?php } ?>
	}
	<?php } ?>	
</style>

