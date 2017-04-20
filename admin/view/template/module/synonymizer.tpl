<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $h1_text; ?> - <small><?php echo $h2_text; ?></small></h1>
	<div class="buttons">
<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
	</div>
  </div>
<div class="content">
<form action="<?php echo $generate; ?>" method="post" enctype="multipart/form-data" id="form">
 		<div id="languages" class="htabs">
           		<?php foreach ($languages as $language) { ?>
           			<a href="#language<?php echo $language['language_id']; ?>" style="display: inline;"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
         		<?php } ?>
		</div>

	<table class="form">
            <tr>
              <td><?php echo $entry_main_category; ?></td>
              <td><select name="synonymizer[category_id]" id="category_id" onchange="templates()">
               <option value="0"></option>
                <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $last_cat_id) ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                <?php } ?>
              </select>


		<a onclick="save()" class="button">Сохранить шаблон</a> <span id="save"></span>
		</td>
            </tr>
	</table>

	<?php foreach ($languages as $language) { ?>
		<div id="language<?php echo $language['language_id']; ?>">
	<table class="form" style="margin-bottom:0;">            
	<tr>
		<td><?php echo $entry_description_syn; ?></td>
		<td><textarea class="syntext" name="synonymizer[descriptions][<?php echo $language['language_id']; ?>]" id="description_syn<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description_syn'] : ''; ?></textarea></td>
	</tr>
	</table>
		</div>
	<?php } /* foreach $languages */ ?>
<input type="radio" name="synonymizer[update_type]" value="new" checked> <b>Обычная генерация</b> <small style="color: green;">новое поле</small><br>
<input type="radio" name="synonymizer[update_type]" value="rewrite"> Перезаписать существующие описания товаров <small style="color: red;">внимательно!</small><br>
</form>
</div> <?php /* class="content" */ ?>
<a onclick="generate()" class="button">Сгенерировать!</a>


  </div>
</div>
<script type="text/javascript" src="view/javascript/synonymizer/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--

		function editor() {
		<?php  foreach ($languages as $language) { ?>
		CKEDITOR.replace('description_syn<?php echo $language['language_id']; ?>', {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
		});
		<?php }  ?>
		}
//--></script> 



<script type="text/javascript">
 function save() {
	<?php foreach ($languages as $language) { ?>
var lang<?php echo $language['language_id']; ?> = CKEDITOR.instances.description_syn<?php echo $language['language_id']; ?>.getData();
 $('textarea#description_syn<?php echo $language['language_id']; ?>').val(lang<?php echo $language['language_id']; ?>);
	<?php } ?>

	$.ajax({
		type: 'POST',
		url: 'index.php?route=module/synonymizer/savetemplate&token=<?php echo $token; ?>',		
		data: $('#category_id,textarea.syntext'),
     		 dataType: 'json',
		success: function(json) {
			if (json['success']) {
				$('#save').html('<small style="color: green;">Шаблон cохранен!</small>');
			} else {
				$('#save').html('<small style="color: red;">Шаблон не cохранен!</small>');
			}
		}
	});
}

 function templates() {
if ($('#category_id').val()==0) {
<?php foreach ($languages as $language) { ?>
$('textarea#description_syn<?php echo $language['language_id']; ?>').val('<p>Добро пожаловать в модуль <b>SynonymizeR</b> — <br>лучший модуль для продвижения, перелинковки и наполнения товаров!</p><p>Чтобы добавить Ваш первый шаблон - выберите категорию!</p>');
<?php } ?>
editor();
return false;
}
$('#save').text('');
$.ajax({
      type: 'POST',
      url: 'index.php?route=module/synonymizer/templates&token=<?php echo $token; ?>',
      data: $('#category_id'),
      dataType: 'json',
      success: function(json) {
            if (json['success']) {
			//$('textarea#description_syn1').val(json['template']);
			<?php foreach ($languages as $language) { ?>
if (CKEDITOR.instances['description_syn<?php echo $language['language_id']; ?>']) {
CKEDITOR.instances['description_syn<?php echo $language['language_id']; ?>'].destroy();
}
if (json['template'][<?php echo $language['language_id']; ?>]!=undefined) {
			$('textarea#description_syn<?php echo $language['language_id']; ?>').val(json['template'][<?php echo $language['language_id']; ?>]['description']);
} else {
$('textarea#description_syn<?php echo $language['language_id']; ?>').val('');
}
			<?php } ?>
            } 
editor();
      }
 });

}
</script>




<script language="javascript">
    function generate(){
        $('#form').submit();
   }
    </script>
<script type="text/javascript">
$('#languages a').tabs(); 
templates();
</script>


<?php echo $footer; ?>