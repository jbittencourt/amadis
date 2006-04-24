<?
abstract class AMThumb extends AMImage {
  protected $maxY;
  protected $maxX;
  public $thumb;
  protected $name_file;
  
  public function load() {

    $this->name_file = "image_".$this->maxX."_".$this->maxY."_".$this->codeArquivo.".png";
    if(!$this->checkThumbExists()) {
      parent::load();
      $this->save();
    }
    
    $this->thumb = new AMTThumb($this->name_file);
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

    $this->resize($this->maxX, $this->maxY);
    $_conf = $_CMAPP['config']->getObj();
    $path =  (string) $_conf->app[0]->paths[0]->thumbnails;
    $image = fopen($path.'/'.$this->name_file, "w+");
    $w = fwrite($image, $this->dados);
    fclose($image);
  }

  static public function getImagesPattern($codeFile) {
    return 'image_([0-9]{1,})_([0-9]{1,})_'.$codeFile.'.png';
  }


  protected function checkThumbExists() {
    global $_CMAPP;
    $_conf = $_CMAPP['config']->getObj();
    $path =  (string) $_conf->app[0]->paths[0]->thumbnails;
    if(file_exists($path.'/'.$this->name_file)) return true;
    else return false;
  }
}