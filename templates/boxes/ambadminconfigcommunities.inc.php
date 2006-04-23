<?

class AMBAdminConfigCommunities extends AMSimpleBox {

  public function __construct() {
    global $_language;
    parent::__construct($_language[config_communities]);
  }

  public function __toString() {
    global $_CMAPP, $_language;
    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/configcommunities.php?action=list_NotAuthorized\">$_language[list_not_authorized]</a><br>");

    return parent::__toString();
  }
}

?>