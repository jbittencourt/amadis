<?php
class AMChat extends CMObj{  
  private $query, $q;
  var $timeOut=1800;
  
  public function configure() {
     $this->setTable("chat_sala");

     $this->addField("codSala",CMObj::TYPE_INTEGER,20,1,0,1);
     $this->addField("nomSala",CMObj::TYPE_VARCHAR,30,1,0,0);
     $this->addField("desSala",CMObj::TYPE_VARCHAR,60,1,0,0);
     $this->addField("flaPermanente",CMObj::TYPE_VARCHAR,1,1,0,0);
     $this->addField("datInicio",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("datFim",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("tempo",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,11,1,0,0);

     $this->addPrimaryKey("codSala");
  }
 


  function verificaNomeExiste($salao){
    $sql="nomSala='$salao'";
    $q = new CMQuery(AMChat);
    $q->setFilter($sql);
    $res =  $q->execute();


    if ($res->__hasItems()){
      return true;
    }
    else{
      return false;
    }
   
  }


  function isLoggedChat($codUser){
    $sala = $this->codSala;
    $sql = "codSala=$sala AND codUser=$codUser AND flaOnline=1";
    $q = new CMQuery(AMChatConnection);
    $q->setFilter($sql);
    $res = $q->execute();
    if ($res->__hasItems()){
      return TRUE;
    }else{
      return FALSE;
    }
    
  }
  
  function getConnectedUsers() {
    $sql = "codSala=".$this->codSala." AND flaOnline=1";
    $query = new CMQuery(AMChatConnection);
    $query->setFilter($sql);
    $res = $query->execute();
   
    return $res;
  } 


  function sendMessage($codeUser,$to,$txt,$tag,$tempo="") {
    global $_CMAPP;
    include_once("$_CMAPP[path]/lib/amchatmensagem.inc.php");


    $men = new AMChatMensagem();
    $men->codSalaChat = $this->codSala;
 
    if(empty($tempo)) {
      $men->tempo = time();
    }
    else {
      $men->tempo = $tempo;
    };    

    $allow[] ="<br>";
    $allow[] ="$_language";

    $texto_sem_html = strip_tags($txt,"<br> $_language");
      
    $men->codRemetente = $codeUser;
    $men->codDestinatario = $to;
    $men->desMensagem = $texto_sem_html;
    $men->desTag = $tag;

    $men->save();

    $sala = new AMChat();
    $sala->codSala = $this->codSala;
    try {
      $sala->load();
    }
    catch(CMDBNorecord $e){
    echo "<b> a sala nao existe</b>";
    }


    $sala->datFim = $sala->datFim+300;

  $sala->save();

  }




  
  function enterRoom($codUser) {
    $sql = "codUser=".$codUser." AND codSala=".$this->codSala." AND flaOnline=1";
    
    $q = new CMQuery(AMChatConnection);
    $q->setFilter($sql);
    $new = $q->execute();  
    
    
    $new = new AMChatConnection();
    $new->codSala = $this->codSala;
    $new->codUser = $codUser;
    $new->datEntrou = time();
    $new->flaOnline = 1;
    $new->save();
    return $new->codConexao;
     
   
  }
  


  function leaveRoom($codUser) {
 
    $conexao = new AMChatConnection();
    $conexao->codUser = $codUser;
    $conexao->codSala = $this->codSala;
    $conexao->flaOnline=1;
    try{
      $conexao->load();
    }
    catch(CMDBNoRecord $e){
      echo " erro : error not found";
    }
    $conexao->datSaiu = time();
    $conexao->flaOnline = 0;
    try{
      $conexao->save();
    }
    catch(CMDBNoRecord $e){
      echo " erro podre";
    }

    return 0;

  }

  public function getName($tipo,$code){
    
    switch($tipo){
    case "Projeto":
      $sql = "codeProject=$code";
      $q = new CMQuery(AMProjeto);
      $q->setFilter($sql);
      $ret = $q->execute();
      $ret = $ret->items[$code]->title;
      
      break;

    case "Comunidade":
      $sql = "code=$code";
      $q = new CMQuery(AMCommunities);
      $q->setFilter($sql);
      $ret = $q->execute();
      $ret = $ret->items[$code]->name;
      
      break;

    }
    return $ret;
  }



  public function pessoasNaSala(){
    $sql = "codSala=".$this->codSala." AND flaOnline=1";
    $query = new CMQuery(AMChatConnection);
    $query->setFilter($sql);
    $total = $query->execute();
    return count($total->items);
    
  }

  public function getInfo($tipo,$code){
    
    switch($tipo){

    case "Comunidade":
      $sql = "code=$code";
      $q = new CMQuery(AMCommunities);
      $q->setFilter($sql);
      $ret = $q->execute();
      break;

    case "Projeto":
      $sql = "codeProject=$code";
      $q = new CMQuery(AMProjeto);
      $q->setFilter($sql);
      $ret = $q->execute();
      break;

    }
    return $ret;
  }


  function setTimeOut($time) {
    $this->timeOut = $time;
    
  }
  
  function is_user_in_chatroom($user,$salas) {
   
    foreach($salas as $sala){
      $sql  ="codUser=$user AND codSala=".$sala->codSala." AND flaOnline=1 ";
      $query = new CMQuery(AMChatConnection);
      $query->setFilter($sql);
      $res = $query->execute();
    }
    
    if ($res->__hasItens()){//count($res->items)>0){
      return TRUE;
    }
    else{
      return FALSE;
    } 
    


  } 







}  


?>