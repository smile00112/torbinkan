<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').attr('action', '<?php echo $action; ?>'); $('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $action_exit; ?>'); $('#form').submit();" class="button"><span><?php echo $button_save_exit; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs"><a href="#tab-pricelist"><?php echo $tab_pricelist; ?></a><a href="#tab-layout"><?php echo $tab_layout; ?></a></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab-pricelist">
        <div class="vtabs">
          <?php $pricelist_row = 1; ?>
          <?php $column_row = 0; ?>
          <?php foreach ($pricelists as $pricelist) { ?>
            <a href="#tab-wpl-<?php echo $pricelist_row; ?>" id="wpl-<?php echo $pricelist_row; ?>"><?php echo $tab_pricelist; ?> <?php echo $pricelist_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#wpl-<?php echo $pricelist_row; ?>').remove(); $('#tab-wpl-<?php echo $pricelist_row; ?>').remove(); $('#module tbody').each(function () { var module_row = $(this).attr('id').substring(10); $('#module' + module_row + '-pricelist<?php echo $pricelist_row; ?>').remove(); }); return false;" /></a>
            <?php $pricelist_row++; ?>
          <?php } ?>
          <span id="wpl-add"><?php echo $button_add_pricelist; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addPricelist();" /></span>
        </div>
        <?php $pricelist_row = 1; ?>
        <?php foreach ($pricelists as $pricelist) { ?>
        <div id="tab-wpl-<?php echo $pricelist_row; ?>" class="vtabs-content">
          <input type="hidden" name="myocwpl_data[<?php echo $pricelist_row; ?>][pricelist_id]" value="<?php echo $pricelist['pricelist_id']; ?>" />
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><?php foreach ($languages as $language) { ?>
                <input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][name][<?php echo $language['language_id']; ?>]" value="<?php echo isset($pricelist['name'][$language['language_id']]) ? $pricelist['name'][$language['language_id']] : ''; ?>" size="58" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php if (isset($error_name[$pricelist_row][$language['language_id']])) { ?>
                <span class="error"><?php echo $error_name[$pricelist_row][$language['language_id']]; ?></span><br />
                <?php } ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="myocwpl_data[<?php echo $pricelist_row; ?>][status]">
                  <option value="1"<?php if($pricelist['status']) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                  <option value="0"<?php if(!$pricelist['status']) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_keyword; ?></td>
              <td><input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][keyword]" value="<?php echo $pricelist['keyword']; ?>" size="63" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_login; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][login]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_login_1"<?php if($pricelist['login']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_login_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][login]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_login_0"<?php if(!$pricelist['login']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_login_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_customer_group; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <label><input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][customer_group][]" value="<?php echo $customer_group['customer_group_id']; ?>"<?php if(isset($pricelist['customer_group']) && in_array($customer_group['customer_group_id'], $pricelist['customer_group'])) { ?> checked="checked"<?php } ?> />
                    <?php echo $customer_group['name']; ?></label>
                  </div>
                  <?php } ?>
                </div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <label><input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][store][]" value="0"<?php if(isset($pricelist['store']) && in_array(0, $pricelist['store'])) { ?> checked="checked"<?php } ?> />
                    <?php echo $text_default; ?></label>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <label><input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][store][]" value="<?php echo $store['store_id']; ?>"<?php if(isset($pricelist['store']) && in_array($store['store_id'], $pricelist['store'])) { ?> checked="checked"<?php } ?> />
                    <?php echo $store['name']; ?></label>
                  </div>
                  <?php } ?>
                </div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_product; ?></td>
              <td><input type="text" id="autocomplete-product-<?php echo $pricelist_row; ?>" value="" size="63" />
                <div id="wpl-<?php echo $pricelist_row; ?>-product" class="scrollbox" style="margin-top:10px; height:180px;">
                <?php $class = 'odd'; ?>
                <?php foreach ($products as $product) { ?>
                  <?php if(isset($pricelist['product']) && in_array($product['product_id'], explode(",", $pricelist['product']))) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div id="wpl-<?php echo $pricelist_row; ?>-product-<?php echo $product['product_id']; ?>" class="<?php echo $class; ?>"><?php echo $product['name']; ?> <img src="view/image/delete.png" />
                      <input type="hidden" value="<?php echo $product['product_id']; ?>" />
                    </div>
                  <?php } ?>
                <?php } ?>
                </div>
                <input type="hidden" name="myocwpl_data[<?php echo $pricelist_row; ?>][product]" value="<?php echo $pricelist['product']; ?>" />
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_category; ?></td>
              <td><div class="scrollbox" style="height:180px;">
                  <?php $class = 'even'; ?>
                  <?php foreach ($categories as $category) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                      <label><input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][category][]" value="<?php echo $category['category_id']; ?>"<?php if(isset($pricelist['category']) && in_array($category['category_id'], $pricelist['category'])) { ?> checked="checked"<?php } ?> />
                      <?php echo $category['name']; ?></label>
                    </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_manufacturer; ?></td>
              <td><div class="scrollbox" style="height:180px;">
                  <?php $class = 'even'; ?>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                      <label><input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][manufacturer][]" value="<?php echo $manufacturer['manufacturer_id']; ?>"<?php if(isset($pricelist['manufacturer']) && in_array($manufacturer['manufacturer_id'], $pricelist['manufacturer'])) { ?> checked="checked"<?php } ?> />
                      <?php echo $manufacturer['name']; ?></label>
                    </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_description_length; ?></td>
              <td><input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][description_length]" value="<?php echo $pricelist['description_length']; ?>" size="4" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_page; ?></td>
              <td><textarea name="myocwpl_data[<?php echo $pricelist_row; ?>][page]" cols="66" rows="4"><?php echo $pricelist['page']; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_image_dimension; ?></td>
              <td><input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][image_width]" value="<?php echo $pricelist['image_width']; ?>" size="4" /> x <input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][image_height]" value="<?php echo $pricelist['image_height']; ?>" size="4" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_barcode_dimension; ?></td>
              <td><input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][barcode_width]" value="<?php echo $pricelist['barcode_width']; ?>" size="4" /> x <input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][barcode_height]" value="<?php echo $pricelist['barcode_height']; ?>" size="4" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_barcode_zoom; ?></td>
              <td><select name="myocwpl_data[<?php echo $pricelist_row; ?>][barcode_zoom]">
                  <option value="1"<?php if($pricelist['barcode_zoom'] == "1") { ?> selected="selected"<?php } ?>>1</option>
                  <option value="2"<?php if($pricelist['barcode_zoom'] == "2") { ?> selected="selected"<?php } ?>>2 (<?php echo $text_default; ?>)</option>
                  <option value="3"<?php if($pricelist['barcode_zoom'] == "3") { ?> selected="selected"<?php } ?>>3</option>
                  <option value="4"<?php if($pricelist['barcode_zoom'] == "4") { ?> selected="selected"<?php } ?>>4</option>
                  <option value="5"<?php if($pricelist['barcode_zoom'] == "5") { ?> selected="selected"<?php } ?>>5</option>
                  <option value="6"<?php if($pricelist['barcode_zoom'] == "6") { ?> selected="selected"<?php } ?>>6</option>
              </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_barcode_fontsize; ?></td>
              <td><input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][barcode_fontsize]" value="<?php echo $pricelist['barcode_fontsize']; ?>" size="4" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_filter_stock; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][filter_stock]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_filter_stock_1"<?php if($pricelist['filter_stock']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_filter_stock_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][filter_stock]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_filter_stock_0"<?php if(!$pricelist['filter_stock']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_filter_stock_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_filter_special; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][filter_special]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_filter_special_1"<?php if($pricelist['filter_special']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_filter_special_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][filter_special]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_filter_special_0"<?php if(!$pricelist['filter_special']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_filter_special_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_filter_discount; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][filter_discount]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_filter_discount_1"<?php if($pricelist['filter_discount']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_filter_discount_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][filter_discount]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_filter_discount_0"<?php if(!$pricelist['filter_discount']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_filter_discount_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_filter_by_category; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][cat_filter]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_cat_filter_1"<?php if($pricelist['cat_filter']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_cat_filter_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][cat_filter]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_cat_filter_0"<?php if(!$pricelist['cat_filter']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_cat_filter_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_multicart; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][multicart]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_multicart_1"<?php if($pricelist['multicart']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_multicart_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][multicart]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_multicart_0"<?php if(!$pricelist['multicart']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_multicart_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_print; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][print]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_print_1"<?php if($pricelist['print']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_print_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][print]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_print_0"<?php if(!$pricelist['print']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_print_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_print_paging; ?></td>
              <td>
                <label><input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][print_paging]" value="1"<?php if($pricelist['print_paging']) { ?> checked="checked"<?php } ?> />
                <?php echo $text_yes; ?></label>
                <label><input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][print_paging]" value="0"<?php if(!$pricelist['print_paging']) { ?> checked="checked"<?php } ?> />
                <?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_pdf; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][pdf]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_pdf_1"<?php if($pricelist['pdf']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_pdf_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][pdf]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_pdf_0"<?php if(!$pricelist['pdf']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_pdf_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_pdf_paging; ?></td>
              <td>
                <label><input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][pdf_paging]" value="1"<?php if($pricelist['pdf_paging']) { ?> checked="checked"<?php } ?> />
                <?php echo $text_yes; ?></label>
                <label><input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][pdf_paging]" value="0"<?php if(!$pricelist['pdf_paging']) { ?> checked="checked"<?php } ?> />
                <?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_pdf_orientation; ?></td>
              <td>
                <select name="myocwpl_data[<?php echo $pricelist_row; ?>][pdf_orientation]">
                  <option value="P"<?php if($pricelist['pdf_orientation'] == "P") { ?> selected="selected"<?php } ?>><?php echo $text_portrait; ?></option>
                  <option value="L"<?php if($pricelist['pdf_orientation'] == "L") { ?> selected="selected"<?php } ?>><?php echo $text_landscape; ?></option>
                </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_discount; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][discount]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_discount_1"<?php if($pricelist['discount']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_discount_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][discount]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_discount_0"<?php if(!$pricelist['discount']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_discount_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_option; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][option]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_option_1"<?php if($pricelist['option']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_option_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][option]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_option_0"<?php if(!$pricelist['option']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_option_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_add_wishlist; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][add_wishlist]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_add_wishlist_1"<?php if($pricelist['add_wishlist']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_add_wishlist_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][add_wishlist]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_add_wishlist_0"<?php if(!$pricelist['add_wishlist']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_add_wishlist_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_add_compare; ?></td>
              <td>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][add_compare]" value="1" id="myocwpl_<?php echo $pricelist_row; ?>_add_compare_1"<?php if($pricelist['add_compare']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_add_compare_1"><?php echo $text_yes; ?></label>
                <input type="radio" name="myocwpl_data[<?php echo $pricelist_row; ?>][add_compare]" value="0" id="myocwpl_<?php echo $pricelist_row; ?>_add_compare_0"<?php if(!$pricelist['add_compare']) { ?> checked="checked"<?php } ?> />
                <label for="myocwpl_<?php echo $pricelist_row; ?>_add_compare_0"><?php echo $text_no; ?></label>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_design; ?></td>
              <td>
                <table class="list">
                  <thead>
                    <tr>
                      <td class="left"><?php echo $entry_store; ?></td>
                      <td class="left"><?php echo $entry_layout_override; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="left"><?php echo $text_default; ?></td>
                      <td class="left"><select name="myocwpl_data[<?php echo $pricelist_row; ?>][pricelist_layout][0]">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>                          
                          <option value="<?php echo $layout['layout_id']; ?>"<?php if (isset($pricelist['pricelist_layout'][0]) && $pricelist['pricelist_layout'][0] == $layout['layout_id']) { ?> selected="selected"<?php } ?>><?php echo $layout['name']; ?></option>
                          <?php } ?>
                        </select></td>
                    </tr>
                  </tbody>
                  <?php foreach ($stores as $store) { ?>
                  <tbody>
                    <tr>
                      <td class="left"><?php echo $store['name']; ?></td>
                      <td class="left"><select name="myocwpl_data[<?php echo $pricelist_row; ?>][pricelist_layout][<?php echo $store['store_id']; ?>]">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"<?php if (isset($pricelist['pricelist_layout'][$store['store_id']]) && $pricelist['pricelist_layout'][$store['store_id']] == $layout['layout_id']) { ?> selected="selected"<?php } ?>><?php echo $layout['name']; ?></option>
                          <?php } ?>
                        </select></td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              </td>
            </tr>
          </table>
          <table id="pricelist-column-<?php echo $pricelist_row; ?>" class="list">
            <thead>
              <tr>
                <td class="center"><?php echo $column_column; ?></td>
                <td class="center"><?php echo $column_name; ?></td>
                <td class="center"><?php echo $column_sortable; ?></td>
                <td class="center"><?php echo $column_barcode; ?></td>
                <td class="center"><?php echo $column_pricelist; ?></td>
                <td class="center"><?php echo $column_print; ?></td>
                <td class="center"><?php echo $column_pdf; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php if(isset($pricelist['columns']) && !empty($pricelist['columns'])) { ?>
            <?php foreach ($pricelist['columns'] as $column) { ?>
            <tbody id="column-row<?php echo $column_row; ?>">
              <tr>
                <td class="center">
                  <select name="myocwpl_data[<?php echo $pricelist_row; ?>][columns][<?php echo $column_row; ?>][column]">
                    <option value="checkbox"<?php if($column['column'] == 'checkbox') { ?> selected="selected"<?php } ?>><?php echo $text_checkbox; ?></option>
                    <option value="num"<?php if($column['column'] == 'num') { ?> selected="selected"<?php } ?>><?php echo $text_num; ?></option>
                    <option value="action"<?php if($column['column'] == 'action') { ?> selected="selected"<?php } ?>><?php echo $text_action; ?></option>
                    <option value="blank"<?php if($column['column'] == 'blank') { ?> selected="selected"<?php } ?>><?php echo $text_blank; ?></option>
                    <optgroup label="<?php echo $text_product_info; ?>">
                      <option value="image"<?php if($column['column'] == 'image') { ?> selected="selected"<?php } ?>><?php echo $text_image; ?></option>
                      <option value="name"<?php if($column['column'] == 'name') { ?> selected="selected"<?php } ?>><?php echo $text_name; ?></option>
                      <option value="description"<?php if($column['column'] == 'description') { ?> selected="selected"<?php } ?>><?php echo $text_description; ?></option>
                      <option value="model"<?php if($column['column'] == 'model') { ?> selected="selected"<?php } ?>><?php echo $text_model; ?></option>
                      <option value="sku"<?php if($column['column'] == 'sku') { ?> selected="selected"<?php } ?>><?php echo $text_sku; ?></option>
                      <option value="upc"<?php if($column['column'] == 'upc') { ?> selected="selected"<?php } ?>><?php echo $text_upc; ?></option>
                      <option value="ean"<?php if($column['column'] == 'ean') { ?> selected="selected"<?php } ?>><?php echo $text_ean; ?></option>
                      <option value="jan"<?php if($column['column'] == 'jan') { ?> selected="selected"<?php } ?>><?php echo $text_jan; ?></option>
                      <option value="isbn"<?php if($column['column'] == 'isbn') { ?> selected="selected"<?php } ?>><?php echo $text_isbn; ?></option>
                      <option value="mpn"<?php if($column['column'] == 'mpn') { ?> selected="selected"<?php } ?>><?php echo $text_mpn; ?></option>
                      <option value="manufacturer"<?php if($column['column'] == 'manufacturer') { ?> selected="selected"<?php } ?>><?php echo $text_manufacturer; ?></option>
                      <option value="price"<?php if($column['column'] == 'price') { ?> selected="selected"<?php } ?>><?php echo $text_price; ?></option>
                      <option value="quantity"<?php if($column['column'] == 'quantity') { ?> selected="selected"<?php } ?>><?php echo $text_quantity; ?></option>
                      <option value="stock_status"<?php if($column['column'] == 'stock_status') { ?> selected="selected"<?php } ?>><?php echo $text_stock_status; ?></option>
                      <option value="minimum"<?php if($column['column'] == 'minimum') { ?> selected="selected"<?php } ?>><?php echo $text_minimum; ?></option>
                      <option value="rating"<?php if($column['column'] == 'rating') { ?> selected="selected"<?php } ?>><?php echo $text_rating; ?></option>
                      <option value="dimension"<?php if($column['column'] == 'dimension') { ?> selected="selected"<?php } ?>><?php echo $text_dimension; ?></option>
                      <option value="weight"<?php if($column['column'] == 'weight') { ?> selected="selected"<?php } ?>><?php echo $text_weight; ?></option>
                      <option value="date_added"<?php if($column['column'] == 'date_added') { ?> selected="selected"<?php } ?>><?php echo $text_date_added; ?></option>
                    </optgroup>
                    <optgroup label="<?php echo $text_attributes; ?>">
                      <?php foreach ($attributes as $attribute) { ?>
                      <option value="attr<?php echo $attribute['attribute_id']; ?>"<?php if($column['column'] == ('attr' . $attribute['attribute_id'])) { ?> selected="selected"<?php } ?>><?php echo $attribute['name']; ?></option>
                      <?php } ?>
                    </optgroup>
                  </select>
                </td>
                <td class="center">
                  <?php foreach ($languages as $language) { ?>
                  <input type="text" name="myocwpl_data[<?php echo $pricelist_row; ?>][columns][<?php echo $column_row; ?>][name][<?php echo $language['language_id']; ?>]" value="<?php echo $column['name'][$language['language_id']]; ?>" size="30" />
                  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                  <?php } ?>
                </td>
                <td class="center">
                  <input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][columns][<?php echo $column_row; ?>][sortable]" value="1"<?php if(isset($column['sortable']) && $column['sortable']) { ?> checked="checked"<?php } ?> />
                </td>
                <td class="center">
                  <select name="myocwpl_data[<?php echo $pricelist_row; ?>][columns][<?php echo $column_row; ?>][barcode]">
                    <option value="0"<?php if($column['barcode'] == 0) { ?> selected="selected"<?php } ?>><?php echo $text_none; ?></option>
                    <option value="codabar"<?php if($column['barcode'] == 'codabar') { ?> selected="selected"<?php } ?>><?php echo $text_bc_codabar; ?></option>
                    <option value="code11"<?php if($column['barcode'] == 'code11') { ?> selected="selected"<?php } ?>><?php echo $text_bc_code11; ?></option>
                    <option value="code39"<?php if($column['barcode'] == 'code39') { ?> selected="selected"<?php } ?>><?php echo $text_bc_code39; ?></option>
                    <option value="code93"<?php if($column['barcode'] == 'code93') { ?> selected="selected"<?php } ?>><?php echo $text_bc_code93; ?></option>
                    <option value="code128"<?php if($column['barcode'] == 'code128') { ?> selected="selected"<?php } ?>><?php echo $text_bc_code128; ?></option>
                    <option value="ean8"<?php if($column['barcode'] == 'ean8') { ?> selected="selected"<?php } ?>><?php echo $text_bc_ean8; ?></option>
                    <option value="ean13"<?php if($column['barcode'] == 'ean13') { ?> selected="selected"<?php } ?>><?php echo $text_bc_ean13; ?></option>
                    <option value="std25"<?php if($column['barcode'] == 'std25') { ?> selected="selected"<?php } ?>><?php echo $text_bc_std25; ?></option>
                    <option value="int25"<?php if($column['barcode'] == 'int25') { ?> selected="selected"<?php } ?>><?php echo $text_bc_int25; ?></option>
                    <option value="msi"<?php if($column['barcode'] == 'msi') { ?> selected="selected"<?php } ?>><?php echo $text_bc_msi; ?></option>
                    <option value="datamatrix"<?php if($column['barcode'] == 'datamatrix') { ?> selected="selected"<?php } ?>><?php echo $text_bc_datamatrix; ?></option>
                  </select>
                </td>
                <td class="center">
                  <input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][columns][<?php echo $column_row; ?>][for_pricelist]" value="1"<?php if(isset($column['for_pricelist']) && $column['for_pricelist']) { ?> checked="checked"<?php } ?> />
                </td>
                <td class="center">
                  <input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][columns][<?php echo $column_row; ?>][for_print]" value="1"<?php if(isset($column['for_print']) && $column['for_print']) { ?> checked="checked"<?php } ?> />
                </td>
                <td class="center">
                  <input type="checkbox" name="myocwpl_data[<?php echo $pricelist_row; ?>][columns][<?php echo $column_row; ?>][for_pdf]" value="1"<?php if(isset($column['for_pdf']) && $column['for_pdf']) { ?> checked="checked"<?php } ?> />
                </td>
                <td class="left"><a onclick="$('#column-row<?php echo $column_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
            </tbody>
            <?php $column_row++; ?>
            <?php } ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="7"></td>
                <td class="left"><a onclick="addColumn(<?php echo $pricelist_row; ?>);" class="button"><span><?php echo $button_add_column; ?></span></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <?php $pricelist_row++; ?>
        <?php } ?>
      </div>
      <div id="tab-layout">
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_pricelist; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="myoc_pricelist_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"<?php if ($layout['layout_id'] == $module['layout_id']) { ?> selected="selected"<?php } ?>><?php echo $layout['name']; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left">
                <div class="pricelist scrollbox" style="width:200px;height:80px;">
                  <?php $pricelist_row = 1; ?>
                  <?php $class = 'odd'; ?>
                  <?php foreach ($pricelists as $pricelist) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="module<?php echo $module_row; ?>-pricelist<?php echo $pricelist_row; ?>" class="<?php echo $class; ?>">
                    <label><input type="checkbox" name="myoc_pricelist_module[<?php echo $module_row; ?>][pricelist][]" value="<?php echo $pricelist['pricelist_id']; ?>"<?php if(isset($module['pricelist']) && in_array($pricelist['pricelist_id'], $module['pricelist'])) { ?> checked="checked"<?php } ?> />
                      <?php echo $tab_pricelist; ?> <?php echo $pricelist_row; ?></label>
                  </div>
                  <?php $pricelist_row++; ?>
                  <?php } ?>
                </div>
              </td>
              <td class="left"><select name="myoc_pricelist_module[<?php echo $module_row; ?>][position]">
                  <option value="content_top"<?php if ($module['position'] == 'content_top') { ?> selected="selected"<?php } ?>><?php echo $text_content_top; ?></option>
                  <option value="content_bottom"<?php if ($module['position'] == 'content_bottom') { ?> selected="selected"<?php } ?>><?php echo $text_content_bottom; ?></option>
                  <option value="column_left"<?php if ($module['position'] == 'column_left') { ?> selected="selected"<?php } ?>><?php echo $text_column_left; ?></option>
                  <option value="column_right"<?php if ($module['position'] == 'column_right') { ?> selected="selected"<?php } ?>><?php echo $text_column_right; ?></option>
                </select></td>
              <td class="left"><select name="myoc_pricelist_module[<?php echo $module_row; ?>][status]">
                  <option value="1"<?php if ($module['status']) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                  <option value="0"<?php if (!$module['status']) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                </select></td>
              <td class="right"><input type="text" name="myoc_pricelist_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="5"></td>
              <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </form>
    <div style="font-size:11px;color:#666;clear:both;"><?php echo $myoc_copyright; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
