<?
include("GenDomPackage.inc.php");
include("gencontroller.inc.php");
include("gentemplate.inc.php");
include("genlib.inc.php");

class GenPackage {

  const SERVICE_PATH = "/home/robson/amadis/ambiente/amservices";
  
  private $package_xml;
  private $package_name;
  private $package_struts = array();
  private $package_path;
  private $module_param;

  public function __construct($module, $genModuleStruts=TRUE) {
    
    $this->module_param = $module;
    $this->package_name = $this->module_param[name];
    $this->package_path = GenPackage::SERVICE_PATH."/".strtolower($this->package_name);

    if($genModuleStruts) {
      $this->makeModuleStruts();
    }
    
  }

  public function makeModuleStruts() {

    //directories
    $this->package_struts[rootDir] = $this->package_path;
    $this->package_struts[media] = $this->package_path."/media";
    $this->package_struts[lib] = $this->package_path."/lib";
    $this->package_struts[lang] = $this->package_path."/lang";
    $this->package_struts[template] = $this->package_path."/template";
    
    //base files
    $this->package_struts[files] = array();
    $this->package_struts[files][package] = "package.xml";
    $this->package_struts[files][lang] = $this->module_param[default_language].".lang";
    $this->package_struts[files][controller] = strtolower($this->package_name)."controller.inc.php";
    $this->package_struts[files][template] = strtolower($this->package_name)."box.inc.php";
    $this->package_struts[files][libfile] = strtolower($this->package_name).".inc.php";
  }

  public function makeInstall() {

    if(!file_exists($this->package_struts[rootDir])) {

      //make directories
      $command  = "mkdir ".$this->package_struts[rootDir];
      $command .= "; mkdir ".$this->package_struts[media];
      $command .= "; mkdir ".$this->package_struts[lib];
      $command .= "; mkdir ".$this->package_struts[lang];
      $command .= "; mkdir ".$this->package_struts[template];
      
      //base files
      $path = $this->package_struts[rootDir];
      $command .= "; touch ".$path."/".$this->package_struts[files][controller];
      $command .= "; touch ".$path."/lang/".$this->package_struts[files][lang];
      $command .= "; touch ".$path."/template/".$this->package_struts[files][template];
      $command .= "; touch ".$path."/lib/".$this->package_struts[files][libfile];
      
      exec($command);
      
      $controller = new GenController($this->module_param[name]);
      $controller->makeFile($path."/".$this->package_struts[files][controller]);
      
      $lib = new GenLib($this->module_param[name]);
      $lib->makeFile($path."/lib/".$this->package_struts[files][libfile]);

      $template = new GenTemplate($this->module_param[name]);
      $template->makeFile($path."/template/".$this->package_struts[files][template]);

      $this->makePackageXML();
    }
  }
  
  public function makePackageXML() {

    $this->package_xml = new GenDomPackage($this->module_param);
    
    $path = $this->package_struts[rootDir];
    $this->package_xml->save($path."/".$this->package_struts[files][package]);
  
  }

}

?>