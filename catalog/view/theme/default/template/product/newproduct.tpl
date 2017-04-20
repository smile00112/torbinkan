<?php echo $header; ?>
<div class="row clearfix">
    <h1><?php echo $heading_title; ?></h1>

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
            href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
</div></div>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- PAGE HEADER ================================================== -->
<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/newproduct/base.css">
<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/newproduct/skeleton.css">
<link rel="stylesheet" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen"/>

<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>

<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if IE]><script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4-iefix.js"></script><![endif]-->

<script type="text/javascript" src="catalog/view/theme/default/js/tabs.js"></script>
<script type="text/javascript" src="catalog/view/theme/default/js/cloud-zoom.1.0.2.js"></script>
<script type="text/javascript" src="catalog/view/theme/default/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="catalog/view/theme/default/js/jquery.hoverIntent.minified.js"></script>
<script type="text/javascript" src="catalog/view/theme/default/js/diapo.js"></script>


<!-- MAIN CONTENT ================================================== -->
<div id="container">
<?php if ($column_right) {
		$info_col = "six";
		$left_div  = '<div class="sixteen pccolumns alpha omega product-info">';
$review_width = "twelve";
} else {
$info_col = "ten";
$left_div = '<div class="sixteen pccolumns alpha omega product-info">';
$review_width = "sixteen";
} ?>

<!-- MAIN WRAPPER -->
<?php echo $left_div; ?>

<?php echo $content_top; ?>
<div class="pccontainer">
<!-- TABS -->
<div id="taboption" class="pctabs">
<div class="sixteen pccolumns clearfix alpha omega">
    <ul>
        <?php if ($options)
        { $ot = 0; ?>
			 <?php foreach ($options as $option):?>
        <?php  $ot++;

			if($ot != 1 )
			{ ?>

        <li><a href="#taboption-<?php echo $ot; ?>"><?php echo $option['step_name']; ?></a></li>


        <?php }else { ?>

        <li><a class="active" href="#taboption-1"><?php echo $option['step_name']; ?></a></li>

        <?php }?>

        <?php if ($option['required']): ?>
        <input type="hidden" id="optionId-<?php echo $ot; ?>" value="<?php echo $option['product_option_id']?>"/>
        <?php endif ?>
        
<?php endforeach ?>
        <?php } ?>
    </ul>
</div>

<br clear="both"/><br clear="both"/>

<!-- FIRST COLUMN -->
<div class="one-third pccolumn alpha pcoptioncolumn leftcorrect">


<!-- DESCRIPTION -->
<div class="pcdescription">

<ul class="tabs-content">


