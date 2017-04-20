<?php if ($articles) { ?>
	<div class="stati_left">
	<div class="title_cat">Обзоры и советы</div>
		<ul class="lsnn"> 
		<?php foreach ($articles as $articles_story) { ?>
		<li>
				<div class="image_cont">
					<div class="image">
						<a href="<?php echo $articles_story['href']; ?>" style="background-image:url(<?php echo $articles_story['thumb']; ?>);"></a>							
					</div>
				</div>
				<a class="title-link" href="<?php echo $articles_story['href']; ?>"><?php echo $articles_story['title']; ?></a>
			</li>
		<?php } ?>
		</ul>
			<a class="all" href="<?php echo $articleslist; ?>"><?php echo $buttonlist; ?></a>
	</div>
<?php } ?>