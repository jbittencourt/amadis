<?php

/**
 *
 * AMLibrary is the core of all libraries at AMADIS. Its answer for the essencial operations.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public or private
 * @package AMADIS
 * @subpackage AMLibrary
 * @version 1.0
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 */
class  AMLibrary extends CMObj{

	public function configure(){
		$this->setTable("Library");
		$this->addField("code",CMObj::TYPE_INTEGER,11,1,0,1);
		$this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);
		$this->addPrimaryKey("code");
	}

	public function setLibrary($lib){
		try {
			$this->code = $lib;
		} catch(CMObjEPropertieValueNotValid $e) {
			new AMErrorReport($e, 'AMLibrary::setLibrary', AMLog::LOG_LIBRARY);
		}
	}


  /**
   * search an file type on DB. In this version of AMADIS, the library support 5 categories of files: 
   * images, documents, videos, audio and other(that receive the files that dont match in the other categories.
   * @param String tipo
   * @return CMContainer res
   *
   */
  public function busca($tipo){

  	$q = new CMQuery('AMFile', 'AMLibraryFiles');

  	switch( $tipo ){
    case "img":
    	$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype LIKE 'image/%') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
    	break;
    case "docs":
    	$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype = 'application/msword' OR AMFile::mimetype LIKE 'text/%' OR AMFile::mimetype = 'application/vnd.sun.xml.writer' OR AMFile::mimetype = 'application/pdf') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
    	break;
    case "video":
    	$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype LIKE 'video/%'OR AMFile::mimetype = 'application/x-shockwave-flash') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
    	break;
    case "audio":
    	$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype LIKE 'audio/%') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
    	break;
    case "outros":
    	$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype NOT LIKE 'audio/%' AND AMFile::mimetype NOT LIKE 'video/%' AND AMFile::mimetype != 'application/x-shockwave-flash' AND AMFile::mimetype NOT LIKE 'image/%' AND AMFile::mimetype != 'application/msword' AND AMFile::mimetype NOT LIKE 'text/%' AND AMFile::mimetype != 'application/vnd.sun.xml.writer' AND AMFile::mimetype != 'application/pdf') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
    	break;
    default:
    	break;
  	} //fecha switch
  	$q->setOrder('AMFile::name');
  	$res = $q->execute();
  	if($res->__hasItems()){
  		return $res;
  	}
  }// fecha funcao

  /**
   * DB Query to count how much items of each category we have. 
   * The return variable, ok, receive the number of results in db query, 
   * relative with the category that we are searching about.
   * @param String mimeType
   * @return CMContainer ok
   */

  public function countBooks($mimeType){

  	$q = new CMQuery('AMFile','AMLibraryFiles');

  	if($mimeType == "image"){
  		$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype LIKE 'image/%') AND (AMLibraryFiles::codeLibrary = '$this->code') AND (AMLibraryFiles::active = 'y')");
  	}
  	if($mimeType == "text"){
  		$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype = 'application/msword' OR AMFile::mimetype LIKE 'text/%' OR AMFile::mimetype = 'application/vnd.sun.xml.writer' OR AMFile::mimetype = 'application/pdf') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
  	}
  	if($mimeType == "audio"){
  		$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype LIKE 'audio/%') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
  	}
  	if($mimeType == "video"){
  		$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype LIKE 'video/%'OR AMFile::mimetype = 'application/x-shockwave-flash') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
  	}
  	if($mimeType == "other"){
  		$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype NOT LIKE 'audio/%' AND AMFile::mimetype NOT LIKE 'video/%' AND AMFile::mimetype != 'application/x-shockwave-flash' AND AMFile::mimetype NOT LIKE 'image/%' AND AMFile::mimetype != 'application/msword' AND AMFile::mimetype NOT LIKE 'text/%' AND AMFile::mimetype != 'application/vnd.sun.xml.writer' AND AMFile::mimetype != 'application/pdf') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
  	}
  	$q->setCount();
  	$ok = $q->execute();
  	return $ok; // ok recebe o numero de resultados para a busca acima.
  }

  /**
   * This function return the files that have an image/% mime type. Its will be use to generate the thumbs later.
   * @param void
   * @return CMContainer 
   */
  public function buscaThumbs(){
  	global $_CMAPP;

  	$q = new CMQuery('AMFile','AMLibraryFiles');
  	$q->setFilter("(AMFile::codeFile = AMLibraryFiles::codeFile ) AND (AMFile::mimetype LIKE 'image/%') AND (AMLibraryFiles::codeLibrary = '$this->code') AND AMLibraryFiles::active='y'");
  	$q->setOrder('AMFile::name');
  	$res = $q->execute();
  	if($res->__hasItems()){
  		return $res;
  	}
  }


  /**
   * This function save a file in db. We need to save the file at 'File' table, and later,
   *  save the relations between files and library. 
   * 
   **/
  public function saveEntry($ret=true){
  	$formName = $_REQUEST['fieldName']; // recebe o nome do campo de tipo 'file'
	
  	$tipo = explode("/", $_FILES[$formName]['type']);

  	$file_type =  explode(".",$_FILES[$formName]['name']);

  	$filelib = new AMLibraryFiles;
  	$file = new AMFile;	
  	//preenche os capos do arquivo
  	$file->name = $_FILES[$formName]['name'];
  	$file->mimeType = $_FILES[$formName]['type'];  	
  	$file->size = $_FILES[$formName]['size'];
  	$file->time = time();  	
  	$file->data  = implode("",file($_FILES[$formName]['tmp_name']));

  	$d = $file->data;
  	if(empty($d)) {
  		Throw new AMExceptionFile($_FILES[$formName]['tmp_name']);
  	}
  	//$file->loadDataFromRequest($formName);
  	//$file->time = time();

  	try {
  		$file->save();	//salva o arquivo
  		$filelib->codeLibrary = $this->code;
  		$filelib->codeFile = $file->codeFile;
  		$filelib->time = time();
  		$filelib->save();
  	}catch(CMException $e){
		new AMErrorReport($e, 'AMLibrary::saveEntry', AMLog::LOG_LIBRARY);
  	}

  	return $ret;
  }//fecha function salva

  /**
   * this method test if the file is in use in another amadis tool( like forum), 
   * the field 'referred' is set "Y" if its used and "N" if isnt.  
   * Ok, if its used, we just set the field active 'N' and keep the file in Arquivo table.
   * But if any tool are using the file, we discard it, removing from libraryfiles and arquivo tables.
   * 
   **/

  	public function deleta($id){
  		$filelib = new AMLibraryFiles;
  		$filelib->codeFile = $id;
  		$filelib->load();

	  	if($filelib->referred == "0"){
  			$filelib->unsetActive();
  			$filelib->save();
  		} else { 
			$filelib->delete();
  		}
  	}

  /**
   * You give a number of results you want, $limit, and its return the last $limit files posted.
   **/
  public function getLastFiles($limit){
	$q = new CMQuery('AMFile','AMLibraryFiles');
  	$q->setFilter("AMFile::codeFile = AMLibraryFiles::codeFile AND AMLibraryFiles::codeLibrary = '$this->code' AND AMLibraryFiles::active='y'");
  	$q->setLimit(0,$limit);
  	$q->setOrder('AMFile::time desc');
  	try {
  		$res = $q->execute();
  		return $res;
  	}catch(CMException $r){
  		new AMErrorReport($r, 'AMLibrary::getLastFiles', AMLog::LOG_LIBRARY);
  		return new CMContainer;
  	}
  	
  }

  /**
   * Return if the file is shared or not.   
   **/
  public function isShared($fileCode){
	$filelib = new AMLibraryFiles;
  	$filelib->codeFile = $fileCode;
  	try{
    	$filelib->load();
  	}catch(AMException $e){
  		new AMErrorReport($e, 'AMLibrary::isShared', AMLog::LOG_LIBRARY); 
  		return "false"; 
  	}
	  	if($filelib->shared == "y")
  			return "true";
  		else
  			return "false";
  	}

  /**
    * List the last $limit  shared files, if $limit = 0, list all..!  >:D~ 
   **/
  public function listSharedFiles($limit){ //if the limit passed is 0, dont set limit
  	try{
  		$q = new CMQuery('AMFile','AMLibraryFiles');
  		$q->setFilter("AMFile::codeFile = AMLibraryFiles::codeFile AND AMLibraryFiles::codeLibrary = '$this->code' AND AMLibraryFiles::active='y' and AMLibraryFiles::shared='y'");
  		if($limit > 0)
  		$q->setLimit(0,$limit);
  		$q->setOrder('AMFile::time desc');
  		return $q->execute();
  	}catch(CMDBNoRecord $r){
		new AMErrorReport($e, 'AMLibrary::listSharedFiles', AMLog::LOG_LIBRARY);
  	}
  }


  /**
   * Get Images from library of a forum
   **/
  public static function loadImageLibrary() {

  	$lib = new AMUserLibraryEntry($_SESSION['user']->codeUser);
  	$lib = $lib->getLibrary($_SESSION['user']->codeUser);

  	$q = new CMQuery(AMFile);

  	$j = new CMJoin(CMJoin::INNER);
  	$j->setClass(AMLibraryFiles);
  	$j->on("codeFile = codeFile");

  	$q->addJoin($j, "lib");
  	$q->setProjection("AMFile::codeFile, AMFile::mimetype, AMFile::name, AMFile::metadata, AMLibraryFiles::*");
  	$q->setFilter("codeLibrary = $lib AND AMFile::mimetype LIKE 'image%'");
  	return $q->execute();
  }

  public static function loadProjectImageLibrary() {
  	$q = new CMQuery(AMLibraryFiles);

  	$j = new CMJoin(CMJoin::LEFT);
  	$j->setClass(AMProjectLibraryEntry);
  	$j->on("AMLibraryFiles::codeLibrary = AMProjectLibraryEntry::codeLibrary");
  	$j->setFake();

  	$j2 = new CMJoin(CMJoin::LEFT);
  	$j2->setClass(AMFile);
  	$j2->on("AMFile::codeFile = AMLibraryFiles::codeFile");

  	$j3 = new CMJoin(CMJoin::LEFT);
  	$j3->setClass(AMProject);
  	$j3->on("AMProjectLibraryEntry::codeProject = AMProject::codeProject");

  	$j4 = new CMJoin(CMJoin::INNER);
  	$j4->setClass('CMGroup');
  	$j4->on('AMProject::codeGroup=CMGroup::codeGroup');
  	$j4->setFake();

  	$j5 = new CMJoin(CMJoin::LEFT);
  	$j5->setClass('CMGroupMember');
  	$j5->on('CMGroupMember::codeGroup=CMGroup::codeGroup');
  	$j5->setFake();

  	$q->addJoin($j, "pjlib");
  	$q->addJoin($j2, "files");
  	$q->addJoin($j3, "proj");
  	$q->addJoin($j4, "grupos");
  	$q->addJoin($j5, "membros");

  	$q->setProjection("AMLibraryFiles::codeFile, AMProject::title, AMProject::codeProject, AMFile::codeFile, AMFile::mimetype, AMFile::metadata, AMFile::name");

  	$q->setFilter('CMGroupMember::codeUser = '.$_SESSION['user']->codeUser.' AND CMGroupMember::status="'.CMGroupMember::ENUM_STATUS_ACTIVE.'" AND codeFile != "NULL" AND mimetype LIKE "image%"');

  	return $q->execute();

  }


}