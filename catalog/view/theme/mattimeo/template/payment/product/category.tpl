<?php
$this->language->load('module/mattimeotheme');
$category_details = $this->language->get('category_details');
$button_quick = $this->language->get('entry_quickview');
?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php echo $content_top; ?>
  
 <!--Category description--> 
  <?php if ($thumb || $description || $categories) { ?>
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
  <?php if ($categories) { ?>
  <h2><?php echo $text_refine; ?></h2>
  <div class="category-list">
    <?php if (count($categories) <= 5) { ?>
    <ul>
      <?php foreach ($categories as $category) { ?>
      <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($categories);) { ?>
    <ul>
      <?php $j = $i + ceil(count($categories) / 4); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($categories[$i])) { ?>
      <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>
  
   </div>
   </div>
  <?php } ?> 
  <!--end Category description--> 
  
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
   <?php if ($this->config->get('show_compare') == '1')  { ?>
    <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><div></div><?php echo $text_compare; ?></a></div>
    <?php } ?>
  </div>
   

  <div class="product-grid">
    <?php foreach ($products as $product) { ?>
   
       <div class="item">
       
       <?php if ($product['thumb']) { ?> 
       
           <?php if  ($this->config->get('img_additional2') == '1') { ?>
               <!--Additional images-->
           <div class="owl-addimagecat owl-carousel"> <?php } ?>
               
         <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" ></a></div>
           
           <?php if ((isset($product['dop_img'])) && ($this->config->get('img_additional2') == '1')) { ?>
           <?php foreach ($product['dop_img'] as $key => $img) { ?>
               <div class="image image<?php echo $key;?>"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $img;?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a></div>
           <?php } ?>
           <?php } ?>
           
           <?php if  ($this->config->get('img_additional2') == '1') { ?>
            <!--end additional images-->
            </div> <?php } ?>
            
           <?php } ?> 
     
     
     <div class="name">
      <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
      <div class="description"><?php echo $product['description']; ?></div>    
          
    </div>
    <?php } ?>

  </div>
  
  
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
  <?php if (!$categories && !$products) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?></div>
  <div class="cont_bottom"></div>
  <?php echo $content_bottom; ?>
<script type="text/javascript"><!--
function display(view) {
	if (view == 'grid') {
		
		$('.product-grid > div').each(function(index, element) {
			html = '';
			
			html += '<div class=img_but>';
		   <?php if  ($this->config->get('img_additional2') == '1') { ?> 
			html += '<div class="owl-addimagecat owl-carousel">';
		   <?php } ?>
			
			var image = $(element).find('.image').html();
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
			<?php if  ($this->config->get('img_additional2') == '1') { 
			for ($key = 0; $key < 6; $key++) { ?>
			var image2 = $(element).find('.image<?php echo $key;?>').html();
			if (image2 != null) { 
			html += ' <div class="image image<?php echo $key;?>">' + image2 + '</div>';
			
			}
			<?php } ?>
			
			html += '</div>';
			<?php } ?>
			

			html += '</div>'; 
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			
			
			
			
			
			
			$(element).html(html);
		});	
		 $(".owl-addimagecat").owlCarousel({ navigation : true, pagination : false, singleItem : true });		
		$('.display').html('<a onclick="display(\'list\');" class="iconlist"></a> <span class="icongrid"></span>');
		
		$.totalStorage('display', 'grid');
	}
}

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