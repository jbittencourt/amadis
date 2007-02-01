<?php

/**
 * This subPackage answer for the users albums.
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMAlbum
 * @version 1.0
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 */

class  AMAlbum extends CMObj {

	public function configure() {
		$this->setTable("Album");

		$this->addField("code",CMObj::TYPE_INTEGER,4,1,0,1);
		$this->addField("codeUser",CMObj::TYPE_INTEGER,4,1,0,0);
		$this->addField("codePhoto",CMObj::TYPE_INTEGER,4,1,0,0);
		$this->addField("comments",CMObj::TYPE_VARCHAR,100,1,0,0);
		$this->addField("time", CMObj::TYPE_INTEGER, 4,1,0,0);

		$this->addPrimaryKey("code");
	}

	public function getMyPhotos(){
		try{
			$q = new CMQuery('AMAlbum');
			$q->setFilter("Album.codeUser = '$this->codeUser'");
			return $q->execute();
		}catch(CMDBNoRecord $w){
			new AMErrorReport($w, 'AMAlbum::getMyPhotos', AMLog::LOG_ALBUM);
			return new CMContainer;
		}
	}

	public function saveEntry(){
		
		$formName = $_REQUEST['fieldName']; // recebe o nome do campo de tipo 'file'
		$file = new AMImage;
		//preenche os capos do arquivo

		$ext = AMImage::getValidImageExtensions();
		$achou=false;
		foreach($ext as $item){
			$g = explode(".",$_FILES[$formName]['name']);
			if($g[1] == $item){
				$achou = true;
			}
		}
		if(!$achou)
		return false;


		$file->name = $_FILES[$formName]['name'];
		$file->name = str_replace(" ", "_",$file->name);
		$file->mimeType = $_FILES[$formName]['type'];
		$file->size = $_FILES[$formName]['size'];
		$file->time = time();


		if($_FILES[$formName]['tmp_name'] == "")
		return false;

		$file->data  = implode("",file($_FILES[$formName]['tmp_name']));
		$d = $file->data;

		if(empty($d))
		return false;

		$tam = $file->getSize();
		if($tam['x'] > 700)
		$file->resize(700,800);

		try {
			$file->save();	//salva o arquivo
		}catch(CMException $e){
			new AMErrorReport($e, 'AMBAlbum::saveEntry - saving_image', AMLog::LOG_ALBUM);
		}

		
		$this->codePhoto = $file->codeFile;
		$this->codeUser = $_SESSION['user']->codeUser;
		$this->comments = $_REQUEST['comment'];
		$this->time = time();

		try{
			$this->save();
		}catch(CMException $e){
			new AMErrorReport($e, 'AMBAlbum::saveEntry - saving', AMLog::LOG_ALBUM);
		}
	}


	public function editComment($codePhoto, $comment){
		$this->codePhoto = $codePhoto;
		try{
			$this->load();
		}catch(CMException $e){
			new AMErrorReport($e, 'AMBAlbum::editComment - loading', AMLog::LOG_ALBUM);
		}

		$this->comments = $comment;

		try{
			$this->save();
		}catch(CMException $e){
			new AMErrorReport($e, 'AMBAlbum::editComment - saving', AMLog::LOG_ALBUM);
		}
	}


	public function deleta($id){

		$this->codePhoto = $id;
		try{
			$this->load();
		}catch(CMException $e){
			new AMErrorReport($e, 'AMBAlbum::deleta - loading', AMLog::LOG_ALBUM);
		}
		$file = new AMFile;
		$file->codeFile = $id;
		try{
			$this->delete();
			$file->load();
			$file->delete();
		}catch(CMException $e){
			new AMErrorReport($e, 'AMBAlbum::deleta - delete', AMLog::LOG_ALBUM);
		}
	}
}