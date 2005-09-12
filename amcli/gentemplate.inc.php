<?
class GenTemplate {

  private $template;

  public function __construct($name) {

    $file = file("amcli/base/template.inc.php.def");
    $template = implode("",$file);
    $this->template = ereg_replace("{TEMPLATE_NAME}",$name, $template);
    
  }

  public function makeFile($file){
    if(file_exists($file)) {
      $handle = fopen($file, "w");
      if(is_writable($file)) {
	fwrite($handle,$this->template);
      }
    }
  }

}

?>