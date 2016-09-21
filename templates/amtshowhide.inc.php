<?php
/**
 * @package AMADIS
 * @subpackage AMTemplates
 **/

class AMTShowHide extends CMHTMLObj {

  const SHOW = "true";
  const HIDE = "false";

  private $initState, $script, $divName;
  private $lines = array();
  private $class='texto';
  public $stringReturn=false;

  public function __construct($divName, $linkLabel, $displayState) {

    parent::__construct();
    $this->linkLabel = $linkLabel;
    $this->divName = $divName;
    switch($displayState) {
    case self::SHOW:
      $this->initState = "display: '';";
      break;
    case self::HIDE:
      $this->initState = "display: none;";
      break;
    }

  }

  public function add($item) {
    $this->lines[] = $item;
  }

  public function setClass($class) {
    $this->class = $class;
  }

  public function getContents() {
    return $this->__toString(true);
  }

  public function __toString() {

    $link = "<a class=\"$this->class cursor\" onClick=\"AM_togleDivDisplay('$this->divName')\">".$this->linkLabel."</a><br />";

    $div[] = "<div id=\"$this->divName\" style=\"$this->initState\">";

    $div[] = $this->lines;

    $div[] = "</div>";

    if($stringReturn) {
      return array("link"=>$link, "box"=>$div);
    } else {
      parent::add($link);
      parent::add($div);
      return parent::__toString();
    }
  }
}
?>
