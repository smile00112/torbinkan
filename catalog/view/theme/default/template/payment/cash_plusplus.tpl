<?php if ($this->config->get('cash_plusplus_instruction_attach')){ ?>
<h2><?php echo $text_instruction; ?></h2>
<div class="content">
  <p><?php echo $bank; ?></p>
</div>
<?php } ?>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		type: 'get',
		url: 'index.php?route=payment/cash_plusplus/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script> 
