<?

// Pedro Pimentel - 22/02/2005 - zukunft@gmail.com

include_once("../../config.inc.php");

include_once($_CMDEVEL[path]."/cmwebservice/cmwsmartform.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("atualizar_curso");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;


$pag = new AMTCurso();
$pag->openNavMenu();





$tab = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
$tab->add("Cadastro de Curso <br>");

  switch($_REQUEST[acao]) {
  
  default :

    if($_REQUEST[erro] == "datas"){
      $tab->add("<center><font color=red size=3>Erro nas Datas</font></center>");
    }

    unset($_SESSION[curso]);

    $curso = new AMCurso();
    $curso->codCurso = $_REQUEST[frm_codCurso];
    try{
      $curso->load();
    }
    catch(CMDBNoRecord $e){
      $pag->addError("Erro inesperado");
    }

    
    $coordenador = $curso->eCoordenador($_SESSION[user]->codeUser);
    
    if (!$coordenador){
      
      $pag->addScript("window.alert('Voce nao eh coordenador');location.href='cursos.php'");
      echo $pag;
    } 




    $requisitados = array("nome","descricao","datInicio","datFim","datInscricaoInicio","datInscricaoFim","flagInscricaoAutomatica");        

    $form = new CMWSmartForm(AMCurso,"cad_curso", $_SERVER[PHP_SELF]."?frm_codCurso=$_REQUEST[frm_codCurso]", $requisitados);
    $form->setDesign(CMWFormEL::WFORMEL_DESIGN_OVER);
    $form->setLabelClass("fontgray");
    $form->setCancelOff();
    $form->submit_label="Atualizar";

    $form->components[descricao]->setCols(70);

    $form->setDate("datInicio","d/m/Y",1);
    $form->components[datInicio]->setCalendarOn();
    
    $form->setDate("datFim", "d/m/Y",1);
    $form->components[datFim]->setCalendarOn();
    
    $form->setDate("datInscricaoInicio", "d/m/Y",1);
    $form->components[datInscricaoInicio]->setCalendarOn();

    $form->setDate("datInscricaoFim", "d/m/Y",1);
    $form->components[datInscricaoFim]->setCalendarOn();
    
    $form->addComponent("acao",new CMWHidden("acao", "A_cadastrar"));
    $form->addComponent("tempo", new CMWHidden("tempo", time())) ;


    $form->loadDataFromObject($curso);
    
    $tab->add($form);
    $pag->add("<br>");
    $pag->add($tab);


    break;
 
  case "A_cadastrar":

    if($_REQUEST[frm_datInscricaoInicio] >= $_REQUEST[frm_datInicio] ||
       $_REQUEST[frm_datInscricaoInicio] >= $_REQUEST[frm_datFim] ||
       $_REQUEST[frm_datInscricaoInicio] >= $_REQUEST[frm_datInscricaoFim] ||
       $_REQUEST[frm_datInicio] >= $_REQUEST[frm_datFim] ||
       $_REQUEST[frm_datInscricaoFim] >= $_REQUEST[frm_datFim]) {
      header("Location:$_SERVER[PHP_SELF]?frm_codCurso=$_REQUEST[frm_codCurso]&erro=datas");
      exit;
    }




    $_SESSION[curso] = unserialize($_SESSION[curso]);
    
    
    $curso = new AMCurso();
    $curso->codCurso = $_REQUEST[frm_codCurso];
    try{
      $curso->load();
    }
    catch(CMDBNoRecord $e){
      $pag->addError("erro Bizarro");
    }
    
    
    $curso->nome = $_REQUEST[frm_nome];
    $curso->flagInscricaoAutomatica = $_REQUEST[frm_flagInscricaoAutomatica];
    $curso->descricao = $_REQUEST[frm_descricao];
    $curso->datInicio = $_REQUEST[frm_datInicio];
    $curso->datFim = $_REQUEST[frm_datFim];
    $curso->datInscricaoInicio = $_REQUEST[frm_datInscricaoInicio];
    $curso->datInscricaoFim = $_REQUEST[frm_datInscricaoFim];
    



    try{
      $curso->save();
    }
    catch(CMDBQueryError $e){
      $pag->addError("Nao pude atualizar o curso");
    }
 
    $tab->add("<div class=fontgray align=center>Atualizado com Sucesso");
    $tab->add("<br></div>");
    $pag->add($tab);

    break;

  

}
echo $pag;


?>