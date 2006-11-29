<?php
$_CMAPP[notrestricted] = True;
include("../../config.inc.php");
include($_CMDEVEL[path]."/cmwebservice/cmwsmartform.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("diary");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;

$items = AMAmbiente::listDiaries();


$pag = new AMTDiario();

$box = new AMTCadbox($title, AMTCadBox::CADBOX_SEARCH);

$box->add("<table id=\"people_list\">");
if($items[data]->__hasItems()) {
  $i = 0;
  foreach($items[data] as $item) {
    $id = "people_list_1";
    if(($i%2)==1) "people_list_2";
    $i++;
    $box->add("<tr id=\"$id\" class=\"people_list_line\">");

    $profile = "";
    //if the user has alredy filled their diary profile
    if($item->profile->__hasItems()) {
      $it = $item->profile->getIterator();
      $profile = $it->current();
    }

    //print the diary image or, if empty, the user image
    $box->add("<td>");

    if(!empty($profile)) {
      $f = $profile->image;
    }
    
    if($f==0) {
      $f = $item->foto;
      $thumb = new AMUserThumb;
    }
    else {
      $thumb = new AMDiaryThumb;
    }

    if($f!=0) {
      $thumb->codeArquivo = $f;
      try {
	$thumb->load();
	$box->add($thumb->getView());
      }
      catch(CMDBException $e) {
	//loads the default user image
	$box->add("&nbsp;");
	//	echo $e; die();
      }
    }

    //print the rest of the table
    $box->add("<td width=40%>");
    if(!empty($profile)) {
      $box->add($profile->tituloDiario);
    } else {
      $box->add($_language[titulo_padra]);
      $box->add(new AMTUserInfo($item));
    }
    $box->add("</td>");
//     $box->add("<td><a href=# class=blue>$_language[add_friend]</a></td>");
//     //$box->add("<td><a href=\"$_CMAPP[services_url]/diario/diario.php?type=user&frm_codeUser=$item->codeUser\" class=blue>$_language[diary]</a></td\>");
//     $box->add("<td><a href=# class=blue>$_language[send_mail]</a></td>");
//     $box->add("<td><a href=\"$_CMAPP[services_url]/webfolio/page.php?frm_codeUser=$item->codeUser\" class=blue>$_language[page]</a></td>");
    $box->add("</tr>");
  }
}
else {
  $box->add("<span class=\"texto\">$_language[no_diary_found]</span>");
}

$box->add("</table>");
$pag->add($box);
echo $pag; 