<?php

// with this you can move FileARBehavior and ImageARBehavior where you want, they ust need to be in the same directory
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'FileARBehavior.php';

/**
 * This behavior extends FileARBehavior to manage an image file.
 * This can create multiple images from the originale one by processing them with resize, crop, sharpen ... (see $IMG_FUNCS for the list)
 * see the extension image of Yii (original code from Kohana Team) to know more about the parameters.
 * As I have added some functions int the Image class, please see the availables functions in Image.php.
 *
 * For an example of how to use this class, see the example file.
 */
class ImageARBehavior extends FileARBehavior {

    /**
     * Set this to true on Unix to use imageMagick (the executable is find with `which convert`)
     * or, for both Windows and Unix set the directory path of the convert executable.
     * Set to null to use GD2 instead.
     *
     * This is the default (GD2), as I believe the most frequently used; but I recommend you
     * to use convert when it's possible (I made some tests and it seems faster now that I have modified the driver
     * in the Image extension provided).
     */
    public $useImageMagick = null;

    // store the config to pass to Image constructor
    protected $_img_config = null;

    public function __construct() {
        // Force the extension of images, and so their types.
        // Default to png because it's a good format with transparency. (otherwise there are
        // some problems using imagemagick and flip for example)
        $this->forceExt = 'png';

        // this use by default the Image library provided in extensions
        $this->processor = 'ext.image.Image';
    }

    // override the processor creation to handle the second argument of Image constructor
    protected function createProcessor($klass, $srcPath) {
        // $klass here is probably Image... if not, it must have a constructor
        // with second parameter which have the same behavior.
        if (!isset($this->_img_config)) {
            if (isset($this->useImageMagick)) {
                $params = array();
                if (is_string($this->useImageMagick))
                    $params['directory'] = $this->useImageMagick;
                $this->_img_config = array('driver' => 'ImageMagick',
                                           'params' => $params);
            } else {
                $this->_img_config = array('driver' => 'GD',
                                           'params' => array());
            }
        }
        return new $klass($srcPath, $this->_img_config);
    }
}