

	<div class="title_cat"><?php echo $heading_title; ?></div>
			<ul class="left-menu">
				<?php foreach ($categories as $category) { ?>
				<?php if ($category['category_id'] == $category_id) { ?>
				<li class="parent selected <?php if ($category['category_id'] == $category_id) { ?>category_open<?php } ?>">
					<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?><span class="arrow"></span></a>
				<?php } else { ?>
				<li class="parent ">
					<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?><span class="arrow"></span></a>
					<?php } ?>
					<?php if ($category['children']) { ?>
					<div class="catalog-section-childs ">
						<?php foreach ($category['children'] as $child) { ?>
						<div class="catalog-section-child">
				
							<a href="<?php echo $child['href']; ?>">
							<span class="child"><span class="image"><img src="<?php echo $child['thumb']; ?>" ></span><span class="text"><?php echo $child['name']; ?></span></span>
							</a>
						
						</div>
						<?php } ?>
					</div>
					<?php } ?>
				</li>
				<?php } ?>
			</ul>

<ul class="new_leader_disc">
								<li>
									<a class="new" href="/new_product">
										<span class="icon">New</span>
										<span class="text">Новинки</span>
									</a>
								</li>
								<li>
									<a class="saleleader" href="/hit">
										<span class="icon">Хит</span>
										<span class="text">Хиты продаж</span>
									</a>
								</li>
								<li>
									<a class="discount" href="/specials">
										<span class="icon">%</span>
										<span class="text">Скидки</span>
									</a>
								</li>
							</ul>