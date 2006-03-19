<?php
// Pedro Pimentel - 22-05-2005
include("../../config.inc.php");
$_language = $_CMAPP[i18n]->getTranslationArray("community_create");

$pag = new AMTUpdateCommunity();   
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;

      
$el["default"] = $_language[pag_0];
$el[pag_1] = $_language[pag_1];
$el[pag_2] = $_language[pag_2];
$ind =  new AMPathIndicator($el);
$ind->setState($_REQUEST[action]);
$pag->setPathIndicator($ind);




// error codes
// 0 = Sem codigo no request
// 1 = usuario nao eh administrador da comunidade
// 2 = comunidade nao existente
function errorHandler($tipo,$_language){
  $pag = new AMTCommunities();  
  switch($tipo){
  case 0:
    $pag->addScript("window.alert('".$_language[error_no_community_id]."');location.href='../communities/communities.php'");
    break;
  case 1:
    $pag->addScript("window.alert('".$_language[not_admin]."');location.href='../communities/communities.php'");
    break;
  case 2:
    $pag->addScript("window.alert('".$_language[error_community_not_exists]."');location.href='../communities/communities.php'");
    break;
  }
  echo $pag;
  die();
}

if (empty($_REQUEST[frm_codeCommunity])){
  errorHandler(0,$_language);
}
else{
  //carrega o objeto na sessao para poder recarregar ele no formulario
  
  if($_SESSION[atu_community] instanceof AMCommunities) {
    $community = new AMCommunities();
    $community->code = $_REQUEST[frm_codeCommunity];
    try{
      $community->load();
    }
    catch(CMDBNoRecord $e){
      errorHandler(2,$_language);
    }
    $_SESSION[atu_community] = new AMCommunities();
    $_SESSION[atu_community] = $community;

    //verificar se usuario q estah alterando a comunidade eh COORDENADOR ou nao
    if(!$_SESSION[atu_community]->isAdmin($_SESSION[user]->codeUser)){

      errorHandler(1,$_language);
    }
  }
}


//form box to interface
$cadBox = new AMTCommunityCadBox("", AMTCadBox::COMMUNITY_THEME);

switch($_REQUEST[action]) {

 default:

   $fields_rec = array("name","description","flagAuth");
      
   //formulary
   $form = new AMWSmartForm(AMCommunities,"atu_community",$_SERVER[PHP_SELF]."?frm_codeCommunity=$_REQUEST[frm_codeCommunity]",$fields_rec);
   
   $form->setWidgetOrder($fields_rec);

  
   if($_SESSION[atu_community] instanceof CMObj) {
     $form->loadDataFromObject($_SESSION[atu_community]);
   }

   $flagAuth = array(AMCommunities::ENUM_FLAGAUTH_ALLOW=>"$_language[public]",
		     AMCommunities::ENUM_FLAGAUTH_REQUEST=>"$_language[moderate]"
		     );
   
   $form->setRadioGroup("flagAuth",$flagAuth);
   //$form->components[flagAuth]->setValue(AMCommunities::ENUM_FLAGAUTH_ALLOW);
   
   $form->addComponent("action",new CMWHidden("action","pag_1"));

   
   $descrip = new CMWTextArea("frm_description", 5, 35);
   $form->addComponent("description",$descrip);
   if($_SESSION[atu_community] instanceof CMObj) {
     $form->loadDataFromObject($_SESSION[atu_community]);
   }

   $cadBox->add($form);
   $cadBox->setTitle($_language[general_data]);
   
   break;
      
 case "pag_1":

   if(!($_SESSION[atu_community] instanceof AMCommunities)) {
     //

     $_SESSION[atu_community]->loadDataFromRequest();
     $_SESSION[atu_community]->time = time();
   }
   else {

     $_SESSION[atu_community]->loadDataFromRequest();
   }

   if(!empty($_FILES[frm_image])) {
     unset($_SESSION[cad_image]);
     $_SESSION[cad_image] = new AMCommunityImage;
     try {
       $_SESSION[cad_image]->loadImageFromRequest("frm_image");
     }
     catch(AMEImage $e) {
       header("Location:$_SERVER[PHP_SELF]?action=pag_1&frm_amerror=invalid_image_type");
     }

     $view = $_SESSION[cad_image]->getView();
     $cadBox->add("<p align=center>");
     $cadBox->add($view);
     $_SESSION[img_mod] = TRUE;
     $_SESSION[cad_image]=serialize($_SESSION[cad_image]);
   }//se imagem foi mandada, carregar ela
   else{

     $cadBox->add("<p align=center>");
     $_SESSION[cad_image] = new AMTCommunityImage($_SESSION[atu_community]->image);
     $cadBox->add($_SESSION[cad_image], AMTwoColsLayout::LEFT);
     $_SESSION[cad_image]=serialize($_SESSION[cad_image]);
}
   //get the image types that are allowed in this installation of gd+php
   $types = AMImage::getValidImageExtensions();

   $cadBox->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]?frm_codeCommunity=$_REQUEST[frm_codeCommunity]\" enctype=\"multipart/form-data\">");
   $cadBox->add("<input type=hidden name=action value=pag_1>");
   $cadBox->add("<p align=center>".$_language[frm_image]);
   $cadBox->add("&nbsp;".$_language[valid_image_types]." ".implode(", ",$types).".");
   $cadBox->add("<br><input type=file name=frm_image onChange=\"this.form.submit()\">");
   $cadBox->add("<br><input type=submit onClick=\"this.form['action'].value='pag_2'\" value=\"$_language[next]\">");
   $cadBox->add("</form>");

   $cadBox->setTitle($_language[community_pic]);


   break;

 case "pag_2":


   if ($_SESSION[img_mod]){
     $foto = unserialize($_SESSION[cad_image]);
     $foto->tempo = time();
     try {
       $foto->save();
     }
     catch(CMDBQueryError $e) {
       header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_picture");
     }
     
     $_SESSION[atu_community]->image = $foto->codeArquivo;
     unset($_SESSION[cad_image]);
     unset($_SESSION[img_mod]);
   } 

   //salva a comunidade
   try {
     $_SESSION[atu_community]->save();
   }
   catch(CMDBException $e) {
     if(!empty($foto)) {
       $foto->delete();
     }
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
   }
   catch(AMException $e) {
     if(!empty($foto)) {
       $foto->delete();
     }
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=creating_user_dir");
   }
   

   $cod = $_SESSION[atu_community]->code;
   unset($_SESSION[atu_community]);
   unset($_SESSION[cad_image]);
   unset($_SESSION[amadis][communities]);


   header("Location: $_CMAPP[services_url]/communities/communities.php?frm_codeCommunity=$cod&frm_ammsg=community_updated");
   
   break;

 case "fatal_error":
   //No caso de um erro fatal.
   //A mensagem de erro e exibida pelo proprio template AMMain.
   $cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
   break;
}
   
$pag->add($cadBox);
echo $pag;








?>