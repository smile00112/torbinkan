 <?php if ($categories) { ?>
    <?php foreach ($categories as $category) { ?>
    <li <?php if ($category['children']) { ?> class="parent default2" <?php }?> >
    <a href="<?php echo $category['href']; ?>" <?php if ($category['active']) { ?>class="active"<?php }?>><span><?php echo $category['name']; ?></span></a>
      <?php if ($category['children']) { ?>
      <div>
      
       <?php if (isset($category['img2'])) { ?>
      <div class="categ_image">
         
         <a href="<?php echo $category['href']; ?>">
         <img src="<?php echo $category['img2']; ?>" alt="<?php echo $category['name']; ?>"/>
          <?php if ($category['description']) { ?> 
           <?php echo $category['description']; ?>
          <?php } ?>
          
          </a>
       </div>
       <?php } ?>  
      
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li <?php if( $category['children'][$i]['children'] ) { ?> class="parent" <?php } ?>>
          
          <a href="<?php echo $category['children'][$i]['href']; ?>">
          
           <?php if (!empty($category['children'][$i]['img']))  { ?>
           <div class="categ_image2">
           <img src="<?php echo $category['children'][$i]['img']; ?>" alt="<?php echo $category['children'][$i]['name']; ?>"/>
          </div>
           <?php } ?> 
          
          <span><?php echo $category['children'][$i]['name']; ?></span></a>
          
              <!--level3-->
               <?php if (isset( $category['children'][$i]['children'] )) { ?>
               <?php if( $category['children'][$i]['children'] ) { ?>
                  <div>
                  <ul>
                  <?php foreach( $category['children'][$i]['children'] as $menu3item ) { ?>
                  <li><a href="<?php echo $menu3item['href']; ?>"><?php echo $menu3item['name']; ?></a></li>
                  <?php } ?>
                  </ul>
                  </div>
            <?php } ?>
            <?php } ?>
          
          
          
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
    </li>
    <?php } ?>
    <?php } ?>
						
