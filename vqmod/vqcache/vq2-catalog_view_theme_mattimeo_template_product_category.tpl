<?php
$this->language->load('module/mattimeotheme');
$category_details = $this->language->get('category_details');
$button_quick = $this->language->get('entry_quickview');
?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $i=> $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><?php if($i+1<count($breadcrumbs)) { ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a> <?php } else { ?><?php echo $breadcrumb['text']; ?><?php } ?>

    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php echo $content_top; ?>
  
    <?php if ($categories) { ?>
  
  <div class="catalog-section">
    <div class="catalog-section-childs">
      <?php foreach ($categories as $category) { ?>
<div class="catalog-section-child">
<a href="<?php echo $category['href']; ?>">
<span class="child"><span class="image"><img src="<?php echo $category['thumb']; ?>" ></span><span class="text"><?php echo $category['name']; ?></span></span>
</a></div>
      <?php } ?>
    </div>

  </div>
  <?php } ?>

  
  <?php if ($products) { ?>
  <div class="product-filter">
   
    
    <div class="limit"><?php echo $text_limit; ?>
      <select onchange="location = this.value;" class="select1">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort">
      <select onchange="location = this.value;" class="select1">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div> 
  </div>
   

  <div class="product-grid catalog-item-cards">
    <?php foreach ($products as $product) { ?>
      <div class="catalog-item-card">
	  <div class="catalog-item-info">
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
		<div class="article_rating"><div class="article">Артикул:	<?php echo $product['model']; ?></div>
		<?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
		<div class="clr"></div>
		</div>
		<div class="description"><?php echo $product['description']; ?></div>

<?php if ($product['price']) { ?>		
<div class="item-price-cont">
<div class="item-price">
<?php if (!$product['special']) { ?>
<span class="catalog-item-price"><?php echo $product['price']; ?></span>
<?php } else { ?>
<span class="catalog-item-price-old"><?php echo $product['price']; ?></span>
<span class="catalog-item-price-percent">Экономия <?php echo $product['saving']; ?></span>
<span class="catalog-item-price"><?php echo $product['special']; ?></span>
 <?php } ?>												
</div>											
</div>
        <?php } ?>
        <div class="buy_more">
		<div class="available">
		<div class="avl"><i class="fa fa-check-circle"></i><span>В наличии <?php echo $product['stock']; ?></span>
		</div>
		</div>
		<div class="clr"></div>
		<div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><i class="fa fa-bar-chart"></i></a></div>
		<div class="delay"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart-o"></i></a></div>
		
		<button data-type="v-category" data-active="<?php echo $this->config->get( 'product_quantity_v_category' ); ?>"  data-type="v-category" data-active="<?php echo $this->config->get( 'product_quantity_v_category' ); ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="btn_buy" ><i class="fa fa-shopping-cart"></i>Купить</button></div>
      </div></div>
    <?php } ?>

  </div>
  
  
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
   <!--Category description-->
  <?php if ($thumb || $description) { ?>
  <div class="accordeon_description">
  <?php if  ($this->config->get('detail_view')) { ?>  <div class="accordeon_plus"><?php echo $category_details; ?></div> <?php } ?>
  <div <?php if  ($this->config->get('detail_view')) { ?> class="view"  <?php } ?>>
  <?php if ($thumb || $description) { ?>

  <div class="category-info">
    <?php if ($thumb) { ?>
    <div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
    <?php } ?>
    <?php if ($description) { ?>
    <?php echo $description; ?>
    <?php } ?>
  </div>
  <?php } ?>

   </div>
   </div>
  <?php } ?>
    <!--end Category description--> 
  <?php if (!$categories && !$products) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
    <?php echo $content_bottom; ?>
  </div>

<script type="text/javascript"><!--

view = $.totalStorage('display');

if (view) {
	display(view);
} else {
	display('grid');
}
//--></script>
  
<script type="text/javascript"><!--
$(document).ready(function() {
if ($('.category-info')[0].scrollHeight > 165)  {

$(".category-info").after('<div id="obexpand" class="obertka"><button class="expand" type="button" id="expand"><span class="">Подробнее >></span></button></div>');
$(".category-info").after('<div id="obhide" class="obertka" style="display:none;"><button class="expand" type="button" id="hide"><span class=""><< Закрыть</span></button></div>');
$('.category-info').append("<div class='hide'></div>");
};

});

$('#expand').live('click',function(){
$('#obexpand').css("display", "none");
               $('.category-info').animate({height: $('.category-info')[0].scrollHeight}, 600);
$('#obhide').css("display", "block");
$('.hide').css("display", "none");
 });


$('#hide').live('click',function(){
$('#obhide').css("display", "none");
               $('.category-info').animate({height: 165}, 600);
$('#obexpand').css("display", "block");
$('.hide').css("display", "block");

 });
//--></script> 

<style type="text/css">
.category-info {
position:relative;
height: 165px;
overflow: hidden;}
.hide {
position:absolute;
top:115px;
width:100%;
height:50px;
background: url("catalog/view/theme/default/image/hide.png") repeat-x transparent;
}
.obertka {
width: 100%;
text-align: center;
border-bottom: #ccc solid 1px;
height: 8px;
margin:0 auto;
margin-bottom: 20px;
}
.expand {
height: 18px;
padding: 0 .9em;
border-width: 1px;
border-style: solid;
outline: 0;
font-weight: normal;
font-size: 11px;
white-space: nowrap;
word-wrap: normal;
vertical-align: middle;
cursor: pointer;
-moz-border-radius: 2px;
-webkit-border-radius: 2px;
border-radius: 2px;
background:white;}

.expand:hover {border:#ccc solid 1px;background:#eee;}
 </style>

<?php echo $footer; ?>