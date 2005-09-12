<?
class GenController {

  private $controller;

  public function __construct($name) {

    $file = file("amcli/base/controller.inc.php.def");
    $controller = implode("",$file);
    $this->controller = ereg_replace("{CONTROLLER_NAME}",$name, $controller);
    
  }

  public function makeFile($file){
    if(file_exists($file)) {
      $handle = fopen($file, "w");
      if(is_writable($file)) {
	fwrite($handle,$this->controller);
      }
    }
  }

}

?>