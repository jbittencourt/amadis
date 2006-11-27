<?php

/** 
 *  Manage user's libraries
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMLibrary
 * @version 1.0
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 */


class AMUserLibraryEntry extends CMObj {

  protected $user;
  
  public function configure() {
    $this->setTable("UsersLibraries");
    $this->addField("codeUser",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codeLibrary",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);

    $this->addPrimaryKey("codeUser");
    $this->addPrimaryKey("codeLibrary");
  }
  
  public function __construct($user){
    parent::__construct();
    $this->user = $user;
  }
  
  
  public function libraryExist(){  // confere se a biblioteca do usuario existe.. caso nao, ele cria uma
    //$l = new AMUserLibraryEntry;
    $this->codeUser = $this->user;
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
      $this->codeUser =  $this->user;
      $this->codeLibrary = $library->code;      
      $this->save();      
    }catch(AMException $e){ 
      $e->getMessage(); die();
    }
  }

  public function getLibrary($user){
    $this->codeUser = $user;
    try{
      $this->load();
    }catch(CMDBNoRecord $w){
      $this->newLibrary();
    }
    return $this->codeLibrary;
  }
}