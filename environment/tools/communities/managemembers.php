<?php
//pedro pimentel 10-06-2005
$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");
$_language = $_CMAPP['i18n']->getTranslationArray("communities");

// error codes
// 0 = Sem codigo no request
// 1 = usuario nao eh administrador da comunidade
// 2 = comunidade nao existente
function errorHandler($tipo){
	$pag = new AMTCommunities;
	switch($tipo){
  case 0:
  	$pag->addScript("window.alert('$_language[error_no_community_id]');location.href='../communities/communities.php'");
  	break;
  case 1:
  	$pag->addScript("window.alert('$_language[not_admin]');location.href='../communities/communities.php'");
  	break;
  case 2:
  	$pag->addScript("window.alert('$_language[error_community_not_exists] !');location.href='../communities/communities.php'");
  	break;
	}
	echo $pag;
	die();
}
if (empty($_REQUEST['frm_codeCommunity'])){
	errorHandler(0);
}
//carrega a comunidade
$comunidade = new AMCommunities;
$comunidade->code = $_REQUEST['frm_codeCommunity'];
try{
	$comunidade->load();
}
catch(CMDBNoRecord $e){
	errorHandler(2);
}
if (!($comunidade->isAdmin($_SESSION['user']->codeUser))){
	errorHandler(1);
}

function load() {
	global $lista, $comunidade;
	switch($lista) {
  case "aceitas":
  	$matriculas = $comunidade->listaMatriculas("aceitas");
  	break;
  case "negadas":
  	$matriculas = $comunidade->listaMatriculas("negadas");
  	break;
  default:
  	$matriculas = $comunidade->listaMatriculas();
	}
	return $matriculas;
}

$lista = $_REQUEST['lista'];
$matriculas = load();
$pag = new AMTCommunities;
$box = new AMBCommunityManageMembers("Gerenciar Usuarios","_lista",3);

switch($lista){
 case "aceitas":
 	$links["negadas"] = "managemembers.php?frm_codeCommunity=$_REQUEST[frm_codeCommunity]&lista=negadas";
 	$links["nao_julgadas"] = "managemembers.php?frm_codeCommunity=$_REQUEST[frm_codeCommunity]";
 	$box->add("<table align=center celspacing=\"0\" celspadding=\"0\" border=\"0\" width=\"500\">");
 	$box->add("<br>");
 	$box->add(new AMDotLine("500"));
 	$box->add("<tr><td class=\"textoint\">$_language[accepted_subscriptions]:</td>");
 	$box->add("<td ><a class=\"cinza\" href=$links[negadas]>$_language[see_not_accepted_subscriptions] </a></td>");
 	$box->add("<td><a  class=\"cinza\"href=$links[nao_julgadas]>$_language[see_not_judged_subscriptions] </a></td>");
 	$box->add("</tr></table>");
 	$box->add(new AMDotLine(500));
 	$pag->add($box);

 	break;
 case "negadas":
 	$links["aceitas"] = "managemembers.php?frm_codeCommunity=$_REQUEST[frm_codeCommunity]&lista=aceitas";
 	$links["nao_julgadas"] = "managemembers.php?frm_codeCommunity=$_REQUEST[frm_codeCommunity]";
 	$box->add("<table align=center celspacing=\"0\" celspadding=\"0\" border=\"0\" width=\"500\">");
 	$box->add("<br>");
 	$box->add(new AMDotLine("500"));
 	$box->add("<tr><td ><a class=\"cinza\" href=$links[aceitas]>$_language[see_accepted_subscriptions] </a></td>");
 	$box->add("<td class=\"textoint\">$_language[not_accepted_subscriptions]:</td>");
 	$box->add("<td><a  class=\"cinza\"href=$links[nao_julgadas]>$_language[see_not_judged_subscriptions] </a></td>");
 	$box->add("</tr></table>");
 	$box->add(new AMDotLine(500));
 	$pag->add($box);

 	break;

 default:
 	$links["aceitas"] = "managemembers.php?frm_codeCommunity=$_REQUEST[frm_codeCommunity]&lista=aceitas";
 	$links["negadas"] = "managemembers.php?frm_codeCommunity=$_REQUEST[frm_codeCommunity]&lista=negadas";
 	$box->add("<table align=center celspacing=\"0\" celspadding=\"0\" border=\"0\" width=\"500\">");
 	$box->add("<br>");
 	$box->add(new AMDotLine("500"));
 	$box->add("<tr><td ><a class=\"cinza\" href=$links[aceitas]>$_language[see_accepted_subscriptions] </a></td>");
 	$box->add("<td><a  class=\"cinza\"href=$links[negadas]>$_language[see_not_accepted_subscriptions] </a></td>");
 	$box->add("<td class=\"textoint\">$_language[not_judged_subscriptions]:</td>");
 	$box->add("</tr></table>");
 	$box->add(new AMDotLine(500));
 	$pag->add($box);

 	break;
}

