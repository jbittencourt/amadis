<?
class AMLibraryFiles extends CMObj {

  public function configure() {
    $this->setTable("FilesLibraries");
    $this->addField("filesCode",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("libraryCode",CMObj::TYPE_INTEGER,11,1,0,0);
    
    $this->addPrimaryKey("filesCode");
    $this->addPrimaryKey("libraryCode");
  }
}

?>