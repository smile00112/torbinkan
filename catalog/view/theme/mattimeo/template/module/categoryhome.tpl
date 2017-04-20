<div class="catalog-section-list">
				<?php foreach ($categories as $category) { ?>
				
				<div class="catalog-section">
				<div class="catalog-section-title active" style="margin:0px 0px 4px 0px;">
				<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a><?php if ($category['children']) { ?><span class="showchild"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></span><?php } ?>
				</div>
					<?php if ($category['children']) { ?>
					<div class="catalog-section-childs">
						<?php foreach ($category['children'] as $child) { ?>
						<div class="catalog-section-child">
							<a href="<?php echo $child['href']; ?>">
							<span class="child"><span class="image"><img src="<?php echo $child['thumb']; ?>" ></span><span class="text"><?php echo $child['name']; ?></span></span>
							</a>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$(".showchild").click(function() {
			var clickitem = $(this);
			if(clickitem.parent("div").hasClass('active')) {
				clickitem.parent("div").removeClass("active");
			} else {
				clickitem.parent("div").addClass("active");
			}
			clickitem.parent("div").parent("div").find(".catalog-section-childs").slideToggle();
		});
	});
	//]]>
</script>