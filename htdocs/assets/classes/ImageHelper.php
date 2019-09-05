<?php

class ImageHelper
{
    const ERROR_CANNOT_CREATE_IMAGE_CANVAS = 513001;
    
    const ERROR_IMAGE_FILE_DOES_NOT_EXIST = 513002;
    
    const ERROR_CANNOT_GET_IMAGE_SIZE = 513003;
    
    const ERROR_UNSUPPORTED_IMAGE_TYPE = 513004;
    
    const ERROR_FAILED_TO_CREATE_NEW_IMAGE = 513005;

    const ERROR_SAVE_NO_IMAGE_CREATED = 513006;
    
    const ERROR_CANNOT_WRITE_NEW_IMAGE_FILE = 513007;
    
    const ERROR_CREATED_AN_EMPTY_FILE = 513008;
    
    const ERROR_QUALITY_VALUE_BELOW_ZERO = 513009;
    
    const ERROR_QUALITY_ABOVE_ONE_HUNDRED = 513010;
    
    const ERROR_CANNOT_CREATE_IMAGE_OBJECT = 513011;
    
    const ERROR_CANNOT_COPY_RESAMPLED_IMAGE_DATA = 513012; 
    
    const ERROR_HEADERS_ALREADY_SENT = 513013;
    
    const ERROR_CANNOT_READ_SVG_IMAGE = 513014;
    
    const ERROR_SVG_SOURCE_VIEWBOX_MISSING = 513015;
    
    const ERROR_SVG_VIEWBOX_INVALID = 513016;
    
    protected $file;

    protected $info;

    protected $type;

    protected $newImage;

    protected $sourceImage;

    protected $width;

    protected $height;

    protected $newWidth;

    protected $newHeight;

    protected $quality = 85;

    public function __construct($sourceFile)
    {
        $this->file = $sourceFile;
        if (!file_exists($this->file)) {
            throw new ImageHelper_Exception(
                'Image file does not exist',
                sprintf(
                    'Could not find the image file on disk at location [%s]',
                    $this->file
                ),
                self::ERROR_IMAGE_FILE_DOES_NOT_EXIST
            );
        }

        $this->type = self::getFileImageType($this->file);
        if (is_null($this->type)) {
            throw new ImageHelper_Exception(
                'Error opening image',
                'Not a valid supported image type for image ' . $this->file,
                self::ERROR_UNSUPPORTED_IMAGE_TYPE
            );
        }
        
        $this->info = $this->getImageSize($this->file);

        $this->width = $this->info[0];
        $this->height = $this->info[1];

        if($this->isVector()) {
            return;
        }
        
        $method = 'imagecreatefrom' . $this->type;
        $this->sourceImage = $method($this->file);
        if (!$this->sourceImage) {
            throw new ImageHelper_Exception(
                'Error creating new image',
                $method . ' failed',
                self::ERROR_FAILED_TO_CREATE_NEW_IMAGE
            );
        }
    }
    
   /**
    * Whether the image is an SVG image.
    * @return boolean
    */
    public function isTypeSVG()
    {
        return $this->type == 'svg';
    }
    
    public function isTypePNG()
    {
        return $this->type == 'png';
    }
    
   /**
    * Whether the image is a vector image.
    * @return boolean
    */
    public function isVector()
    {
        return $this->isTypeSVG();
    }
    
   /**
    * Calculates the size of the image by the specified width,
    * and returns an indexed array with the width and height size.
    * 
    * @param integer $height
    * @return integer[]
    */
    public function getSizeByWidth($width)
    {
        $height = ceil(($width * $this->height) / $this->width);
        
        return array(
            $width,
            $height
        );
    }
    
   /**
    * Calculates the size of the image by the specified height,
    * and returns an indexed array with the width and height size.
    * 
    * @param integer $height
    * @return integer[]
    */
    public function getSizeByHeight($height)
    {
        $width = ceil(($height * $this->width) / $this->height);
        
        return array(
            $width, 
            $height
        );
    }

    public function resampleByWidth($width)
    {
        $size = $this->getSizeByWidth($width);
        return $this->createImage($size[0], $size[1]);
    }

   /**
    * Resamples the image by height, and creates a new image file on disk.
    * 
    * @param int $height
    * @return boolean
    */
    public function resampleByHeight($height)
    {
        $size = $this->getSizeByHeight($height);
        return $this->createImage($size[0], $size[1]);
    }

    /**
     * Creates the target image without resampling it.
     * @return boolean
     */
    public function noResampling()
    {
        return $this->resampleByWidth($this->width);
    }

