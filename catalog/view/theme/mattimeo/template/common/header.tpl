<?php
           $wayPath = 'catalog/view/theme/mattimeo/template/';
?> 
<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; if (isset($_GET['page'])) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?></title>
<base href="<?php echo $base; ?>" />
 <?php if ($this->config->get('gen_responsive') == '1') { ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php } ?>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; if (isset($_GET['page'])) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta property="og:title" content="<?php echo $title; if (isset($_GET['page'])) { echo " - ". ((int) $_GET['page'])." ".$text_page;} ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo $og_url; ?>" />
<?php if ($og_image) { ?>
<meta property="og:image" content="<?php echo $og_image; ?>" />
<?php } else { ?>
<meta property="og:image" content="<?php echo $logo; ?>" />
<?php } ?>
<meta property="og:site_name" content="<?php echo $name; ?>" />
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/mattimeo/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/mattimeo/stylesheet/style_one.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/mattimeo/stylesheet/style_respons.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">



<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/common2.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/mattimeo/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/mattimeo/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<!--JS 
*******************************************-->
<script type="text/javascript" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script type="text/javascript" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/owl.carousel.js"></script>
<script type="text/javascript" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/main.js"></script>

<script type="text/javascript" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/responsive/enquire.min.js"></script>
<script type="text/javascript" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/newselect.js"></script>


<!--Top Control 
*******************************************-->
<?php if ($this->config->get('topcontrol') == '1') { ?>
<script src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/scroll/scrolltopcontrol.js" type="text/javascript"></script>
<?php } ?>

<!--Fixed menu 
*******************************************-->
<?php if ($this->config->get('fixmenu') == '1') { ?>
<script src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/fixmenu.js" type="text/javascript"></script>
<?php } ?>

<!--Responsive
*******************************************-->
 <?php if ($this->config->get('gen_responsive') == '1') { ?>
<script type="text/javascript" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/js/responsive/menu_script.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/mattimeo/stylesheet/responsive.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/topmenu.css" />
<?php } ?>
<!--***************************************-->

<!--CSS 
*******************************************-->
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/carousel_style.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/owl.carousel.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/owl.theme.css" />

<!--***************************************-->
<!-- Theme Fonts
**************************************-->
<?php if ($this->config->get('fonts_status') == '1') { 
		include $wayPath . "common/m_fonts.php";
	} ?>
<!--***************************************-->
<!-- Theme Custom colors
**************************************-->
<?php if ($this->config->get('color_status') == '1') { 
		include $wayPath . "common/m_colors.php";
	} ?>
<!--***************************************-->
<!-- Parallax box
**************************************-->
<?php if ($this->config->get('comptext_status') == '1')  { ?>

     <?php if($this->config->get('parall_fonts')!='') {
		$reg2fonts = array('Arial', 'Verdana', 'Helvetica', 'Lucida Grande', 'Trebuchet MS', 'Times New Roman', 'Tahoma', 'Georgia' );
         if (in_array($this->config->get('parall_fonts'), $reg2fonts)==false && $this->config->get('parall_fonts')!='') { ?>
         <link href='//fonts.googleapis.com/css?family=<?php echo $this->config->get('parall_fonts') ?>&v1' rel='stylesheet' type='text/css'>
       <?php } } ?>

   <style type="text/css">
	<?php if($this->config->get('parallax_bg')!='' ){ ?> 
     #center_custom_box {
	background-image: url(<?php echo $this->config->get('config_url'); ?>image/<?php echo $this->config->get('parallax_bg') ?>);
	}
    <?php } ?>
	
	<?php if(($this->config->get('parall_fonts') != '') ||
         ($this->config->get('parall_fonts_size')!='none') ||
		 ($this->config->get('parall_fonts_weight')) ||
		 ($this->config->get('parall_fonts_transf')) ||
		 ($this->config->get('parall_fonts_color')!= ''))  
  {?>
  	
    .custom_box_parallax .bigtext { 
    <?php if($this->config->get('parall_fonts') != '') { 
     $fontpre =  $this->config->get('parall_fonts'); $font1 = str_replace("+", " ", $fontpre); ?>
	font-family: <?php echo $font1 ?>;
	<?php } 
	 if($this->config->get('parall_fonts_size')!='none') { ?> 
	font-size: <?php echo $this->config->get('parall_fonts_size') ?>px;
	<?php } 
	 if($this->config->get('parall_fonts_weight')) { ?>
	font-weight:bold;
	<?php } 
	 if($this->config->get('parall_fonts_transf')) { ?>
	text-transform:uppercase;
    <?php }
	if($this->config->get('parall_fonts_color') != '') { ?>
	color:<?php echo $this->config->get('parall_fonts_color') ?>;
    <?php } ?>
	}
	
	
<?php } ?>
	</style>
    
<?php } ?>
<!--***************************************-->
    

