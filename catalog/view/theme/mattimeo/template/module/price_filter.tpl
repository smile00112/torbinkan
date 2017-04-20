<style type="text/css">
    .ui-widget-header {background: #FDDF83;}
</style>
<script type="text/javascript">
	$(function() {
		$slider = $("#slider");
		$amountmin = $("#amount-min");
                $amountmax = $("#amount-max");
		$slider.slider({
			range: true, 
			min: <?php echo $price_min; ?>, 
			max: <?php echo $price_max; ?>, 
			values: [<?php echo $price_minprice; ?>, <?php echo $price_maxprice; ?>],
			slide: function(event, ui) {
                                <?php echo $symbol_html; ?>
			},
			stop: function(event, ui){
				location.href= '<?php echo $action ;?>&minprice='+ui.values[0]+'&maxprice='+ui.values[1]+'';

			}
		});
<?php echo $symbol; ?>
		
	});
</script>
<div class="box">
  <div class="box-content price-filter">
      <div id="range" style="margin-bottom: 6px; margin-left: -7px; margin-right: -7px;">
      <span id="amount-min" style="float: left;"></span>
      <span style="float: right;" id="amount-max"></span></div>
    <br />
    <div id="slider" style="margin-top:6px;"></div>
  </div>
</div>