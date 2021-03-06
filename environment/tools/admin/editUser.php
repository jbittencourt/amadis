<?php

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

//soh imprime se o usuario for super
//if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {
$pag->add("<br /><br />");

$box = new AMBAdminAlfabeto;

$pag->add($box);

$letra = $_REQUEST['initial'];

if($letra != ""){
	$q = new CMQuery("AMUser");
  	if(strlen($letra) == 1){ 
    	$q->setFilter("name LIKE '$letra%'");
  	}
  	$res = $q->execute();
  
  	$conteudo .= "<table>";
  	if($res->__hasItems()){
    	foreach($res as $item){
      		$conteudo .= "<tr>"; 
      		$conteudo .= "<td width = '80'>";

			$pag->add($conteudo);
      		$thumb = new AMUserThumb;
      		$thumb->codeFile = (integer) $item->picture;
      		try {
				$thumb->load();
				$pag->add($thumb->getView());
      		} catch(CMDBException $e) {
				new AMLog('editUser.php thumb->load', $e, AMLog::LOG_CORE);
      		}

      		$pag->add("</td><td>");
      		$pag->add(new AMTUserInfo($item));
      		$conteudo = "</td>";
      		$conteudo .= "<td width='90'>".AMMain::getChangePwButton($item->codeUser);
      		$conteudo .= "<td width='90'>".AMMain::getChangeStatusButton($item->codeUser);
      		$conteudo .= "<td width='90'>".AMMain::getSendNotificationButton($item->codeUser);
      		$conteudo .= "</tr>";    
    	}
  	} else {
    	$conteudo .=  $_language['no_users_with_initial'];
  	}
}
$conteudo .= "</tr></table>";
$pag->add($conteudo);

echo $pag;