<?php if ($stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>

<script type="text/javascript">
	$(document).ready(function(){
		var link = window.location.pathname;
		$('.store-horizontal li a[href="'+link+'"]'  ).addClass('root-item-selected');
	});
	</script>
<script type="text/javascript">
	$(document).ready(function(){
		var link = window.location.pathname;
		$('.submenu li a[href="'+link+'"]'  ).addClass('root-item-selected');
	});
	</script>
<?php echo $google_analytics; ?>
</head>
<body>              
<div class="center" id="container" >
 <!--Move panels-->
<div class="foot_panel_all">
<div class="foot_panel">
<div class="foot_panel_1">							
<div class="kabinet">
<?php if (!$logged) { ?><a href="/index.php?route=account/login" class="login_anch"><i class="fa fa-user"></i><span>Войти</span></a><?php } else { ?><?php echo $text_logged; ?><?php } ?>
<a class="register" href="/index.php?route=account/register" title="Регистрация" rel="nofollow"><i class="fa fa-user-plus"></i><span>Регистрация</span></a>
</div>		
<div class="compare_line">
<a href="<?php echo $compare; ?>" id="compare-total">
			<i class="fa fa-bar-chart"></i>
			<?php echo $text_compare; ?>
		</a>
</div>		
<div class="delay_line"><a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a></div>							
</div>
<div class="foot_panel_2"><?php echo $cart; ?></div>
</div>
</div>  
<!--end move panels-->     
<header id="header" class="header_middle ">
<div  class="header_1">
  <div  class="logo">
  <?php if (isset($this->request->get['route']) && $this->request->get['route'] != 'common/home') { ?> 
  <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" >
  <span>Магазин строительных товаров</span>
  </a>
    <?php } else { ?>
   <a> <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" >
	<span>Магазин строительных товаров</span>
   </a>
    <?php } ?>
  </div></div>
  <div class="header_2">
  <div id="search_h" >
  <i class="fa fa-search"></i>
    <input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" >
	<input type="submit" name="submit" class="button-search" value="Найти">
  </div> </div>
					<div class="header_3">
						<div class="schedule">
							<p class="time"><i class="fa fa-clock-o"></i><span>Время работы:</span></p><p>ПН-ПТ 09:00 - 18:00</p></div>
					</div>
					<div class="header_4">
						<div class="telephone">
							<p><span>7 (495)</span> 664-35-01<i class="fa fa-phone"></i></p><p><span>7 (929)</span> 648-04-53<i class="fa fa-phone"></i></p>							
							<a class="btn_buy apuo callback_anch callme" href="./index.php?route=module/callme/open"><span class="cont"><i class="fa fa-phone"></i><span class="text">Заказать звонок</span></span></a>
						</div>
					</div>

<div class="top-menu">
<ul class="store-horizontal">
<li><a href="/" >Главная</a></li>
<li><a href="/category" class="root-item">Каталог</a></li>
<li><a href="/brands" class="root-item">Производители</a></li>
<li><a href="/news" class="root-item">Новости</a></li>
<li><a href="/reviews" class="root-item">Обзоры</a></li>
<li><a href="/oplata" class="root-item">Оплата</a></li>
<li><a href="/dostavka" class="root-item">Доставка</a></li>
<li><a href="/specials" class="root-item">Акции</a></li>
<li><a href="/garantiy" class="root-item">Гарантия</a></li>
<li><a href="/o-kompanii" class="root-item">О Компании</a></li>			
<li><a href="/contact-us" class="root-item">Контакты</a></li>
</ul>
</div>   
</header>

<div class="top_panel">
<div class="panel_1">					
<ul class="section-vertical">
	<li>
		<a href="javascript:void(0)" class="showsection"><i class="fa fa-bars"></i><span>Каталог</span></a>
<?php if ($categories) { ?>
<div class="catalog-section-list" style="display:none;">
    <?php foreach ($categories as $category) { ?>
    <div class="catalog-section">
	<div class="catalog-section-title" style="margin:0px 0px 4px 0px;"><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	<span class="showsectionchild"><i class="fa fa-minus"></i><i class="fa fa-plus"></i><i class="fa fa-minus-circle"></i><i class="fa fa-plus-circle"></i></span>
	</div>
      <?php if ($category['children']) { ?>
      <div class="catalog-section-childs" style="display:none;">
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <div class="catalog-section-child">
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
         <a href="<?php echo $category['children'][$i]['href']; ?>"><span class="child"><span class="text"><?php echo $category['children'][$i]['name']; ?></span></span></a>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
    <?php } ?>
</div>
<?php } ?>
	</li>
</ul>
</div>

<div class="panel_2">					
<ul class="store-vertical">
	<li><a href="javascript:void(0)" class="showsubmenu">Меню</a>
	<ul class="submenu" style="display:none;">
<li><a href="/" >Главная</a></li>
<li><a href="/category" class="root-item">Каталог</a></li>
<li><a href="/brands" class="root-item">Производители</a></li>
<li><a href="/news" class="root-item">Новости</a></li>
<li><a href="/reviews" class="root-item">Обзоры</a></li>
<li><a href="/oplata" class="root-item">Оплата</a></li>
<li><a href="/dostavka" class="root-item">Доставка</a></li>
<li><a href="/specials" class="root-item">Акции</a></li>
<li><a href="/garantiy" class="root-item">Гарантия</a></li>
<li><a href="/o-kompanii" class="root-item">О Компании</a></li>			
<li><a href="/contact-us" class="root-item">Контакты</a></li>				
	</ul>
	</li>
</ul>
</div>
					<div class="panel_3">
						<ul class="contacts-vertical">
							<li>
								<a class="showcontacts" href="javascript:void(0)"><i class="fa fa-phone"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel_4">
						<ul class="search-vertical">
							<li class=''>
								<a class="showsearch" href="javascript:void(0)"><i class="fa fa-search"></i></a>
							</li>
						</ul>
					</div>
</div>
<div class="content"><div id="notification"></div></div>

<div class="center">
<div class="content-wrapper">
<div class="content">


