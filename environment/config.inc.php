<?php
/**
 * Discover the root of the application
 **/
$parts = explode('/',dirname(__FILE__));
array_pop($parts);
$_CMAPP['path'] = implode('/',$parts);

/**
 * PARSING THE config.xml FILE AND LOAD OF DEFAULT VARS.
 **/
$_CMAPP['config_file'] = $_CMAPP['path'].'/etc/config.xml';
$_CMAPP['config'] = simplexml_load_file($_CMAPP['config_file']);

/**
 * @todo test if the config file does not exist
 **/

//load the path of the libs
foreach($_CMAPP['config']->app[0]->libs[0]->lib as $item) {
  $attributes = $item->attributes();
  switch((string) $attributes['name']) {
  case 'cmdevel':
    $_CMDEVEL['path'] = $attributes['path']; break;
  case 'xoad':
    $_XOAD['path'] = $attributes['path']; break;
  case 'lastRSS':
    $_LAST_RSS['path'] = $attributes['path']; break;
  }
}

/**
 * START OF THE OBRIGATORY INCLUDES
 **/

//include the class loader function
include($_CMAPP['path'].'/classload.inc.php');
include($_CMAPP['path'].'/exceptionhandler.inc.php');
set_exception_handler('exception_handler');


//PATHs dos servicos da aplicacao
$_CMAPP['services_path'] = $_CMAPP['path'] . "enviroments/tools";

include($_CMDEVEL['path'] . "/cmdevel.inc.php");
include("cmpersistence.inc.php");
include("cmapp.inc.php");
include("cminterface.inc.php");
include("cmwebservice/cmwsmartform.inc.php");
//load the exception file, that contation many exceptions, so
//the classes can't be resolved by the classloader(__autoload)
include($_CMAPP['path']."/lib/exceptions/amexceptions.inc.php");



$_conf = $_CMAPP['config'];
$_CMAPP['url']   = (string) $_conf->app[0]->urls[0]->base;
$_CMAPP['media_url']  = (string) $_conf->app[0]->urls[0]->media;
$_CMAPP['images_url'] = (string) $_conf->app[0]->urls[0]->images;
$_CMAPP['js_url']     = (string) $_conf->app[0]->urls[0]->js;
$_CMAPP['css_url']    = (string) $_conf->app[0]->urls[0]->css;
$_CMAPP['services_url']  = (string) $_conf->app[0]->urls[0]->services;
$_CMAPP['pages_url']  = (string) $_conf->app[0]->urls[0]->pages;
$_CMAPP['thumbs_url']  = (string) $_conf->app[0]->urls[0]->thumbnails;


$_CMAPP['environment'] = $_conf->app->environment;
$_CMAPP['finder'] = $_conf->app->finder;


/**
* Conecta com o banco de dados
**/
try {
  $_CMAPP['db'] = new CMDBConnection($_CMAPP['config']);
}
catch (CMDBCannotConnect $e) {
  die($e->getMessage());
}



session_name($_conf->app[0]->session->name);
session_start();


if(empty($_SESSION['environment'])) {
  $_SESSION['environment'] = new AMEnvironment();

}


/**
 *  A Partir deste ponto sao configuracoes do AMADIS
 *
 **/

if($_conf->app->languages->active == 1) {
  include("cminterface/cmi18n.inc.php");
  $_CMAPP['i18n'] = new CMi18n;
}

//set uma url para as imagens relativas a sua linguagem. \xc9 importante
//para suportar imagens em varias linguas
$_CMAPP['imlang_url'] = $_CMAPP['images_url']."/".$_CMAPP['i18n']->getActualLang();

//colocar o login aqui, servir� para que mais tarde o logon possa ocorrer em qualquer p�gina.
if(isset($_REQUEST['login_action'])) {
  switch($_REQUEST['login_action']) {
  case "A_login":
    try {
      $_SESSION['environment']->login($_REQUEST['frm_username'],$_REQUEST['frm_password']);
    }
    catch(CMLoginFailure $e) {
      $file = basename($_SERVER['SCRIPT_FILENAME']);
      if($file!="loginfailure.php") {
	$_SESSION['login_failed_trying_to_access'] = $file;
      }
      Header("Location: ".$_CMAPP['url']."/loginfailure.php?frm_amerror=invalid_login");
      die();
    }

    break;
   
  case "A_logout":
    $_SESSION['environment']->logout();
    Header("Location: ".$_CMAPP[url]."/index.php");
    break;   
  }
}

if($_SESSION['environment']->logged) {
  $_SESSION['session']->update();
}
else {
  if($_CMAPP['notrestricted']==false) {
    Header("Location: ".$_CMAPP['url']."/index.php?frm_amerror=session_timeout");
  }
}

if(!ereg("(index|register|recoverpassword|loginfailure)",basename($_SERVER['SCRIPT_FILENAME']))) {
  $_CMAPP['form_login_action'] = $_SERVER['PHP_SELF'];
} else { 
  $_CMAPP['form_login_action'] = $_CMAPP['services_url']."/webfolio/webfolio.php";
}

//include XOAD AJAX Framework;
define('XOAD_AUTOHANDLE', true);
include( $_XOAD['path'] . "/xoad.php");