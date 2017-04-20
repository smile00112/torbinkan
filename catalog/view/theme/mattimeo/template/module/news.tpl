
<?php if ($news) { ?>


<div class="news_home">
	<div class="h3">Новости</div>
	<a class="all" href="/news/">Все новости</a>
	<div class="clr"></div>
	<ul class="lsnn">
	<?php foreach ($news as $news_story) { ?>
					<li>
				<a href="<?php echo $news_story['href']; ?>">
					<span class="date"><?php echo $news_story['posted']; ?></span>
					<span class="title-link">
						<span><?php echo $news_story['title']; ?></span>
					</span>
				</a>
			</li>
			 <?php } ?>
			</ul>
</div>
<?php } ?>