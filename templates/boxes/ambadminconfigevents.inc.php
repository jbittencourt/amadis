<?

class AMBAdminConfigEvents extends AMSimpleBox { 

  public function __construct() {
    global $_language;
    
    parent::__construct($_language[configuration]);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    parent::add($_language['event']."<br>");
    
    parent::add("<a href=\"$_CMAPP[services_url]/admin/sendmessages.php\">$_language[send_messages]</a><br>");    
//     parent::add("<a href=\"$_CMAPP[services_url]/admin/configforum.php\">$_language[config_forum]</a><br>");
//     parent::add("<a href=\"$_CMAPP[services_url]/admin/configchat.php\">$_language[config_chat]</a><br>");
//     parent::add("<a href=\"$_CMAPP[services_url]/admin/configcommunities.php\">$_language[config_communities]</a><br>");
       
   
    return parent::__toString();
  }

}

?>