if ($_REQUEST[acao] == "A_aprovar"){
	$membro = new AMCommunityMembers();
	$membro->codeCommunity = $comunidade->code;
	$membro->codeUser = $_REQUEST['user'];
	$membro->flagAdmin = AMCommunityMembers::ENUM_FLAGADMIN_MEMBER;
	$membro->time = time();
	try{
		$membro->save();
	}catch(CMDBQueryError $e){
		echo "erro salvando usuario" ;
	}
	unset($membro);
	$membro = new AMCommunityMemberJoin();
	$membro->codeCommunity = $comunidade->code;
	$membro->codeUser = $_REQUEST[user];
	try{
		$membro->load();
	}catch(CMDBNoRecord $e){
		echo "usuario nao foi pre cadastrado";
	}
	$membro->status = AMCommunityMemberJoin::ENUM_STATUS_ACCEPTED;
	try{
		$membro->save();
	}catch(CMDBQueryError $e){
		echo " nhaco" ;
	}
	$pag->addError($_language['user_added']);
	unset($matriculas);
	$matriculas = load();
}

if ($_REQUEST['acao'] == "rejeitar") {
	$membro = new AMCommunityMemberJoin();
	$membro->codeCommunity = $comunidade->code;
	$membro->codeUser = $_REQUEST[user];
	try{
		$membro->load();
	}catch(Exception $e){
		echo "Usuario nao estah na tabela join" ;
	}
	$membro->status = AMCommunityMemberJoin::ENUM_STATUS_REJECTED;
	try{
		$membro->save();
	}catch(CMDBQueryError $e){
		echo "erro salvando usuario";
	}
	unset($membro);
	$membro = new AMCommunityMembers();
	$membro->codeCommunity = $comunidade->code;
	$membro->codeUser = $_REQUEST[user];
	try{
		$membro->load();
	}catch(Exception $e){

	}
	try{
		$membro->delete();
	}catch(Exception $e){
		echo "erro deletando da tabela de membros";
	}

	unset($membro);
	unset($matriculas);
	$matriculas = load();

	$pag->addError($_language['user_rejected']);
}


$box->add("<table align=center celspacing=\"0\" celspadding=\"0\" border=\"0\" width=\"500\">");
if (empty($matriculas->items)) {
	$box->add("<tr><td rowspan=2 class=\"textoint\"><b>$_language[no_users_waiting]</b> </td></tr> ");
}
else{
	$box->add("<tr><td class=\"textoint\"><b>$_language[user_name]</b> </td> <td class=\"textoint\"><b>$_language[action]</b></td></tr> ");
	foreach ($matriculas as $matricula) {
		$user = new AMUser();
		$user->codeUser = $matricula->codeUser;
		try{
			$user->load();
		}
		catch(CMDBNoRecord $e){
			echo " usuario nao exist" ;
		}

		$aprovado = "<form method=post action=\"managemembers.php\" name=\"altera".$user->codeUser."\">";
		$aprovado .= "<input type=hidden name=\"frm_codeCommunity\" value=\"$_REQUEST[frm_codeCommunity]\">";
		$aprovado .= "<input type=hidden name=\"user\" value=\"".$user->codeUser."\">";
		$aprovado .= "<select name=\"acao\" onChange=\"document.altera".$user->codeUser.".submit();\">";

		try{
			if ($matricula->community->items[0]->flagAdmin==AMCommunityMembers::ENUM_FLAGADMIN_MEMBER) {
				$aprovado .= "<option value=\"A_aprovar\">$_language[aprovado]</option>";
				$aprovado .= "<option value=\"rejeitar\">$_language[rejeitado]</option>";
			}
		}
		catch(Exception $e){
			if ($matricula->users->items[0]->status==AMCommunityMemberJoin::ENUM_STATUS_NOT_ANSWERED) {
				$aprovado .= "<option value=\"nada\">aguardando</option>";
				$aprovado .= "<option value=\"A_aprovar\">$_language[aprovado]</option>";
				$aprovado .= "<option value=\"rejeitar\">$_language[rejeitado]</option>";
			}
			if ($matricula->users->items[0]->status==AMCommunityMemberJoin::ENUM_STATUS_REJECTED){
				$aprovado .= "<option value=\"rejeitar\">$_language[rejeitado]</option>";
				$aprovado .= "<option value=\"A_aprovar\">$_language[aprovado]</option>";
			}
		}
		$aprovado .= "</select></form>";

		$box->add("<tr><td class=\"textoint\">");
		$box->add(new AMTUserInfo($user,"",TRUE));
		$box->add("</td><td>$aprovado</td></tr>");

	}
}
$box->add("</table>");

echo $pag;