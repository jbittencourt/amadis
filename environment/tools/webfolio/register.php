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

   		if(array_key_exists('cad_user', $_SESSION) and $_SESSION['cad_user'] instanceof AMUser) {
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
   		$_REQUEST['frm_username'] = ereg_replace('\ +','', trim(strtolower($_REQUEST['frm_username'])));
   		$user = new AMUser;
   		$user->username = $_REQUEST['frm_username'];
   		try {
     		$user->load();
     		header("Location:$_SERVER[PHP_SELF]?frm_amerror=user_exists");
   		}catch (CMDBNoRecord $e) {
     		unset($user);
   		}
	
   		$complete = (int) $_conf->app->environment->use_complete_register_form;
   		if($complete) $fields_rec = array("name", "birthDate", "codeCity", "address", "cep", "aboutMe");
   		else $fields_rec = array("name", "birthDate", "codeCity","aboutMe");
   
   		if($_SESSION['config']['webfolio']['emailRequired'] == 'FALSE') {
   			$not_req = array('email');
   		} else $not_req;
   		
   		$form = new AMWSmartForm('AMUser', "cad_user", $_SERVER['PHP_SELF'], $fields_rec, '', $not_req);
	
		$form->setLabelClass('cad-user-label');
   		$form->setWidgetOrder($fields_rec);

   		if(!(array_key_exists('cad_user', $_SESSION) && $_SESSION['cad_user'] instanceof AMUser)) {
     		//if this is the first submit, create an object in the session to store the user data
     		$_SESSION['cad_user'] = new AMUser();
     		$_SESSION['cad_user']->loadDataFromRequest();     
     		$_SESSION['cad_user']->time = time();
   		} else {
     		//if the user hit back, fill the from with the data from the session object
     		$_SESSION['cad_user']->loadDataFromRequest();     
     		$form->loadDataFromObject($_SESSION['cad_user']);
   		}

   		if($_SESSION['config']['webfolio']['emailRequired'] == 'FALSE') {
   			$form->addOnSubmitAction("email_validate(this['frm_email'])");
   		}

      
   		//campo cidade
   		$cidades = $_SESSION['environment']->listCities();

   		$form->setSelect("codeCity",$cidades,"codeCity","name");
   		$form->components['codeCity']->addOption(0,$_language['choose_city']);

   		//die("V2:".$form->components[codCidade]->getValue());
   		if(!$form->components['codeCity']->getValue()) $form->components['codeCity']->setValue(0);

   
   
      	
   		//campo idade
   		$form->setDate("birthDate","d/m/Y",1);
		
   		if($_SESSION['config']['webfolio']['richTextAboutMe'] == 'TRUE') {
   			//campo aboutMe
   			$form->setRichTextArea("aboutMe");
   		}
   
   		//campo acao
   		$form->addComponent("action",new CMWHidden("action","pag_2"));
   		$form->submit_label = $_language['next'];
   		$form->setCancelUrl($_SERVER['HTTP_REFERER']);
   		//campos da pagina anterior

   
   		$cadBox->setTitle($_language['pag_1']);
   		$msg = "<b>$_language[your_username]</b><b style='font-size:17px; color:red'>$_REQUEST[frm_username]</b>";
   		$pag->add(new AMAlertBox(AMAlertBox::ALERT, $msg));
   		$pag->add('<br />');
   
   		$cadBox->add($form);
   
   		break;

 	case "pag_2":

   		if(!($_SESSION['cad_user'] instanceof AMUser)) {
     		header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=fatal");
   		}

   		$_SESSION['cad_user']->loadDataFromRequest();
   
   		if($_SESSION['config']['webfolio']['emailRequired'] == 'TRUE') {
   			//tests if the email is a defined internet site
   			$email = explode("@",$_SESSION['cad_user']->email);
   			if(!checkdnsrr(array_pop($email),"MX")) {
     			$_REQUEST['frm_amerror'][] = "cannot_contact_email_server";

     			//change the string {EMAIL} in the language file key error_cannot_contact_email_server by the user email address
     			$_language['error_cannot_contact_email_server'] = str_replace("{EMAIL}",$_SESSION['cad_user']->email,$_language['error_cannot_contact_email_server']);
   			}
   		}

   		$_SESSION['cad_user']->picture = 0;


   		//verifica se o ambiente esta configurado para aceitar o cadastro
   		//automaticamente
   		/**
   		 * TODO Make this test based in the database configuration.
   		 */
   		$auto_accept =  $_conf->app->environment->auto_accept_subscribe;
   
   		if($auto_accept) {
     		$_SESSION['cad_user']->active = 1;
   		} else {
     		$_SESSION['cad_user']->active = 0;
   		}
		try {
     		$_SESSION['cad_user']->save();
   		} catch(CMDBException $e) {
   	 		new AMLog('AMUser register.php', $e, AMLog::LOG_CORE);
     		header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
   		} catch(AMException $e) {
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
   		} else {
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
   		} catch(CMWEmailNotSend $e) {
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