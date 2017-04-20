<?php echo $header; ?>

<link rel="stylesheet" type="text/css" href="view/stylesheet/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/adminmattimeo.css" />
<script src="view/javascript/mattimeo/bootstrap.min.js"></script>

<div id="content" class="matt-mod">
	<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>

	<div class="navbar">
    <div class="navbar-inner">
		<span class="brand"><b><?php echo $heading_title; ?></b></span>
		<div class="buttons pull-right">
			<a onclick="location = '<?php echo $news; ?>';" class="btn btn-success btn-large"><span><?php echo $button_news; ?></span></a>
			<a onclick="buttonSave();" class="btn btn-success btn-large"><?php echo $button_save; ?></a>
			<a onclick="buttonApply();" class="btn btn-success btn-large"><?php echo $button_apply; ?></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-large"><span><?php echo $button_cancel; ?></span></a>
		</div>
    </div>
    </div>
    <div class="content categorymodul">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="newsform">
         
			<table class="form">
			
				<tr> 
					<td><?php echo $entry_customtitle; ?></td> 
					<td> 
                    <?php foreach ($languages as $language) { ?>
					<input type="text" name="news_customtitle<?php echo $language['language_id']; ?>" id="news_customtitle<?php echo $language['language_id']; ?>" size="30" value="<?php echo ${'news_customtitle' . $language['language_id']}; ?>" />
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
                   <?php } ?>  
					</td>
				</tr>
			
				<tr> 
					<td><?php echo $entry_header; ?></td> 
					<td>

		<div class="controls">
          <?php 
			$valueq 	= $news_header; $name	= 'news_header'; $id = 'news_header';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></span>
			</span>
		</div>

						
					</td>
				</tr>

				<tr>
					<td><?php echo $entry_template; ?></td>
					<td>
						<?php foreach ($templates as $template) { ?>
							<?php if ($template == $config_template) { ?>
								<span style='color: #225599; padding: 0 5px;'><b><?php echo $template; ?></b></span> 
							<?php } ?>
						<?php } ?>	
					</td>
				</tr>
			
				<tr>
					<td><span class="required">*</span> <?php echo $entry_newspage_thumb; ?></td>
					<td>
						<input type="text" name="news_thumb_width" value="<?php echo $news_thumb_width; ?>" class="shortfield"> px 
						<input type="text" name="news_thumb_height" value="<?php echo $news_thumb_height; ?>"  class="shortfield"> px
						<?php if ($error_newspage_thumb) { ?>
							<span class="error"><?php echo $error_newspage_thumb; ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="required">*</span> <?php echo $entry_newspage_popup; ?></td>
					<td>
						<input type="text" name="news_popup_width" value="<?php echo $news_popup_width; ?>" class="shortfield"> px 
						<input type="text" name="news_popup_height" value="<?php echo $news_popup_height; ?>" class="shortfield"> px
						<?php if ($error_newspage_popup) { ?>
							<span class="error"><?php echo $error_newspage_popup; ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_newspage_addthis; ?></td>
					<td><div class="controls">
          <?php 
			$valueq 	= $news_newspage_addthis; $name	= 'news_newspage_addthis'; $id = 'news_newspage_addthis';
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></span>
			</span>
		</div>
						
					</td>
				</tr>
				<tr>
					<td><span class="required">*</span> <?php echo $entry_headline_chars; ?></td>
					<td><input type="text" name="news_headline_chars" value="<?php echo $news_headline_chars; ?>" class="shortfield"> <?php echo $text_chars; ?>
						<?php if (isset($error_numchars)) { ?>
							<span class="error"><?php echo $error_numchars; ?></span>
						<?php } ?>
					</td>
				</tr>
				
			</table>
			
			<table id="module" class="all_topmenulink">
			<thead>
				<tr>
					<td><?php echo $entry_limit; ?></td>
					<td><?php echo $entry_headline; ?></td>
                    <td><?php echo $entry_box; ?></td>
                    <td><?php echo $entry_row; ?></td>
					<td><?php echo $entry_numchars; ?></td>
					<td><?php echo $entry_layout; ?></td>
					<td><?php echo $entry_position; ?></td>
					<td><?php echo $entry_status; ?></td>	
					<td><?php echo $entry_sort_order; ?></td>
					<td></td>
				</tr>
			</thead>
            <?php $module_row = 0; ?>
            <?php foreach ($modules as $module) { ?>
            <tbody id="module-row<?php echo $module_row; ?>">
				<tr>
                <td><input type="text" name="news_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>"  class="shortfield"></td>
				<td>

		<div class="controls">
          <?php 
			$valueq 	= isset($module['headline']) ? $module['headline'] : '';
             $name	= 'news_module[' . $module_row . '][headline]'; 
             $id = 'headline'.$module_row;
			?>
			<span class="switch">
				<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php if ($valueq) { ?>checked="checked"<?php }?> value="1">
				<label for="<?php echo $id; ?>" class="switch-img"></span>
			</span>
		</div>
 

				</td>
                <td>
                <div class="controls">
						<?php 
							$ar 	= array( 'Left' => true , 'Top' => false ); 
							$valueq 	= isset($module['pos_img']) ? $module['pos_img'] : '';
                             $name	= 'news_module[' . $module_row . '][pos_img]'; 
                             $id = 'pos_img'.$module_row;
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
					</div>
                </td>
                 <td>
                <div class="controls">
						<?php 
							$ar 	= array( 'Line' => true , 'Column' => false ); 
							$valueq 	= isset($module['pos_line']) ? $module['pos_line'] : '';
                             $name	= 'news_module[' . $module_row . '][pos_line]'; 
                             $id = 'pos_line'.$module_row;
						?>
						<div class="btn-group" data-toggle="buttons-radio">
							<?php foreach ($ar as $key => $value) { ?>
								<?php ($value ==  $valueq) ? $selected = ' active' : $selected=''; ?>
								<label for="<?php echo $id . '-' . $value; ?>"  type="button" class="btn<?php echo $selected; ?>">
									<input type="radio" id="<?php echo $id . '-' . $value; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php if ($valueq == $value) { ?>checked="checked"<?php }?>>
									<?php echo $key; ?>
								</label>
							<?php } ?>
						</div>
					</div>
                </td>
                <td>
					<input type="text" name="news_module[<?php echo $module_row; ?>][numchars]" value="<?php echo $module['numchars']; ?>"  class="shortfield">
				</td>
                <td>
					<select name="news_module[<?php echo $module_row; ?>][layout_id]">
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
						<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
						<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
					</select>
				</td>
                <td>
					<select name="news_module[<?php echo $module_row; ?>][position]">
                    <?php if ($module['position'] == 'content_top') { ?>
                    <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                    <?php } else { ?>
                    <option value="content_top"><?php echo $text_content_top; ?></option>
                    <?php } ?>
                    <?php if ($module['position'] == 'content_bottom') { ?>
                    <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                    <?php } else { ?>
                    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                    <?php } ?>
                    <?php if ($module['position'] == 'column_left') { ?>
                    <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                    <?php } else { ?>
                    <option value="column_left"><?php echo $text_column_left; ?></option>
                    <?php } ?>
                    <?php if ($module['position'] == 'column_right') { ?>
                    <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                    <?php } else { ?>
                    <option value="column_right"><?php echo $text_column_right; ?></option>
                    <?php } ?>
					</select>
				</td>
                <td>
					<select name="news_module[<?php echo $module_row; ?>][status]">
                    <?php if ($module['status']) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
					</select>
				</td>
                <td><input type="text" name="news_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>"  class="shortfield"></td>
                <td><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="btn btn-danger">&times;</a></td>
				</tr>
            </tbody>
            <?php $module_row++; ?>
            <?php } ?>
            <tfoot>
				<tr>
                <td colspan="9"></td>
                <td class="center"><a onclick="addModule();" class="btn btn-success"><span><?php echo $button_add_module; ?></span></a></td>
				</tr>
            </tfoot>
        </table>
		<input type="hidden" name="buttonForm" value="">

      </form>

    </div>

	<br>
		<div style="text-align:center; color:#555;">News v<?php echo $news_version; ?></div>
</div>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td><input type="text" name="news_module[' + module_row + '][limit]" value="5"  class="shortfield"></td>';

	
	html += '<td><div class="controls"><span class="switch">';
	html += '<input type="checkbox" id="headline' + module_row +'" name="news_module[' + module_row + '][headline]" value="1">';
	html += '<label for="headline' + module_row +'" class="switch-img"></label>';
	html += '</span></div></td>';
	
	html += '<td><div class="btn-group" data-toggle="buttons-radio">';
	 
	  html += '<label for="pos_img' + module_row + '-1"  type="button" class="btn active">';
	  html += '<input type="radio" id="pos_img' + module_row + '-1" name="news_module[' + module_row + '][pos_img]" value="1" checked="checked">Left';				
	  html += '</label>';
      html += '<label for="pos_img' + module_row + '-0"  type="button" class="btn">';
	  html += '<input type="radio" id="pos_img' + module_row + '-0" name="news_module[' + module_row + '][pos_img]" value="0">Top';				
	  html += '</label>';
	  
	  html += '</div></td>';
	  
	  html += '<td><div class="btn-group" data-toggle="buttons-radio">';
	 
	  html += '<label for="pos_line' + module_row + '-1"  type="button" class="btn active">';
	  html += '<input type="radio" id="pos_line' + module_row + '-1" name="news_module[' + module_row + '][pos_line]" value="1" checked="checked">Line';				
	  html += '</label>';
      html += '<label for="pos_line' + module_row + '-0"  type="button" class="btn">';
	  html += '<input type="radio" id="pos_line' + module_row + '-0" name="news_module[' + module_row + '][pos_line]" value="0">Column';				
	  html += '</label>';
	  
	  html += '</div></td>';

	html += '    <td><input type="text" name="news_module[' + module_row + '][numchars]" value=""  class="shortfield"></td>';
	html += '    <td><select name="news_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      	<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td><select name="news_module[' + module_row + '][position]">';
	html += '      	<option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      	<option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      	<option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      	<option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td><select name="news_module[' + module_row + '][status]">';
    html += '      	<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      	<option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td><input type="text" name="news_module[' + module_row + '][sort_order]" value=""  class="shortfield"></td>';
	html += '    <td><a onclick="$(\'#module-row' + module_row + '\').remove();" class="btn btn-danger">&times;</a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>

<script type="text/javascript">
function buttonSave() {document.newsform.buttonForm.value='save';$('#form').submit();}
function buttonApply() {document.newsform.buttonForm.value='apply';$('#form').submit();}
</script>

<?php echo $footer; ?>