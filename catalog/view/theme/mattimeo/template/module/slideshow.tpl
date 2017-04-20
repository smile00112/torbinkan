<?php if ($this->config->get('slider_status')) { ?>

<div class="slideshow">
  <div id="slideshow<?php echo $module; ?>" class="owl-carousel mattSlider">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
    <?php } ?>
    <?php } ?>
  </div>
</div>
<script type="text/javascript">	
                $(document).ready(function(){
				 $("#slideshow<?php echo $module; ?>").owlCarousel({
				<?php if ($this->config->get('slider_directionNav') != '1') { ?>
			     navigation:true,
			     <?php } ?>
			     <?php if ($this->config->get('slider_controlNav') != '1') { ?>
			     pagination: true,
			     <?php } else { ?>	
				  pagination: false,
				 <?php } ?>	 

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



<?php } else { ?>

 <?php  
    $this->document->addScript('catalog/view/theme/' . $this->config->get('config_template') . '/js/jquery.nivo.slider.pack.js');
    $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/nivo-slider.css');
 ?>



<div class="slideshow slider-wrapper">
  <div id="slideshow<?php echo $module; ?>" class="nivoSlider mattSlider">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
    <?php } ?>
    <?php } ?>
  </div>
</div>
<div class="advantages">
			<div class="adv-item">		
			<div class="adv-icon">
				<i class="fa fa-truck"></i>
			</div>
			<div class="adv-text">
				Бесплатная доставка по Москве от 10000 руб.			</div>
		</div>
			<div class="adv-item">		
			<div class="adv-icon">
				<i class="fa fa-star-o"></i>
			</div>
			<div class="adv-text">
				Все товары сертифицированы			</div>
		</div>
			<div class="adv-item">		
			<div class="adv-icon">
				<i class="fa fa-thumbs-o-up"></i>
			</div>
			<div class="adv-text">
				Скидки и акции. Успейте купить!		</div>
		</div>
			<div class="adv-item">		
			<div class="adv-icon">
				<i class="fa fa-history"></i>
			</div>
			<div class="adv-text">
				Круглосуточный прием заказов - 24/7			</div>
		</div>
	</div>
<script type="text/javascript"><!--
 $(document).ready(function(){
	$('#slideshow<?php echo $module; ?>').animate({opacity:1}, 50, function(){
		$(this).nivoSlider({
			<?php if (($this->config->get('slider_effect') != 'SELECT') && ($this->config->get('slider_effect') != '')) { ?>
			effect: '<?php echo $this->config->get('slider_effect'); ?>',
			<?php } ?>
			<?php if ($this->config->get('slider_animSpeed') != '') { ?>
			animSpeed: <?php echo $this->config->get('slider_animSpeed'); ?>,
			<?php } ?>
			<?php if ($this->config->get('slider_pauseTime') != '') { ?>
			pauseTime: <?php echo $this->config->get('slider_pauseTime'); ?>,
			<?php } ?>
			<?php if ($this->config->get('slider_directionNav') == '1') { ?>
			directionNav:false,
			<?php } ?>
			<?php if ($this->config->get('slider_controlNav') == '1') { ?>
			controlNav: false,
			<?php } ?>
			<?php if ($this->config->get('slider_pauseOnHover') == '1') { ?>
			pauseOnHover: false,
			<?php } ?>
			randomStart: false,
			});	
	});
	
	});
--></script> 
<?php } ?>
<div class="banners_main">	
				<div class="row">
				<a class="banner-item" href="http://krep-stroy.ru/instrument-i-oborudovanie/elektroinstrument" id="sl_1"  style="width:50%;">			
			<div class="item-block-cont">
				<div class="item-block">
											<div class="item-btn">
										
					<div class="item-text">Электроинструменты</div>					
											<button name="item-button" class="btn_buy" value="Перейти в каталог">Перейти в каталог</button>				
						</div>
									
				</div>				
			</div>
		</a>
				<a class="banner-item" href="http://krep-stroy.ru/specials" id="sl_2"  style="width:50%;">			
			<div class="item-block-cont">
				<div class="item-block">
											<div class="item-btn">
										
					<div class="item-text">Строительные товары со скидкой</div>					
											<button name="item-button" class="btn_buy" value="Получить свою скидку">Получить свою скидку</button>					
						</div>
									
				</div>				
			</div>
		</a>
					</div>
						<div class="row">
				<a class="banner-item" href="http://krep-stroy.ru/pechi-kaminy" id="sl_3" style="width:25%;">			
			<div class="item-block-cont">
				<div class="item-block">
										
					<div class="item-text small">Печи и камины</div>					
									
				</div>				
			</div>
		</a>
				<a class="banner-item" href="http://krep-stroy.ru/instrument-i-oborudovanie/ruchnoj-instrument" id="sl_4" style="width:50%;">			
			<div class="item-block-cont">
				<div class="item-block">
											<div class="item-btn">
										
					<div class="item-text">Ручной инструмент</div>					
											<button name="item-button" class="btn_buy" value="Выбрать себе по душе">Выбрать себе по душе</button>					
						</div>
									
				</div>				
			</div>
		</a>
				<a class="banner-item" id="sl_5" href="http://krep-stroy.ru/instrument-i-oborudovanie" style="width:25%;">			
			<div class="item-block-cont">
				<div class="item-block">
										
					<div class="item-text small">Строительная техника</div>					
									
				</div>				
			</div>
		</a>
					</div>
			</div>