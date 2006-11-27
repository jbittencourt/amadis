<?php
class AMUpload {

  const ABSOLUTE_PATHS = 1;
  const RELATIVE_PATHS = 2;

  private $dir, $uploadDir;
  private $path, $errors = array();
  private $files = array();
  private $moveFiles;

  public function __construct($path) {
    $this->path = $path;
    if(!is_dir($path)) {
      return FALSE;
    }
    $this->dir = $this->readDir($path);
  }
  

  /**
   *Esta funcao cria diretorios apartir do diretorio root
   *sendo assim necessario desenhar o caminho da navegacao
   *na arvore para nao ocorrer erros durante o processo.
   */
  public function createDir($dirName) {
    if(!file_exists($dirName)){
      if(mkdir($dirName, 0755))
	return TRUE;
      else Throw new AMUECannotCreateDir($dirName);
    }else Throw new AMUEFileExists($dirName);
  }

  public function removeFiles($dir) {
    
    foreach($_REQUEST as $k=>$item) {
      if(substr($k, 0, 9) == "frm_file_") {
	$file = $_SESSION['upload']['current']."/$item";
	if(file_exists($file)) {
	  if(!is_dir($file)) {
	    if(!unlink($file)) Throw new AMUECannotRemoveFile;
	  } else $this->removeDir($file);
	}else Throw new AMUEFileNotExists($item);
      }
    }
  }

  public function removeDir($dirName) {
    if(file_exists($dirName)) {
      try {
	$dh=opendir($dirName);
	while (false !== ($file = readdir($dh))) {
	  //	  while ($file=readdir($dh)) {
	  if($file!="." && $file!="..") {
	    $fullpath=$dirName."/".$file;
	    if(!is_dir($fullpath)) {
	      if(!unlink($fullpath)) Throw new AMUECannotRemoveFile;
	    } else {
	      $this->removeDir($fullpath);
	    }
	  }
	}
	closedir($dh);
	
      	if(@rmdir($dirName)===FALSE) Throw new AMUECannotDeleteDiretory($dirName);
	
      }catch (AMException $e) {
	Throw new AMUECannotDeleteDiretory($dirName);
      }
    }else Throw new AMUEFileNotExists($dir);
  }



  public function loadFileFromRequest($fieldName="",$moveFiles=TRUE) {
    $this->moveFiles = $moveFiles;
    $files_exists = array();

    if(!empty($_FILES)) {
      foreach($_FILES as $k=>$file) {
	if($file['error'] != 4) {
	  $rest = substr($k, 0,-2);
	  if($rest == $fieldName || $k == $fieldName){
	    if($moveFiles) {
	      $this->moveFile($file);
	    } else $this->files[] = $file;
	  }else Throw new AMUEDontFoundFilesToUpload($fieldName);
	}
      }
    }else Throw new AMEUFileDontUploaded;

    $this->msgs['error'] = $this->errors;
    $this->msgs['files_exists'] = $files_exists;
    return $this->msgs;

  }

  public function moveFile($file) {
    if(empty($this->uploadDir)) Throw new AMUEUploadDirNotSpecified;
    $file_name = $this->uploadDir."/".$file['name'];
    if(move_uploaded_file($file['tmp_name'], $file_name)) {
	    
      $this->filesUploaded[$file['name']] = "SUCCESS";
      
    }else $this->errors[] = explode(":", $this->getError($file));
  }


  public function setUploadDir($path) {

    if(file_exists($path)) $this->uploadDir = $path;
    else Throw new AMUEFileNotExists($path);

  }

  private function getError($file){
    
    switch($file['error']) {
    case UPLOAD_ERR_INI_SIZE:
      return "upload_err_ini_size:$file[name]";
      break;

    case UPLOAD_ERR_FORM_SIZE:
      return "upload_err_form_size:$file[name]";
      break;

    case UPLOAD_ERR_PARTIAL:
      return "upload_err_partial:$file[name]";
      break;

    case UPLOAD_ERR_NO_FILE:
      return "upload_err_no_file:$file[name]";
      break;
    }
  }

  
  public function readDir($path) {
    
    global $_language;
    
    $dirMime = array();
    $list = scandir($path);
    $dir = array_splice($list, 2);
    
    if(!empty($dir)) {
      $i=0;
      foreach($dir as $item) {
       	$mime = $this->getMime($path."/".$item);
	$dirMime[$i]['mime'] = $mime[0];
	$dirMime[$i]['name'] = $item;
	//timestamp
       	$stat = @stat($_SESSION['upload']['current']."/".$item);
	$dirMime[$i]['time'] = date("d/m/Y",$stat['mtime']);
	$dirMime[$i]['mime_info'] = $_language[$mime[1]];
	$i++;  
      }
      return $dirMime;
    }else return FALSE;
    
  }

  public function getDir() {
    return $this->dir;
  }

