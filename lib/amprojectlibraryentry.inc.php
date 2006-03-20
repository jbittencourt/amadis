<?
class AMProjectLibraryEntry extends CMObj {

  public function configure() {
    $this->setTable("ProjectsLibraries");
    $this->addField("projectCode",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("libraryCode",CMObj::TYPE_INTEGER,11,1,0,0);

    $this->addPrimaryKey("projectCode");
    $this->addPrimaryKey("libraryCode");
  }
 
  public function __construct($project=""){
    parent::__construct();
    if(!empty($project))
      $this->projectCode = $project;
  }

  public function libraryExist(){  // confere se a biblioteca do usuario existe.. caso nao, ele cria uma
    try{
      $this->load();
    }catch(CMDBNoRecord $e){
      try{
	$this->newLibrary();
      }catch(CMDBNoRecord $f){ 
	$f->getMessage(); die();
      }
    }
  }

  protected function newLibrary(){ //cria uma nova biblioteca para o usuario    
    //adiciona uma nova biblioteca no banco
    $library = new AMLibrary;
    $library->time = time();
    try{
      $library->save();
      //adiciona esta nova biblioteca ao usuario que esta sendo cadastrado
      $this->libraryCode = $library->code;
      $this->save();
    }catch(CMDBException $e){ 
      $e->getMessage();
      die();
    }
  }
  
  public function getLibrary($proj){
    $this->projectCode = $proj;
    try{
      $this->load();
    }catch(CMException $e){
      echo $e;
    }
    return $this->libraryCode;
  }

}

?>