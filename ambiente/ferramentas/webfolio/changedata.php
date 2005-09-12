<?
include("../../config.inc.php");
include("cmwebservice/cmwemail.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("changeUserData");


$pag = new AMTCadastro();

/*
 *Load language module
 */
    
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;
    

$el["default"] = $_language[pag_0];
$el[pag_1] = $_language[pag_1];
$el[pag_2] = $_language[pag_2];
$ind =  new AMPathIndicator($el);
$ind->setState($_REQUEST[action]);
$pag->setPathIndicator($ind);


//form box to interface
$cadBox = new AMTCadBox("",AMTCadBox::CADBOX_CREATE,AMTCadBox::WEBFOLIO_THEME);

switch($_REQUEST[action]) {

 default:
   if(empty($_SESSION[cad_user])) {
     $_SESSION[cad_user] = clone $_SESSION[user];
     $_SESSION[cad_foto] = new AMUserFoto();
     $_SESSION[cad_foto]->codeArquivo = $_SESSION[cad_user]->foto;
     try {
       $_SESSION[cad_foto]->load();
     }
     catch(CMDBNoRecord $e) {
       $_SESSION[cad_foto] = new AMFoto();
     };
     $_SESSION[cad_foto]=serialize($_SESSION[cad_foto]);
   }
   

   $fields_rec = array("name","datNascimento","email","endereco","codCidade","cep","telefone","historico");
   $form = new AMWSmartForm(AMUser, "cad_user", $_SERVER[PHP_SELF], $fields_rec);
   $form->loadDataFromObject($_SESSION[cad_user]);
         
   
   //$form-setWidgetOrder($fields_rec);
   $form->setDesign(CMWFormEL::WFORMEL_DESIGN_OVER);
   $form->setLabelClass("fontgray");
      
   $form->addOnSubmitAction("email_validate(this['frm_email'])");
      
      
   //campo cidade
   $cidades = $_SESSION[environment]->listaCidades();
   $form->setSelect("codCidade",$cidades,"codCidade","nomCidade");
   $form->components[codCidade]->addOption(0,$_language[escolher_cidade]);

   if(!$form->components[codCidade]->getValue())
     $form->components[codCidade]->setValue(0);

      
   //campo idade
   $form->setDate("datNascimento","d/m/Y",1);
      
   //campo acao
   $form->addComponent("action",new CMWHidden("action","pag_1"));
   $form->submit_label = $_language[next];
   $form->setCancelUrl($_CMAPP[services_url]."/webfolio/webfolio.php");
   //campos da pagina anterior


   $cadBox->add($form);
   $cadBox->setTitle($_language[pag_0]);
   
   break;

 case "pag_1":


   if(!is_a($_SESSION[cad_user],AMUser)) {
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=fatal");
   }

   $_SESSION[cad_user]->loadDataFromRequest();
   

   //tests if the email is a defined internet site
   if(!checkdnsrr(array_pop(explode("@",$_SESSION[cad_user]->email)),"MX")) {
     $_REQUEST[frm_amerror][] = "cannot_contact_email_server";

     //change the string {EMAIL} in the language file key error_cannot_contact_email_server by the user email address
     $_language[error_cannot_contact_email_server] = str_replace("{EMAIL}",$_SESSION[cad_user]->email,$_language[error_cannot_contact_email_server]);
   }


   if(!empty($_FILES[frm_foto])) {
     $_SESSION[cad_foto] = unserialize($_SESSION[cad_foto]);
     try {
       $_SESSION[cad_foto]->loadImageFromRequest("frm_foto");
     }
     catch(AMEImage $e) {
       header("Location:$_SERVER[PHP_SELF]?action=pag_2&frm_amerror=invalid_image_type");
     }

     $view = $_SESSION[cad_foto]->getView();

     $_SESSION[cad_foto]=serialize($_SESSION[cad_foto]);
   }
   else {
     $foto = unserialize($_SESSION[cad_foto]);
     $view = $foto->getView();
   }

   $cadBox->add("<p align=center>");
   $cadBox->add($view);


   //get the image types that are allowed in this installation of gd+php
   $types = AMImage::getValidImageExtensions();

   $cadBox->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
   $cadBox->add("<input type=hidden name=action value=pag_1>");
   $cadBox->add("<p align=center>".$_language[frm_foto]);
   $cadBox->add("&nbsp;".$_language[valid_image_types]." ".implode(", ",$types).".");
   $cadBox->add("<br><input type=file name=frm_foto onChange=\"this.form.submit()\">");
   $cadBox->add("<br><input type=submit onClick=\"this.form['action'].value='pag_2'\" value=\"$_language[conclude]\">");
   $cadBox->add("</form>");

   $cadBox->setTitle($_language[pag_1]);

   break;
 case "pag_2":

   
   if(empty($_SESSION[cad_foto])) {
     header("Location:$_SERVER[PHP_SELF]?action=pag_2&frm_amerror=picture_not_defined");
   }

   $foto = unserialize($_SESSION[cad_foto]);

   if($foto->state==CMObj::STATE_DIRTY) {
     try {
       $foto->save(); 
       $_SESSION[cad_user]->foto = $foto->codeArquivo;
     }
     catch(CMDBException $e) {
       header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_picture");
     }
   }


   try {
     $_SESSION[cad_user]->save();
   }
   catch(CMDBException $e) {
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
   }

   $_SESSION[user] = $_SESSION[cad_user];
   
   unset($_SESSION[cad_user]);
   unset($_SESSION[cad_foto]);
   
   header("Location:$_CMAPP[services_url]/webfolio/webfolio.php?frm_ammsg=data_changed");
   $cadBox->setTitle($_language[pag_2]);

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