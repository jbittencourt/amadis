<?php
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
 * @see AMDiaryList, AMAmbiente
 */

$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("blog");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;


$pag = new AMTBlog;

$box = new AMBlogList;
$items = AMEnvironment::listBlogs($box->getInitial(),$box->getFinal());
$box->init($items['data'],$items['count']);
$pag->add($box);

echo $pag; 