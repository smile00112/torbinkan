<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <h1><?php echo $heading_title; ?></h1>
<?php if ($form_id == 'of4') { ?>
<?php echo $content_top; ?>
<?php } ?>

<?php if ($form_id == 'of1') { ?>
<?php echo $content_bottom; ?>
<?php } ?>
</div>
<?php echo $footer; ?>