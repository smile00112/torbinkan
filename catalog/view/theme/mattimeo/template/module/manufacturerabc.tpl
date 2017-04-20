<div class="vendors">
<div class="vendors-list">
	<div class="h3">Производители</div>
<div class="vendors-list">
        <?php foreach ($manufactureres as $manufacturer) { ?>
<p class="vendors-item">
<a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a>
</p>
        <?php } ?>
</div>
<a class="all" href="/index.php?route=product/manufacturer">Все производители</a>	
</div>

</div>