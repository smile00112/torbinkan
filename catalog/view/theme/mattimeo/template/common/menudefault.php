 <?php if ($categories) { ?>
    <?php foreach ($categories as $category) { ?>
    <li <?php if ($category['children']) { ?> class="parent" <?php }?> ><a href="<?php echo $category['href']; ?>"><span><?php echo $category['name']; ?></span></a>
      <?php if ($category['children']) { ?>
      <div class="topmenu default">
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
						
