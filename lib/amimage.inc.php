<?php
/**
 * This class models image that should be stored in the database.
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFile, AMPicture
 **/
class AMImage extends AMFile
{

  /**
   * Return an array with the images type that are supported by GD.
   **/
    public static function getValidImageTypes()
    {
        $validTypes = array();
        $info = gd_info();

        if(($info['GIF Read Support']==true) and ($info['GIF Create Support']==true)) $validTypes[] = IMAGETYPE_GIF;
        if($info['JPG Support']==true) $validTypes[] = IMAGETYPE_JPEG;
        if($info['PNG Support']==true) $validTypes[] = IMAGETYPE_PNG;

        return $validTypes;
    }

  /**
   * Return an array with the images extensions relative to the images types supported by GD.
   **/
    public static function getValidImageExtensions()
    {
        $types = AMImage::getValidImageTypes();
        $extensions = array();

        if(!empty($types)) {
            foreach($types as $imagetype) {
                switch($imagetype) {
                    case IMAGETYPE_GIF    : $extensions[] = 'gif'; break;
                    case IMAGETYPE_JPEG    : $extensions[] = 'jpg'; $extensions[] = 'jpeg'; break;
                    case IMAGETYPE_PNG    : $extensions[] = 'png'; break;
                }
            }
            
            return $extensions;

        } else {
            Throw new AMException("Cannot find any valid image type.");
        }
    }

  /**
   * Load an image from the request to the object.
   *
   * PHP handles files uploads with a predefined bidimensional array $_FILES. This
   * array contains various informations about the file being uploaded and a pointer
   * to the temporary file in the servers filesystem. This information should be handled
   * by the user to store the file in it's persistent location. This method, handles this
   * process, using the information provided by PHP and Apache to fill the AMArquivo
   * properties. This method also tests if the image is a valid image type.
   *
   * @param string $inputName The name of the <INPUT type=file> element in the form.
   **/
    public function loadImageFromRequest($inputName)
    {
        $name = $_FILES[$inputName]['name'];
        $parts = explode(".",$name);
        $extension = strtolower($parts[count($parts)-1]);

        $valid = self::getValidImageExtensions();
        if(!in_array($extension,$valid)) {
            Throw new AMEImage;
        }

        parent::loadFileFromRequest($inputName);
    }

  /**
   * Return the size in pixels of the image.
   *
   * Return an array in the format ('x'=>,'y'=>) with the size
   * in pixels of the image.
   **/
    public function getSize()
    {
        $d = $this->data;
        if(empty($d)) {
            Throw new AMException('Empty image data');
        }

        $im = imagecreatefromstring($this->data);
        return array("x"=>imagesx($im),
		 "y"=>imagesy($im));
    }

  /**
   * Resize the image.
   *
   * @param integer $x1 The new width of the image.
   * @param integer $y1 The new height of the image.
   **/
    public function resize($x1,$y1)
    {
    //calculate proportions
        $size = $this->getSize();
        $x0 = $size['x'];
        $y0 = $size['y'];

    //compute the new dimmensions of the image considering
    //the requested new dimensions and mantaining the
    //porportion betwen the sides;
    // x0,y0 = sizes of the original image
    // x1,y1 = requested new size of the image
    // xc,yc = computed new size of the image


        $dx = $x1/$x0;
        $dy = $y1/$y0;

        if($dx<$dy) {
            $xc = $x1;
            $yc = round($y0*$dx);
        }
        else {
            $xc = round($x0*$dy);
            $yc = $y1;
        }
        
    //resizing image

        $img_src = imagecreatefromstring($this->data);
        $img_dst = ImageCreateTrueColor($xc,$yc);

        $src_width = imagesx($img_src);
        $src_height = imagesy($img_src);

        imagecopyresampled($img_dst,$img_src,0,0,0,0,$xc,$yc,$src_width, $src_height);


    //captures the image
        ob_start();
        Imagepng($img_dst);
        $this->data = ob_get_contents();
        $this->mimetype = "image/png";
    //clear the buffer
        ob_end_clean();

    }



  /**
   * Overloding of the CMObj::save() method.
   *
   * This overloding of the CMObj::save() method performs two operations.
   * In first place it fill the metada about the image. In second, if
   * this image is not new, if deletes all the thumbnails of this images,
   * if they exists.
   **/
    public function save()
    {
        global $_CMAPP;

        $dm = $this->getSize();
        $s = round($this->size/1024);
        $this->metadata = "$dm[x]|$dm[y]|$s";

        $old_state = $this->state;
        parent::save();
    //we must delete all the existing thumbnails of this image if the save was sucessfull
    //see AMThumb for more information about thumbnail generation
        if($old_state==CMObj::STATE_DIRTY) {
            $p = AMThumb::getImagesPattern($this->codeFile);

            $_conf = $_CMAPP['config'];
            $path =  (string) $_conf->app[0]->paths[0]->thumbnails;
            $handle = opendir($path);

            while (($file = readdir($handle))!==false) {
                if(ereg($p,$file)) {
                    unlink($path.'/'.$file);
                }
            }
        }
    }


}


?>