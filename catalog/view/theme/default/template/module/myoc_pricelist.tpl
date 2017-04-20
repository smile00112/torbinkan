<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
  	<?php if(VERSION < '1.5.5') { ?>
    <div class="box-category">
      <ul>
  	<?php } else { ?>
    <ul class="box-category">
    <?php } ?>
      <?php foreach ($pricelists as $pricelist) { ?>
      <li><a href="<?php echo $pricelist['url']; ?>"><?php echo $pricelist['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php if(VERSION < '1.5.5') { ?>
	</div>
    <?php } ?>
  </div>
</div>