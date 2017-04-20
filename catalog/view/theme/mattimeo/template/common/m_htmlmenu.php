<?php 
$htmlmenu_t1 = $this->registry->get('htmlmenu_t1');
$htmlmenu_t2 = $this->registry->get('htmlmenu_t2');
if (($this->config->get('status_menu2') == '1') && (isset($htmlmenu_t1))&& ($htmlmenu_t1 != '') ){ ?>

    
<li class="custombox">
	<a><?php echo $htmlmenu_t1; ?></a>

    <?php  if((isset($htmlmenu_t2)) && ($htmlmenu_t2 != '')){?>
    
		<div><?php echo $htmlmenu_t2; ?></div>
        
    <?php } ?>
    
</li>


   
<?php } ?>