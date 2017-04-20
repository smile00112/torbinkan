<?php
class ModelToolImage extends Model {
	/**
	*	
	*	@param filename string
	*	@param width 
	*	@param height
	*	@param type char [default, w, h]
	*				default = scale with white space, 
	*				w = fill according to width, 
	*				h = fill according to height
	*	
	*/

    protected function log( $message ){
        if( $this->config->get("soforp_watermark_debug") )
            file_put_contents(DIR_LOGS . $this->config->get("config_error_filename"), date("Y-m-d H:i:s - ") . "SOFORP Watermark: " . $message . "\r\n", FILE_APPEND );
    }

	protected function _getImageUrl($new_image) {
		$parts = explode('/', $new_image);
		$new_url = implode('/', array_map('rawurlencode', $parts));
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_url;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_url;
		}
	}

	public function _resize($filename, $width, $height, $type = "") {

		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		}

		$info = pathinfo($filename);
		$extension = $info['extension'];
		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type;

        $watermark = $this->config->get('soforp_watermark_status');
        if( $watermark ) {
            $watermark_image = $this->config->get('soforp_watermark_image');
            $watermark_size = $this->config->get('soforp_watermark_size');
            $watermark_min_height = $this->config->get('soforp_watermark_min_width');
            $watermark_min_width = $this->config->get('soforp_watermark_min_height');
            if (!$watermark_image || !file_exists(DIR_IMAGE . $watermark_image) ) {
                $this->log("watermark image not found: " . $watermark_image);
                $watermark = FALSE;
            } else if( $watermark_min_width > $width || $watermark_min_height > $height ) {
                $this->log("Requested image size is too small for watermark: $width x $height, watermark is used from $watermark_min_width x $watermark_min_height");
                $watermark = FALSE;
            } else if (is_array($this->config->get('soforp_watermark_exclude'))) {
                $find = dirname(DIR_IMAGE . $filename);

                foreach ($this->config->get('soforp_watermark_exclude') as $dir) {
                    if (strpos($find, $dir) === 0) {
                        $watermark = FALSE;
                        $this->log("image path is in excludes: $find");
                        break;
                    }
                }
            }
            if ($watermark) {
                $watermark_position = $this->config->get('soforp_watermark_position');
                $watermark_position_text = $this->config->get("soforp_watermark_offset_x") . '-' . $this->config->get("soforp_watermark_offset_y");
                $new_image .= '-w-' . (int)$this->config->get('soforp_watermark_size') . '-' . $watermark_position_text;
            }
        }

		$new_image .= '.' . $extension;

		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height || $watermark) {

				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);

                if ($watermark) {
                    $image->watermark(DIR_IMAGE . $watermark_image, $watermark_position, $watermark_size);
                }

				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		}

		return $this->_getImageUrl($new_image);
	}
            
	public function resize($filename, $width, $height, $type = "") {

        return $this->_resize($filename, $width, $height, $type );
            
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		
		$info = pathinfo($filename);
		
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . $type .'.' . $extension;
		
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		}

		return $this->getImageUrl($new_image);
		
	}

	protected function getImageUrl($new_image) {
		$parts = explode('/', $new_image);
		$new_url = implode('/', array_map('rawurlencode', $parts));
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_url;
		}
		else {
			return $this->config->get('config_url') . 'image/' . $new_url;
		}
	}
}
