<?
/**
 * This file changes the personal data of an user.
 *
 * LICENSE: Licensed under GPL
 *
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    $Id$
 * @since      File available since Release 1.2.0
 * @author     Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
include("../../config.inc.php");
include("cmwebservice/cmwemail.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("changeUserData");


$pag = new AMTCadastro();

/*
*Load language module
*/

$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;


$el['default'] = $_language['pag_0'];
$el['pag_2'] = $_language['pag_2'];
$ind =  new AMPathIndicator($el);

if(!isset($_REQUEST['action'])) $_REQUEST['action'] = '';

$ind->setState($_REQUEST['action']);
$pag->setPathIndicator($ind);


//form box to interface
$cadBox = new AMTCadBox("",AMTCadBox::CADBOX_CREATE,AMTCadBox::WEBFOLIO_THEME);

switch(AMEnvironment::processActionRequest()) {
    default:
        if(empty($_SESSION['cad_user'])) {
            $_SESSION['cad_user'] = clone $_SESSION['user'];
        }
         

        $complete = (int) $_conf->app->environment->use_complete_register_form;
        if($complete) {
	       	$fields_rec = array("name", "email", "birthDate", "codeCity", "address", "aboutMe");
        } else {
	        $fields_rec = array("name","email", "birthDate", "codeCity","aboutMe");
        }

        $form = new AMWSmartForm('AMUser', "cad_user", $_SERVER['PHP_SELF'], $fields_rec);
        $form->loadDataFromObject($_SESSION['cad_user']);
   

   //$form-setWidgetOrder($fields_rec);
        $form->setDesign(CMWFormEL::WFORMEL_DESIGN_OVER);
        $form->setLabelClass("fontgray");

        $form->addOnSubmitAction("email_validate(this['frm_email'])");


   //campo cidade
        $cidades = $_SESSION['environment']->listCities();
        $form->setSelect("codeCity",$cidades,"codeCity","codeCity");
        $form->components['codeCity']->addOption(0,$_language['escolher_cidade']);

        if(!$form->components['codeCity']->getValue())
        $form->components['codeCity']->setValue(0);


   		//campo idade
        $form->setDate("birthDate","d/m/Y",1);

	   //campo acao
        $form->addComponent("action",new CMWHidden("action","pag_1"));
        $form->submit_label = $_language['finish'];
        $form->setCancelUrl($_CMAPP['services_url']."/webfolio/webfolio.php");



        $cadBox->add($form);
        $cadBox->setTitle($_language['pag_0']);

        break;

    case "pag_1":

    	if(!($_SESSION['cad_user'] instanceof AMUser)) {
        	header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=fatal");
	    }

    	$_SESSION['cad_user']->loadDataFromRequest();

    	//tests if the email is a defined internet site
	    if(!checkdnsrr(array_pop(explode("@",$_SESSION['cad_user']->email)),"MX")) {
     	 $_REQUEST['frm_amerror'][] = "cannot_contact_email_server";
 
	      //change the string {EMAIL} in the language file key error_cannot_contact_email_server by the user email address
     	 $_language['error_cannot_contact_email_server'] = str_replace("{EMAIL}",$_SESSION['cad_user']->email,$_language['error_cannot_contact_email_server']);
	    }
    	

	    try {
    	    $_SESSION['cad_user']->save();
	    } catch(CMDBException $e) {
    	    header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
	    }

	    $_SESSION['user'] = $_SESSION['cad_user'];

    	unset($_SESSION['cad_user']);

	    header("Location:$_CMAPP[services_url]/webfolio/webfolio.php?frm_ammsg=data_changed");
    	$cadBox->setTitle($_language['pag_2']);

	    break;
    
	/**
	 *  In the case of a fatal error, a standart error message is exibided to the user.
	 */
	case "fatal_error":
	    $cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
    	break;
}

$pag->add($cadBox);
echo $pag;

?>