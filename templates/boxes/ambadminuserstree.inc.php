<?

class AMBAdminUsersTree extends AMSimpleBox { 

  public function __construct() {
    global $_language;    
    parent::__construct($_language[edit_tables]);
  }

  public function __toString() {
    global $_language, $_CMAPP;
    
    $q = new CMQuery('AMUser');    
    $res = $q->execute();

//    notelastquery();die();

    $box = new AMBAdminUsersList($res,"$_language[search_users]",AMBAdminUsersList::PEOPLE);    

    parent::add($box);  

    return parent::__toString();
  }

}

?>