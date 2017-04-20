<?php echo $header; ?>
<div id="content">
<style>
    .mpchanges-actions{
        vertical-align: top;
    }

    .mpchanges-actions input.date{
        width: 70px;
    }

    table.form > tbody > tr > td:first-child {
        width: 90px;
    }

</style>
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="box" id="mpchanges_box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $url_cancel; ?>';" class="button">
                    <?php echo $button_cancel; ?>
                </a>
            </div>
        </div>
        <div class="content">
            <table class="form" id="filter">
                <tr>
                    <td><?php echo $entry_store; ?></td>
                    <td width="100px">
                        <select name="store_id" class="filter_option">
                            <option value="0"><?php echo $store_default; ?></option>
                        </select>
                    </td>
                    <td rowspan="8" class="mpchanges-actions">
                    <div class="vtabs">
                        <a href="#prices">
                            <?php echo $tab_prices; ?>
                        </a>
                    
                    </div>

                    <div id="prices" class="vtabs-content">
                        <form action="<?php echo $action_change_price; ?>" method="post" enctype="multipart/form-data" id="form_change_price">
                            <table class="form">
                                <tr>
                                    <td><?php echo $entry_price; ?></td>
                                    <td colspan="2">
                                        <select name="price_diff">
                                            <option value="-">-</option>
                                            <option value="+">+</option>
                                            <option value="*">*</option>
                                            <option value="/">/</option>
                                            <option value="=">=</option>
                                        </select>
                                        <input type="text" name="manufacturer_price" value=""/>
                                        <select name="change_type">
                                            <option value="percent"><?php echo $label_percent; ?></option>
                                            <option value="number"><?php echo $label_number; ?></option>
                                        </select>
                                    </td>
                                </tr>
								
								  <tr>
                                    <td>Валюта</td>
                                    <td colspan="2">
                                        <select style="display:none" name="quantities_diff">
                                            <option value="=">=</option>
                                        </select>
										 <select  name="manufacturer_quantities">
                                        
										 <option value="1">Рубль</option>
										  <option value="2">USD</option>
										   <option value="3">Euro</option>
										</select>
                                        <select style="display:none" name="change_type_quantities">
                                            <option value="number"><?php echo $label_number; ?></option>
                                        </select>
                                    </td>
                                </tr>
                                

                                <tr>
                                    <td>
                                        <div class="buttons">
                                            <a class="button submit-form" data-form="form_change_price"><?php echo $button_change_price; ?></a>
                                        </div>
                                    </td>
                                   
                                </tr>
                            </table>
                        </form>
                    </div>

                 </td>
                </tr>
                <tr>
                    <td><?php echo $entry_manufacturer; ?></td>
                    <td>
                        <select name="manufacturer_id" class="filter_option">
                            <option value="0"><?php echo $option_all; ?></option>
                            <?php foreach ($manufacturers as $manufacturer) { ?>
                            <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
             
                <tr>
                    <td><?php echo $entry_category; ?></td>
                    <td>
                        <select name="category_id" class="filter_option">
                            <option value="0"><?php echo $option_all; ?></option>
                            <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                            <?php } ?>
                        </select>
                        <input type="checkbox" id="filter_sub_category" name="filter_sub_category"/>
                        <label for="filter_sub_category"><?php echo $entry_subcategory; ?></label>
                    </td>
                </tr>
              
                <tr>
                    <td colspan="3">
                        <?php echo $text_filtered_products;?> <span id="product-total"></span>
                        <div class="scrollbox" id="check-all" style="height: 22px; width: 100%; background: #B7D7F5; cursor:pointer;"><input type="checkbox" checked="checked" name="change_all" value="true" /> <?php echo $text_all_products;?></div>
                        <div class="scrollbox" id="filtered-products" style="height: 330px; width: 100%;"></div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    <style>
        #filtered-products div:hover{background: lightgrey;}
        #filtered-products div{height:16px;}
    </style>

    <script>
        $(function(){
            $('body').on('click', '.submit-form', function(){
                var action = $(this).data('action') != undefined ? $(this).data('action') : 'add';
                $.ajax({
                    url: $('#' + $(this).data('form')).attr('action') + '&action=' + action,
                    type: 'post',
                    data: $('#' + $(this).data('form') + ' input[type="text"], #' + $(this).data('form') + ' input[type="radio"], #' + $(this).data('form') + ' input[type="checkbox"]:checked, #' + $(this).data('form') + ' select, #filtered-products input[type="checkbox"]:checked, #check-all input[type="checkbox"]:checked').add($('#filter .filter_option, #filter input[type="checkbox"]:checked')),
                    dataType: 'json',
                    success: function(json) {
                        productTotal = parseInt(json['total']);
                        $('.success, .warning, .attention, .information, .error').remove();
                        $('#mpchanges_box').before('<div class="' + json['message']['type'] + '" style="display: none;">' + json['message']['message'] + '</div>');
                        $('.' + json['message']['type']).fadeIn('slow');
                        $('#filtered-products').html('');
                        check_all = $('input', '#check-all').prop('checked') ? 'checked="checked"' : '';
                        html = productHTML(json['products'], check_all);
                        $('#filtered-products').append(html);
                    }
                });
            })
            $('.vtabs a').tabs();
        });
    </script>
    <script type="text/javascript"><!--
    var special_row = 1;
    var discount_row = 1;
    var productTotal = 0;
    function addSpecials() {
        html  = '<tbody id="special-row' + special_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
        <?php foreach ($customer_groups as $customer_group) { ?>
            html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
        <?php } ?>
        html += '    </select></td>';
        html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
        html += '    <td class="right"><select name="product_special[' + special_row + '][price_diff]"><option value="-">-</option><option value="+">+</option><option value="*">*</option><option value="/">/</option><option value="=">=</option></select>';
        html += '    <input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
        html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
        html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
        html += '    <td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#special').append(html);

        $('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

        special_row++;
    }

    function addDiscounts() {
        html  = '<tbody id="discount-row' + discount_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><select name="product_discount[' + discount_row + '][customer_group_id]">';
        <?php foreach ($customer_groups as $customer_group) { ?>
            html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
        <?php } ?>
        html += '    </select></td>';
        html += '    <td class="right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" /></td>';
        html += '    <td class="right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
        html += '    <td class="right"><select name="product_discount[' + discount_row + '][price_diff]"><option value="-">-</option><option value="+">+</option><option value="*">*</option><option value="/">/</option><option value="=">=</option></select>';
        html += '    <input type="text" name="product_discount[' + discount_row + '][price]" value="" /></td>';
        html += '    <td class="left"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" class="date" /></td>';
        html += '    <td class="left"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" class="date" /></td>';
        html += '    <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#discount').append(html);

        $('#discount-row' + discount_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

        discount_row++;
    }

    function productHTML(products, check_all){
        html= '';
        index = 0;
        for (product in products){
            var discount = "&nbsp;";
            var special = "&nbsp;";

            if (products[product]['discount'] == '1'){
                discount = 'D';
            }

            if (products[product]['special'] == '1'){
                special = 'S';
            }

            rowType = (index % 2 == 0) ? 'odd' : 'even';
            html += '<div class="' + rowType + '" style="cursor:pointer;">';
            html += '<input type="checkbox" ' + check_all + ' name="product_to_change[]" value="'+products[product]['product_id']+'" />[<span style="width: 10px;">'+discount+'</span>][<span style="width: 10px;">'+special+'</span>] ' + products[product]['name'];
            html += '<span style="float:right; margin:0 3px; min-width: 25px;">';
            html += products[product]['quantity'];
            html += '</span>';

            html += '<span style="float:right; margin:0 3px; width: 100px;">';
            html += products[product]['price'];
            html += '</span>';
            html += '</div>';
            index++;
        }
        return html;
    }

    function load_products(start, limit){
        $.ajax({
            url: 'index.php?route=module/mpchanges/loadFilteredProducts&product_list=1&start=' + start + '&limit=' + limit + '&token=<?php echo $token; ?>',
            type: 'post',
            data: $('.filter_option', '#filter').add($('input[type="checkbox"]:checked', '#filter')),
            dataType: 'json',
            success: function(json) {
                productTotal = parseInt(json['total']);
                $('#product-total').html(json['total']);
                check_all = $('input', '#check-all').prop('checked') ? 'checked="checked"' : '';
                html = productHTML(json['products'], check_all);
                $('#filtered-products').append(html);
            }
        });
    }

    $('body')
            .on('click', '#filtered-products div', function(e){
                if (e.target.tagName != "INPUT"){
                    $('input', $(this)).prop('checked', !$('input', $(this)).prop('checked'));
                }
                $('input','#check-all').prop('checked', false);
            })
            .on('click', '#check-all', function(e){
                if (e.target.tagName != "INPUT"){
                    $('input', $(this)).prop('checked', !$('input', $(this)).prop('checked'));
                }
                if ($('input', $(this)).prop('checked')){   $('input', '#filtered-products').prop('checked',true);
                }else{                                      $('input', '#filtered-products').prop('checked',false);}
            });

    start = 30;
    limit = 30;

    $('.filter_option, #filter input[type="checkbox"]').on('change', function(){
        if ($(this).attr('name') != 'customer_group' && $(this).attr('name') != 'filter_round') {
            $('#filtered-products').html('');
            start = 30;
            limit = 30;
            load_products(0, limit);
        }
    });

    load_products(0, limit);

    $('#filtered-products').on('scroll',function(){
        productCount = $('#filtered-products div').size();
        scrollTop = (productCount - 15) * 22;
        if ($(this).scrollTop() == scrollTop && start <= productTotal){
            load_products(start, limit);
            start += limit;
        }
    })
    //--></script>
</div>
<?php echo $footer; ?>