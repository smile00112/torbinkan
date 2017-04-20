<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['../../../../../../private/var/folders/zE/zEHjsKdkG5ueD1bdacX97U+++TM/-Tmp-/857713fe-913d-4481-bf88-e0bd567e505a/public/sites/development.y84.nl/pc/admin/view/template/module/href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> Product Configurator</h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
    	<p> This version of Product Configurator has no editable options yet. <p>
		
    </div>
  </div>
</div>
<script type="text/javascript"><!--

}
//--></script> 
<?php echo $footer; ?>