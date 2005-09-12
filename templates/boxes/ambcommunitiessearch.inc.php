<?

class AMBCommunitiesSearch extends AMBSearch {

  public function __construct() {
    global $_CMAPP;
    parent::__construct("$_CMAPP[services_url]/communities/listcommunities.php","$_CMAPP[imlang_url]/img_localizar_comunidades.gif", self::COLOR_BOX_LGREEN);
  }


}

?>