<?php
/**
 * This file register a new user to the environment.
 *
 * LICENSE: Licensed under GPL
 *
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    $Id$
 * @since      File available since Release 1.2.0
 * @author     Juliano Bittencourt <juliano@lec.ufrgs.br>
 */

$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");

if(!empty($_SESSION['user'])) {
  Header("Location: $_CMAPP[url]/index.php?frm_amerror=cannot_register_logged");
}

$_language = $_CMAPP['i18n']->getTranslationArray("register");


$pag = new AMTCadastro();

/*
 *Load language module
 */
    
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;
    

$el['default'] = $_language['pag_0'];
$el['pag_1'] = $_language['pag_1'];
$el['pag_2'] = $_language['pag_2'];
$el['pag_3'] = $_language['pag_3'];
$ind =  new AMPathIndicator($el);
if(array_key_exists('action', $_REQUEST)) {
	$ind->setState($_REQUEST['action']);	
}
$pag->setPathIndicator($ind);

$cadBox = new AMTCadBox("",AMTCadBox::CADBOX_CREATE,AMTCadBox::WEBFOLIO_THEME);

//form box to interface
if(!isset($_REQUEST['action'])) $_REQUEST['action'] = "";

switch($_REQUEST['action']) {

 default:
   $fields_rec = array("username");
      
   //formulary
   $form = new AMWSmartForm('AMUser',"cad_user",$_SERVER['PHP_SELF'],$fields_rec);

   if(array_key_exists('cad_user', $_SESSION) and 
   	 $_SESSION['cad_user'] instanceof AMUser) {
     $form->loadDataFromObject($_SESSION['cad_user']);
   }

   $pwd = new CMWText("frm_password","",20,10);
   $pwd->setPassword();
   $form->addComponent("password",$pwd);
      
   $rpwd = new CMWText("frm_re_password","",20,10);
   $rpwd->setPassword();
   $form->addComponent("re_password", $rpwd);
   $form->addComponent("action",new CMWHidden("action","pag_1"));

   $form->forceCheckField(array("username","password","re_password"));
   $form->submit_label = $_language['next'];
   $form->addOnSubmitAction("passwd_validate(this['frm_password'],this['frm_re_password'])");      

   $form->setCancelUrl($_SERVER['HTTP_REFERER']);

   $cadBox->setTitle($_language['pag_0']);
   $cadBox->add($form);
   
   
   break;
      
 case "pag_1":
   //checa os dados do usuario, pra ver se nao existe o usuario
   $_REQUEST['frm_username'] = strtolower($_REQUEST['frm_username']);
   $user = new AMUser;
   $user->username = $_REQUEST['frm_username'];
   try {
     $user->load();
     header("Location:$_SERVER[PHP_SELF]?frm_amerror=user_exists");
   }catch (CMDBNoRecord $e) {
     unset($user);
   }

   
   $complete = (int) $_conf->app->environment->use_complete_register_form;
   if($complete) 
     $fields_rec = array("name", "email", "birthDate", "codeCity", "address", "cep", "aboutMe");
   else
     $fields_rec = array("name","email", "birthDate", "codeCity","aboutMe");
   
   $form = new AMWSmartForm('AMUser', "cad_user", $_SERVER['PHP_SELF'], $fields_rec);


   $form->setWidgetOrder($fields_rec);

   if(!(array_key_exists('cad_user', $_SESSION) && $_SESSION['cad_user'] instanceof AMUser)) {
     //if this is the first submit, create an object in the session to store the user data
     $_SESSION['cad_user'] = new AMUser();
     $_SESSION['cad_user']->loadDataFromRequest();     
     $_SESSION['cad_user']->time = time();
   }
   else {
     //if the user hit back, fill the from with the data from the session object
     $_SESSION['cad_user']->loadDataFromRequest();     
     $form->loadDataFromObject($_SESSION['cad_user']);
   }

   $form->addOnSubmitAction("email_validate(this['frm_email'])");

      
   //campo cidade
   $cidades = $_SESSION['environment']->listCities();

   $form->setSelect("codeCity",$cidades,"codeCity","name");
   $form->components['codeCity']->addOption(0,$_language['choose_city']);

   //die("V2:".$form->components[codCidade]->getValue());
   if(!$form->components['codeCity']->getValue())
     $form->components['codeCity']->setValue(0);

   

      
   //campo idade
   $form->setDate("birthDate","d/m/Y",1);
      
   //campo acao
   $form->addComponent("action",new CMWHidden("action","pag_2"));
   $form->submit_label = $_language['next'];
   $form->setCancelUrl($_SERVER['HTTP_REFERER']);
   //campos da pagina anterior

   
   $cadBox->setTitle($_language['pag_1']);
   $msg = "<b>$_language[your_username]</b><b style='font-size:17px; color:red'>$_REQUEST[frm_username]</b>";
   $cadBox->add(new AMAlertBox(AMAlertBox::ALERT, $msg));
   $cadBox->add($form);
   
   break;

 case "pag_2":

   if(!($_SESSION['cad_user'] instanceof AMUser)) {
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=fatal");
   }

   $_SESSION['cad_user']->loadDataFromRequest();
   
   //tests if the email is a defined internet site
   $email = explode("@",$_SESSION['cad_user']->email);
   if(!checkdnsrr(array_pop($email),"MX")) {
     $_REQUEST['frm_amerror'][] = "cannot_contact_email_server";

     //change the string {EMAIL} in the language file key error_cannot_contact_email_server by the user email address
     $_language['error_cannot_contact_email_server'] = str_replace("{EMAIL}",$_SESSION['cad_user']->email,$_language['error_cannot_contact_email_server']);
   }

   $_SESSION['cad_foto'] = new AMUserPicture;

   if(!empty($_FILES['frm_foto'])) {
     try {
       $_SESSION['cad_foto']->loadImageFromRequest("frm_foto");
     }
     catch(AMEImage $e) {
       $pag->addError($_language['error_invalid_image_type'], $e->getMessage());
     }
   }
   
   $view = $_SESSION['cad_foto']->getView();
   $cadBox->add("<p align=center>");
   $cadBox->add($view);
   $_SESSION['cad_foto']=serialize($_SESSION['cad_foto']);

   //get the image types that are allowed in this installation of gd+php
   $types = AMImage::getValidImageExtensions();
   
   $cadBox->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
   $cadBox->add("<input type=hidden name=action value=pag_2>");
   $cadBox->add("<p align=center>".$_language['frm_foto']);
   $cadBox->add("&nbsp;".$_language['valid_image_types']." ".implode(", ",$types).".");
   $cadBox->add("<br><input type=file name=frm_foto onChange=\"this.form.submit()\">");
   $cadBox->add("<br><input type=submit onClick=\"this.form['action'].value='pag_3'\" value=\"$_language[conclude]\">");
   $cadBox->add("</form>");

   $cadBox->setTitle($_language['pag_2']);
   
   break;
 case "pag_3":

   if(empty($_SESSION['cad_foto'])) {
     header("Location:$_SERVER[PHP_SELF]?action=pag_2&frm_amerror=picture_not_defined");
   }
   
   $foto = unserialize($_SESSION['cad_foto']);
   if($foto==false) $foto = $_SESSION['cad_foto'];
   
   if(($foto->state==CMObj::STATE_DIRTY) || ($foto->state==CMObj::STATE_DIRTY_NEW)) {
     $foto->time = time();
     try {
		$fName = 'USER_IMAGE_'.$_SESSION['cad_user']->username.'_'.$foto->name;
     	$foto->name = $fName;
        $foto->save(); 
     }
     catch(CMDBException $e) {
     	new AMErrorReport($e, 'Register_User - saving image', AMLog::LOG_CORE);
        header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_picture");
     }
     $_SESSION['cad_user']->picture = (integer) $foto->codeFile;
   }

   //verifica se o ambiente esta configurado para aceitar o cadastro
   //automaticamente
   $auto_accept =  $_conf->app->environment->auto_accept_subscribe;
   
   if($auto_accept) {
     $_SESSION['cad_user']->active = 1;
   }
   else {
     $_SESSION['cad_user']->active = 0;
   }

   try {
     $_SESSION['cad_user']->save();
   }
   catch(CMDBException $e) {
   	 echo $e->getMessage();
     $foto->delete();
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
   }
   catch(AMException $e) {
     $foto->delete();
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=creating_user_dir");
   }


   //tests
   $mail = new AMMailMessage;
   $mail->addTo($_SESSION['cad_user']->email,$_SESSION['cad_user']->name);
   $mail->setHTMLMessage();
   $mail->setSubject($_language['register_subject_email']);


   if($auto_accept) {
     $mensagem = $_language['register_accepted_email'];
     $cadBox->add("<p align=center>$_language[register_ok]");
   }
   else {
     $cadBox->add("<p align=center>a$_language[register_wait]");
   }

   //prepares the email to be sent

   //finds the user firstname
   $temp = explode(" ",trim($_SESSION['cad_user']->name));
   $firstname = $temp[0];

   $keys = array("{FIRSTNAME}","{USERNAME}","{NAME}","{URL}","{IMG_URL}");
   $values = array($firstname,$_SESSION['cad_user']->username,$_SESSION['cad_user']->name,$_CMAPP['url'],$_CMAPP['imlang_url']);
   $mensagem = str_replace($keys,$values,$mensagem);
   
   $mail->setMessage($mensagem);

   try {
     $mail->send();
   }
   catch(CMWEmailNotSend $e) {
     $_REQUEST['frm_amalert'] = "sending_email";
   }
   
   unset($_SESSION['cad_user']);
   unset($_SESSION['user']);
   $cadBox->setTitle($_language['pag_3']);


   break;
 case "fatal_error":
   //No caso de um erro fatal.
   //A mensagem de erro e exibida pelo proprio template AMMain.
   $cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
   break;
}
   
$pag->add($cadBox);
echo $pag;