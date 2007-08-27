<?
class AMAdministration implements CMACLAppInterface {

	//privileges to access this community
    const PRIV_ADMIN_ALL = "admin_all";
    const PRIV_MANAGE_USERS = "manage_users";
	const PRIV_MANAGE_MODULES = "manage_modules";
	
    protected $cacheACO;
    
    /**
     * Return the associated group.
     *  
     * @return CMGroup The group associated with this object.
     **/
    public function getGroup()
    {
    	global $_mConfig;

    	if(empty($_SESSION['AMADIS']['Admin']['group'])) {
            $g = new CMGroup;
            $g->codeGroup = 30;
            $g->load();
            $_SESSION['AMADIS']['Admin']['group'] = $g;
        }  else {
            $g = $_SESSION['AMADIS']['Admin']['group'];
        }
        return $g;
    }


    /**
     * Get the ACO of this Community.
     *
     * Return the respective aco of the administration group. This
     * function uses a cache to avoid unnecessary querys
     * to the database.
     *
     * @return CMAco A CMAco object.
     **/
    public function getACO()
    {
        if(!empty($this->cacheACO)) return $this->cacheACO;
    	    $this->cacheACO = new CMACO($this);
        	$this->cacheACO->code = $this->codeACO;
        try {
            $this->cacheACO->load();
        } catch(CMDBNoRecord $e) {
            Throw new AMException('NO ACO Defined');
        }

        return $this->cacheACO;
    }

    /**
     * List the privileges of this community.
     *
     * @return array An array contaning the priviles valid in this class.
     **/
	public function listPrivileges()
    {
        return  array(self::PRIV_ADMIN_ALL,
        	self::PRIV_MANAGE_USERS,
        	self::PRIV_MANAGE_MODULES
        );
    }

	/**
	 * List the names of the privilges.
 	 *
	 * This function return an array with the privilege as key and
	 * with an string as value. This string represents an user
	 * readable message that represents the privilege in the
	 * current language.
	 *
 	 * @see CMi18n
	 * @return array An array contaning the priviles valid in this class.
	 **/
    public function listPrivilegesMessages()
    {
        $_lang = $_CMAPP[i18n]->getTranslationArray("admin");
        return array( self::PRIV_ADMIN_ALL=>$_lang['privs_admin_all'],
        	self::PRIV_MANAGE_USERS=>$_lang['privs_manage_user'],
        	self::PRIV_MANAGE_MODULES=>$_lang['privs_manage_modules']
        );
    }
    

}