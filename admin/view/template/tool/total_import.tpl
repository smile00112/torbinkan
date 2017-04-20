<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.1.x From HostJars opencart.hostjars.com #
#####################################################################################
?>
<?php echo $header; ?>
<style type="text/css">
.info_image{ 
	vertical-align: middle;
	padding-bottom: 3px;
}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($attention) { ?>
<div class="attention"><?php echo $attention; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src='view/image/feed.png' /><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
      <form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="settings_form">
        <table class="form">
        	<tr class="instructions"><td colspan="2">You can use the buttons below to skip to the steps you require. You will usually need to run at least Step 1 and Step 5. If you are using this module for the first time, you should run all steps in order from Step 1.</td></tr>
	        <?php if (count($saved_settings)) { ?>
	        <tr>
		        <td>
		        	<span><label for="settings_groupname">Load Settings Profile:&nbsp;<a href="http://helpdesk.hostjars.com/entries/21991386-using-profiles" target="_blank" alt="Profiles"><img class="info_image" src="view/image/information.png" title="Information About Using Profiles"/></a></label>
		        	<select name="settings_groupname">
		        			<?php foreach ($saved_settings as $setting_name) { ?><option value="<?php echo $setting_name; ?>"><?php echo $setting_name; ?></option><?php } ?>
	        		</select></span>
	        	</td>
	        	<td>
	        		<a href="#" class="button" onclick="$('#settings_form').submit();return false;" ><span>Load</span></a>
	        		<a href="#" class="button" id="deleteProfile"><span>Delete</span></a>
		        </td>
	        </tr>
	        <?php } ?>
	        <?php foreach ($pages as $page => $page_info) { ?>
	        <tr>
	        	<td style="width:80px;"><a href="<?php echo $page_info['link']; ?>" class="button"><span><?php echo $page_info['button']?></span></a></td>
	        	<td><a href="<?php echo $page_info['helpdesk']; ?>" target="_blank" alt="Profiles"><img class="info_image" src="view/image/information.png" title="Details on <?php echo $page_info['button']?>"/></a>&nbsp;<?php echo $page_info['title']?></td>
	        </tr>
	        <?php } ?>
        </table>
      </form>
  </div>
</div>
<script type="text/javascript">
 $("#deleteProfile").click(function(e) {
	current_selected = document.settings_form.settings_groupname.value;
	$.post('<?php echo $ajax_action ?>', {'profile_name':current_selected}, function(data) {
		if (data != 'error') {
			$("option[value='"+current_selected+"']").remove();
		}	
		alert(data);
	});
	e.preventDefault();
	return false;
 });
</script>
<?php echo $footer; ?>