<?php 
$Header_m_link = $this->registry->get('Header_m_link');
if  (isset($Header_m_link)) { ?>

<?php 	

$l_id = $this->config->get('config_language_id');

   foreach ($Header_m_link as $item) { 
   if ((array_key_exists($l_id, $item['namelink'])) && $item['namelink'][$l_id] != '') { 
      ?>
   	 <div> <a href="<?php echo $item['url']; ?>" title="<?php echo $item['namelink'][$l_id]; ?>"><?php echo $item['namelink'][$l_id]; ?></a></div>
   <?php } 
   } ?>
<?php } ?>

						
