<?


class AMTLibrary extends AMMain {
  

  function __construct($libraryType) {
    global $_CMAPP;

    parent::__construct();
    $this->requires("library.css",CMHTMLObj::MEDIA_CSS);
    $this->requires("library.js",CMHTMLObj::MEDIA_JS);

    if($libraryType == "project" || $libraryType == "course")
      $this->setImgId($_CMAPP['imlang_url']."/top_biblioteca.gif");
    else
      $this->setImgId($_CMAPP['imlang_url']."/top_meusarquivos.gif");
      
    $this->openNavMenu();
  }
}

?>