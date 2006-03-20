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
    
    $q = new CMQuery('AMArquivo', 'AMLibraryFiles');

    switch( $tipo ){
    case "img":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'image/%') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
      break;
    case "docs":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime = 'application/msword' OR Arquivo.tipoMime LIKE 'text/%' OR Arquivo.tipoMime = 'application/vnd.sun.xml.writer' OR Arquivo.tipoMime = 'application/pdf') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
      break;    
    case "video":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'video/%'OR Arquivo.tipoMime = 'application/x-shockwave-flash') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
      break;
    case "audio":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'audio/%') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
      break;
    case "outros":
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime NOT LIKE 'audio/%' AND Arquivo.tipoMime NOT LIKE 'video/%' AND Arquivo.tipoMime != 'application/x-shockwave-flash' AND Arquivo.tipoMime NOT LIKE 'image/%' AND Arquivo.tipoMime != 'application/msword' AND Arquivo.tipoMime NOT LIKE 'text/%' AND Arquivo.tipoMime != 'application/vnd.sun.xml.writer' AND Arquivo.tipoMime != 'application/pdf') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
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

    $q = new CMQuery('AMArquivo','AMLibraryFiles');
    
    if($mimeType == "image"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'image/%') AND (FilesLibraries.libraryCode = '$this->code') AND (FilesLibraries.active = 'y')");
    }
    if($mimeType == "text"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime = 'application/msword' OR Arquivo.tipoMime LIKE 'text/%' OR Arquivo.tipoMime = 'application/vnd.sun.xml.writer' OR Arquivo.tipoMime = 'application/pdf') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
    }
    if($mimeType == "audio"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'audio/%') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
    }
    if($mimeType == "video"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'video/%'OR Arquivo.tipoMime = 'application/x-shockwave-flash') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
    }
    if($mimeType == "other"){
      $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime NOT LIKE 'audio/%' AND Arquivo.tipoMime NOT LIKE 'video/%' AND Arquivo.tipoMime != 'application/x-shockwave-flash' AND Arquivo.tipoMime NOT LIKE 'image/%' AND Arquivo.tipoMime != 'application/msword' AND Arquivo.tipoMime NOT LIKE 'text/%' AND Arquivo.tipoMime != 'application/vnd.sun.xml.writer' AND Arquivo.tipoMime != 'application/pdf') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
    }
    $q->setCount();
    $ok = $q->execute();
    return $ok; // ok recebe o numero de resultados para a busca acima.
  }
  
  public function buscaThumbs(){
    global $_CMAPP;

    $q = new CMQuery(AMArquivo,AMLibraryFiles);
    $q->setFilter("(Arquivo.codeArquivo = FilesLibraries.filesCode ) AND (Arquivo.tipoMime LIKE 'image/%') AND (FilesLibraries.libraryCode = '$this->code') AND FilesLibraries.active='y'");
    $q->setOrder('nome');
    $res = $q->execute();    
    if($res->__hasItems()){
      return $res;
    }      
  }
  
  public function saveEntry($ret=true){
    $formName = $_REQUEST['nomeCampo']; // recebe o nome do campo de tipo 'file'
    
    $tipo = explode("/", $_FILES[$formName]['type']);
    
    $file_type =  explode(".",$_FILES[$formName]['name']);
    
    $filelib = new AMLibraryFiles;
    $file = new AMArquivo;
  
  //preenche os capos do arquivo
    $file->nome = $_FILES[$formName]['name'];
    $file->nome = str_replace(" ", "_",$file->nome);
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
    try {
      $file->save();	//salva o arquivo
      $filelib->libraryCode = $this->code;
      $filelib->filesCode = $file->codeArquivo;
      $filelib->time = time();
      $filelib->save();

    }catch(CMException $e){
      die($e->getMessage());
    }  
    //} //aqui fecha o if la de cima, qnd eu testo a ext do arquivo..
    return $ret;
  }//fecha function salva


  public function deleta($id){
    
    $filelib = new AMLibraryFiles;    
    $filelib->filesCode = $id;
    $filelib->load();
    
    if($filelib->referred == "y"){ //soh muda o campo active pra n, pra manter a integridade nas outras ferramentas.
      $filelib->unsetActive();
      $filelib->save(); //nao ta atualizando..diz q ta mas n atualiza
      notelastquery();
    }
    else{ //senao, como ele nao referencia ngm, vai pra banha..

      $file = new AMArquivo;    
      $file->codeArquivo = $id;    
      $file->load();    
      
      $file->delete();//ok!
      $filelib->delete();//nao o reg tira da tabela no bd :/
    }
  }
  public function getLastFiles($limit){
    try{

      $q = new CMQuery('AMArquivo','AMLibraryFiles');
      $q->setFilter("Arquivo.codeArquivo = FilesLibraries.filesCode AND FilesLibraries.libraryCode = '$this->code' AND FilesLibraries.active='y'");
      $q->setLimit(0,$limit);
      $q->setOrder('tempo desc');
      $res = $q->execute();    
      return $res;            
    }catch(CMDBNoRecord $r){
    }
  }

  public function isShared($fileCode){
    $filelib = new AMLibraryFiles;
    $filelib->filesCode = $fileCode;
    try{
    $filelib->load();
    }catch(AMException $e){ return "false"; }

    if($filelib->shared == "y")
      return "true";
    else
      return "false";
  }


  public function listSharedFiles($limit){ //if the limit passed is 0, dont set limit
    try{
      $q = new CMQuery(AMArquivo,AMLibraryFiles);
      $q->setFilter("Arquivo.codeArquivo = FilesLibraries.filesCode AND FilesLibraries.libraryCode = '$this->code' AND FilesLibraries.active='y' and FilesLibraries.shared='y'");
      if($limit > 0)
	$q->setLimit(0,$limit);
      $q->setOrder('tempo desc');
      $res = $q->execute();    
      return $res;            
    }catch(CMDBNoRecord $r){}
  }
}

?>