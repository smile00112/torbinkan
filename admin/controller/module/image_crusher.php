<?php

class ControllerModuleImageCrusher extends Controller {
	
	private $error = array(); 
	
	public function index() {   
		//Load the language file for this module
		$this->load->language('module/image_crusher');

		//Set the title from the language file $_['heading_title'] string
		$this->document->setTitle($this->language->get('heading_title'));
		
		//Load the settings model. You can also add any other models you want to load here.
		$this->load->model('setting/setting');
		
		//Save the settings if the user has submitted the admin form (ie if someone has pressed save).
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('image_crusher', $this->request->post);	
				
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		//This is how the language gets pulled through from the language file.
		//
		// If you want to use any extra language items - ie extra text on your admin page for any reason,
		// then just add an extra line to the $text_strings array with the name you want to call the extra text,
		// then add the same named item to the $_[] array in the language file.
		//
		// 'image_crusher_example' is added here as an example of how to add - see admin/language/english/module/my_module.php for the
		// other required part.
		
		$text_strings = array(
				'heading_title',
				'text_enabled',
				'text_disabled',
				'button_save',
				'button_cancel',
				'image_crusher_title',
				'image_crusher_intro',
				'image_crusher_image_manager_text',
				'image_crusher_excluded_filetypes',
				'image_crusher_switch_title',
				'image_crusher_switch_text',
				'image_crusher_switch_on',
				'image_crusher_switch_off',
				'image_crusher_compression_level_title',
				'image_crusher_compression_level_text',
				'image_crusher_compression_level_label',
				'image_crusher_default_compression_level',
				'image_crusher_existing_images_title',
				'image_crusher_existing_images_text',
				'image_crusher_existing_images_warning_title',
				'image_crusher_existing_images_warning_text',
				'image_crusher_existing_images_image_folder_text',
				'image_crusher_existing_images_image_folder_placeholder_text',
				'image_crusher_existing_images_submit_button',
				'image_crusher_existing_images_popup_text_1',
				'image_crusher_existing_images_popup_text_2'
		);
		
		foreach ($text_strings as $text) {
			$this->data[$text] = $this->language->get($text);
		}

		
		//set the default compression level when the user first installs the module.
		//This is pulled in the from the image_crushers.php language file.
		if ($this->config->get('compression-level') != '' && $this->config->get('compression-level') != null) {
		 	$this->data['image_crusher_default_compression_level'] = $this->config->get('compression-level');
		} 
		
		//END LANGUAGE
		
		//The following code pulls in the required data from either config files or user
		//submitted data (when the user presses save in admin). Add any extra config data
		// you want to store.
		//
		// NOTE: These must have the same names as the form data in your my_module.tpl file
		//
		$config_data = array(
				'image_crusher_example' //this becomes available in our view by the foreach loop just below.
		);
		
		foreach ($config_data as $conf) {
			if (isset($this->request->post[$conf])) {
				$this->data[$conf] = $this->request->post[$conf];
			} else {
				$this->data[$conf] = $this->config->get($conf);
			}
		}
	
		//This creates an error message. The error['warning'] variable is set by the call to function validate() in this controller (below)
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		//SET UP BREADCRUMB TRAIL. YOU WILL NOT NEED TO MODIFY THIS UNLESS YOU CHANGE YOUR MODULE NAME.
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/image_crusher', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/image_crusher', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

	
		//This code handles the situation where you have multiple instances of this module, for different layouts.
		$this->data['modules'] = array();
		
		if (isset($this->request->post['image_crusher_module'])) {
			$this->data['modules'] = $this->request->post['image_crusher_module'];
		} elseif ($this->config->get('image_crusher_module')) { 
			$this->data['modules'] = $this->config->get('image_crusher_module');
		}		

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		//Choose which template file will be used to display this request.
		$this->template = 'module/image_crusher.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		//Load the css
		$this->document->addStyle('view/stylesheet/imagecrusher.css');

		//Load the js
		$this->document->addScript('view/javascript/jquery/imagecrusher/imagecrusher.js');
		
		//Send the output.
		$this->response->setOutput($this->render());
	}
	
	/*
	 * 
	 * This function is called to ensure that the settings chosen by the admin user are allowed/valid.
	 * You can add checks in here of your own.
	 * 
	 */
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/image_crusher')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}