<?php if ($options) { $ot = 0;  ?>

<?php foreach ($options as $option) {  $ot++;  ?>

<li class="<?php if( $ot ==1 )
{echo 'active'; }?>" id="taboption-<?php echo $ot; ?>" style="padding:0px;margin:0px;">
    <div class="pop-content">
        <div class="pcoptions">

            <?php if ($option['type'] == 'select') { ?>
            <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
                <span class="required">*</span>
                <?php } ?><span style=" float:right">Price<span style="padding-left:10px;">Add</span></span>
            </h3>

            <div id="option-<?php echo $option['product_option_id']; ?>" class="pcsingleoption">


              <select name="option[<?php echo $option['product_option_id']; ?>]">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($option['option_value'] as $option_value) { ?>
                    <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                        <?php if ($option_value['price']) { ?>
                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                        <?php } ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            </div>
   
    </li>

<?php } ?>
<?php if ($option['type'] == 'radio') { ?>
<h3 class="pcoptiontitle"><?php  echo $option['name']; ?>
    <?php if ($option['required']): ?>
    <span class="required">*</span>
    <?php endif ?>
    <span style=" float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

<div id="option-<?php echo $option['product_option_id']; ?>" class="option ui-widget-content">

    <?php foreach ($option['option_value'] as $option_value) { ?>


    <div align="left" class="pcsingleoption">
        <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>">
                    <span style="float:left; margin-top: -8px;"><img src="<?php echo $option_value['image']; ?>"
                                                                     title="<?php echo $option_value['name']; ?>"
                                                                     alt="<?php echo $option_value['name']; ?>"/></span>&nbsp;

            <?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
										<span style="float:right"><?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>
                                            <input type="radio"
                                                   name="option[<?php echo $option['product_option_id']; ?>]"
                                                   onclick="addoptionradio( <?php echo $option_value['product_option_value_id']; ?> , '<?php echo addslashes($option_value['name']); ?>' , '<?php echo $option_value['price_prefix'] . $option_value['price_raw']; ?>' , '<?php echo $option_value['price_prefix'] . $option_value['price']; ?>' , <?php echo $option['product_option_id']; ?> , <?php echo $ot; ?>, <?php echo $option_value['option_value_id']; ?> );"
                                                   value="<?php echo $option_value['product_option_value_id']; ?>"
                                                   id="option-value-<?php echo $option_value['product_option_value_id']; ?>"/>
											</span>
            <?php }else{ ?>
                                            <span style="float:right;">
                                            <input type="radio"
                                                   name="option[<?php echo $option['product_option_id']; ?>]"
                                                   onclick="addoptionradio( <?php echo $option_value['product_option_value_id']; ?> , '<?php echo addslashes($option_value['name']); ?>' , '<?php echo $option_value['price_prefix'] .'0'?>' , '<?php echo $option_value['price_prefix'] .'0'?>' , <?php echo $option['product_option_id']; ?> , <?php echo $ot; ?>, <?php echo $option_value['option_value_id']; ?> );"
                                                   value="<?php echo $option_value['product_option_value_id']; ?>"
                                                   id="option-value-<?php echo $option_value['product_option_value_id']; ?>"/>
                                                <?php } ?>
        </label>
        <?php if ($option_value['description']) { ?>
        <br clear="both"/><span>
										<?php echo $option_value['description']; ?>
										 </span><?php } ?>
    </div>
    <br clear="both"/>
    <?php  } ?>
    </div>
	</li>

    <?php } ?>
    <?php if ($option['type'] == 'checkbox') { ?>

    <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?><span style=" float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

    <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

        <br clear="both"/>
        <?php foreach ($option['option_value'] as $option_value) { ?>
        <div align="left">
            <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>">


                        <span style="float:left; margin-top: -8px;"><img src="<?php echo $option_value['image']; ?>"
                                                                         title="<?php echo $option_value['name']; ?>"
                                                                         alt="<?php echo $option_value['name']; ?>"/></span>&nbsp;


                <?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
												<span style="float:right"><?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>
                                                    <input type="checkbox"
                                                           name="option[<?php echo $option['product_option_id']; ?>][]"
                                                           onclick="addoption( <?php echo $option_value['product_option_value_id']; ?> , '<?php echo addslashes($option_value['name']); ?>' , '<?php echo $option_value['price_prefix'] . $option_value['price_raw']; ?>', '<?php echo $option_value['price_prefix'] . $option_value['price']; ?>' , <?php echo $option['product_option_id']; ?> , <?php echo $ot; ?>, <?php echo $option_value['option_value_id']; ?> );"
                                                           value="<?php echo $option_value['product_option_value_id']; ?>"
                                                           id="option-value-<?php echo $option_value['product_option_value_id']; ?>"/>

												</span>

                <?php }else{ ?>
                								<span style=" float:right">
                                                    <input type="checkbox"
                                                           name="option[<?php echo $option['product_option_id']; ?>][]"
                                                           onclick="addoption( <?php echo $option_value['product_option_value_id']; ?> , '<?php echo addslashes($option_value['name']); ?>' , '<?php echo $option_value['price_prefix'] .'0'?>' , '<?php echo $option_value['price_prefix'] .'0'?>' , <?php echo $option['product_option_id']; ?> , <?php echo $ot; ?>, <?php echo $option_value['option_value_id']; ?> );"
                                                           value="<?php echo $option_value['product_option_value_id']; ?>"
                                                           id="option-value-<?php echo $option_value['product_option_value_id']; ?>"/>

												</span>

                <?php } ?>
            </label>
            <?php if ($option_value['description']) { ?>
            <br clear="both">
										<span>
										<?php echo $option_value['description']; ?>
										 </span><?php } ?>
        </div>
        <br clear="both"/>

        <?php } ?>
    </div>
</div>
    </li>
    <?php } ?> 

    <?php if ($option['type'] == 'image') { ?>
    <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?><span style="float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

    <div id="option-<?php echo $option['product_option_id']; ?>" class="pcsingleoption">
        <b><?php echo $option['name']; ?>:</b><br/>
        <table class="option-image">
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <tr>
                <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]"
                                               value="<?php echo $option_value['product_option_value_id']; ?>"
                                               id="option-value-<?php echo $option_value['product_option_value_id']; ?>"/>
                </td>
                <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img
                        src="<?php echo $option_value['image']; ?>"
                        alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>"/></label>
                </td>
                <td><label
                        for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                </label></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    </div>
    </li>
    <?php } ?>

    <?php if ($option['type'] == 'text') { ?>
    <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?><span style=" float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

    <div id="option-<?php echo $option['product_option_id']; ?>" class="pcsingleoption">

        <b><?php echo $option['name']; ?>:</b><br/>
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
               value="<?php echo $option['option_value']; ?>"/>
    </div>
</div>
</li>
<?php } ?>

    <?php if ($option['type'] == 'textarea') { ?>
    <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?><span style="float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

    <div id="option-<?php echo $option['product_option_id']; ?>" class="pcsingleoption">

        <b><?php echo $option['name']; ?>:</b><br/>
        <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40"
                  rows="5"><?php echo $option['option_value']; ?></textarea>
    </div>
</div>
</li>
<?php } ?>

    <?php if ($option['type'] == 'file') { ?>
    <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?><span style=" float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

    <div id="option-<?php echo $option['product_option_id']; ?>" class="pcsingleoption">

        <b><?php echo $option['name']; ?>:</b><br/>
        <a id="button-option-<?php echo $option['product_option_id']; ?>"
           class="button"><span><?php echo $button_upload; ?></span></a>
        <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value=""/>
    </div>
</div>
</li>
<?php } ?>

    <?php if ($option['type'] == 'date') { ?>
    <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?><span style=" float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

    <div id="option-<?php echo $option['product_option_id']; ?>" class="pcsingleoption">
        <b><?php echo $option['name']; ?>:</b><br/>
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
               value="<?php echo $option['option_value']; ?>" class="date"/>
    </div>
</div>
</li>
<?php } ?>

    <?php if ($option['type'] == 'datetime') { ?>
    <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?><span style=" float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

    <div id="option-<?php echo $option['product_option_id']; ?>" class="pcsingleoption">

        <b><?php echo $option['name']; ?>:</b><br/>
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
               value="<?php echo $option['option_value']; ?>" class="datetime"/>
    </div>
</div>
</li>
<?php } ?>

    <?php if ($option['type'] == 'time') { ?>
    <h3 class="pcoptiontitle"><?php  echo $option['name']; ?><?php if ($option['required']) { ?>
        <span class="required">*</span>
        <?php } ?><span style=" float:right">Price<span style="padding-left:10px;">Add</span></span></h3>

    <div id="option-<?php echo $option['product_option_id']; ?>" class="pcsingleoption">

        <b><?php echo $option['name']; ?>:</b><br/>
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]"
               value="<?php echo $option['option_value']; ?>" class="time"/>
    </div>
