<?php
/**  
 *  Manage projects libraries
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMLibrary
 * @version 1.0
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 */

class AMProjectLibraryEntry extends CMObj {

  public function configure() {
    $this->setTable("ProjectsLibraries");
    $this->addField("codeProject",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codeLibrary",CMObj::TYPE_INTEGER,11,1,0,0);

    $this->addPrimaryKey("codeProject");
    $this->addPrimaryKey("codeLibrary");
  }
 
  public function __construct($project=""){
    parent::__construct();
    if(!empty($project))
      $this->codeProject = $project;
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
      $this->codeLibrary = $library->code;
      $this->save();
    }catch(CMDBException $e){ 
      $e->getMessage();
      die();
    }
  }
  
  public function getLibrary($proj){
    $this->codeProject = $proj;
    try{
      $this->load();
    }catch(CMException $e){
      echo $e;
    }
    return $this->codeLibrary;
  }

}