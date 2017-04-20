<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table id="module" class="list">
					<tr>
						<td style="width: 200px">
							<?php echo $text_response?>
						</td>
						<td><input style="width: 100%" value="<?php echo $oneclick_settings['response_text']?>" name="oneclick_settings[response_text]"></td>
					</tr>
					<tr>
						<td><?php echo $text_order_status?></td>
						<td><select name="oneclick_settings[order_status_id]">
							<?php foreach($order_statuses as $status) { ?>
							    <option value="<?php echo $status['order_status_id']?>" <?php if($status['order_status_id'] == $oneclick_settings['order_status_id']) { echo 'selected="selected"'; }?>><?php echo $status['name']; ?></option>
						     <? }?>
						</select></td>
					</tr>
				</table>
			</form>
		</div>
	</div>

<?php echo $footer; ?>