  private function getMime($fileName){
    
    $ext = strtolower($this->getExtention($fileName));

    switch($ext) {
    
    case "dir":
      return array("pasta","diretory");
      break;
    case ".html":
    case ".htm":
      return array("html","html_page");
      break;
    case ".gif":
      return array("img01","image_gif");
      break;
    case ".jpg":
    case ".jpeg":
      return array("img02","image_jpg");
      break;
    case ".png":
      return array("img03","image_png");
      break;
    case ".bmp":
      return array("img01","image_bmp");
      break;
    case ".zip":
      return array("zip","zip_file");
      break;
    case ".asf":
    case ".wmv":
    case ".mpeg":
    case ".mov":
    case ".avi":
      return array("video", "video_file");
      break;
    case ".pdf":
      return array("pdf", "pdf_file");
      break;
    case ".swf":
      return array("swf", "swf_flash_file");
      break;
    default:
      return array("outro","unknow_file");
      break;
    }
  }

  private function getExtention($fileName) {
    
    $pos = strrpos($fileName, ".");
    if(empty($pos)) {
      if(is_dir($fileName)) return "dir";
      else return;
    }
    else return $ext = substr($fileName, $pos);
    
  }
  
  public function getFilesDownload() {
    $i = 0;
    $file = array();
    foreach($_REQUEST as $k=>$item) {
      if(substr($k, 0, 9) == "frm_file_") {
	$file[$i]['mime'] = $this->getMime($item);
	$file[$i]['path'] = $_SESSION['upload']['current']."/$item";
	$file[$i]['name'] = $item;
	$i++;
      }
    }
    return $file;
  }

  public function toZip($files, $zipName) {
    $command = "cd ".$_SESSION['upload']['current']."; zip -r $zipName ";
    foreach($files as $file) {
      $command .= $file['name']." ";
    }
    
    return $command;
  }
  
  public function saveFile($fileName, $fileContent) {
    $file = fopen($fileName, "w+");
    $w = fwrite($file, $fileContent);
    fclose($file);
  }

  public function unZip($file, $path) {
    $command = "cd $path; unzip $file";
    exec($command);
    return true;
  }

  static function getImagesFromFolder($dir, $recursive=true) {
    global $_CMAPP;

    $ER = "(jpg|jpeg|png|gif)";
    
    if ($handle = opendir($dir)) {
      $dir .= "/";
      $pos = strpos($dir, "paginas");
      $sufix = substr($dir, ($pos+8), -1);
      $imgs = array();
      $i = 0;
      while (false !== ($file = readdir($handle))) {
	if($file != "." && $file != "..") {
	  if(is_dir($dir.$file) && $recursive) 
	    //$imgs = array_merge($imgs, self::getImagesFromFolder("$dir$file"));
	    $imgs[$file] = self::getImagesFromFolder("$dir$file");
	}
	if(ereg($ER, strtolower($file))){ 
	  $imgs[$i]['filename'] = $file;
	  $imgs[$i]['src'] = "$sufix/$file";
	  $imgs[$i]['url'] = "$_CMAPP[pages_url]/$sufix/$file";
	}
	$i++;
      }
      closedir($handle);
    }
    return $imgs;
  }

  /** Esta funcao retornar os caminhos de imagens e links no formato
   *  de links relativos e absolutos
   */
  static function getRAPaths($method = self::RELATIVE_PATHS) {
    
    $folders = split("/",$_REQUEST['frm_dir']);
    
    $foldersP = array();
    $absolute = "";
    $relative = "";
    $i = 0;
    $j = count($folders)-1;
    
    foreach($folders as $folder) {
      $absolute .= "$folder/";
      $relative = str_repeat("../",$j);
      $foldersP[$i]['absolute']  = $absolute;
      $foldersP[$i]['relative']  = $relative;
      $i++;
      $j--;
    }
    
    switch($method) {
    case self::RELATIVE_PATHS :
      return array_reverse($foldersP);
      break;
    case self::ABSOLUTE_PATHS :
      return $foldersP;
    }
  }

  static public function getRealPath($pathBase) {
    if(!isset($_REQUEST['frm_dir'])) $_REQUEST['frm_dir']='';
    $pathDir = $_SESSION['upload']['current'] = $pathBase;
    $len = strlen($_SESSION['upload']['current'] = realpath($pathDir));
    $real = realpath($pathDir.$_REQUEST['frm_dir']);
    $temp = substr($real,0,$len);
    
    if($_SESSION['upload']['current'] != $temp) {
      header("Location:$urlBase&frm_amerror=diretory_not_exists");
    }else $_SESSION['upload']['current'] = $real;
    
    return $real;
  }

  static public function registerLog($uploadType, $time, $codeAnchor) {
    $log = new AMLogUploadFiles;
    $log->uploadType = $uploadType;
    $log->codeAnchor = $codeAnchor;
    
    try {

      $log->load();
      $log->time = $time;
      $log->state = CMObj::STATE_DIRTY;
      $log->save();
    }catch (CMException $e) {
      $log->time = $time;
      $log->save();
    }
  }
 
}