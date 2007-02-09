<?php
abstract class AMThumb extends AMImage {
  protected $maxY;
  protected $maxX;
  public $thumb;
  public $type;
  protected $name_file;
  
  public function load() {

  	if(!empty($this->codeFile)) {
  		$this->name_file = "image_".$this->maxX."_".$this->maxY."_".$this->codeFile.".png";
  	} else $this->name_file = "image_".$this->maxX."_".$this->maxY."_".substr($this->type,0, -4).".png";

    if(!$this->checkThumbExists()) {
    	if(!empty($this->codeFile)) parent::load();
    	$this->loadFile();
    	$this->save();
    }
    
    $this->thumb = new AMTThumb($this->name_file);
  }

  public function loadFile() 
  {
  	global $_CMAPP;

  	$_conf = $_CMAPP['config'];
  	if(empty($this->type)) {
  		$path =  (string) $_conf->app[0]->paths[0]->files;
  		$file = $path."/".$this->codeFile.'_'.$this->name;
  	} else {
  		$file = $_CMAPP['path'].'/environment/media/images/'.$this->type;
  	}
  	if(file_exists($file)) {
  		$f = fopen($file, 'r');
  		$this->data = fread($f, filesize($file));
		fclose($f);	
  	}

  	
  }
  public function getView() {
    return $this->thumb;
  }


  public function setSize($x="", $y="") {
    $this->maxX = ($x=="" ? $this->maxX : $x);
    $this->maxY = ($y=="" ? $this->maxY : $y);
  }

  public function save() {
    global $_CMAPP;

    try {
      $this->resize($this->maxX, $this->maxY);
    } catch(AMException $e) {
      	/**
       	 * @todo Add an error image when the thumbnail cannot be genereated.
       	 **/
		return false;
    }

    $_conf = $_CMAPP['config'];
    $path =  (string) $_conf->app[0]->paths[0]->thumbnails;
    $image = fopen($path.'/'.$this->name_file, "w+");
    $w = fwrite($image, $this->data);
    fclose($image);
  }

  static public function getImagesPattern($codeFile) {
    return 'image_([0-9]{1,})_([0-9]{1,})_'.$codeFile.'.png';
  }


  protected function checkThumbExists() {
    global $_CMAPP;
    
    $_conf = $_CMAPP['config'];
    $path =  (string) $_conf->app[0]->paths[0]->thumbnails;
    if(file_exists($path.'/'.$this->name_file)) return true;
    else return false;
  }
}