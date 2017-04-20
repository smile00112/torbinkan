 <?php if ($categories) { ?>
  <?php
           $this->language->load('module/mattimeotheme');
           $text_category = $this->language->get('text_category');  /*
  ?>    
    <li class="parent categories"><a><?php echo $text_category; ?></a>

    <div class="topmenu_theme">

     <?php foreach ($categories as $category) { ?>
       <ul class="mcolumn<?php if (($this->config->get('gen_m_column')!='') &&
	   ($this->config->get('gen_m_column')!='none')) { ?><?php echo $this->config->get('gen_m_column'); ?><?php }?>">
          <li <?php if ($category['children']) { ?> class="parent" <?php }?> ><a href="<?php echo $category['href']; ?>"><span><?php echo $category['name']; ?></span></a>
         <?php if ($category['children']) { ?>

         <span class="categ_image">
       <?php if (isset($category['img'])) { ?>
         <img src="<?php echo $category['img']; ?>" alt="<?php echo $category['name']; ?>"/>
          <?php } ?>
      </span>

      <div class="topmenu <?php if (isset($category['img'])) { ?>leftotstup <?php } ?>">

         <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
            <li
            <?php if (isset($category['children'][$i]['children'])) { ?>
		    <?php if ($category['children'][$i]['children']) { ?>
            class="parent"
		    <?php }?>
            <?php }?>
            >
             <a href="<?php echo $category['children'][$i]['href']; ?>"><span><?php echo $category['children'][$i]['name']; ?></span></a>

              <!--level3-->
               <?php if (isset( $category['children'][$i]['children'] )) { ?>
               <?php if( $category['children'][$i]['children'] ) { ?>
                  <div class="level3">
                  <ul>
                  <?php foreach( $category['children'][$i]['children'] as $menu3item ) { ?>
                  <li><a href="<?php echo $menu3item['href']; ?>"><?php echo $menu3item['name']; ?></a></li>
                  <?php } ?>
                  </ul>
                  </div>
            <?php } ?>
            <?php } ?>
              <!--end level3-->

          </li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
     </li>
    </ul>
    <?php } ?>

    </div>
   </li>
  
    <?php*/ } ?>
						
