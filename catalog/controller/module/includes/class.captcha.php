<?php  
class Captcha {
	
	protected $code;
	public $width = 150;
	public $height = 40;
	public $backColor = 0xFFFFFF;
	public $showLine = TRUE;
	public $line_color = array(56, 169, 237);
	public $text_color = array(56, 169, 237);
	public $offset = 4;
	public $padding = 2;
	
	function __construct($alpha, $langth) { 
	
		$code = '';
		
		for ($i = 0; $i < $langth; $i++){
			$pos = rand(0, strlen($alpha)-1);
			$code .= $alpha[$pos];
		}
	
		$this->code = $code; 
		
	}

	function getCode(){
		return $this->code;
	}
	
	function showImage() {
		
		$image = imagecreatetruecolor($this->width,$this->height);

		$backColor = imagecolorallocate($image, (int)($this->backColor % 0x1000000 / 0x10000), (int)($this->backColor % 0x10000 / 0x100), $this->backColor % 0x100);
		imagefilledrectangle($image, 0, 0, $this->width, $this->height, $backColor);
		imagecolordeallocate($image, $backColor);
		
		imagecolortransparent($image,$backColor);
		
		$text_color = imagecolorallocate($image, $this->text_color[0], $this->text_color[1], $this->text_color[2]);
		
		$fontFile = dirname(__FILE__) . '/fonts/Duality.ttf';
		
		$length = strlen($this->code);
		
		$box = imagettfbbox(30, 0, $fontFile, $this->code);
		
		$w = $box[4] - $box[0] + $this->offset * ($length - 1);
		$h = $box[1] - $box[5];
		
		$scale = min(($this->width - $this->padding * 2) / $w, ($this->height - $this->padding * 2) / $h);
		
		$x = 10;
		$y = round($this->height * 27 / 33); 
		
		for ($i = 0; $i < $length; ++$i) {
			
			$fontSize = (int)(rand(26, 35) * $scale * 0.8);
			$angle = rand(-20, 30);
			$letter = $this->code[$i];
			$o = rand(-2, 2);
			
			$box = imagettftext($image,$fontSize,$angle,$x,$y,$text_color,$fontFile,$letter);
			$x = $box[2] + $o;
			
		}
		
		if ($this->showLine) {
			
			$line_color = imagecolorallocate($image, $this->line_color[0], $this->line_color[1], $this->line_color[2]);
			
			for ($i=0; $i < rand(4, 8); $i++) {
				imageline($image, 1, rand(1, 50), rand(150, 180), rand(1, 50), $line_color);
			}
			
		}
        
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Transfer-Encoding: binary');
		header("Content-type: image/png");
		
		imagepng($image);
		imagedestroy($image);
	}
}
?>