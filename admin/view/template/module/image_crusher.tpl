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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
       
      <!--START OF IMAGE CRUSHER-->
      <p><?php echo $image_crusher_intro; ?></p>
      <h1><?php echo $image_crusher_title; ?></h1>
      <p><?php echo $image_crusher_image_manager_text; ?></p>
   

      <p><?php echo $image_crusher_excluded_filetypes; ?></p>

      <h2 class="title"><?php echo $image_crusher_switch_title; ?></h2>
      <div><?php echo $image_crusher_switch_text; ?></div>
      <div>        
          <input type = "radio"
                 name = "image-optimise-on"
                 id = "image-optimise-on"
                 value = "on"
                 checked="checked" 
                 onclick = "deselectOff()"
                 />
          <label for = "image-optimise-on"><?php echo $image_crusher_switch_on; ?></label>
          
          <input type = "radio"
                 name = "image-optimise-off"
                 id = "image-optimise-off"
                 value = "off"
                 onclick = "deselectOn()" 
                 />
          <label for = "image-optimise-off"><?php echo $image_crusher_switch_off; ?></label>         
      </div>

      <div>
      <h2 class="title"><?php echo $image_crusher_compression_level_title; ?></h2>
      <div><?php echo $image_crusher_compression_level_text; ?></div>
        <input type="range" id="compression-level" class="compression-level" name="compression-level" min="1" max="10" value="<?php echo $image_crusher_default_compression_level; ?>" step="1" onchange="showValue(this.value, 'new-image-range')">
      </div>

      <div class="level">
      <?php echo $image_crusher_compression_level_label; ?> <span id="new-image-range"><?php echo $image_crusher_default_compression_level; ?></span>
      </div>


      <!--Existing Image Crusher Block-->
      <h1 class="title"><?php echo $image_crusher_existing_images_title; ?></h1>
      <p><?php echo $image_crusher_existing_images_text; ?></p>

      <div class="warning">
        <p><strong><?php echo $image_crusher_existing_images_warning_title; ?></strong></p>
        <p><?php echo $image_crusher_existing_images_warning_text; ?></p>
      </div>
     
      <!--Level slider for existing image crusher-->
      <div>
      <h2 class="title"><?php echo $image_crusher_compression_level_title; ?></h2>
      <div><?php echo $image_crusher_compression_level_text; ?></div>
        <input type="range" class="compression-level" id="existing-image-compression-level" name="existing-image-compression-level" min="1" max="10" value="<?php echo $image_crusher_default_compression_level; ?>" step="1" onchange="showValue(this.value, 'existing-image-range')">
      </div> 

      <div class="level">
      <?php echo $image_crusher_compression_level_label; ?> <span id="existing-image-range"><?php echo $image_crusher_default_compression_level; ?></span>
      </div>

      <!--Form-->
      <div class="existing-images-input-box">
        <p><?php echo $image_crusher_existing_images_image_folder_text; ?> </p>
        <input type="text" name="existing-images-folder" id="existing-images-folder" placeholder="<?php echo $image_crusher_existing_images_image_folder_placeholder_text; ?> " value="">
      </div>
      <div id="existing-images-submit"><?php echo $image_crusher_existing_images_submit_button; ?></div>

      <div id="image-crush-results"></div>

      <div id="existing-images-dialog-confirm" title="Crush Existing Images?">
        <p><?php echo $image_crusher_existing_images_popup_text_1; ?></p>
        <p><?php echo $image_crusher_existing_images_popup_text_2; ?></p>
      </div>
    
      

      <script type="text/javascript">
             
          //post the form values to the crusher script when a user presses submit.       
          $("#existing-images-submit").click(function() {

              //get the value of the form for the image folder
              var imageFolder = $("input[name=existing-images-folder]").val();

              if (imageFolder === '') {
                  $( "#image-crush-results" ).empty().append('<span class="error">Please enter a folder</span>');
                  return;
              }

              //show a popup to the user for one last check
              $("#existing-images-dialog-confirm").dialog({
                resizable: false,
                height:200,
                modal: true,
                buttons: {
                  "Crush My Images": function() {
                    $( this ).dialog( "close" );
                  
                        //get the compression level.
                        var existingImageSlider = $("#existing-image-compression-level").val();

                        //post the value to the image crusher script and then display results in #image-crush-results.
                        $.ajax({ url: 'index.php?route=module/image_crusher/imageCrushreceive&token=<?php echo $this->session->data["token"]; ?>', 
                            data: {
                              function: "compressImages", 
                              imageFolder: imageFolder, 
                              existingImageSlider: existingImageSlider
                            },
                            type: 'post',
                            beforeSend: function() {
                              $('#image-crush-results').html('<img src="../image/data/processing.gif" />');
                            },
                            success: function(output) {
                                var content = output;
                                $( "#image-crush-results" ).empty().append( content );
                            },
                            error: function(output) {
                              $( "#image-crush-results" ).empty().append('<span class="error">The specified image folder does not exist!</span>');
                            }
                        });
                  },
                  Cancel: function() {
                    $( this ).dialog( "close" );
                  }
                }
              });
          });

      </script>

      <!--END OF IMAGE CRUSHER-->

    </form>
  </div>
</div>

<?php echo $footer; ?>