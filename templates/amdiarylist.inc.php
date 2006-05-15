<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMDiaryList extends AMPageBox {

  public function __construct() {
    parent::__construct(10);
  }
  

  public function init($list,$count) {
    $this->numItems = $count;
    $this->itens = $list;
  }

  public function __toString() {
    global $_language,$_CMAPP;

    $box = new AMTCadbox($_language[diary_amadis], AMTCadBox::CADBOX_LIST,AMTCadBox::DIARY_THEME);
    $box->add("<table id=\"diary_list\">");
    if($this->itens->__hasItems()) {
      $i = 0;
      foreach($this->itens as $item) {
	$id = "diary_list_1";
	if(($i%2)==1) "diary_list_2";
	$i++;
	$box->add("<tr id=\"$id\" class=\"diary_list_line\">");

	$profile = "";
	//if the user has alredy filled their diary profile
	$test = $item->profile;
	if(!empty($test)) {
	  $it = $item->profile->getIterator();
	  $profile = $it->current();
	}

	//print the diary image or, if empty, the user image
	$box->add("<td>");
	$f =0;
	if(!empty($profile)) {
	  $f = $profile->image;
	}
	if(empty($f)) {
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
	    $box->add(new AMTDefaultImage);
	  }
	}

	//print the rest of the table
	$box->add("<td width=40%>");
	$link = "<a href=\"$_CMAPP[services_url]/diario/diario.php?frm_codeUser=$item->codeUser\" class=\"blue\">";
	if(!empty($profile)) {
	  $box->add("$link $profile->tituloDiario</a>");
	} else {
	  $box->add("$link $_language[titulo_padrao] $item->name</a>");
	}
	$box->add("</td>");
	$box->add("<td>");
	$box->add(new AMTUserInfo($item));
	$box->add("</td>");
	$box->add("<td><span class='texto'>".date("$_language[hour_format] $_language[date_format]",$item->lastPostTime)."</a></span></td\>");
	$box->add("</tr>");
      }
    }
    else {
      $box->add("<span class=\"texto\">$_language[no_diary_found]</span>");
    }

    $box->add("</table>");

    parent::add($box);
    return parent::__toString();
  }


}

?>