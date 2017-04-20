<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (isset($success) && $success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if (isset($updated) && $updated) { ?>
  <div class="success"><?php echo $updated; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
  <?php
 
  ?>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
        <table class="form">
          <tr>
            <td>
              <?php echo $entry_update_quantity; ?>:
            </td>
            <td width="130">
              <input type="checkbox" name="related_options[update_quantity]" value="1" <?php  if(isset($modules['update_quantity']) && $modules['update_quantity']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_update_quantity_help; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_update_options; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[update_options]" value="1" <?php  if(isset($modules['update_options']) && $modules['update_options']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_update_options_help; ?></span>
            </td>
          </tr>
          
          <tr>
            <td>
              <?php echo $entry_spec_model; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[spec_model]" value="1" <?php  if(isset($modules['spec_model']) && $modules['spec_model']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_spec_model_help; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_spec_price; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[spec_price]" value="1" <?php  if(isset($modules['spec_price']) && $modules['spec_price']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_spec_price_help; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_spec_price_discount; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[spec_price_discount]" value="1" <?php  if(isset($modules['spec_price_discount']) && $modules['spec_price_discount']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_spec_price_discount_help; ?></span>
            </td>
          </tr>
          
          <tr>
            <td>
              <?php echo $entry_allow_zero_select; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[allow_zero_select]" value="1" <?php  if(isset($modules['allow_zero_select']) && $modules['allow_zero_select']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_allow_zero_select_help; ?></span>
            </td>
          </tr>  
          <tr>
            <td>
              <?php echo $entry_stock_control; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[stock_control]" value="1" <?php  if(isset($modules['stock_control']) && $modules['stock_control']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_stock_control_help; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_show_clear_options; ?>:
            </td>
            <td>
              <select name="related_options[show_clear_options]">
                <option value="0" <?php  if(!isset($modules['show_clear_options']) || (int)$modules['show_clear_options']==0) echo "selected";?>><?php echo $option_show_clear_options_not; ?></option>
                <option value="1" <?php  if(isset($modules['show_clear_options']) && (int)$modules['show_clear_options']==1) echo "selected";?>><?php echo $option_show_clear_options_top; ?></option>
                <option value="2" <?php  if(isset($modules['show_clear_options']) && (int)$modules['show_clear_options']==2) echo "selected";?>><?php echo $option_show_clear_options_bot; ?></option>
              </select>
            </td>
            <td>
              <span class="help"><?php echo $entry_show_clear_options_help; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_hide_inaccessible; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[hide_inaccessible]" value="1" <?php  if(isset($modules['hide_inaccessible']) && $modules['hide_inaccessible']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_hide_inaccessible_help; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_select_first; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[select_first]" value="1" <?php  if(isset($modules['select_first']) && $modules['select_first']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_select_first_help; ?></span>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_step_by_step; ?>:
            </td>
            <td>
              <input type="checkbox" name="related_options[step_by_step]" value="1" <?php  if(isset($modules['step_by_step']) && $modules['step_by_step']) echo "checked"; ?> >
            </td>
            <td>
              <span class="help"><?php echo $entry_step_by_step_help; ?></span>
            </td>
          </tr>
          
          <tr>
            <td style="vertical-align: top">
              <?php echo $entry_ro_use_variants; ?><br>
              
            </td>
            <td colspan="2">
              <input type="checkbox" id="use_variants" name="related_options[use_variants]" value="1"  onclick="show_options_variants();" <?php  if(isset($modules['use_variants']) && $modules['use_variants']) echo "checked"; ?> >
              <br><br>
              
              <div id="options_variants" style="display: none">
                
                <table id="variants_list" class="list" style="width: 700px">
                  <thead>
                    <tr>
                      <td class="left" style="width: 250px"><?php echo $entry_ro_variant_name; ?></td>
                      <td class="left" ><?php echo $entry_ro_options; ?></td>
                      <td style="width: 170px"></td>
                    </tr>
                  </thead>

                </table>
                
                <a onclick="add_new_variant();" class="button"><?php echo $entry_ro_add_variant; ?></a>
                <br><br>
                
              </div>
              
              
            </td>
          </tr>
          
        </table>
      
        <br>
        <br>
        <br>
        <span class="help">
          
          <?php echo $entry_ro_version.": ".$this->data['modules']['related_options_version']; ?>
          <br><br>
          <?php echo $text_ro_support; ?>
        </span>
        
        <input type="hidden" name="related_options[related_options_version]" value="<?php echo $modules['related_options_version'].""; ?>">
      
      <?php
      
      
        
      ?>  
        
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  
  var variant_cnt = 0;
  var options_cnt = 0;
  var all_options = [];
  
  <?php
   foreach($options as $option_id => $option_name) {
    echo "all_options['".$option_id."']=\"".$option_name."\";";
   }
  ?>
  
  function add_option(elem_num, option_id) {
    
    var str_add = "";
    
    str_add += "<div id='variant_option"+options_cnt+"'>";
    str_add += "<select name='variants["+elem_num+"][options]["+options_cnt+"]' style=\"width: 180px;\">";
    
    for (var i = 0, length = all_options.length; i < length; i++) {
      if (i in all_options) {
        str_add += '<option value="'+i+'" '+((option_id==i)?("selected"):(""))+' >'+all_options[i]+'</option>';  
      }
    }
    
    str_add += "";
    str_add += "";
    str_add += "";
    str_add += "</select>";
    str_add += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"$('#variant_option"+options_cnt+"').remove();\"  title='<?php echo $entry_ro_delete_option; ?>'>XX</a></div>";
    
    options_cnt++;
    
    $('#variant_options'+elem_num).append("<tr><td style='border: 0px;'>"+str_add+"</td></tr>");
  }
  
  function add_new_variant() {
    add_variant('', '', '');
  }
  
  function add_variant(variant_id, variant_name, variant_options) {
    
    var str_add = "";
    str_add += "<tr id='variant"+variant_cnt+"'>";
    str_add += "<td style='vertical-align: top; padding: 10px;'>";
    str_add += '<input name="variants['+variant_cnt+'][name]" size=\'40\' value="'+variant_name+'">';
    str_add += "<input type='hidden' name='variants["+variant_cnt+"][id]' value='"+variant_id+"'>";
    str_add += "<div style=' text-align: right; padding: 7px;'><a onclick=\"add_option("+variant_cnt+",0);\" ><?php echo $entry_ro_add_option; ?></a></div>";
    str_add += "</td>";
    str_add += "<td style='vertical-align: top; padding: 10px;'>";
    str_add += "<table id='variant_options"+variant_cnt+"' style='border-spacing: 0px;'>";
    str_add += "</table>";
    str_add += "";
    str_add += "</td>";
    str_add += "<td class='right'><a onclick=\"$('#variant"+variant_cnt+"').remove();\" class='button'><?php echo $entry_ro_delete_variant; ?></a></td>";
    str_add += "";
    str_add += "</tr>";
    
    $('#variants_list').append(str_add);
    
    if (variant_options) {
      for (var i = 0, length = variant_options.length; i < length; i++) {
        if (i in variant_options) {
          add_option(variant_cnt, variant_options[i]);  
        }
      }
    }
    
    
    variant_cnt++;
    
  }
  
  
  function show_options_variants() {
    $('#options_variants')[0].style.display=$('#use_variants')[0].checked?'':'none'
  }
  
  $(document).ready(  function () {
    show_options_variants();
    
    <?php
      
      foreach ($variants_options as $vo_id => $vo_data) {
        
        $str_arr = "";
        foreach ($vo_data['options'] as $option_id => $option_name) {
          $str_arr .= ",".$option_id;
        }
        if ($str_arr != "") $str_arr = substr($str_arr, 1);
        echo "add_variant(".$vo_id.", '".$vo_data['name']."', [".$str_arr."]);";
        
      }
      
    ?>
    
  } );
  //                  $(document).ready( function () { show_options_variants(); } ');
  
</script>


<?php echo $footer; ?>