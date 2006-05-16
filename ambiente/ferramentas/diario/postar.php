<?
/**
 * Post a entry in the diary
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @category AMVisualization
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMDiarioPost, AMDiarioComentario
 */

include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("diary");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;

$pag = new AMTDiario();


if(!empty($_REQUEST[frm_action])) {
  switch($_REQUEST[frm_action]) {
  case "A_post":
    $post = new AMDiarioPost;

    if(!empty($_REQUEST[frm_codePost])) {
      $post->codePost = $_REQUEST[frm_codePost];
      try {
	$post->load();
      }catch(CMObjException $e) {
	$pag->addError($_language[error_post_cannot_be_edited]);
      }
    } else {
      $post->tempo = time();
      $post->codeUser = $_SESSION[user]->codeUser;
    }
    $post->loadDataFromRequest(); 
    $post->texto = stripslashes($_REQUEST['frm_texto']);
    try{
      $post->save();
      header("Location: diario.php?frm_ammsg=post_success"); 
    }catch(CMObjException $e) {
      $pag->addError($_language[error_post_cannot_be_edited]); 
    }
    break;

  case "editar":
    if(!empty($_REQUEST[frm_codePost])) {
      $editar = new AMDiarioPost;
      $editar->codePost=$_REQUEST[frm_codePost];
      try {
	$editar->load();
      }catch (CMDBNoRecord $exception) {
	$pag->addError($_language[post_not_exists]);
	echo $pag;
      }
    }
   
    
    break;
  }
}


//usando o CMWSmartForm - gerador de formularios
//a partir de uma classe de persistencia extendido
//ao CMObj
//CMWSmartForm(classe, nome_form, action, campos requisitados);
$campos_requisitados = array("titulo","texto");
$form = new AMWSmartForm(AMDiarioPost, "cad_post", $_SERVER[PHP_SELF],$campos_requisitados,array('codePost'));
if(!empty($editar)) {
  $form->loadDataFromObject($editar);
}

$form->submit_label = "Publicar";
$form->setCancelUrl("diario.php?frm_type=$_REQUEST[frm_type]&frm_codeProjeto=$_REQUEST[frm_codeProjeto]");
$form->components[texto]->setCols(50);
$form->components[texto]->setRows(5);
$form->addComponent("frm_action", new CMWHidden("frm_action","A_post"));
$form->setLabelClass("titpost");
$form->setRichTextArea("texto");
$form->setDesign(CMWFormEl::WFORMEL_DESIGN_OVER);   // muda as labels do smart form



$url = $_CMAPP[images_url];
$pag->add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=20 height=20>");
$pag->add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"500\">");
$pag->add("<tr>");
$pag->add("<td width=\"20\"><img src=\"$url/box_diario_01.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
$pag->add("<td background=\"$url/box_diario_bgtop.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
$pag->add("<td width=\"20\"><img src=\"$url/box_diario_02.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
$pag->add("</tr>");
$pag->add("<tr>");
$pag->add("<td background=\"$url/box_diario_bgleft.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
$pag->add("<td bgcolor=\"#FAFBFB\" valign=\"top\">");

$pag->add("<!-- cabeçalho do diario -->");
$pag->add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">");
$pag->add("<tr>");
$pag->add("<td width=\"87\"><img src=\"$url/box_diario_logo_postar.gif\" border=\"0\"></td>");
$pag->add("<td width=\"20\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
$pag->add("<td valign=\"top\"><br><font class=\"titdiario\">$_language[post_diary]</font><br>");

$pag->add("</td>");
$pag->add("<td width=\"20\"><img src=\"$url/dot.gif\" width=\"20\" height=\"10\" border=\"0\"></td>");
$pag->add("</tr>");
$pag->add("</table>");


$pag->add($form);

$pag->add("</td>");
$pag->add("<td background=\"$url/box_diario_bgrigth.gif\"><img src=\"$url/dot.gif\" width=\"20\" height=\"18\" border=\"0\"></td>");
$pag->add("</tr>");

//fim cabeçalho do diario
$pag->add("<tr>");
$pag->add("<td><img src=\"$url/box_diario_05.gif\" width=\"20\" height=\"20\" border=\"0\"></td>");
$pag->add("<td bgcolor=\"#F2F2FE\"><img src=\"$url/dot.gif\" width=\"20\" height=\"20\" border=\"0\"></td>");
$pag->add("<td><img src=\"$url/box_diario_06.gif\" width=\"20\" height=\"20\" border=\"0\"></td>");

$pag->add("</tr>");

$pag->add("<!-- fim corpo do diario -->");
$pag->add("</td>");

    
$pag->add("</tr>");
$pag->add("</table>");

$pag->add("<br> <a href=\"diario.php\" ><img src=\"$url/diario_btvoltar.gif\" border=\"0\" ></a><br><br>");



echo $pag;

?>