<?
/**
 * List of all blogs
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @category AMVisualization
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMDiaryList, AMEnvironment
 */

$_CMAPP[notrestricted] = True;
include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("diary");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;


$pag = new AMTDiario();

$box = new AMDiaryList;
$items = $_SESSION['environment']->listDiaries($box->getInitial(),$box->getFinal());
$box->init($items[data],$items[count]);
$pag->add($box);
echo $pag; 

?>