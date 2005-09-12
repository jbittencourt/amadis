<?


class AMBox extends AMAbstractBox {

  const COLOR_WHITE = "box_12";

  protected $theme;
  protected $class;
  private $items;

  public function __construct($id,$theme) {
    parent::__construct($id);
    $this->theme = $theme;

    $this->requires("box.css",CMHTMLObj::MEDIA_CSS);

    switch($theme) {
    case self::COLOR_WHITE:
      $this->class = "box_white";
      break;
    }
  }

  public function add($line) {
    $this->items[] = $line;
  }

  public function __toString() {
    global $_CMAPP;
    parent::add('<table id="'.$this->id.'" class="box '.$this->class.'">');

    $prefix = $_CMAPP[images_url].'/'.$this->theme.'/'.$this->theme.'_';
    //header
    parent::add('<tr>');
    parent::add('<td id="ctl"><img src="'.$prefix.'es.gif">');
    parent::add('<td id="top" background="'.$prefix.'bg_top.gif"><img src="'.$_CMAPP[images_url].'/dot.gif">');
    parent::add('<td id="ctr"><img src="'.$prefix.'ds.gif">');

    //body
    parent::add('<tr>');
    parent::add('<td id="left" background="'.$prefix.'bg_left.gif"><img src="'.$_CMAPP[images_url].'/dot.gif">');

    parent::add('<td id="center">');
    parent::add($this->items);

    parent::add('<td id="right" background="'.$prefix.'bg_right.gif"><img src="'.$_CMAPP[images_url].'/dot.gif">');

    //footer
    parent::add('<tr>');
    parent::add('<td id="cbl"><img src="'.$prefix.'ei.gif">');
    parent::add('<td id="bottom" background="'.$prefix.'bg_bottom.gif"><img src="'.$_CMAPP[images_url].'/dot.gif">');
    parent::add('<td id="cbr"><img src="'.$prefix.'di.gif">');

    parent::add('</table>');

    return parent::__toString();
  }

}


?>