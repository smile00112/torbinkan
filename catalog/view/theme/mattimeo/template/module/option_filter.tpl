<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content filterprice">
    <ul class="box-filter" id="box-oprion-filter">
      <?php foreach ($option_filter_groups as $filter_group) { ?>
      <li><span id="filter-option-group<?php echo $filter_group['option_name']; ?>"><?php echo $filter_group['option_name']; ?></span>
        <ul>
          <?php foreach ($filter_group['option_filter'] as  $name => $filter) { ?>
          <?php if (in_array($name, $option_filter_category)) { ?>
          <li>
            <input type="checkbox" value="<?php echo $name; ?>" id="option_filter_<?php echo $name; ?>" checked="checked" />
            <label for="option_filter_<?php echo $name; ?>"><?php echo $filter[1] . ' ('.$filter[0] .')'; ?></label>
          </li>
          <?php } else { ?>
           <li>
            <input type="checkbox" value="<?php echo $name; ?>" id="option_filter_<?php echo $name; ?>" />
            <label for="option_filter_<?php echo $name; ?>"><?php echo $filter[1] . ' ('.$filter[0] .')'; ?></label>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
    <a id="button-option-filter" class="button"><?php echo $button_action; ?></a>
  </div>
</div>

<script type="text/javascript"><!--
$('#button-option-filter').bind('click', function() {
	filter = [];
	
	$('#box-oprion-filter input[type=\'checkbox\']:checked').each(function(element) {
		filter.push(this.value);
	});
	
	location = '<?php echo $action; ?>&option_filter=' + filter.join(',');
});
//--></script> 
