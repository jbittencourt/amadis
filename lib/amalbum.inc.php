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
		$file = new AMAlbumPicture;
		//preenche os capos do arquivo

		$file->loadFileFromRequest($formName);
		$file->time = time();

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

	public function getPhoto() 
	{
		$pict = new AMAlbumPicture;
		$pict->codeFile = $this->codePhoto;
		try {
			$pict->load();	
		} catch(CMDBException $e) {
			Throw $e;
		}
		
		return $pict;
	}

	public function deleta($id)
	{

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