function getAutocompleteSource(target_pricelist_row)
{
  $('#wpl-' + target_pricelist_row + '-product div img').live('click', function() {
    $(this).parent().remove();
    
    $('#wpl-' + target_pricelist_row + '-product div:odd').attr('class', 'odd');
    $('#wpl-' + target_pricelist_row + '-product div:even').attr('class', 'even');

    data = $.map($('#wpl-' + target_pricelist_row + '-product input'), function(element){
      return $(element).attr('value');
    });
          
    $('input[name=\'myocwpl_data[' + target_pricelist_row + '][product]\']').attr('value', data.join());
  });

  return {
    delay: 0,
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
        type: 'POST',
        dataType: 'json',
        data: 'filter_name=' +  encodeURIComponent(request.term),
        success: function(json) {   
          response($.map(json, function(item) {
            return {
              label: item.name,
              value: item.product_id,
            }
          }));
        }
      });
    }, 
    select: function(event, ui) {
      $('#wpl-' + target_pricelist_row + '-product' + ui.item.value).remove();
      
      $('#wpl-' + target_pricelist_row + '-product').append('<div id="wpl-' + target_pricelist_row + '-product-' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" value="' + ui.item.value + '" /></div>');

      $('#wpl-' + target_pricelist_row + '-product div:odd').attr('class', 'odd');
      $('#wpl-' + target_pricelist_row + '-product div:even').attr('class', 'even');
      
      data = $.map($('#wpl-' + target_pricelist_row + '-product input'), function(element){
        return $(element).attr('value');
      });
              
      $('input[name=\'myocwpl_data[' + target_pricelist_row + '][product]\']').attr('value', data.join());
            
      return false;
    }
  }
}

