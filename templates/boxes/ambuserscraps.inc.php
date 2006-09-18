<?
/**
 * Message box, list scrap messages send to a user
 *
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @package AMADIS
 * @subpackage AMBoxes
 * @access public
 * @see AMpageBox, CMActionListener
 * 
 * @var Array $itens - Links list.
 * @var String $title - Title to box
 * @var int $box_type - Type of box
 * @see AMTCadBox constants
 */
class AMBUserScraps extends AMPageBox implements CMActionListener {

  private $itens = array();
  protected $title;
  protected $box_type;
  
  public function __construct() {
    parent::__construct(10);
  }

  public function doAction() {
    global $_language, $pag;

    if(isset($_REQUEST[frm_codeMessage]) && !empty($_REQUEST[frm_codeMessage])) {
      try {
	$men = new AMUserMessages;
	$men->code = $_REQUEST[frm_codeMessage];
	$men->load();
	$men->delete();
      }catch(CMException $e) {
	$pag->addError($_language[fail_delete_message], $e->getMessage());
	$pag->add("<a href=$_SERVER[HTTP_REFERER]>$_language[voltar]</a>");
	echo $pag;
	die();
      }
    }
    if(empty($_REQUEST[frm_codeUser])) {
      $user = $_SESSION[user];
    } else {
      $user = new AMUser;
      $user->codeUser = $_REQUEST[frm_codeUser];

      try{
	$user->load();
      }catch(CMDBException $e) {
	$pag->addError($_language[user_not_loaded], $e->getMessage());
	$pag->add("<a href=$_SERVER[HTTP_REFERER]>$_language[voltar]</a>");
	echo $pag;
	die();
      }
    }
    
    $result = $messages = $user->listMyMessages($this->init, $this->numHitsFP);
    $this->box_type = AMTCadBox::CADBOX_LIST;
    $this->title = $_language[scraps_of].' '.$user->name;
    $this->itens = $result[0];
    $this->numItems = $result[count];
  }
  
  public function __toString() {
    global $_language, $_CMAPP;

    $box = new AMTCadBox("", $this->box_type, AMTCadBox::WEBFOLIO_THEME, AMTCadBox::CADBOX_LIST);
   
    if($this->itens->__hasItems()) {
      $box->add('<table id="scraps">');
      foreach($this->itens as $men) {
	$box->add('<tr>');
	$box->add('<td>');
    
	$thumb = new AMUserThumb;
	$thumb->codeArquivo = $men->author[0]->foto;
	$thumb->load();
      
	$box->add($thumb->getView());
	$box->add('<td valign=top>');
	$men->author[0]->codeUser=$men->codeUser;
	$box->add(new AMTUserInfo($men->author[0]));
	$box->add(':<br>'.$men->message);
	$box->add('<td valign=top>');
	$box->add(date("$_language[hour_format]",$men->time)."<br>");
	$box->add(date("$_language[date_format]",$men->time));

	if($_SESSION[environment]->logged) {
	  if($men->codeTo==$_SESSION[user]->codeUser) {
	    $box->add('<td valign=top>');
	    $link = "$_SERVER[PHP_SELF]?frm_codeMessage=$men->code&action=A_delete";
	    if(!empty($_REQUEST[frm_codeUser])) {
	      $link.="&frm_codeUser=$_REQUEST[frm_codeUser]";
	    }
	    $box->add("<a href='$link'>$_language[delete]</a>");
	  }
	} 
      }
      $box->add('</table>');
    } else {
      $box->add('<span class="texto">'.$_language[no_scraps].'</span>');
    }

    $box->setTitle($this->title);
    parent::add("<style type='text/css'>#cad-box{width:90%;}</style>");
    parent::add($box);

    return parent::__toString();
  }
}
?>
