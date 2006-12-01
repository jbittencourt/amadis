<?
/**
 * Visualization pages as listing, when a index.(html|htm|php) was notFounded.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMUpload
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see CMHTMLPage,AMUpload, AMBdirList
 */

$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("upload");

$pag = new CMHTMLPage;
$dirBase = $_REQUEST[frm_page];
$dir = new AMUpload($_CMAPP[path]."/environment/pages/$_REQUEST[frm_page]");

$urlBase  = "$_CMAPP[pages_url]/$_REQUEST[frm_page]";
$pathBase = (string) $_conf->app[0]->paths[0]->pages."/$_REQUEST[frm_page]";

$pos = strrpos($_REQUEST['frm_page'],"/");
$dir_pai = substr($_REQUEST['frm_page'],0,$pos);
if(!ereg("(users\/|projects\/)", $dir_pai)) $dir_pai="";

$pag->add(new AMBDirList($dir->getDir()));

echo $pag;


?>