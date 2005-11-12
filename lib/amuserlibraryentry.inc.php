<?
class AMUserLibraryEntry extends CMObj {

  protected $user;
  
  public function configure() {
    $this->setTable("UsersLibraries");
    $this->addField("userCode",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("libraryCode",CMObj::TYPE_INTEGER,11,1,0,0);

    $this->addPrimaryKey("userCode");
    $this->addPrimaryKey("libraryCode");
  }
  
  public function __construct(){
    parent::__construct();
    $this->user = $_SESSION[user]->codeUser;
  }
  
  
  public function libraryExist(){  // confere se a biblioteca do usuario existe.. caso nao, ele cria uma
    //$l = new AMUserLibraryEntry;
    $this->userCode = $_SESSION[user]->codeUser;
    try{
      $this->load();
    }catch(CMDBNoRecord $w){
      try{
	$this->newLibrary();
      }catch(AMException $e){ 
	//$e->getMessage(); die();
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
      $this->userCode =  $_SESSION[user]->codeUser;
      $this->libraryCode = $library->code;      
      $this->save();      
    }catch(AMException $e){ 
      $e->getMessage(); die();
    }
  }

  public function getLibrary($user){
    $this->userCode = $user;
    try{
      $this->load();
    }catch(CMException $e){
      echo $e;
    }
    return $this->libraryCode;
  }

}
?>