<?

// Made by Pedro Pimentel  zukunft@gmail.com


include_once("../../config.inc.php");
include_once($_CMDEVEL[path]."/cmwebservice/cmwsmartform.inc.php");


$_language = $_CMAPP[i18n]->getTranslationArray("cad_curso");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;

$pag = new AMTCurso();
$pag->openNavMenu();
$box = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
$box->setWidth("100%");
$box->add("<br>&nbsp;&nbsp;$_language[inicial]");

$tab = new AMColorBox("",AMColorBox::COLOR_BOX_BLUE);
$tab->setWidth("100%");

switch($_REQUEST[acao]) {
  
 default :


   
   if($_REQUEST[erro] == "datas"){
     $tab->add("<center><font color=red size=3>".$_language[erro_datas]."</font></center>");
   }
  
   $requisitados = array("nome","descricao","datInicio","datFim","datInscricaoInicio","datInscricaoFim","flagInscricaoAutomatica");
   $form = new CMWSmartForm(AMCurso,"cad_curso", $_SERVER[PHP_SELF], $requisitados);

   
   
   
   if($_SESSION[curso] instanceof AMCurso) {
     //$_SESSION[curso]->loadDataFromRequest();
     $form->loadDataFromObject($_SESSION[curso]);
   }
   unset($_SESSION[curso]);

   $form->setDesign(CMWFormEL::WFORMEL_DESIGN_OVER);
   $form->setLabelClass("fontgray");
   $form->submit_label="Cadastrar";
   
   $form->components[descricao]->setCols(70);	   
   
   $form->setDate("datInicio","d/m/Y",1);
   $form->components[datInicio]->setCalendarOn();
   
   $form->setDate("datFim", "d/m/Y",1);
   $form->components[datFim]->setCalendarOn();
   
   $form->setDate("datInscricaoInicio", "d/m/Y",1);
   $form->components[datInscricaoInicio]->setCalendarOn();
   
   $form->setDate("datInscricaoFim", "d/m/Y",1);
   $form->components[datInscricaoFim]->setCalendarOn();
   
   $form->addComponent("tempo",new CMWHidden("tempo",time()));

   $form->addComponent("acao", new CMWHidden("acao", "A_cadastrar"));


   $tab->add($form);
   $box->add($tab);
   $pag->add($box);
  
   break;    
   
 case "A_cadastrar":

   

   //se for a primeira tentativa, cria o obejto na sessao
   if(!($_SESSION[curso] instanceof AMCurso)){
     $_SESSION[curso] = new AMCurso();
     $_SESSION[curso]->loadDataFromRequest();
     $_SESSION[curso]->tempo = time();
   }
   
   if($_REQUEST[frm_datInscricaoInicio] > $_REQUEST[frm_datInicio] ||
      $_REQUEST[frm_datInscricaoInicio] >= $_REQUEST[frm_datInscricaoFim] ||
      $_REQUEST[frm_datInicio] >= $_REQUEST[frm_datFim] ||
      $_REQUEST[frm_datInscricaoFim] >= $_REQUEST[frm_datFim]) {
     
     

     
     header("Location:$_SERVER[PHP_SELF]?erro=datas");
     exit;
   }

   // $_SESSION[curso]->loadDataFromRequest();
   $_SESSION[curso]->tempo = time();
   try{
     $_SESSION[curso]->save();
   }
   catch(CMDBQueryError $e){
     echo "erro, nao foi possivel salvar o curso";
   }

//    $up = new AMCurso();
//    $up->tempo = $_SESSION[curso]->tempo;
//    try{
//      $up->load();
//    }
//    catch(CMDBNoRecord $e){
//      echo "nao existe curso com a data indicada";
//    }
   
   $coord = new AMCursoParticipante();
   $coord->codUser = $_SESSION[user]->codeUser;
   $coord->codeCurso = $_SESSION[curso]->codCurso;;
   
   $coord->flagCoordenador = 1;
   $coord->flagAutorizado = 1;
   $coord->tempo = time();
   $coord->matriculado =1;
   try{
     $coord->save();
   }
   catch(CMDBQueryError $e){
     echo " erro ao salvar o coordenador";
   }  



      
   $tab->add("<center><font color=red>"."cadastrado com sucesso"."</font></center>");
   
   
   $pag->add($tab);
	  
 
    


}
//$curso->seObject();

unset($_SESSION[curso]);

echo $pag;





?>