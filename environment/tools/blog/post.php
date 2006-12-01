<?php
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

$_language = $_CMAPP['i18n']->getTranslationArray("blog");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

$pag = new AMTBlog();


if(array_key_exists('frm_action', $_REQUEST)) {
	switch($_REQUEST[frm_action]) {
		case "A_post":
			$post = new AMBlogPost;

			if(!empty($_REQUEST['frm_codePost'])) {
				$post->codePost = $_REQUEST[frm_codePost];
				try {
					$post->load();
				}catch(CMObjException $e) {
					$pag->addError($_language['error_post_cannot_be_edited']);
				}
			} else {
				$post->time = time();
				$post->codeUser = $_SESSION['user']->codeUser;
			}
			$post->loadDataFromRequest();
			$post->body = stripslashes($_REQUEST['frm_body']);
			try{
				$post->save();
				header("Location: blog.php?frm_ammsg=post_success");
			}catch(CMObjException $e) {
				$pag->addError($_language['error_post_cannot_be_edited']);
			}
			break;

		case "editar":
			if(!empty($_REQUEST['frm_codePost'])) {
				$editar = new AMBlogPost;
				$editar->codePost=$_REQUEST['frm_codePost'];
				try {
					$editar->load();
				}catch (CMDBNoRecord $exception) {
					$pag->addError($_language['post_not_exists']);
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
$campos_requisitados = array("title","body");
$form = new AMWSmartForm('AMBlogPost', "cad_post", $_SERVER['PHP_SELF'],$campos_requisitados,array('codePost'));
if(!empty($editar)) {
	$form->loadDataFromObject($editar);
}

$form->submit_label = "Publicar";
$form->setCancelUrl("blog.php");
$form->components['body']->setCols(50);
$form->components['body']->setRows(5);
$form->addComponent("frm_action", new CMWHidden("frm_action","A_post"));
$form->setLabelClass("titpost");
$form->setRichTextArea("body");
$form->setDesign(CMWFormEl::WFORMEL_DESIGN_OVER);   // muda as labels do smart form



$url = $_CMAPP['images_url'];
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
$pag->add("<td valign=\"top\"><br><font class=\"titdiario\">$_language[post_blog]</font><br>");

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

$pag->add("<br> <a href=\"blog.php\" ><img src=\"$url/diario_btvoltar.gif\" border=\"0\" ></a><br><br>");



echo $pag;
?>