    public function resample($width = null, $height = null)
    {
        if($this->isVector()) {
            return true;
        }
        
        if ($width == null && $height == null) {
            return $this->noResampling();
        }

        if (empty($width)) {
            return $this->resampleByHeight($height);
        }

        if (empty($height)) {
            return $this->resampleByWidth($width);
        }

        return $this->resampleAndCrop($width, $height);
    }

    public function resampleAndCrop($width, $height)
    {
        if($this->isVector()) {
            return true;
        }
        
        if ($this->width <= $this->height) {
            if (!$this->resampleByWidth($width)) {
                return false;
            }
        } else {
            if (!$this->resampleByHeight($height)) {
                return false;
            }
        }

        $newCanvas = imagecreatetruecolor($width, $height);
        if (!$newCanvas) {
            throw new ImageHelper_Exception(
                'Error creating new image',
                'Cannot create new image canvas',
                self::ERROR_CANNOT_CREATE_IMAGE_CANVAS
            );
        }

        $this->addAlphaSupport($newCanvas, $width, $height);

        // and now we can add the crop
        if (!imagecopy(
            $newCanvas,
            $this->newImage,
            0, // destination X
            0, // destination Y
            0, // source X
            0, // source Y
            $width,
            $height
        )
        ) {
            throw new ImageHelper_Exception(
                'Error creating new image',
                'Cannot create crop of the image'
            );
        }

        $this->newImage = $newCanvas;

        return true;
    }

    protected function addAlphaSupport($canvas, $width, $height)
    {
        if ($this->isVector() || $this->isTypePNG()) {
            return;
        }

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocate($canvas, 0, 0, 0);
        imagecolortransparent($canvas, $transparent);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
    }

    public function save($targetFile)
    {
        if($this->isVector()) {
            return true;
        }
        
        if (!isset($this->newImage)) {
            throw new ImageHelper_Exception(
                'Error creating new image',
                'Cannot save an image, no image was created. You have to call one of the resample methods to create a new image.',
                self::ERROR_SAVE_NO_IMAGE_CREATED
            );
        }

        if (file_exists($targetFile)) {
            unlink($targetFile);
        }

        $method = 'image' . $this->type;
        if (!$method($this->newImage, $targetFile, $this->resolveQuality())) {
            throw new ImageHelper_Exception(
                'Error creating new image',
                'Cannot write the new image to ' . $targetFile,
                self::ERROR_CANNOT_WRITE_NEW_IMAGE_FILE
            );
        }

        $this->optimize($targetFile);

        if (filesize($targetFile) < 1) {
            throw new ImageHelper_Exception(
                'Error creating new image',
                'Resampling completed sucessfully, but the generated file is 0 bytes big.',
                self::ERROR_CREATED_AN_EMPTY_FILE
            );
        }

        return true;
    }

    protected function optimize($targetFile)
    {
        if (!APP_OPTIMIZE_IMAGES) {
            return;
        }

        if ($this->isTypePNG()) {
            shell_exec(APP_OPTIPNG_BINARY . ' ' . escapeshellarg($targetFile));
        }
    }

    protected function resolveQuality()
    {
        switch ($this->type) {
            case 'png':
                return 0;

            case 'jpeg':
                return $this->quality;

            default:
                return 0;
        }
    }

    /**
     * Sets the quality for image types like jpg that use compression.
     * @param int $quality
     */
    public function setQuality($quality)
    {
        $quality = $quality * 1;
        if ($quality < 0) {
            throw new ImageHelper_Exception(
                'Invalid configuration',
                'Cannot set a quality less than 0.',
                self::ERROR_QUALITY_VALUE_BELOW_ZERO
            );
        }

        if ($quality > 100) {
            throw new ImageHelper_Exception(
                'Invalid configuration',
                'Cannot set a quality higher than 100.',
                self::ERROR_QUALITY_ABOVE_ONE_HUNDRED
            );
        }

        $this->quality = $quality * 1;
    }

    protected function setMemory()
    {
        $MB = 1048576; // number of bytes in 1M
        $K64 = 65536; // number of bytes in 64K
        $tweakFactor = 25; // magic adjustment value as safety threshold
        $memoryNeeded = ceil(($this->info[0] * $this->info[1] * $this->info['bits'] * $this->info['channels'] / 8 + $K64) * $tweakFactor);

        //ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
        //default memory limit is 8MB so we will stick with that.
        $memoryLimit = 8 * $MB;
        if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > $memoryLimit) {
            $newLimit = ($memoryLimit + (memory_get_usage() + $memoryNeeded)) / $MB;
            $newLimit = ceil($newLimit);
            ini_set('memory_limit', $newLimit . 'M');

            return true;
        }