<?php $pricelist_row = count($pricelists) + 1; ?>
var pricelist_row = <?php echo $pricelist_row; ?>;
var pricelist_id = <?php echo $next_pricelist_id; ?>;
function addPricelist() {  
  html  = '<div id="tab-wpl-' + pricelist_row + '" class="vtabs-content">';
  html += '<input type="hidden" name="myocwpl_data[' + pricelist_row + '][pricelist_id]" value="' + pricelist_id + '" />';
  html += '  <table class="form">';
  html += '    <tr>';
  html += '      <td><span class="required">*</span> <?php echo $entry_name; ?></td>';
  html += '      <td>';
  <?php foreach ($languages as $language) { ?>
  html += '        <input type="text" name="myocwpl_data[' + pricelist_row + '][name][<?php echo $language['language_id']; ?>]" value="" size="58" />';
  html += '        <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo addslashes($language['name']); ?>" /><br />';
  <?php } ?>
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_status; ?></td>';
  html += '      <td><select name="myocwpl_data[' + pricelist_row + '][status]">';
  html += '        <option value="1"><?php echo $text_enabled; ?></option>';
  html += '        <option value="0"><?php echo $text_disabled; ?></option>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_keyword; ?></td>';
  html += '      <td><input type="text" name="myocwpl_data[' + pricelist_row + '][keyword]" value="" size="63" /></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_login; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][login]" value="1" id="myocwpl_' + pricelist_row + '_login_1" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_login_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][login]" value="0" id="myocwpl_' + pricelist_row + '_login_0" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_login_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_customer_group; ?></td>';
  html += '      <td><div class="scrollbox">';
  <?php $class = 'even'; ?>
  <?php foreach ($customer_groups as $customer_group) { ?>
    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '          <div class="<?php echo $class; ?>">';
  html += '            <label><input type="checkbox" name="myocwpl_data[' + pricelist_row + '][customer_group][]" value="<?php echo $customer_group['customer_group_id']; ?>" />';
  html += '            <?php echo addslashes($customer_group['name']); ?></label>';
  html += '          </div>';
  <?php } ?>
  html += '        </div>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_store; ?></td>';
  html += '      <td><div class="scrollbox">';
  <?php $class = 'even'; ?>
  html += '          <div class="<?php echo $class; ?>">';
  html += '            <label><input type="checkbox" name="myocwpl_data[' + pricelist_row + '][store][]" value="0" checked="checked" />';
  html += '            <?php echo $text_default; ?></label>';
  html += '          </div>';
  <?php foreach ($stores as $store) { ?>
    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '          <div class="<?php echo $class; ?>">';
  html += '            <label><input type="checkbox" name="myocwpl_data[' + pricelist_row + '][store][]" value="<?php echo $store['store_id']; ?>" />';
  html += '            <?php echo addslashes($store['name']); ?></label>';
  html += '          </div>';
  <?php } ?>
  html += '        </div>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_product; ?></td>';
  html += '      <td><input type="text" id="autocomplete-product-' + pricelist_row + '" value="" size="63" />';
  html += '        <div id="wpl-' + pricelist_row + '-product" class="scrollbox" style="margin-top:10px;height:180px;"></div>';
  html += '        <input type="hidden" name="myocwpl_data[' + pricelist_row + '][product]" value="" />';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_category; ?></td>';
  html += '      <td><div class="scrollbox" style="height:180px;">';
  <?php $class = 'even'; ?>
  <?php foreach ($categories as $category) { ?>
    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '          <div class="<?php echo $class; ?>">';
  html += '            <label><input type="checkbox" name="myocwpl_data[' + pricelist_row + '][category][]" value="<?php echo $category['category_id']; ?>" />';
  html += '            <?php echo addslashes($category['name']); ?></label>';
  html += '          </div>';
  <?php } ?>
  html += '        </div>';
  html += '        <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
  html += '      </td>';
  html += '    </tr>';  
  html += '    <tr>';
  html += '      <td><?php echo $entry_manufacturer; ?></td>';
  html += '      <td><div class="scrollbox" style="height:180px;">';
  html += '          <?php $class = 'even'; ?>';
  <?php foreach ($manufacturers as $manufacturer) { ?>
  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '          <div class="<?php echo $class; ?>">';
  html += '            <label><input type="checkbox" name="myocwpl_data[' + pricelist_row + '][manufacturer][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />';
  html += '            <?php echo addslashes($manufacturer['name']); ?></label>';
  html += '          </div>';
  <?php } ?>
  html += '        </div>';
  html += '        <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_description_length; ?></td>';
  html += '      <td><input type="text" name="myocwpl_data[' + pricelist_row + '][description_length]" value="80" size="4" /></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_page; ?></td>';
  html += '      <td><textarea name="myocwpl_data[' + pricelist_row + '][page]" cols="66" rows="4">10,20,50,100</textarea></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_image_dimension; ?></td>';
  html += '      <td><input type="text" name="myocwpl_data[' + pricelist_row + '][image_width]" value="80" size="4" /> x <input type="text" name="myocwpl_data[' + pricelist_row + '][image_height]" value="80" size="4" /></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_barcode_dimension; ?></td>';
  html += '      <td><input type="text" name="myocwpl_data[' + pricelist_row + '][barcode_width]" value="200" size="4" /> x <input type="text" name="myocwpl_data[' + pricelist_row + '][barcode_height]" value="60" size="4" /></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_barcode_zoom; ?></td>';
  html += '      <td><select name="myocwpl_data[' + pricelist_row + '][barcode_zoom]">';
  html += '          <option value="1">1</option>';
  html += '          <option value="2" selected="selected">2 (<?php echo $text_default; ?>)</option>';
  html += '          <option value="3">3</option>';
  html += '          <option value="4">4</option>';
  html += '          <option value="5">5</option>';
  html += '          <option value="6">6</option>';
  html += '        </select></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_barcode_fontsize; ?></td>';
  html += '      <td><input type="text" name="myocwpl_data[' + pricelist_row + '][barcode_fontsize]" value="12" size="4" /></td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_filter_stock; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][filter_stock]" value="1" id="myocwpl_' + pricelist_row + '_filter_stock_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_filter_stock_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][filter_stock]" value="0" id="myocwpl_' + pricelist_row + '_filter_stock_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_filter_stock_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_filter_special; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][filter_special]" value="1" id="myocwpl_' + pricelist_row + '_filter_special_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_filter_special_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][filter_special]" value="0" id="myocwpl_' + pricelist_row + '_filter_special_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_filter_special_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_filter_discount; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][filter_discount]" value="1" id="myocwpl_' + pricelist_row + '_filter_discount_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_filter_discount_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][filter_discount]" value="0" id="myocwpl_' + pricelist_row + '_filter_discount_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_filter_discount_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_filter_by_category; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][cat_filter]" value="1" id="myocwpl_' + pricelist_row + '_cat_filter_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_cat_filter_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][cat_filter]" value="0" id="myocwpl_' + pricelist_row + '_cat_filter_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_cat_filter_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_multicart; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][multicart]" value="1" id="myocwpl_' + pricelist_row + '_multicart_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_multicart_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][multicart]" value="0" id="myocwpl_' + pricelist_row + '_multicart_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_multicart_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_print; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][print]" value="1" id="myocwpl_' + pricelist_row + '_print_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_print_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][print]" value="0" id="myocwpl_' + pricelist_row + '_print_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_print_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_print_paging; ?></td>';
  html += '      <td>';
  html += '        <label><input type="radio" name="myocwpl_data[' + pricelist_row + '][print_paging]" value="1" checked="checked" />';
  html += '        <?php echo $text_yes; ?></label>';
  html += '        <label><input type="radio" name="myocwpl_data[' + pricelist_row + '][print_paging]" value="0" />';
  html += '        <?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_pdf; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][pdf]" value="1" id="myocwpl_' + pricelist_row + '_pdf_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_pdf_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][pdf]" value="0" id="myocwpl_' + pricelist_row + '_pdf_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_pdf_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_pdf_paging; ?></td>';
  html += '      <td>';
  html += '        <label><input type="radio" name="myocwpl_data[' + pricelist_row + '][pdf_paging]" value="1" checked="checked" />';
  html += '        <?php echo $text_yes; ?></label>';
  html += '        <label><input type="radio" name="myocwpl_data[' + pricelist_row + '][pdf_paging]" value="0" />';
  html += '        <?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_pdf_orientation; ?></td>';
  html += '      <td><select name="myocwpl_data[' + pricelist_row + '][pdf_orientation]">';
  html += '        <option value="P"><?php echo $text_portrait; ?></option>';
  html += '        <option value="L"><?php echo $text_landscape; ?></option>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_discount; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][discount]" value="1" id="myocwpl_' + pricelist_row + '_discount_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_discount_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][discount]" value="0" id="myocwpl_' + pricelist_row + '_discount_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_discount_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_option; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][option]" value="1" id="myocwpl_' + pricelist_row + '_option_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_option_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][option]" value="0" id="myocwpl_' + pricelist_row + '_option_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_option_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_add_wishlist; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][add_wishlist]" value="1" id="myocwpl_' + pricelist_row + '_add_wishlist_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_add_wishlist_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][add_wishlist]" value="0" id="myocwpl_' + pricelist_row + '_add_wishlist_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_add_wishlist_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_add_compare; ?></td>';
  html += '      <td>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][add_compare]" value="1" id="myocwpl_' + pricelist_row + '_add_compare_1" checked="checked" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_add_compare_1"><?php echo $text_yes; ?></label>';
  html += '        <input type="radio" name="myocwpl_data[' + pricelist_row + '][add_compare]" value="0" id="myocwpl_' + pricelist_row + '_add_compare_0" />';
  html += '        <label for="myocwpl_' + pricelist_row + '_add_compare_0"><?php echo $text_no; ?></label>';
  html += '      </td>';
  html += '    </tr>';
  html += '    <tr>';
  html += '      <td><?php echo $entry_design; ?></td>';
  html += '      <td>';
  html += '        <table class="list">';
  html += '          <thead>';
  html += '            <tr>';
  html += '              <td class="left"><?php echo $entry_store; ?></td>';
  html += '              <td class="left"><?php echo $entry_layout_override; ?></td>';
  html += '            </tr>';
  html += '          </thead>';
  html += '          <tbody>';
  html += '            <tr>';
  html += '              <td class="left"><?php echo $text_default; ?></td>';
  html += '              <td class="left"><select name="myocwpl_data[<?php echo $pricelist_row; ?>][pricelist_layout][0]">';
  html += '                  <option value="" selected="selected"></option>';
                          <?php foreach ($layouts as $layout) { ?>                          
  html += '                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
                          <?php } ?>
  html += '                </select></td>';
  html += '            </tr>';
  html += '          </tbody>';
  <?php foreach ($stores as $store) { ?>
  html += '          <tbody>';
  html += '            <tr>';
  html += '              <td class="left"><?php echo addslashes($store['name']); ?></td>';
  html += '              <td class="left"><select name="myocwpl_data[<?php echo $pricelist_row; ?>][pricelist_layout][<?php echo $store['store_id']; ?>]">';
  html += '                  <option value="" selected="selected"></option>';
  <?php foreach ($layouts as $layout) { ?>
  html += '                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
  <?php } ?>
  html += '                </select></td>';
  html += '            </tr>';
  html += '          </tbody>';
  <?php } ?>
  html += '        </table>';
  html += '      </td>';
  html += '    </tr>';
  html += '  </table>';
  html += '  <table id="pricelist-column-' + pricelist_row + '" class="list">';
  html += '    <thead>';
  html += '      <tr>';
  html += '        <td class="center"><?php echo $column_column; ?></td>';
  html += '        <td class="center"><?php echo $column_name; ?></td>';
  html += '        <td class="center"><?php echo $column_sortable; ?></td>';
  html += '        <td class="center"><?php echo $column_barcode; ?></td>';
  html += '        <td class="center"><?php echo $column_pricelist; ?></td>';
  html += '        <td class="center"><?php echo $column_print; ?></td>';
  html += '        <td class="center"><?php echo $column_pdf; ?></td>';
  html += '        <td></td>';
  html += '      </tr>';
  html += '    </thead>';
  html += '    <tfoot>';
  html += '      <tr>';
  html += '        <td colspan="7"></td>';
  html += '        <td class="left"><a onclick="addColumn(' + pricelist_row + ');" class="button"><span><?php echo $button_add_column; ?></span></a></td>';
  html += '      </tr>';
  html += '    </tfoot>';
  html += '  </table>';
  html += '</div>';

  $('#tab-pricelist').append(html);

  $('#wpl-add').before('<a href="#tab-wpl-' + pricelist_row + '" id="wpl-' + pricelist_row + '"><?php echo $tab_pricelist; ?> ' + pricelist_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#wpl-' + pricelist_row + '\').remove(); $(\'#tab-wpl-' + pricelist_row + '\').remove(); $(\'#module tbody\').each(function () { var module_row = $(this).attr(\'id\').substring(10); $(\'#module\' + module_row + \'-pricelist' + pricelist_row + '\').remove(); }); return false;" /></a>');
  
  $('.vtabs a').tabs();

  $('#autocomplete-product-' + pricelist_row).autocomplete(getAutocompleteSource(pricelist_row));

  $('#wpl-' + pricelist_row).trigger('click');

  $('#module tbody').each(function () {
    var module_row = $(this).attr('id').substring(10);
    html = '<div id="module' + module_row + '-pricelist' + pricelist_row + '"><label><input type="checkbox" name="myoc_pricelist_module[' + module_row + '][pricelist][]" value="' + pricelist_id + '" /> <?php echo $tab_pricelist; ?> ' + pricelist_row + '</label></div>';
    $('#module-row' + module_row + ' .pricelist').append(html);

    $('#module-row' + module_row + ' .pricelist div:odd').attr('class', 'odd');
    $('#module-row' + module_row + ' .pricelist div:even').attr('class', 'even');
  });
  
  pricelist_row++;
  pricelist_id++;
}

var column_row = <?php echo $column_row; ?>;

function addColumn(pricelist_row) {
  html  = '<tbody id="column-row' + column_row + '">';
  html += '  <tr>';
  html += '    <td class="center">';
  html += '      <select name="myocwpl_data[' + pricelist_row + '][columns][' + column_row + '][column]">';
  html += '        <option value="checkbox"><?php echo $text_checkbox; ?></option>';
  html += '        <option value="num"><?php echo $text_num; ?></option>';
  html += '        <option value="action"><?php echo $text_action; ?></option>';
  html += '        <option value="blank"><?php echo $text_blank; ?></option>';
  html += '        <optgroup label="<?php echo $text_product_info; ?>">';
  html += '          <option value="image"><?php echo $text_image; ?></option>';
  html += '          <option value="name"><?php echo $text_name; ?></option>';
  html += '          <option value="description"><?php echo $text_description; ?></option>';
  html += '          <option value="model"><?php echo $text_model; ?></option>';
  html += '          <option value="sku"><?php echo $text_sku; ?></option>';
  html += '          <option value="upc"><?php echo $text_upc; ?></option>';
  html += '          <option value="ean"><?php echo $text_ean; ?></option>';
  html += '          <option value="jan"><?php echo $text_jan; ?></option>';
  html += '          <option value="isbn"><?php echo $text_isbn; ?></option>';
  html += '          <option value="mpn"><?php echo $text_mpn; ?></option>';
  html += '          <option value="manufacturer"><?php echo $text_manufacturer; ?></option>';
  html += '          <option value="price"><?php echo $text_price; ?></option>';
  html += '          <option value="quantity"><?php echo $text_quantity; ?></option>';
  html += '          <option value="stock_status"><?php echo $text_stock_status; ?></option>';
  html += '          <option value="minimum"><?php echo $text_minimum; ?></option>';
  html += '          <option value="rating"><?php echo $text_rating; ?></option>';
  html += '          <option value="dimension"><?php echo $text_dimension; ?></option>';
  html += '          <option value="weight"><?php echo $text_weight; ?></option>';
  html += '          <option value="date_added"><?php echo $text_date_added; ?></option>';
  html += '        </optgroup>';
  html += '        <optgroup label="<?php echo $text_attributes; ?>">';
  <?php foreach ($attributes as $attribute) { ?>
  html += '          <option value="attr<?php echo $attribute['attribute_id']; ?>"><?php echo addslashes($attribute['name']); ?></option>';
  <?php } ?>
  html += '        </optgroup>';
  html += '      </select>';
  html += '    </td>';
  html += '    <td class="center">';
  <?php foreach ($languages as $language) { ?>
  html += '      <input type="text" name="myocwpl_data[' + pricelist_row + '][columns][' + column_row + '][name][<?php echo $language['language_id']; ?>]" value="<?php echo $text_column_name; ?>" size="30" />';
  html += '      <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo addslashes($language['name']); ?>" /><br />';
  <?php } ?>
  html += '    </td>';
  html += '    <td class="center">';
  html += '      <input type="checkbox" name="myocwpl_data[' + pricelist_row + '][columns][' + column_row + '][sortable]" value="1" />';
  html += '    </td>';
  html += '    <td class="center">';
  html += '      <select name="myocwpl_data[' + pricelist_row + '][columns][' + column_row + '][barcode]">';
  html += '        <option value="0"><?php echo $text_none; ?></option>';
  html += '        <option value="codabar"><?php echo $text_bc_codabar; ?></option>';
  html += '        <option value="code11"><?php echo $text_bc_code11; ?></option>';
  html += '        <option value="code39"><?php echo $text_bc_code39; ?></option>';
  html += '        <option value="code93"><?php echo $text_bc_code93; ?></option>';
  html += '        <option value="code128"><?php echo $text_bc_code128; ?></option>';
  html += '        <option value="ean8"><?php echo $text_bc_ean8; ?></option>';
  html += '        <option value="ean13"><?php echo $text_bc_ean13; ?></option>';
  html += '        <option value="std25"><?php echo $text_bc_std25; ?></option>';
  html += '        <option value="int25"><?php echo $text_bc_int25; ?></option>';
  html += '        <option value="msi"><?php echo $text_bc_msi; ?></option>';
  html += '        <option value="datamatrix"><?php echo $text_bc_datamatrix; ?></option>';
  html += '      </select>';
  html += '    </td>';
  html += '    <td class="center">';
  html += '      <input type="checkbox" name="myocwpl_data[' + pricelist_row + '][columns][' + column_row + '][for_pricelist]" value="1" checked="checked" />';
  html += '    </td>';
  html += '    <td class="center">';
  html += '      <input type="checkbox" name="myocwpl_data[' + pricelist_row + '][columns][' + column_row + '][for_print]" value="1" checked="checked" />';
  html += '    </td>';
  html += '    <td class="center">';
  html += '      <input type="checkbox" name="myocwpl_data[' + pricelist_row + '][columns][' + column_row + '][for_pdf]" value="1" checked="checked" />';
  html += '    </td>';
  html += '    <td class="left"><a onclick="$(\'#column-row' + column_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
  html += '  </tr>';
  html += '</tbody>';

  $('#pricelist-column-' + pricelist_row + ' tfoot').before(html);

  column_row++;
}

var module_row = <?php echo $module_row; ?>;

function addModule() {  
  html  = '<tbody id="module-row' + module_row + '">';
  html += '  <tr>';
  html += '    <td class="left"><select name="myoc_pricelist_module[' + module_row + '][layout_id]">';
  <?php foreach ($layouts as $layout) { ?>
  html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
  <?php } ?>
  html += '    </select></td>';  
  html += '    <td class="left"><div class="pricelist scrollbox" style="width:200px;height:80px;"></div></td>';
  html += '    <td class="left"><select name="myoc_pricelist_module[' + module_row + '][position]">';
  html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
  html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
  html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
  html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
  html += '    </select></td>';
  html += '    <td class="left"><select name="myoc_pricelist_module[' + module_row + '][status]">';
  html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
  html += '      <option value="0"><?php echo $text_disabled; ?></option>';
  html += '    </select></td>';
  html += '    <td class="right"><input type="text" name="myoc_pricelist_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
  html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
  html += '  </tr>';
  html += '</tbody>';
  
  $('#module tfoot').before(html);

  html = '';
  $('#tab-pricelist .vtabs a').each(function () {
    var pricelist_row = $(this).attr('id').substring(4);
    var pricelist_id = $("input[name='myocwpl_data[" + pricelist_row + "][pricelist_id]']").val();
    html += '<div id="module' + module_row + '-pricelist' + pricelist_row + '"><label><input type="checkbox" name="myoc_pricelist_module[' + module_row + '][pricelist][]" value="' + pricelist_id + '" /> <?php echo $tab_pricelist; ?> ' + pricelist_row + '</label></div>';
  });

  $('#module-row' + module_row + ' .pricelist').append(html);

  $('#module-row' + module_row + ' .pricelist div:odd').attr('class', 'odd');
  $('#module-row' + module_row + ' .pricelist div:even').attr('class', 'even');
  
  module_row++;
}
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('.vtabs a').tabs();
<?php $pricelist_row = 1; ?>
<?php foreach ($pricelists as $pricelist) { ?>
  $('#autocomplete-product-<?php echo $pricelist_row; ?>').autocomplete(getAutocompleteSource(<?php echo $pricelist_row; ?>));
  <?php $pricelist_row++; ?>
<?php } ?> 
//--></script>
<?php echo $footer; ?>