<?


class AMImage extends AMArquivo {


  public static function getValidImageTypes() {
    $validTypes = array();
    $info = gd_info();
    
    if(($info['GIF Read Support']==true) and ($info['GIF Create Support']==true)) $validTypes[] = IMAGETYPE_GIF;
    if($info['JPG Support']==true) $validTypes[] = IMAGETYPE_JPEG;
    if($info['PNG Support']==true) $validTypes[] = IMAGETYPE_PNG;

    return $validTypes;
  }


  public static function getValidImageExtensions() {
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


  public function loadImageFromRequest($formname) {
    $name = $_FILES[$formname]['name'];
    $parts = explode(".",$name);
    $extension = strtolower($parts[count($parts)-1]);

    $valid = self::getValidImageExtensions();
    if(!in_array($extension,$valid)) { 
      Throw new AMEImage;
    }

    parent::loadFileFromRequest($formname);
  }

  public function getSize() {
    $im = imagecreatefromstring($this->dados);
    return array("x"=>imagesx($im),
		 "y"=>imagesy($im));
  }

  public function resize($x1,$y1) {
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

    $img_src = imagecreatefromstring($this->dados);
    $img_dst = ImageCreateTrueColor($xc,$yc);

    $src_width = imagesx($img_src);
    $src_height = imagesy($img_src);

    imagecopyresampled($img_dst,$img_src,0,0,0,0,$xc,$yc,$src_width, $src_height);


    //captures the image
    ob_start();
    Imagepng($img_dst);
    $this->dados = ob_get_contents();
    $this->tipoMime = "image/png";
    //clear the buffer
    ob_end_clean();

  }


  public function save() {
    global $_CMAPP;
    
    $dm = $this->getSize();
    $s = round($this->tamanho/1024);
    $this->metaDados = "$dm[x]|$dm[y]|$s";

    parent::save();
    //we must delete all the existing thumbnails of this image if the save was sucessfull
    //see AMThumb for more information about thumbnail generation
    $p = AMThumb::getImagesPattern($this->codeArquivo);

    $_conf = $_CMAPP['config']->getObj();
    $path =  (string) $_conf->app[0]->paths[0]->thumbnails;
    $handle = opendir($path);
    
    while (($file = readdir($handle))!==false) {
      if(ereg($p,$file)) {
	unlink($path.'/'.$file);
      }
    }
  }


}


?>