	/*
	 * This function is called from the image_crusher.tpl. The values from the module form in the admin are posted to the function
	 *
	 */
	public function imageCrushreceive() {
		//If compressImages was posted to the function we call the iterate function and pass in the folder to iterate over
		if(isset($this->request->post['function']) && !empty($this->request->post['function']) && $this->request->post['function'] === 'compressImages') {		
			$this->iterateOverImages($this->request->post['imageFolder']);
		}
	}

	/*
	* This function iterates recursively over the image folder passed in and calls compressImage for every valid jpeg
	* @param - $imageFolder. The image folder passed in from the form in the module admin page.
	*/
	public function iterateOverImages($imageFolder) {
		
		$imageFolder = DIR_IMAGE . $imageFolder;
	
		//Set the path to the folder to iteratre over
		$path = realpath($imageFolder);

		//Check if the image folder passed in exists.
		if (!file_exists($path)) {
			die("<span class='red'><strong>" . $imageFolder . "</strong> is not a valid directory name. Please check it exists!</span>");
		}

		$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		
		//loop over the images and call the function to compress them if they are the right size.
		foreach($objects as $imageName) {

			//check that we have a file
			if (is_file($imageName)) {
			   
			    //skip images greater than 20MB
			    if (filesize($imageName) > 20000000) {
			    	echo "$imageName is too large.<br>";
			    	continue;
			    }

			    //call compressImage function and compress the file otherwise output an error
			    if ($this->compressImage($imageName, realpath($imageName), $this->fetchCompressionLevel($this->request->post['existingImageSlider']))) {
			    } else {
			        echo "$imageName cannot be compressed.<br>";
			    }

			   
			}
		}
	}

	/**
	 * Compress image without losing quality.
	 * @param $originalImage - string - source file path.
	 * @param $newImageName - string -newImageNameination file path.
	 * @param $quality - string -The image quality setting from the admin. 
	 * @return boolean
	 * @see http://www.php.net/manual/en/ref.image.php - GD manual pages.
	 */
	public function compressImage($originalImage, $newImageName, $quality) {

	    $info = getimagesize($originalImage);
	 
	    //filter out everything except jpegs
	    switch ($info['mime']) {
	        case 'image/jpeg': 
	        $image = imagecreatefromjpeg($originalImage); 
	        imagejpeg($image, $newImageName, $quality);
	        break;  
	        //case 'image/gif' : $image = imagecreatefromgif ($originalImage); break;
	        case 'image/png' : 
	        $image = $this->compressPngs($originalImage, $newImageName, $quality); 
	        break;

	        default: $image = null;
	    }

	    //if we have a jpeg then create a new image with the desired image quality and echo out feedback to the user.
	    if (is_null($image)) {
	        return false;
	    } else {      
	        echo $newImageName . " was successfully compressed!" . "<span class=\"checkmark\"><div class=\"stem\"></div><div class=\"kick\"></div></span>" . "<br/>";
	        return true;
	    }   
	}

	/*
	* Convert the quality to a value between 46 and 96 to pass to the imagejpeg function
	* @param $qualitySetting - string. The quality passed through from the form in the admin page
	* @return - boolean
	*
	*/
	public function fetchCompressionLevel($qualitySetting) {

		$compressionLevel = intval($qualitySetting);

	    if ($compressionLevel == 1) {
	            $quality = 96;
	    } elseif ($compressionLevel == 2) {
	            $quality = 94;
	    } elseif ($compressionLevel == 3) {
	            $quality = 88;
	    } elseif ($compressionLevel == 4) {
	            $quality = 82;
	    } elseif ($compressionLevel == 5) {
	            $quality = 76;
	    } elseif ($compressionLevel == 6) {
	            $quality = 70;
	    } elseif ($compressionLevel == 7) {
	            $quality = 64;
	    } elseif ($compressionLevel == 8) {
	            $quality = 58;
	    } elseif ($compressionLevel == 9) { 	
	            $quality = 52; 
	    } elseif ($compressionLevel == 10) {
	            $quality = 46; 
	    } else {
	    	//default to 70% if something weird is passed in
	        $quality = 70; 
	    } 

	    return $quality;                     
	}

