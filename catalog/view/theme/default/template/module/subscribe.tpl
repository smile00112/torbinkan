<div class="box subs">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
		<div class="subscribe<?php echo $module; ?>">
			<span><?php echo $text_enter_email; ?></span><br/>
			<input type="text" name="subscribe_email<?php echo $module; ?>" value="" />
			<input type="button" value="<?php echo $button_subscribe; ?>" onclick="addSubscribe(<?php echo $module; ?>);" class="button" />
		</div>
	</div>
</div>
