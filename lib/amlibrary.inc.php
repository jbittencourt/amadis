<?
class AMLibrary extends CMObj{

  public function configure(){
    $this->setTable("Library");
    $this->addField("code",CMObj::TYPE_INTEGER,11,1,0,1);
    $this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addPrimaryKey("code");
  }
  
  public function setLibrary($lib){
    $this->code = $lib;    
  }
  public function busca($tipo){    
    
    $q = new CMQuery(AMArquivo, AMLibraryFiles);

    switch( $tipo ){
    case "img":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'image/%') AND (FilesLibraries.libraryCode = '$this->code')");
      break;
    case "docs":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime = 'application/msword' OR Arquivo.tipoMime LIKE 'text/%' OR Arquivo.tipoMime = 'application/vnd.sun.xml.writer' OR Arquivo.tipoMime = 'application/pdf') AND (FilesLibraries.libraryCode = '$this->code')");
      break;    
    case "video":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'video/%'OR Arquivo.tipoMime = 'application/x-shockwave-flash') AND (FilesLibraries.libraryCode = '$this->code')");
      break;
    case "audio":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'audio/%') AND (FilesLibraries.libraryCode = '$this->code')");
      break;
    case "outros":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime NOT LIKE 'audio/%' AND Arquivo.tipoMime NOT LIKE 'video/%' AND Arquivo.tipoMime != 'application/x-shockwave-flash' AND Arquivo.tipoMime NOT LIKE 'image/%' AND Arquivo.tipoMime != 'application/msword' AND Arquivo.tipoMime NOT LIKE 'text/%' AND Arquivo.tipoMime != 'application/vnd.sun.xml.writer' AND Arquivo.tipoMime != 'application/pdf') AND (FilesLibraries.libraryCode = '$this->code')");
      break;
    default:
      break;
    } //fecha switch
    $q->setOrder('nome');
    $res = $q->execute();
    if($res->__hasItems()){
      return $res;
    }
  }// fecha funcao
  
  public function countBooks($mimeType){

    $q = new CMQuery(AMArquivo,AMLibraryFiles);
    
    if($mimeType == "image"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'image/%') AND (FilesLibraries.libraryCode = '$this->code')");
    }
    if($mimeType == "text"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime = 'application/msword' OR Arquivo.tipoMime LIKE 'text/%' OR Arquivo.tipoMime = 'application/vnd.sun.xml.writer' OR Arquivo.tipoMime = 'application/pdf') AND (FilesLibraries.libraryCode = '$this->code')");
    }
    if($mimeType == "audio"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'audio/%') AND (FilesLibraries.libraryCode = '$this->code')");
    }
    if($mimeType == "video"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'video/%'OR Arquivo.tipoMime = 'application/x-shockwave-flash') AND (FilesLibraries.libraryCode = '$this->code')");
    }
    if($mimeType == "other"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime NOT LIKE 'audio/%' AND Arquivo.tipoMime NOT LIKE 'video/%' AND Arquivo.tipoMime != 'application/x-shockwave-flash' AND Arquivo.tipoMime NOT LIKE 'image/%' AND Arquivo.tipoMime != 'application/msword' AND Arquivo.tipoMime NOT LIKE 'text/%' AND Arquivo.tipoMime != 'application/vnd.sun.xml.writer' AND Arquivo.tipoMime != 'application/pdf') AND (FilesLibraries.libraryCode = '$this->code') "); 
    }
    $q->setCount();
    $ok = $q->execute();
    return $ok; // ok recebe o numero de resultados para a busca acima.
  }
  
  public function buscaThumbs(){
    global $_CMAPP;
    $q = new CMQuery(AMArquivo,AMLibraryFiles);
    $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'image/%') AND (FilesLibraries.libraryCode = '$this->code')");
    $q->setOrder('nome');
    $res = $q->execute();    
    if($res->__hasItems()){
      return $res;
    }      
  }
  
  public function saveEntry(){
    $formName = $_REQUEST[nomeCampo]; // recebe o nome do campo de tipo 'file'
    $tipo = explode("/", $_FILES[$formName]['type']);
    $file_type =  explode(".",$_FILES[$formName]['name']);
    $bad_ext = array("exe","bin","php","sh","com");  //lista de arquivos indevidos..

    if( in_array($file_type[1] , $bad_ext) ){ // se o arquivo a ser enviado possui uma extensao nao permitida..
      //echo "Ops, tamanho ou tipo de arquivo invalido.<br>";
    }
    else{ //ta ok..pode enviar
      
      $file = new AMArquivo;
      $filelib = new AMLibraryFiles;
      
      $file->nome = $_FILES[$formName]['name'];
      $file->tipoMime = $_FILES[$formName]['type'];
      $file->tamanho = $_FILES[$formName]['size'];
      $file->tempo = time();
      
      if($_FILES[$formName]['tmp_name'] == "")
	return false;

      $file->dados  = implode("",file($_FILES[$formName]['tmp_name']));
      
      $d = $file->dados;
      if(empty($d)) {
	return false;
      }
      try{
	$file->save();	//salva o arquivo

	$filelib->libraryCode = $this->code;
	$filelib->filesCode = $file->codeArquivo;
	$filelib->save();
      }catch(CMException $e){
	die($e->getMessage());
      }  
    }
    return true;
  }//fecha function salva


  public function deleta($id){
    $file = new AMArquivo;
    
    $file->codeArquivo = $id;
    
    $filelib = new AMLibraryFiles;
    
    $filelib->filesCode = $id;

    $filelib->load();    
    $file->load();    

    $file->delete();
    $filelib->delete();

  }
  public function getLastFiles($limit){
    try{
      $q = new CMQuery(AMArquivo,AMLibraryFiles);
      $q->setFilter("Arquivo.codeArquivo = FilesLibraries.filesCode AND FilesLibraries.libraryCode = '$this->code'");
      $q->setLimit('','$limit');
      $q->setOrder('tempo desc');
      $res = $q->execute();    
      return $res;            
    }catch(CMDBNoRecord $r){
    }
  }
}

?>