</div>
</li>
<?php } ?>
<?php } ?>
    <?php } ?>




</ul>
</div>
</div>
</div>
<!-- PRODUCT INFORMATION -->


<!-- PRODUCT IMAGES -->
<div class="one-third pccolumn alpha omega">
    <!-- BUTTONS -->
    <div class="pcbuttons">
        <span class="pop-content pcprevious" align="left" id="previous"><a>Previous step</a></span>
        <span class="pop-content pcstartover" align="center" id="startover"><a>Start over</a></span>
        <span class="pop-content pcnext" align="right" id="next"><a>Next step</a></span>
    </div>

    <?php if ($thumb || $images) { ?>
    <?php if ($thumb) { ?>
    <div class="zoom-section">
        <div class="zoom-small-image image">
            <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class='cloud-zoom' id='zoom1'
               rel="adjustX: 57, adjustY:3"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>"
                                                 alt="<?php echo $heading_title; ?>" id="image"/></a>
        </div>
    </div>
    <?php } ?>
    <?php if ($images) { ?>
    <div class="image-additional no-mobile">
        <?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="fancybox"
           rel="fancybox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>"
                               alt="<?php echo $heading_title; ?>"/></a>
        <?php } ?>
    </div>
    <?php } ?>
    <?php } ?>
</div>