	/*
	* Compress pngs with full color. PNG-24 images are not compressed but retain alpha transparenct
    * @param $originalImage - string - source file path.
    * @param $newImageName - string -newImageNameination file path.
    * @param $quality - string -The image quality setting from the admin. 
    * @return boolean
	*
	*/
	public function compressPngs($originalImage, $newImageName, $quality) {
	
		// Size
		$imageSize = getimagesize($originalImage);

		//Dimensions
		$imageWidth = $imageSize[0];
		$imageHeight = $imageSize[1];

		// Source - get a handle to our image. This will be false if we pass anything other than png
		$sourceImage = imagecreatefrompng($originalImage);

		// Destination - create a background with the width and height of our input image
		$destination = imagecreatetruecolor($imageWidth, $imageHeight);

		// if this has no alpha transparency defined as an index
		// it could be a palette image??
		$palette = (imagecolortransparent($sourceImage)<0);

		//Hardcorde the quality to be 9. This is for pngs with alpha or tranparency as the file get bigger if the quality is anything else.
		//Images that dont have transparency use the quality slider from admin and fetchCompressionLevelForPng works out the quality.
		$pngQuality = 9;
		
		/**ALPHA IMAGES**/
		// If this has transparency, or is defined
		if (!$palette || (ord(file_get_contents ($originalImage, false, null, 25, 1)) & 4)) {
			//print ("Is Alpha");
			// Has indexed transparent color
			if(($transparentColour = imagecolorstotal($sourceImage)) && $transparentColour<=256)
				imagetruecolortopalette($destination, false, $transparentColour);
				imagealphablending($destination, false);
				$alpha = imagecolorallocatealpha($destination, 0, 0, 0, 127);
				imagefill($destination, 0, 0, $alpha);
				imagesavealpha($destination, true);
		}

		// Resample Image
		//print ("Resampling Image");
		imagecopyresampled($destination, $sourceImage, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageSize[0], $imageSize[1]);

		// Did the original PNG supported Alpha?
		if ((ord(file_get_contents ($originalImage, false, null, 25, 1)) & 4)) {
			//print ("Testing is there is Alpha transparency");

			// we dont have to check every pixel.
			// We take a sample of 2500 pixels (for images between 50X50 up to 500X500), then 1/100 pixels thereafter.
			$dx = min(max(floor($imageWidth/50), 1), 10);
			$dy = min(max(floor($imageHeight/50), 1), 10);

			$palette = true;
			for($x = 0; $x < $imageWidth; $x = $x + $dx) {
				for($y = 0; $y < $imageHeight; $y = $y + $dy) {
					$col = imagecolorsforindex($destination, imagecolorat($destination,$x,$y));
					// How transparent until it's actually visible
					// I reackon atleast 10% of 127 before its noticeable, e.g. ~13
					if ($col['alpha'] > 13) {
						$palette = false;
						break 2;
					}
				}
			}
			//var_dump(!$palette);
		}

		/***FULL COLOR IMAGES**/
		if ($palette) {
			//print "Converting to indexed colors";
			imagetruecolortopalette($destination, false, 256);
			$pngQuality = $this->fetchCompressionLevelForPng($quality);
		}

		// Save file, Add filters... although sometimes better without.
		return imagepng($destination, $newImageName, $pngQuality, PNG_ALL_FILTERS);
	}


	/*
	* Convert the quality to an int between 2 and 8 for PNG's with full color.
	* @param $qualitySetting - string. The quality passed through from the form in the admin page
	* @return - boolean
	*
	*/
	public function fetchCompressionLevelForPng($qualitySetting) {

		$compressionLevel = intval($qualitySetting);

	    if ($compressionLevel == 1) {
	            $quality = 8;
	    } elseif ($compressionLevel == 2) {
	            $quality = 8;
	    } elseif ($compressionLevel == 3) {
	            $quality = 7;
	    } elseif ($compressionLevel == 4) {
	            $quality = 7;
	    } elseif ($compressionLevel == 5) {
	            $quality = 6;
	    } elseif ($compressionLevel == 6) {
	            $quality = 5;
	    } elseif ($compressionLevel == 7) {
	            $quality = 4;
	    } elseif ($compressionLevel == 8) {
	            $quality = 4;
	    } elseif ($compressionLevel == 9) { 	
	            $quality = 3; 
	    } elseif ($compressionLevel == 10) {
	            $quality = 2; 
	    } else {
	    	//default to 3 if something weird is passed in
	        $quality = 3; 
	    } 

	    return $quality;                     
	}
}
?>