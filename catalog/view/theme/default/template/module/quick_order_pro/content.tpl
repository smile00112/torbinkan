<div class="quick-order-pro <?php echo $class; ?> item-<?php echo $item_id; ?>">
	<?php if ($heading_title || $description) { ?>
	<div class="header">
		<?php if ($heading_title) { ?>
		<h2><?php echo $heading_title; ?></h2>
		<?php } ?>
		<?php if ($description) { ?>
		<div class="description">
			<?php echo $description; ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="notification">
		<?php if (count($error)) { ?>
		<div class="warning">
			<ul style="list-style: none; padding-left: 10px;">
				<?php foreach($error as $item) { ?>
				<li><?php echo $item; ?></li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
		<?php if (isset($success)) { ?>
		<div class="success"><?php echo $success; ?></div>
		<?php } ?>
		<?php if (isset($attention)) { ?>
		<div class="attention"><?php echo $attention; ?></div>
		<?php } ?>
	</div>
	<?php if ($type == 1) { ?>
	<div class="product-info-block">
		<p class="name"><?php echo $product_info['name']; ?> &times; <span class="quantity"><?php echo $product_info['quantity']; ?> шт.</span></p>
		<?php if ($template_info['show_option']) { ?>
		<ul class="options">
			<?php if ($product_info['option']) { ?>
			<?php foreach ($product_info['option'] as $option) { ?>
			<li>- <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small></li>
			<?php } ?>
			<?php } ?>
		</ul>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="fields">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="rows">
				<?php echo $fields; ?>
				<div class="row button">
					<?php if ($total) { ?>
					<div class="total">
						<span class="total-label"><?php echo $text_total; ?></span> <b><?php echo $total; ?></b>
					</div>
					<?php } ?>
					<input name="form_id" type="hidden" value="<?php echo $form_id; ?>" />
					<button type="submit" name="send" data-process="<?php echo $text_process; ?>" class="disabled"><span><?php echo $button_send; ?></span></button>
				</div>
			</div>
		</form>
	</div>
</div>