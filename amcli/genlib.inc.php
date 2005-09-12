<?
class GenLib {

  private $lib;

  public function __construct($name) {

    $file = file("amcli/base/lib.inc.php.def");
    $lib = implode("",$file);
    $this->lib = ereg_replace("{LIB_NAME}",$name, $lib);
    
  }

  public function makeFile($file){
    if(file_exists($file)) {
      $handle = fopen($file, "w");
      if(is_writable($file)) {
	fwrite($handle,$this->lib);
      }
    }
  }

}

?>