        return false;
    }

    protected function createImage($newWidth, $newHeight)
    {
        if($this->isVector()) {
            return true;
        }
        
        $this->setMemory();

        $this->newImage = ImageCreateTrueColor($newWidth, $newHeight);
        if (!$this->newImage) {
            throw new ImageHelper_Exception(
                'Error creating new image',
                'Cannot create new true color image object using ImageCreateTrueColor',
                self::ERROR_CANNOT_CREATE_IMAGE_OBJECT
            );
        }

        $this->addAlphaSupport($this->newImage, $newWidth, $newHeight);

        if (!imagecopyresampled($this->newImage, $this->sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height)) {
            throw new ImageHelper_Exception(
                'Error creating new image',
                'Cannot copy resampled image data',
                self::ERROR_CANNOT_COPY_RESAMPLED_IMAGE_DATA
            );
        }

        $this->newWidth = $newWidth;
        $this->newHeight = $newHeight;

        return true;
    }

    /**
     * Sends the correct headers to show an image directly in
     * the browser and sends the image. Exits the script afterwards.
     *
     * @param string $file
     */
    function displayImageFile($file)
    {
        self::displayImage($file);
    }

    protected static $imageTypes = array(
        'png' => 'png',
        'jpg' => 'jpeg',
        'jpeg' => 'jpeg',
        'gif' => 'gif',
        'svg' => 'svg'
    
    );

    /**
     * Gets the image type for the specified file name.
     * Like {@link getImageType()}, except that it automatically
     * extracts the file extension from the file name.
     *
     * @param string $fileName
     * @return string|NULL
     * @see getImageType()
     */
    public static function getFileImageType($fileName)
    {
        return self::getImageType(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)));
    }

    /**
     * Gets the image type for the specified file extension,
     * or NULL if the extension is not among the supported
     * file types.
     *
     * @param string $extension
     * @return string|NULL
     */
    public static function getImageType($extension)
    {
        if (isset(self::$imageTypes[$extension])) {
            return self::$imageTypes[$extension];
        }

        return null;
    }

    /**
     * Displays an existing image file stream created with one of the
     * image functions like imacreatefromgif, imagecreateturecolor, etc.
     *
     * @param resource $resource
     * @param string $imageType
     */
    public static function displayImageStream($resource, $imageType)
    {
        header('Content-type:image/' . $imageType);

        $method = 'image' . $imageType;
        $method($resource);
        exit;
    }

    /**
     * Displays an image from an existing image file.
     * @param string $imageFile
     */
    public static function displayImage($imageFile)
    {
        $file = null;
        $line = null;
        if (headers_sent($file, $line)) {
            throw new ImageHelper_Exception(
                'Error displaying image',
                'Headers have already been sent: in file ' . $file . ':' . $line,
                self::ERROR_HEADERS_ALREADY_SENT
            );
        }

        if (!file_exists($imageFile)) {
            throw new ImageHelper_Exception(
                'Image file does not exist',
                sprintf(
                    'Cannot display image, the file does not exist on disk: [%s].',
                    $imageFile
                ),
                self::ERROR_IMAGE_FILE_DOES_NOT_EXIST
            );
        }

        $format = strtolower(pathinfo($imageFile, PATHINFO_EXTENSION));
        if($format == 'svg') {
            $format = 'svg+xml';
        }
        
        $contentType = 'image/' . $format;
        
        header('Content-Type: '.$contentType);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($imageFile)) . " GMT");
        header('Cache-Control: public');
        header('Content-Length: ' . filesize($imageFile));

        readfile($imageFile);
        exit;
    }
    
   /**
    * Trims the current loaded image.
    * 
    * @param array $color A color definition, as an associative array with red, green, and blue keys. If not specified, the color at pixel position 0,0 will be used.
    */
    public function trim($color=null)
    {
        return $this->trimImage($this->sourceImage, $color);
    }
    
   /**
    * Trims the specified image resource.
    * 
    * @param resource $img
    * @param array $color A color definition, as an associative array with red, green, and blue keys. If not specified, the color at pixel position 0,0 will be used.
    */
    protected function trimImage($img, $color=null)
    {
        if($this->isVector()) {
            return true;
        }
        
        if(empty($color)) {
            $color = imagecolorat($img, 0, 0);
        }
        
        // Get the image width and height.
        $imw = imagesx($img);
        $imh = imagesy($img);
         
        // Set the X variables.
        $xmin = $imw;
        $xmax = 0;
        $ymin = null;
         
        // Start scanning for the edges.
        for ($iy=0; $iy<$imh; $iy++){
            $first = true;
            for ($ix=0; $ix<$imw; $ix++){
                $ndx = imagecolorat($img, $ix, $iy);
                $colors = imagecolorsforindex($img, $ndx);
                if(!$this->colorsMatch($colors, $color)) {
                    if ($xmin > $ix){ $xmin = $ix; }
                    if ($xmax < $ix){ $xmax = $ix; }
                    if (!isset($ymin)){ $ymin = $iy; }
                    $ymax = $iy;
                    if ($first){ $ix = $xmax; $first = false; }
                }
            }
        }
         
        // The new width and height of the image. 
        $imw = 1+$xmax-$xmin; // Image width in pixels
        $imh = 1+$ymax-$ymin; // Image height in pixels
         
        // Make another image to place the trimmed version in.
        $im2 = imagecreatetruecolor($imw, $imh);
         
        // Make the background of the new image the same as the background of the old one.
        $bg2 = imagecolorallocate($im2, $color['red'], $color['green'], $color['blue']);
        imagefill($im2, 0, 0, $bg2);
         
        // Copy it over to the new image.
        imagecopy($im2, $img, 0, 0, $xmin, $ymin, $imw, $imh);
         
        // To finish up, we replace the old image which is referenced.
        imagedestroy($img);

        $this->newImage = $im2;
        
        return true;
    }

	protected function colorsMatch($a, $b)
	{
		$parts = array('red', 'green', 'blue');
		foreach($parts as $part) {
			if($a[$part] != $b[$part]) {
				return false;
			}
		} 
		
		return true;
	}
	
	public static function getImageSize($imagePath)
	{
	    $type = self::getFileImageType($imagePath);
	    
	    if($type == 'svg') 
	    {
	        $info = self::getImageSize_svg($imagePath);
	    } 
	    else 
	    {
    	    $info = getimagesize($imagePath);
    	    
    	    if($info === false) {
    	        throw new ImageHelper_Exception(
    	            'Error opening image file',
    	            'Could not get image size for image ' . $this->file,
    	            self::ERROR_CANNOT_GET_IMAGE_SIZE
	            );
    	    }
	    }
	    
	    if (!isset($info['channels'])) {
	        $info['channels'] = 1;
	    }
	    
	    return $info;
	}
	
	protected static function getImageSize_svg($imagePath)
	{
	    $xml = XMLHelper::createSimplexml();
	    $xml->loadFile($imagePath);
	    
	    if($xml->hasErrors()) {
	        throw new ImageHelper_Exception(
	            'Error opening SVG image',
	            sprintf(
	                'The XML content of the image [%s] could not be parsed.',
	                $imagePath
                ),
	            self::ERROR_CANNOT_READ_SVG_IMAGE
            );
	    }
	    
	    $data = $xml->toArray();
	    $xml->dispose();
	    unset($xml);
	    
	    if(!isset($data['@attributes']) || !isset($data['@attributes']['viewBox'])) {
	        throw new ImageHelper_Exception(
	            'SVG Image is corrupted',
	            sprintf(
	                'The [viewBox] attribute is missing in the XML of the image at path [%s].',
	                $imagePath
                ),
	            self::ERROR_SVG_SOURCE_VIEWBOX_MISSING
            );
	    }
	    
	    $viewBox = str_replace(' ', ',', $data['@attributes']['viewBox']);
	    $tokens = explode(',', $viewBox);
	    if(count($tokens) != 4) {
	        throw new ImageHelper_Exception(
	            'SVG image has an invalid viewBox attribute',
	            sprintf(
	               'The [viewBox] attribute does not have an expected value: [%s] in path [%s].',
	                $viewBox,
	                $imagePath
                ),
	            self::ERROR_SVG_VIEWBOX_INVALID
            );
	    }
	    
	    $width = $tokens[2] * 10;
	    $height = $tokens[3] * 10;
	    
	    return array(
	        $width,
	        $height,
	        'bits' => 8
	    );
	}
}

class ImageHelper_Exception extends Exception
{
    protected $details;
    
    public function __construct($message, $details=null, $code=null, $previous=null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }
    
    public function getDetails()
    {
        return $this->details;
    }
}