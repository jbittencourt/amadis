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

  public function save() 
  {
  	global $_CMAPP;
  	
  	$_conf = $_CMAPP['config'];

  	$data = $this->data;
  	unset($this->fieldsValues['data']);
  	parent::save();
  	
  	$path = (string) $_conf->app[0]->paths[0]->files;
  	$file = $path.'/'.$this->codeFile.'_'.$this->name;
  	$f = fopen($file, "a");
  	fwrite($f, $data);
  	fclose($f);
	
  	if(isset($_SESSION['amadis']['old_photo_name'])) {
		$file = $path.'/'.$this->codeFile.'_'.$_SESSION['amadis']['old_photo_name'];
		if(file_exists($file)) unlink($file);
		unset($_SESSION['amadis']['old_photo_name']);
	}
	
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

  	$this->name = $_FILES[$formName]['name'];
    $this->mimeType = $_FILES[$formName]['type'];
    $this->size = $_FILES[$formName]['size'];
    $this->time = time();

    $this->data  = implode("",file($_FILES[$formName]['tmp_name']));

    $d = $this->data;
    if(empty($d)) {
      Throw new AMExceptionFile($_FILES[$formName]['tmp_name']);
    }

  }
}