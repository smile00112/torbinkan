<?php if ($articles) { ?>
<?php if($box) { ?>
	<div class="box">
		<div class="box-heading">
			<?php if($icon) { ?>
				<div style="float: left; margin-right: 8px;"><img src="catalog/view/theme/default/image/message.png" alt="" /></div>
			<?php } ?>
			<?php if($customtitle) { ?>
				<?php echo $customtitle; ?>
			<?php } ?>
		</div>
		<div class="box-content">
		<?php foreach ($articles as $articles_story) { ?>
			<div class="box-articles">
				<?php if ($show_headline) { ?>
					<h4><?php echo $articles_story['title']; ?></h4>
				<?php } ?>
			<p><a href="<?php echo $articles_story['href']; ?>"><img style="vertical-align: middle;" src="catalog/view/theme/default/image/message-articles.png" alt="" /></a> 
				<span><?php echo $articles_story['posted']; ?></span></p>
				<p><?php echo $articles_story['description']; ?> .. </p>
				<p><a href="<?php echo $articles_story['href']; ?>"> <?php echo $text_more; ?></a></p>
			</div>
		<?php } ?>
		<?php if($showbutton) { ?>
			<div style="text-align:right;">
				<a href="<?php echo $articleslist; ?>" class="button"><span><?php echo $buttonlist; ?></span></a>
			</div>
		<?php } ?>
		</div>
	</div>
<?php } else { ?>
	<div style="margin-bottom:10px;">
		<?php foreach ($articles as $articles_story) { ?>
			<div class="box-articles">
				<?php if ($show_headline) { ?>
					<h4><?php echo $articles_story['title']; ?></h4>
				<?php } ?>
				<?php echo $articles_story['description']; ?> .. <br />
				<a href="<?php echo $articles_story['href']; ?>"> <?php echo $text_more; ?></a>
				</p>
				<a href="<?php echo $articles_story['href']; ?>"><img src="catalog/view/theme/default/image/message-articles.png" alt="" /></a> 
				<span><b><?php echo $text_posted; ?></b> <?php echo $articles_story['posted']; ?></span>
			</div>
		<?php } ?>
		<?php if($showbutton) { ?>
			<div style="text-align:right;">
				<a href="<?php echo $articleslist; ?>" class="button"><span><?php echo $buttonlist; ?></span></a>
			</div>
		<?php } ?>
	</div>
<?php } ?>
<?php } ?>