<!-- Product details -->
<div class="one-third pccolumn pcdescription alpha omega">
    <div class="pop-content">

        <div class="indent-content">
            <!-- Product title -->
            <div class="row">
                <h1 class="pctitle"><?php echo $heading_title; ?></h1>
            </div>
            <!-- Image and product -->
            <div class="row">
                <div class="eight pccolumns alpha omega">
                    <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>"
                         alt="<?php echo $heading_title; ?>" height="90" width="100"/>
                </div>
                <div class="eight pccolumns alpha omega">
                    <?php if ($manufacturer) { ?>
                    <span class="pcvar"><?php echo $text_manufacturer; ?></span> <a
                        href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a><br/>
                    <?php } ?>
                    <span class="pcvar"><?php echo $text_model; ?></span> <?php echo $model; ?><br/>
                    <span class="pcvar"><?php echo $text_reward; ?></span> <?php echo $reward; ?><br/>
                    <span class="pcvar"><?php echo $text_stock; ?></span> <?php echo $stock; ?>
                </div>
            </div>
        </div>
        <div classs="row">
            <?php if ($price) { ?>
            <div class="pcprice"><?php echo $text_price; ?>
                <?php if (!$special) { ?>
                <?php echo $price; ?>
                <?php } else { ?>
                <span class="pcprice-old"><?php echo $price; ?></span> <span
                        class="price-new"><?php echo $special; ?></span>
                <?php } ?>
                <?php if ($tax) { ?>
                <span class="pcprice-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span><br/>
                <?php } ?>
                <?php if ($points) { ?>
                <span class="pcreward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span> <br/>
                <?php } ?>
                <?php if ($discounts) { ?>
                <br/>

                <div class="discount">
                    <?php foreach ($discounts as $discount) { ?>
                    <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br/>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <br/>

        <div class="row">
            <div class="pcedited"><h2 class="pctitle">Your configuration:</h2></div>
        </div>
        <div class="row">
            <table id="specialoption">
                <tr class="sixteen pccolumns">
                    <td>
                    <td></td>
                    </td></tr>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
    <?php if ($price) { ?>
    <div class="row">

        <label style="display:inline">
            <?php echo $text_price; ?>
            <?php if (!$special) { ?>
            <?php  echo($currency->getSymbolLeft());?> <input class="customprice" style="auto" value="<?php echo number_format($price_tax, 2, '.', ''); ?>" readonly="readonly"
                   id="priceupdate"/> <?php echo($currency->getSymbolRight());?>

            <?php } else { ?>

            <input class="customprice" value="<?php echo $specialprice_tax; ?>" readonly="readonly" id="priceupdate"/>

            <?php } ?>
        </label>

    </div>
    <?php } ?>

    <div class="cart row">
        <div class="indent-content"><?php echo $text_qty; ?>
            <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>"/>
            <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>"/>
            &nbsp;<a id="button-cart" class="pcbutton"><span><?php echo $button_cart; ?></span></a>
        </div>
        <div class="indent-content"><span>&nbsp;&nbsp;&nbsp;<?php echo $text_or; ?>&nbsp;&nbsp;&nbsp;</span></div>
        <div class="makeawish"><a
                onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a><br/>
            <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a>
        </div>
        <?php if ($minimum > 1) { ?>
        <div class="minimum"><?php echo $text_minimum; ?></div>
        <?php } ?>
    </div>
    <?php if ($review_status) { ?>
    <div class="review indent-content">
        <div><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png"
                  alt="<?php echo $reviews; ?>"/>&nbsp;&nbsp;<a
                onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a
                onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a></div>
        <div class="share"><!-- AddThis Button BEGIN -->
            <div class="addthis_default_style"><a class="addthis_button_compact"><?php echo $text_share; ?></a> <a
                    class="addthis_button_email"></a><a class="addthis_button_print"></a> <a
                    class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a></div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
            <!-- AddThis Button END -->
        </div>
    </div>
    <?php } ?>
</div>
</div>

<!-- PRODUCT TABS -->
<div class="<?php echo $review_width; ?> pccolumns clearfix alpha omega">
    <ul class="tabs">
        <li><a class="active" href="#tab-description"><?php echo $tab_description; ?></a></li>
        <?php if ($attribute_groups) { ?>
        <li><a href="#tab-attribute"><?php echo $tab_attribute; ?></a></li>
        <?php } ?>
        <?php if ($review_status) { ?>
        <li><a href="#tab-review"><?php echo $tab_review; ?></a></li>
        <?php } ?>
        <?php if ($products) { ?>
        <li><a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a></li>
        <?php } ?>
        <?php if ($tags) { ?>
        <li><a href="#tab-tags"><?php echo $text_tags; ?></a></li>
        <?php } ?>
    </ul>
    <ul class="tabs-content" style="padding:0px; margin:0px;">
        <li class="active" id="tab-description">
            <?php echo $description; ?>
        </li>
        <?php if ($attribute_groups) { ?>
        <li id="tab-attribute">
            <table class="attribute">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                <tr>
                    <td colspan="2"><?php echo $attribute_group['name']; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <tr>
                    <td><?php echo $attribute['name']; ?></td>
                    <td><?php echo $attribute['text']; ?></td>
                </tr>
                <?php } ?>
                </tbody>
                <?php } ?>
            </table>
        </li>
        <?php } ?>
        <?php if ($review_status) { ?>
        <li id="tab-review">
            <div id="review"></div>
            <h4><?php echo $text_write; ?></h4>
            <label><?php echo $entry_name; ?></label>
            <input type="text" name="name" value=""/>
            <label><?php echo $entry_review; ?></label>
            <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
            <span style="font-size: 11px;"><?php echo $text_note; ?></span><br/>
            <strong><?php echo $entry_rating; ?></strong> <span><?php echo $entry_bad; ?></span>
            <input type="radio" name="rating" value="1"/>
            <input type="radio" name="rating" value="2"/>
            <input type="radio" name="rating" value="3"/>
            <input type="radio" name="rating" value="4"/>
            <input type="radio" name="rating" value="5"/>
            <span><?php echo $entry_good; ?></span><br/><br/>
            <label><?php echo $entry_captcha; ?></label>
            <input type="text" name="captcha" value=""/>
            <img src="index.php?route=product/product/captcha" alt="" id="captcha"/>

            <div class="buttons"><a id="button-review" class="button"><span><?php echo $button_continue; ?></span></a>
            </div>
        </li>
        <?php } ?>
        <?php if ($products) { ?>
        <li id="tab-related">
            <div class="box-product">
                <?php foreach ($products as $product) { ?>
                <div style="width: 200px; float: left;" class="product-related">
                    <?php if ($product['thumb']) { ?>
                    <div class="image"><a href="<?php echo $product['href']; ?>"><img
                            src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"/></a></div>
                    <?php } ?>
                    <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                    </div>
                    <?php if ($product['price']) { ?>
                    <div class="price">
                        <?php if (!$product['special']) { ?>
                        <?php echo $product['price']; ?>
                        <?php } else { ?>
                        <span class="price-old"><?php echo $product['price']; ?></span> <span
                            class="price-new"><?php echo $product['special']; ?></span>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <?php if ($product['rating']) { ?>
                    <div class="rating"><img
                            src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png"
                            alt="<?php echo $product['reviews']; ?>"/></div>
                    <?php } ?>
                    <a onclick="addToCart('<?php echo $product['product_id']; ?>');"
                       class="button"><span><?php echo $button_cart; ?></span></a></div>
                <?php } ?>
            </div>
        </li>
        <?php } ?>
        <?php if ($tags) { ?>
        <li id="tab-tags">
            <?php foreach ($tags as $tag) { ?>
            <a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a>,
            <?php } ?>
        </li>
        <?php } ?>
    </ul>


</div>

<?php echo $content_bottom; ?>
</div>
<!-- RIGHT COLUMN ================================================== -->
<?php if ($column_right) { ?>
<div class="four pccolumns omega no-mobile">
    <div style="padding-left: 10px;"><?php echo $column_right; ?></div>
</div>
<?php } ?>
</div>
<script type="text/javascript"><!--
$('.fancybox').fancybox({cyclic: true});
//--></script>
<script type="text/javascript"><!--
$('#button-cart').bind('click', function () {
    $.ajax({
        url:'index.php?route=checkout/cart/add',
        type:'post',
        data:$('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
        dataType:'json',
        success:function (json) {
            $('.success, .warning, .attention, information, .error').remove();

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                    $('.warning').fadeIn('slow');
                }

                if (json['error']['option']) {
                    for (errori in json['error']['option']) {
                        $('#notification').after('<div class="warning" style="display: none;">' + json['error']['option'][errori] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                        $('#option-' + errori).after('<span class="error">' + json['error']['option'][errori] + '</span>');
                    }
                }
            }

            if (json['success']) {
                $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');

                $('#cart_total').html(json['total']);


            }
            $('html, body').animate({ scrollTop:0 }, 'slow');
        }
    });
});
//--></script>

<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file' && isset($option['product_option_id'])) { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
    action:'index.php?route=product/product/upload',
    name:'file',
    autoSubmit:true,
    responseType:'json',
    onSubmit:function (file, extension) {
        $('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
    },
    onComplete:function (file, json) {
        $('.error').remove();

        if (json.success) {
            $('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json.file);
        }

        if (json.error) {
            $('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json.error + '</span>');
        }

        $('.loading').remove();
    }
}
)
;
//--></script>

<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function () {
    $('#review').slideUp('slow');

    $('#review').load(this.href);

    $('#review').slideDown('slow');

    return false;
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function () {
    $.ajax({
        type:'POST',
        url:'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
        dataType:'json',
        data:'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
        beforeSend:function () {
            $('.success, .warning').remove();
            $('#button-review').attr('disabled', true);
            $('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
        },
        complete:function () {
            $('#button-review').attr('disabled', false);
            $('.attention').remove();
        },
        success:function (data) {
            if (data.error) {
                $('#review-title').after('<div class="warning">' + data.error + '</div>');
            }

            if (data.success) {
                $('#review-title').after('<div class="success">' + data.success + '</div>');

                $('input[name=\'name\']').val('');
                $('textarea[name=\'text\']').val('');
                $('input[name=\'rating\']:checked').attr('checked', '');
                $('input[name=\'captcha\']').val('');
            }
        }
    });
});
//--></script>


<script type="text/javascript">


function addoption(Id, name, price_raw, price_full, tab_id, opidx, parentopt) {

    selectedoptions[tab_id] = selectedoptions[tab_id] == undefined ? 1 : selectedoptions[tab_id] + 1;

    var optionid = 'option-value-' + Id;

    html = '<tbody id="special-row' + Id + '" align="left" class="special-row">';
    html += '<tr><td><label class="pclabel">';
    html += '<input type="checkbox" name="' + Id + '" checked="checked" onclick="removeoption( ' + Id + ' , \'' + price_raw + '\',' + tab_id + ',\'checkbox\' )"  />';
    html += name;
    html += '<span style="float:right" class="pcpricespan">' + price_full;
    html += '</span></label></td></tr></tbody>';

    var priceadd = document.getElementById('priceupdate').value;

    var symbol = price_raw.substr(0, 1);
    var mainprice = priceadd;
    var addvalue = price_raw.substr(1);


    if (document.getElementById(optionid).checked) {
        $('#specialoption tfoot').before(html);

        var price_final = (symbol === '-') ? (Number(mainprice) - Number(addvalue)).toFixed(2) : (Number(mainprice) + Number(addvalue)).toFixed(2);

        document.getElementById('priceupdate').value = price_final;
    }
    else {
        var price_final = (Number(mainprice) - Number(addvalue)).toFixed(2);

        document.getElementById('priceupdate').value = price_final;

        var removeid = '#special-row' + Id;
        $(removeid).remove();


    }
    var ast = Number(opidx) + 1;
    $('#taboption-' + ast).hide();
    $.ajax({
        url:'index.php?route=product/product/childoption&product_id=<?php echo $product_id; ?>',
        type:'post',
        data:'tabid=' + opidx + '&parentopt=' + parentopt,
        dataType:'json',
        success:function (json) {
            submit_success(json, ast, opidx);
        }
    });
}

function submit_success(json, ast, opidx) {

    if (json['type']) {

        htmlx = '<div class="pop-content">';
        htmlx += '<div class="pcoptions">';

        if (json['type'] == 'radio') {
            htmlx += '<h3 class="pcoptiontitle">' + json['name'];

            if (json['required']) {
                htmlx += '<span class="required">*</span>';
                
            }

            htmlx += '<span style=" float:right">Price<span style="padding-left:10px;">Add</span></span></h3>';
            htmlx += '<div id="option-' + json['product_option_id'] + '" class="option">';

            for (i = 0; i < json['option_value'].length; i++) {
                htmlx += '<div align="left" class="pcsingleoption">';

                htmlx += '<label for="option-value-' + json['option_value'][i]['product_option_value_id'] + '">';
                htmlx += '<span style="float:left; margin-top: -8px;" >';
                htmlx += '<img src="' + json['option_value'][i]['image'] + '" title="' + json['option_value'][i]['name'] + '" alt="' + json['option_value'][i]['name'] + '" />';
                htmlx += '</span>&nbsp;';

                htmlx += json['option_value'][i]['name'];

                htmlx += '<span style=" float:right">';
                if (json['option_value'][i]['price'])
                {
                    htmlx += json['option_value'][i]['price_prefix'];
                    htmlx += json['option_value'][i]['price_raw'];
                }
                htmlx += '<input type="radio" name="option[' + json['product_option_id'] + ']"';
                htmlx += 'onclick="addoptionradio( ' + json['option_value'][i]['product_option_value_id'] + ' ,\'' + json['option_value'][i]['name'] + '\',\'' + json['option_value'][i]['price_prefix'] + json['option_value'][i]['price_raw'] + '\',\'' + json['option_value'][i]['price_prefix'] + json['option_value'][i]['price'] + '\',' + json['product_option_id'] + ',' + ast + ',' + json['option_value'][i]['option_value_id'] + ');"';
                htmlx += 'value="' + json['option_value'][i]['product_option_value_id'] + '" id="option-value-' + json['option_value'][i]['product_option_value_id'] + '" />';
                htmlx += '</span>';

                htmlx += '</label>';
                if (json['option_value'][i]['description']) {
                    htmlx += '<br clear="both"><span >' + json['option_value'][i]['description'] + '</span>';
                }
                htmlx += '</div><br clear="both" />';
            }

            htmlx += '</div> ';
            document.getElementById('taboption-' + ast).innerHTML =htmlx;
            $('#taboption-' + ast).slideDown('slow');
        }

        if (json['type'] == 'checkbox') {
            htmlx += '<h3 class="pcoptiontitle">' + json['name'];

            if (json['required']) {
                htmlx += '<span class="required">*</span>';
            }

            htmlx += '<span style="float:right">Price<span style="padding-left:10px;">Add</span></span></h3>';

            htmlx += '<div id="option-' + json['product_option_id'] + '" class="option">';

            for (i = 0; i < json['option_value'].length; i++) {
                htmlx += '<div align="left" class="pcsingleoption">';
                htmlx += '<label for="option-value-' + json['option_value'][i]['product_option_value_id'] + '">';
                htmlx += '<span style="float:left; margin-top: -8px;" >';
                htmlx += '<img src="' + json['option_value'][i]['image'] + '" title="' + json['option_value'][i]['name'] + '" alt="' + json['option_value'][i]['name'] + '" />';
                htmlx += '</span>&nbsp;';

                htmlx += json['option_value'][i]['name'];

                htmlx += '<span style=" float:right">';
                if (json['option_value'][i]['price'])
                {
                    htmlx += json['option_value'][i]['price_prefix'];
                    htmlx += json['option_value'][i]['price_raw'];
                }
                htmlx += '<input type="checkbox" name="option[' + json['product_option_id'] + '][]"';

                htmlx += 'onclick="addoption( ' + json['option_value'][i]['product_option_value_id'] + ' ,\'' + json['option_value'][i]['name'] + '\',\'' + json['option_value'][i]['price_prefix'] + json['option_value'][i]['price_raw'] + '\',\'' + json['option_value'][i]['price_prefix'] + json['option_value'][i]['price'] + '\',' + json['product_option_id'] + ',' + ast + ',' + json['option_value'][i]['option_value_id'] + ');"';
    
                htmlx += 'value="' + json['option_value'][i]['product_option_value_id'] + '" id="option-value-' + json['option_value'][i]['product_option_value_id'] + '" />';
                htmlx += '</span>';

                htmlx += '</label>';
                if (json['option_value'][i]['description']) {
                    htmlx += '<br clear="both"><span>' + json['option_value'][i]['description'] + '</span>';
                }
                htmlx += '</div><br clear="both"/>';
            }

            htmlx += '</div> ';

            document.getElementById('taboption-' + ast).innerHTML =htmlx;
            $('#taboption-' + ast).slideDown('slow');

        }
    }
}

var radio = new Array();
var radioval = 1;
var radioold = new Array();
var pos = 1;
var selectedoptions = new Array();

function addoptionradio(Id, name, price_raw, price_full, tab_id, opidx, parentopt) {

    var optionid = 'option-value-' + Id;

    selectedoptions[tab_id] = 1;

    html = '<tbody id="special-row' + Id + '" align="left" class="special-row">';
    html += '<tr><td><label class="pclabel">';
    html += '<input type="checkbox" name="' + Id + '" checked="checked" onclick="removeoption( ' + Id + ' , \'' + price_raw + '\',' + tab_id + ' )"  />';
    html += name;

    if (price_raw > 0) {
        html += '<span style="float:right" class="pcpricespan">' + price_full;
        html += '</span>';
    }
    html += '</label></td></tr></tbody>';

    var price_final = document.getElementById('priceupdate').value;

    var addvalue = price_raw.substr(1);
    var symbol = price_raw.substr(0, 1);
    var removeid = '#special-row' + Id;

    if (!(Id in radio)) {
        radio[Id] = radioval;
    }

    radioold[pos] = tab_id;
    radioold[pos + 1] = Id;
    radioold[pos + 2] = removeid;
    radioold[pos + 3] = addvalue;
    pos = pos + 4;

    if ((document.getElementById(optionid).checked ) && ( radio[Id] == 1 )) {
        for (nt = 0; nt <= radioold.length;) {
            var couold = pos - 8 - nt;
            var counew = pos - 4 - nt;
            if (couold in radioold) {
                if (radioold[couold] == radioold[counew]) {
                    var price_final = (Number(price_final) - Number(radioold[couold + 3])).toFixed(2);
                    radioold[couold + 3] = 0;

                    document.getElementById('priceupdate').value = price_final;
                    radio[radioold[couold + 1]] = 1;

                    $(radioold[couold + 2]).remove();
                }
            }

            nt = nt + 4;

        }
        $('#specialoption tfoot').before(html);

        var priceaddx = document.getElementById('priceupdate').value;

        mainprice = priceaddx;

        var price_final = (symbol === '-') ? (Number(mainprice) - Number(addvalue)).toFixed(2) : (Number(mainprice) + Number(addvalue)).toFixed(2);

        document.getElementById('priceupdate').value = price_final;
        radio[Id] = 2;
        var ast = Number(opidx) + 1;
        $('#taboption-' + ast).hide();
        $.ajax({
            url:'index.php?route=product/product/childoption&product_id=<?php echo $product_id; ?>',
            type:'post',
            data:'tabid=' + opidx + '&parentopt=' + parentopt,
            dataType:'json',
            success:function (json) {
                submit_success(json, ast, opidx);
            }
        });
    }
}

function removeoption(Id, price, tab_id, type) {
    var optionid = 'option-value-' + Id;
    var priceadd = document.getElementById('priceupdate').value;

    var mainprice = priceadd;
    var symbol = price.substr(0, 1);
    var addvalue = price.substr(1);

    var price_final = (symbol === '-') ? (Number(mainprice) + Number(addvalue)).toFixed(2) : (Number(mainprice) - Number(addvalue)).toFixed(2);

    document.getElementById('priceupdate').value = price_final;

    var removeId = '#special-row' + Id;
    $(removeId).remove();
    delete radio[Id];
    for (nt = 1; nt <= radioold.length;) {
        if (radioold[nt + 1] == Id) {
            delete radioold[nt];
            delete radioold[nt + 1];
            delete radioold[nt + 2];
            delete radioold[nt + 3];
        }
        nt = nt + 4;
    }
    document.getElementById(optionid).checked = false;
}


var current;
var isValid = false; // form validation returning true or false
var previousIndex = 1;
$(function () {
    $('#taboption').tabs({

        select:function (event, ui) {
	        
            var $tabs = $('#taboption').tabs();
            selected1= $tabs.tabs('option', 'selected');
            next1 = Number(selected1) + 1;
            hiddenOption = $("#optionId-" + previousIndex);

            if (hiddenOption == undefined || ui.index < previousIndex){
                previousIndex = ui.index+ 1;
                return true;
            }
            optionId = hiddenOption.val();

            if (selectedoptions[optionId] != undefined) {
                previousIndex = ui.index + 1;
                return true;
            }
            return false;
        }
    });
});


$('#next').click(function () { // bind click event to link

    var $tabs = $('#taboption').tabs();
    var selected = $tabs.tabs('option', 'selected');

    isValid = true;
    $tabs.tabs('select', Number(selected) + 1); // switch to third tab
    isValid = false;

    return false;
});


$('#previous').click(function () { // bind click event to link
    var $tabs = $('#taboption').tabs();
    var selected = $tabs.tabs('option', 'selected');

    isValid = true;
    $tabs.tabs('select', Number(selected) - 1); // switch to third tab
    isValid = false;
    return false;
});


$('#startover').click(function () { // bind click event to link
    location.reload();
    return;
});


</script>

<?php echo $footer; ?>