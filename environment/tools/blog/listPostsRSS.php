<?php
/**
 * RSS feeds file
 * This file is provide RSS feeds to a client softwares able to read them.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @version 1.0.2
 * @author Robson Mendonça <robson@lec.ufrgs.br>
 * 
 */
$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("blog");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

header('Content-type: text/xml');
print '<?xml version="1.0" encoding="UTF-8"?>';
print '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/"><channel>';

$today['m'] = date('m',time());
$today['y'] = date('Y',time());
$today['d'] = date('d',time());

$lower = mktime(0,0,0,$today['m'],$today['d'],$today['y']);
$upper = mktime(23,59,59,$today['m'],$today['d'],$today['y']);

$posts = AMEnvironment::listBlogsPosts($lower, $upper,array());


$xml = '   <title>'.html_entity_decode($_language['blog_today_feeds'])."</title>\n";
$xml .= '   <link>'.$_CMAPP['services_url']."/blog/listPosts.php</link>\n";
$xml .= '   <description>'.html_entity_decode($text)."</description>\n";

foreach($posts['data'] as $post) {
	$xml .= "   <item>\n";
	$xml .= '      <title>'.html_entity_decode($post->title).'</title>'."\n";
	$text = str_replace("../../media", "$_CMAPP[media_url]", $post->body);
	$xml .= '      <description>'.html_entity_decode($text).'</description>'."\n";
	$xml .= '      <author>'.html_entity_decode($post->user[0]->name).'</author>'."\n";
	$xml .= '      <link>'.$_CMAPP['services_url'].'/blog/blog.php?frm_codePost='.$post->codePost.'#anchor_post_'.$post->codePost."</link>\n";
	$xml .= '      <pubDate>'.date("h:i ".$_language['date_format'],$post->time).'</pubDate>'."\n";
	$xml .= "   </item>\n";
}

$xml .= "</channel>\n</rss>";
print($xml);
