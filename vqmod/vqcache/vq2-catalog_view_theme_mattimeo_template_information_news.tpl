
<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
	<div class="breadcrumb">
	<?php foreach ($breadcrumbs as $i=> $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><?php if($i+1<count($breadcrumbs)) { ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> <?php } else { ?><?php echo $breadcrumb['text']; ?><?php } ?>

	<?php } ?>
	</div>
	<h1><?php echo $heading_title; ?></h1>
	<?php if(isset($news_info)) { ?>
		<div class="content-news">
			<div class="news">
				<?php echo $description; ?>
			</div>
			<div class="addthis">
			<?php if($addthis) { ?>
<!-- AddThis Button BEGIN -->
			<div class="share42init" data-image="<?php echo $thumb; ?>"></div>
			<script type="text/javascript" src="catalog/view/javascript/jquery/share42/share42.js"></script> 
		<!-- AddThis Button END --> 
			<?php } ?>
			</div>
		</div>
		<div class="buttons">
			<div class="right">
				<a onclick="location='<?php echo $news; ?>'" class="button"><span><?php echo $button_news; ?></span></a>
				
			</div>
		</div> 
        
	   <?php } elseif (isset($news_data)) { ?>
       
		<?php foreach ($news_data as $news) { ?>
           <div class="news_page">
			
	    	<?php if ($news['thumb']) { ?>
			<div class="image">
			<a href="<?php echo $news['href']; ?>">
		    <img src="<?php echo $news['thumb']; ?>" title="<?php echo $news['title']; ?>" alt="<?php echo $news['title']; ?>" ></a>
			</div>
            

            
		    <?php } ?>

			<div class="news_description <?php if ($news['thumb']) { ?> otstup <?php } ?>">
            
            <div class="datepost"><?php echo $news['posted']; ?></div>
            <div class="heading_news_mod"><a href="<?php echo $news['href']; ?>"> <?php echo $news['title']; ?></a></div>   
            
			<p><?php echo $news['description']; ?> .. </p>
			<a href="<?php echo $news['href']; ?>" class="readmore"> <?php echo $text_more; ?></a>
			</div>
			</div>
		<?php } ?>
		
<div class="pagination"><?php echo $pagination; ?></div>
	<?php } ?></div>
     <div class="cont_bottom"></div>
	<?php echo $content_bottom; ?>

<?php if ((isset($widthimg)) && (isset($heightimg))) { ?>
 <script type="text/javascript">
 $(document).ready(function() {
	var widthimg1 = <?php echo $widthimg; ?> + 20;
	var heightimg1 = <?php echo $heightimg; ?>;
	
	 <?php if ($this->config->get('gen_responsive') == '1') { ?>
	 enquire.register("only screen and (min-width: 790px)", {
			  match : function() {
		   $('.otstup').css('margin-left',widthimg1);
		    $('.otstup').css('min-height',heightimg1);
			  }
		    }).register("only screen and (max-width: 789px)", {
			  match : function() {
		   $('.otstup').css('margin-left',0);
		    $('.otstup').css('min-height','auto');
			  }
		    }); 

		<?php } else { ?>
	 $('.otstup').css('margin-left',widthimg1);
	   $('.otstup').css('min-height',heightimg1);
	<?php } ?>
	   	   
 });
</script>
<?php } ?>
<?php echo $footer; ?>