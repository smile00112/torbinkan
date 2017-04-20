<?php

/*
*
* This is the text for the admin panel. These are passed to the controller and called in the view.
*
*/

$_['image_crusher_example'] = 'Example Extra Text';



// Heading Goes here:
$_['heading_title']    = 'Image Crusher';


// Text
$_['text_module']      = 'Modules';
$_['text_success']     = 'Success: You have modified the Image Crusher Module!';


//Image Crusher
$_['image_crusher_intro']    = 'This is the module to configure the jpeg image crusher.';
$_['image_crusher_title']    = 'New Images via Image Manager';
$_['image_crusher_image_manager_text']    = 'This section configures the settings for all future images uploaded via the image manager. Once turned on, all jpeg images uploaded through the image manager interface will be automatically optmised.';
$_['image_crusher_excluded_filetypes']    = 'Any other image type will be ignored.';
$_['image_crusher_switch_title']    = 'Switch On/Off';
$_['image_crusher_switch_text']    = 'Turn the jpeg compressor on or off by selecting one of radio buttons below.';
$_['image_crusher_switch_on']    = 'On';
$_['image_crusher_switch_off']    = 'Off';
$_['image_crusher_compression_level_title']    = 'Compression Level';
$_['image_crusher_compression_level_text']    = 'Select the level of compression you want to apply to your images. The higher the compression level, the smaller the file size.';
$_['image_crusher_compression_level_label']    = 'Level ';
$_['image_crusher_popup_success']    = 'Success: Image Crusher uploaded and reduced your file size by ';
$_['image_crusher_default_compression_level']    = '5';
$_['image_crusher_existing_images_title']    = 'Crush Existing Images';
$_['image_crusher_existing_images_text']    = 'This feature allows you to compress images that you have previously uploaded to the image folder on your site. You simply enter the path of a folder and click Crush. The Crusher will then scan this folder and any subfolders and compress all of the jpeg images that it contains.';
$_['image_crusher_existing_images_warning_title']    = 'BE CAREFUL';
$_['image_crusher_existing_images_warning_text']    = 'This is a very powerful feature and cannot be undone. Once the images have been compressed the old version cannot be retrieved. You should try this on one folder to see if you are happy with the results before Crushing all of the images on your site.';
$_['image_crusher_existing_images_image_folder_text']    = 'Enter the path of a folder inside the image directory e.g. data/demo';
$_['image_crusher_existing_images_image_folder_placeholder_text']    = 'data/demo';
$_['image_crusher_existing_images_submit_button']    = 'Crush Images';
$_['image_crusher_existing_images_image_folder_submit_button']    = 'Crush Images';
$_['image_crusher_existing_images_popup_text_1']    = 'Once this process is started, all the images in the specified folder will be crushed. This action cannot be paused or reversed.';
$_['image_crusher_existing_images_popup_text_2']    = 'Are you sure?';


// Error
$_['error_permission'] = 'Warning: You do not have permission to modify module My Module!';
?>