<?php 
$Top_m_link = $this->registry->get('Top_m_link');
if (($this->config->get('status_menu') == 1 ) && (isset($Top_m_link))) { ?>

<?php 	
$l_id = $this->config->get('config_language_id');


   foreach ($Top_m_link as $item) { 
   if ((array_key_exists($l_id, $item['namelink'])) && $item['namelink'][$l_id] != ''){ 
      ?>
      <li>
   	  <a href="<?php echo $item['url']; ?>" title="<?php echo $item['namelink'][$l_id]; ?>"><?php echo $item['namelink'][$l_id]; ?></a>
      </li>
   <?php } 
   } ?>
<?php } ?>

						
