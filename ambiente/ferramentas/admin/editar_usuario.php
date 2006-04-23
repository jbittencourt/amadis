<?

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

//soh imprime se o usuario for super
//if ($_SESSION[usuario]->eMembroCategoria(ADMINISTRADOR_PLATAFORMA)) {
$pag->add("<br><br>");

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
      $f = $item->foto;
      if($f!=0) {
	$pag->add($conteudo);
	$thumb = new AMUserThumb;
	$thumb->codeArquivo = $item->foto;
	try {
	  $thumb->load();
	  $pag->add($thumb->getView());
	}
	catch(CMDBException $e) {
	  echo $e; die();
	}
      }
      else {
	$pag->add($conteudo);
	$pag->add("&nbsp;");			
      }    
      $pag->add("</td><td>");
      $pag->add(new AMTUserInfo($item));
      $conteudo = "</td>";
      $conteudo .= "<td width='90'>".AMMain::getChangePwButton($item->codeUser);
      $conteudo .= "<td width='90'>".AMMain::getChangeStatusButton($item->codeUser);
      $conteudo .= "<td width='90'>".AMMain::getSendNotificationButton($item->codeUser);
      $conteudo .= "</tr>";    
    }
  }
  else{
    $conteudo .=  $_language['no_users_with_initial'];
  }
}
$conteudo .= "</tr></table>";
$pag->add($conteudo);


//}

//else die($lang[acesso_nao_permitido]);

echo $pag; 

?>