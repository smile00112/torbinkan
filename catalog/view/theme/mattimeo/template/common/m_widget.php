 <?php if ($this->config->get('facebook_f_status') == '1') {?> 
   <div id="box-facebook" class="movebox">
      <div class="icon-facebook"></div>
    <div class="content-move box">
 <h2 class="box-heading"><?php echo $this->config->get('facebook_f_title'); ?></h2>
	    <div> 
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

       <div class="fb-like-box" 
       data-href="https://www.facebook.com/<?php echo $this->config->get('facebook_f_name'); ?>"  
       data-width="240" 
       data-height="230"
       data-show-faces="true" 
       data-colorscheme="<?php echo $this->config->get('facebook_f_theme'); ?>" 
       data-stream="false" 
       data-show-border="false" 
       data-header="false">
       </div>
	</div>
</div>
</div> 
<?php } ?>
           
	<?php if($this->config->get('twitter_f_status') == '1'){?>           
<div id="box-twitter" class="movebox">
<!--  TWITTER -->
     <div class="icon-twitter"></div>
	<div class="content-move box">
		<h2 class="box-heading"><?php echo $this->config->get('twitter_f_title'); ?></h2>
		<div>
		<a class="twitter-timeline" height="211" <?php if($this->config->get('twitter_f_link') != ''){ ?>data-link-color="#<?php echo $this->config->get('twitter_f_link'); ?>"<?php }else{ ?>data-link-color="#222"<?php } ?> href="https://twitter.com/<?php echo $this->config->get('twitter_f_user'); ?>" data-chrome="noheader nofooter noborders  transparent" data-theme="<?php echo $this->config->get('twitter_f_theme'); ?>"  data-related="twitterapi,twitter" data-aria-polite="assertive" data-widget-id="<?php echo $this->config->get('twitter_f_id'); ?>">Tweets by @<?php echo $this->config->get('twitter_f_user'); ?></a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		<a target="_blank" href="http://twitter.com/#!/<?php echo $this->config->get('twitter_f_user'); ?>"><?php echo $this->config->get('twitter_f_user'); ?></a>
        </div>
	</div>

</div>
<?php } ?>

  
	<?php if($this->config->get('vk_f_status') == '1'){?>
	<div id="box-vkt" class="movebox">
     <!--VKONTAKTE-->
      <div class="icon-vkt"></div>
	  <div class="content-move box">
		<h2 class="box-heading"><?php echo $this->config->get('vk_f_title'); ?></h2>
          <script type="text/javascript" src="//vk.com/js/api/openapi.js?112"></script>

      <!-- VK Widget -->
     <div id="vk_groups"></div>
     <script type="text/javascript">
     VK.Widgets.Group("vk_groups", {mode: 2, width: "240", height: "240"}, <?php echo $this->config->get('vk_f_id')?>);
     </script>    

     </div> 
	</div>	
	
	<?php } ?>

