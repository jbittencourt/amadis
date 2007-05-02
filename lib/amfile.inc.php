<?php
/**
 * This class models a file that is be stored in the database.
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMImage
 **/
class AMFile extends CMObj {

	public function configure() {
		$this->setTable("Files");

		$this->addField("codeFile",CMObj::TYPE_INTEGER,11,1,0,1);
		$this->addField("data",CMObj::TYPE_BLOB,16777215,1,0,0);
		$this->addField("mimeType",CMObj::TYPE_VARCHAR,100,1,0,0);
		$this->addField("size",CMObj::TYPE_INTEGER,11,1,0,0);
		$this->addField("name",CMObj::TYPE_VARCHAR,30,1,0,0);
		$this->addField("metadata",CMObj::TYPE_VARCHAR, 255,1,0,0);
		$this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);

		$this->addPrimaryKey("codeFile");
	}

	public function load()
	{
		try {
			parent::load();
			$_SESSION['amadis']['old_photo_name'] = $this->name;
		}catch(CMException $e){
			throw $e;
		}
		 
	}
	
	
	public function save()
	{
		global $_CMAPP;
  
		$_conf = $_CMAPP['config'];
		$data = $this->data;

		unset($this->fieldsValues['data']);
		parent::save();
  
		$path = (string) $_conf->app[0]->paths[0]->files;

		if(isset($_SESSION['amadis']['old_photo_name'])) {
			$file = $path.'/'.$this->codeFile.'_'.$_SESSION['amadis']['old_photo_name'];
			if(file_exists($file)) unlink($file);
			unset($_SESSION['amadis']['old_photo_name']);
		}
		 
		$file = $path.'/'.$this->codeFile.'_'.$this->name;
		$f = fopen($file, "a");
		fwrite($f, $data);
		fclose($f);

	}
	
	function delete()
	{
		global $_CMAPP;

		$_conf = $_CMAPP['config'];
		$path = (string) $_conf->app[0]->paths[0]->files;
		$file = $path.'/'.$this->codeFile.'_'.$this->name;
		if(file_exists($file)) {
			parent::delete();
			unlink($file);
		} else Throw new CMException("This file don't exists. Check reference: $file");
	}
	
	
  /**
   * Load an image from the request to the object.
   *
   * PHP handles files uploads with a predefined bidimensional array $_FILES. This
   * array contains various informations about the file being uploaded and a pointer
   * to the temporary file in the servers filesystem. This information should be handled
   * by the user to store the file in it's persistent location. This method, handles this
   * process, using the information provided by PHP and Apache to fill the AMArquivo
   * properties.
   *
   * @param string $inputName The name of the <INPUT type=file> element in the form.
   **/
	public function loadFileFromRequest($formName) {

		 
		switch ($_FILES[$formName]['error']) {
			case UPLOAD_ERR_OK:
				$errorMsg = '';
				break;
			case UPLOAD_ERR_INI_SIZE:
				$errorMsg = "The uploaded file exceeds the upload_max_filesize directive (".ini_get("upload_max_filesize").") in php.ini.";
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$errorMsg = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
				break;
			case UPLOAD_ERR_PARTIAL:
				$errorMsg =  "The uploaded file was only partially uploaded.";
				break;
			case UPLOAD_ERR_NO_FILE:
				$errorMsg = "No file was uploaded.";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$errorMsg = "Missing a temporary folder.";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$errorMsg = "Failed to write file to disk";
				break;
			default:
				$errorMsg = "Unknown File Error";
		}
	
		if(!empty($errorMsg)) {
			Throw new AMExceptionFile($_FILES[$formName]['tmp_name'], $errorMsg);
		}
		
		$this->name = $_FILES[$formName]['name'];
		$this->mimeType = $_FILES[$formName]['type'];
		$this->size = $_FILES[$formName]['size'];
		$this->time = time();

		$this->data  = @implode("",file($_FILES[$formName]['tmp_name']));

				
		
		

	}
}
?>