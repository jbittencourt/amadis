<?

// Pedro Pimentel - 22/02/2005 - zukunft@gmail.com

class AMCurso extends CMObj {

   public function configure() {
     $this->setTable("Cursos");

     $this->addField("codCurso",CMObj::TYPE_INTEGER,11,1,0,1);
     $this->addField("nome",CMObj::TYPE_VARCHAR,59,1,0,0);
     $this->addField("flagInscricaoAutomatica",CMObj::TYPE_VARCHAR,1,1,0,0);
     $this->addField("descricao",CMObj::TYPE_TEXT,65535,1,0,0);
     $this->addField("datInicio",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("datFim",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("datInscricaoInicio",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("datInscricaoFim",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("tempo",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addPrimaryKey("codCurso");
     
  }
  function listaMatriculas($lista=""){
    global $_CMAPP;
    include_once($_CMAPP[path]."/lib/amcursoparticipante.inc.php");
    switch($lista){

    case "naojulgadas":
      $sql ="codeCurso=".$this->codCurso." AND flagAutorizado=1 AND matriculado=0";
      $query = new CMQuery(AMCursoParticipante);
      $query->setFilter($sql);
      $ret = $query->execute();
      break;

    case "negadas":
      $sql="codeCurso=".$this->codCurso." AND flagAutorizado=2";
      $query = new CMQuery(AMCursoParticipante);
      $query->setFilter($sql);
      $ret = $query->execute();
      break;

      //default = lista todas matriculas ATIVAS
    default:
      $sql = "codeCurso=".$this->codCurso." AND matriculado=1 AND flagAutorizado=1";
      $query = new CMQuery(AMCursoParticipante);
      $query->setFilter($sql);
      $ret = $query->execute();
      break;
    }

    return $ret;

  }




  function eCoordenador($codUser) {
//     global $_CMAPP;
//     include_once($_CMAPP[path]."/lib/amcursoparticipante.inc.php");
    $coord = new AMCursoParticipante();
    $coord->codeCurso = $this->codCurso;
    $coord->codUser = $codUser;
    try{
      $coord->load();
    }
    catch(CMDBNoRecord $e){
      
    }
    if($coord->flagCoordenador=="1")
      return 1; // 1 se for coordenador
    else
      return 0; //0 se nao for
  }


  function getMatricula($coduser){
    global $_CMAPP;
    include_once($_CMAPP[path]."/lib/amcursoparticipante.inc.php");
    $mat = new AMCursoParticipante();
    $mat->codeCurso = $this->codCurso;
    $mat->codUser = $codUser;
    try{
      $mat->load();
    }
    catch(CMDBNoRecord $e){
      return 0; 
    }
   
    return 1;   //aluno matriculado

  }




}

?>
